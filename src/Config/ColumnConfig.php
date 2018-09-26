<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Config;

use EdmondsCommerce\SpreadsheetToCsv\Config\Exceptions\InvalidCellTypeException;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ColumnConfigInterface;

class ColumnConfig implements ColumnConfigInterface
{
    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $dateFormat;

    public function __construct(
        string $header,
        string $type,
        string $dateFormat = 'd/m/Y'
    ){
        $this->assertValidType($type);

        $this->header     = $header;
        $this->type       = $type;
        $this->dateFormat = $dateFormat;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    private function assertValidType(string $type): void
    {
        if (\in_array($type, self::CELL_TYPES, true)) {
            return;
        }

        throw new InvalidCellTypeException(
            "Cell type '$type' is invalid."
        );
    }
}