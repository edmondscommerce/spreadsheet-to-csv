<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface FileReaderInterface
{
    public function read(string $path): Spreadsheet;
}