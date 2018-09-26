<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\IO\Interfaces;

interface FileWriterInterface
{
    public function write(string $path, array $data): void;
}