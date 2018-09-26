<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\Fakes\IO;

use EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces\FileReaderInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FileReaderFake implements FileReaderInterface
{
    private $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function read(string $path): Spreadsheet
    {
        return $this->spreadsheet;
    }
}