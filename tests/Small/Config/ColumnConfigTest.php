<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Small\Config;

use EdmondsCommerce\SpreadsheetToCsv\Config\ColumnConfig;
use EdmondsCommerce\SpreadsheetToCsv\Config\Exceptions\InvalidCellTypeException;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ColumnConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\AbstractTestCase;

/**
 * Class ColumnConfigTest
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Tests\Small\Config
 *
 * @covers \EdmondsCommerce\SpreadsheetToCsv\Config\ColumnConfig
 */
class ColumnConfigTest extends AbstractTestCase
{
    private const HEADER      = 'Some Header';
    private const TYPE        = ColumnConfigInterface::CELL_TYPE_DATE;
    private const DATE_FORMAT = 'Y-m-d';

    private const INVALID_TYPE = 'not_a_valid_type';

    private function getClass(
        string $header,
        string $type,
        string $dateFormat = self::DATE_FORMAT
    ): ColumnConfigInterface {
        return new ColumnConfig(
            $header,
            $type,
            $dateFormat
        );
    }

    /**
     * @test @small
     */
    public function iCanGetTheHeader(): void
    {
        $columnConfig = $this->getClass(self::HEADER,self::TYPE);

        $expected = self::HEADER;
        $actual   = $columnConfig->getHeader();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test @small
     *
     * @param string $cellType
     *
     * @dataProvider iCanGetTheTypeDataProvider
     */
    public function iCanGetTheType(string $cellType): void
    {
        $columnConfig = $this->getClass(self::HEADER, $cellType);

        $expected = $cellType;
        $actual   = $columnConfig->getType();

        $this->assertSame($expected, $actual);
    }

    public function iCanGetTheTypeDataProvider(): array
    {
        $data = [];

        foreach (ColumnConfigInterface::CELL_TYPES as $cellType) {
            $data[][] = $cellType;
        }

        return $data;
    }

    /**
     * @test @small
     */
    public function iCanGetTheDateFormat(): void
    {
        $columnConfig = $this->getClass(self::HEADER,self::TYPE,self::DATE_FORMAT);

        $expected = self::DATE_FORMAT;
        $actual   = $columnConfig->getDateFormat();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test @small
     */
    public function iGetAnExceptionIfIProvideAnInvalidType(): void
    {
        $this->expectException(InvalidCellTypeException::class);
        $this->expectExceptionMessage("Cell type '" . self::INVALID_TYPE . "' is invalid.");

        $this->getClass(self::HEADER,self::INVALID_TYPE);
    }
}