<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Converter;

use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ColumnConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Converter\Exceptions\CellNotFoundException;
use EdmondsCommerce\SpreadsheetToCsv\Converter\Exceptions\FailedCalculatingValueException;
use EdmondsCommerce\SpreadsheetToCsv\Helper\DateHelper;
use EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces\FileReaderInterface;
use EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces\FileWriterInterface;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class SpreadsheetToCsv
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Converter
 */
class SpreadsheetToCsv
{
    public const MAX_SKIPPED_ROWS = 300;

    /**
     * @var FileReaderInterface
     */
    private $reader;

    /**
     * @var FileWriterInterface
     */
    private $writer;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var DateHelper
     */
    private $dateHelper;

    public function __construct(
        FileReaderInterface $reader,
        FileWriterInterface $writer,
        ConfigInterface $config,
        DateHelper $dateHelper
    ) {
        $this->reader     = $reader;
        $this->writer     = $writer;
        $this->config     = $config;
        $this->dateHelper = $dateHelper;
    }

    public function convert(string $inputPath): string
    {
        $spreadsheet = $this->reader->read($inputPath);
        $csv         = $this->toCsv($spreadsheet);
        $outputPath  = $this->inputPathToOutputPath($inputPath);

        $this->writer->write($outputPath, $csv);

        return $outputPath;
    }

    protected function toCsv(Spreadsheet $spreadsheet): array
    {
        /** @var Worksheet[] $worksheets */
        $worksheets = $spreadsheet->getAllSheets();

        $csv   = [];
        $csv[] = $this->config->getCsvHeader();
        $skippedRowCount = 0;

        foreach ($worksheets as $worksheet) {

            $worksheetName = $worksheet->getTitle();

            foreach ($worksheet->getRowIterator() as $worksheetRow) {

                $rowIndex = $worksheetRow->getRowIndex();

                if ($rowIndex < $this->config->getStartingRow()) {
                    continue;
                }

                $csvRow = [];

                /**
                 * @var string $column
                 * @var ColumnConfigInterface $columnConfig
                 */
                foreach ($this->config->getColumnIterator() as $column => $columnConfig) {

                    $cellCoordinate = $column . $rowIndex;

                    try {
                        $worksheetCell = $worksheetRow->getWorksheet()->getCell($cellCoordinate);
                    } catch (Exception $e) {
                        $errMsg = "Cell '$cellCoordinate' not found in worksheet '$worksheetName': " . $e->getMessage();
                        throw new CellNotFoundException(
                            $errMsg,
                            $e->getCode(),
                            $e
                        );
                    }

                    if (null === $worksheetCell) {
                        throw new CellNotFoundException(
                            "Cell '$cellCoordinate' not found in worksheet '$worksheetName'"
                        );
                    }

                    // If the cell is blank then add a null value to the CSV
                    if (ColumnConfigInterface::CELL_TYPE_NULL === $worksheetCell->getDataType()) {
                        $csvRow[] = $this->getNullValue();
                        continue;
                    }

                    // As functions seem to find there way into other column types; if PhpSpreadsheet
                    // says this is a function cell then lets just treat it as such
                    if (ColumnConfigInterface::CELL_TYPE_FUNCTION === $worksheetCell->getDataType()) {
                        $csvRow[] = $this->getFunctionValue($worksheetCell);
                        continue;
                    }

                    switch ($columnConfig->getType()) {
                        case ColumnConfigInterface::CELL_TYPE_STRING:
                            $csvRow[] = $this->getStringValue($worksheetCell);
                            break;
                        case ColumnConfigInterface::CELL_TYPE_NUMBER:
                            $csvRow[] = $this->getNumberValue($worksheetCell);
                            break;
                        case ColumnConfigInterface::CELL_TYPE_DATE:
                            $csvRow[] = $this->getDateValue($worksheetCell, $columnConfig->getDateFormat());
                            break;
                        case ColumnConfigInterface::CELL_TYPE_FUNCTION:
                            $csvRow[] = $this->getFunctionValue($worksheetCell);
                            break;
                    }
                }

                if ($this->shouldSkipRow($csvRow)) {
                    $skippedRowCount++;

                    if ($skippedRowCount > self::MAX_SKIPPED_ROWS) {
                        break;
                    }

                    continue;
                }

                $csv[] = $csvRow;
            }
        }

        return $csv;
    }

    /**
     * @return string
     */
    protected function getNullValue(): string
    {
        return '';
    }

    /**
     * @param Cell $worksheetCell
     *
     * @return string
     */
    protected function getStringValue(Cell $worksheetCell): string
    {
        return $worksheetCell->getValue();
    }

    /**
     * @param Cell $worksheetCell
     *
     * @return float
     */
    protected function getNumberValue(Cell $worksheetCell): float
    {
        return $worksheetCell->getValue();
    }

    /**
     * @param Cell $worksheetCell
     * @param string $dateFormat
     *
     * @return string
     */
    protected function getDateValue(Cell $worksheetCell, string $dateFormat): string
    {
        $value    = (int) $worksheetCell->getValue();
        $dateTime = $this->dateHelper->excelToDateTimeObject($value);

        return $dateTime->format($dateFormat);
    }

    /**
     * @param Cell $worksheetCell
     *
     * @return mixed
     */
    protected function getFunctionValue(Cell $worksheetCell)
    {
        try {
            return $worksheetCell->getCalculatedValue();
        } catch (Exception $e) {
            $coordinate = $worksheetCell->getCoordinate();

            throw new FailedCalculatingValueException(
                "Unable to calculate value for cell '$coordinate': " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    protected function shouldSkipRow(array $row): bool
    {
        return $this->isEmptyRow($row);
    }

    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $cell) {
            if ('' !== $cell) {
                return false;
            }
        }

        return true;
    }

    protected function inputPathToOutputPath(string $inputPath): string
    {
        $pathInfo = pathinfo($inputPath);

        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.csv';
    }
}