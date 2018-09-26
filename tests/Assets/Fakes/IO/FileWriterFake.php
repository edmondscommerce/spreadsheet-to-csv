<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\Fakes\IO;

use EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces\FileWriterInterface;

class FileWriterFake implements FileWriterInterface
{
    public function write(string $path, array $data): void
    {
        // Do nothing
    }
}