<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Config;

use EdmondsCommerce\SpreadsheetToCsv\Config\Exceptions\InvalidColumnException;
use EdmondsCommerce\SpreadsheetToCsv\Config\Exceptions\MissingColumnConfigException;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ColumnConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ConfigInterface;

class Config implements ConfigInterface
{
    /**
     * @var ColumnConfigInterface[]
     */
    private $columns = [];

    /**
     * @var int
     */
    private $startingRow = 0;

    public function setStartingRow(int $startingRow): void
    {
        $this->startingRow = $startingRow;
    }

    public function getStartingRow(): int
    {
        return $this->startingRow;
    }

    public function addColumn(string $column, ColumnConfigInterface $config): ConfigInterface
    {
        $this->assertValidColumn($column);

        $this->columns[$column] = $config;

        return $this;
    }

    public function getColumnIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->columns);
    }

    public function getCsvHeader(): array
    {
        $header = [];

        foreach ($this->columns as $column) {
            $header[] = $column->getHeader();
        }

        return $header;
    }

    private function assertValidColumn(string $column): void
    {
        if (ctype_alpha($column) && ctype_upper($column)) {
            return;
        }

        throw new InvalidColumnException(
            "Column '$column' is invalid."
        );
    }
}