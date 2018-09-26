<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Medium\Converter;

use EdmondsCommerce\SpreadsheetToCsv\Config\ColumnConfig;
use EdmondsCommerce\SpreadsheetToCsv\Config\Config;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ColumnConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Converter\SpreadsheetToCsv;
use EdmondsCommerce\SpreadsheetToCsv\Helper\DateHelper;
use EdmondsCommerce\SpreadsheetToCsv\IO\CsvWriter;
use EdmondsCommerce\SpreadsheetToCsv\IO\GenericReader;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\AbstractTestCase;

/**
 * Class SpreadsheetToCsvTest
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Tests\Medium\Converter
 *
 * @covers \EdmondsCommerce\SpreadsheetToCsv\Converter\SpreadsheetToCsv
 */
class SpreadsheetToCsvTest extends AbstractTestCase
{
    private const HEADER_STRING   = 'STRING';
    private const HEADER_NUMBER   = 'NUMBER';
    private const HEADER_DATE     = 'DATE';
    private const HEADER_FUNCTION = 'FUNCTION';

    private const FIXTURES_DIR         = __DIR__ . '/../../Assets/Fixtures/Medium/Converter';
    private const FIXTURE_SPREADSHEET  = self::FIXTURES_DIR . '/spreadsheet.xlsx';
    private const FIXTURE_CSV          = self::FIXTURES_DIR . '/spreadsheet.expected.csv';

    private const OUTPUT_DIR        = __DIR__ . '/../../../var';
    private const SPREADSHEET_COPY  = self::OUTPUT_DIR . '/spreadsheet.copy.xlsx';

    /**
     * @var ConfigInterface
     */
    private $config;

    private function getClass(): SpreadsheetToCsv
    {
        $reader       = new GenericReader();
        $writer       = new CsvWriter();
        $this->config = new Config();
        $dateHelper   = new DateHelper();

        return new SpreadsheetToCsv(
            $reader,
            $writer,
            $this->config,
            $dateHelper
        );
    }

    /**
     * @test @medium
     */
    public function iConvertTheFileToCsvAsExpected(): void
    {
        $converter = $this->getClass();

        $this->setupConfig();
        $this->copyFixture(self::FIXTURE_SPREADSHEET, self::SPREADSHEET_COPY);

        $expected = self::FIXTURE_CSV;
        $actual   = $converter->convert(self::SPREADSHEET_COPY);

        $this->assertFileEquals($expected, $actual);
    }

    private function setupConfig(): void
    {
        $this->config
            ->addColumn('B', new ColumnConfig(
                self::HEADER_STRING,
                ColumnConfigInterface::CELL_TYPE_STRING
            ))
            ->addColumn('C', new ColumnConfig(
                self::HEADER_NUMBER,
                ColumnConfigInterface::CELL_TYPE_NUMBER
            ))
            ->addColumn('D', new ColumnConfig(
                self::HEADER_DATE,
                ColumnConfigInterface::CELL_TYPE_DATE
            ))
            ->addColumn('E', new ColumnConfig(
                self::HEADER_FUNCTION,
                ColumnConfigInterface::CELL_TYPE_FUNCTION
            ));

        $this->config->setStartingRow(15);
    }
}