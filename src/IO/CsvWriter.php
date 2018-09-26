<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\IO;

use EdmondsCommerce\SpreadsheetToCsv\IO\Exceptions\FileWriterException;
use EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces\FileWriterInterface;

class CsvWriter implements FileWriterInterface
{
    public function write(string $path, array $data): void
    {
        $fp = fopen($path, 'w');

        if (false === $fp) {
            throw new FileWriterException(
                "Unable to open '$path' for writing"
            );
        }

        foreach ($data as $row) {
            $success = fputcsv($fp, $row);

            if (! $success) {
                throw new FileWriterException(
                    "Error while writing data to file '$path'"
                );
            }
        }

        fclose($fp);
    }
}