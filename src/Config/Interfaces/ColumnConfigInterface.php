<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces;

interface ColumnConfigInterface
{
    public const CELL_TYPE_NULL     = 'null';
    public const CELL_TYPE_STRING   = 's';
    public const CELL_TYPE_NUMBER   = 'n';
    public const CELL_TYPE_DATE     = 'd';
    public const CELL_TYPE_FUNCTION = 'f';

    public const CELL_TYPES = [
        self::CELL_TYPE_NULL,
        self::CELL_TYPE_STRING,
        self::CELL_TYPE_NUMBER,
        self::CELL_TYPE_DATE,
        self::CELL_TYPE_FUNCTION
    ];

    /**
     * @return string
     */
    public function getHeader(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getDateFormat(): string;
}