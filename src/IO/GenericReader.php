<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\IO;

use EdmondsCommerce\SpreadsheetToCsv\IO\Exceptions\FileReaderException;
use EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces\FileReaderInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GenericReader implements FileReaderInterface
{
    public function read(string $path): Spreadsheet
    {
        try {
            $fileType = IOFactory::identify($path);

            /** @var BaseReader $reader */
            $reader = IOFactory::createReader($fileType);
            $reader->setReadDataOnly(true);

            return $reader->load($path);
        } catch (Exception $e) {
            throw new FileReaderException(
                "Unable to read file '$path': " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}