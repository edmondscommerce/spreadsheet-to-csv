<?php declare(strict_types=1);


namespace EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces;

interface ConfigInterface
{
    public function setStartingRow(int $startingRow): void;

    public function getStartingRow(): int;

    public function addColumn(string $column, ColumnConfigInterface $config): ConfigInterface;

    public function getColumnIterator(): \ArrayIterator;

    public function getCsvHeader(): array;
}