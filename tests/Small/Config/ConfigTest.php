<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Small\Config;

use EdmondsCommerce\SpreadsheetToCsv\Config\ColumnConfig;
use EdmondsCommerce\SpreadsheetToCsv\Config\Config;
use EdmondsCommerce\SpreadsheetToCsv\Config\Exceptions\InvalidColumnException;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ColumnConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\AbstractTestCase;

/**
 * Class ConfigTest
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Tests\Small\Config
 *
 * @covers \EdmondsCommerce\SpreadsheetToCsv\Config\Config
 */
class ConfigTest extends AbstractTestCase
{
    /**
     * Config Values
     */
    private const STARTING_ROW   = 15;
    private const COLUMN_A       = 'A';
    private const COLUMN_B       = 'B';
    private const COLUMN_C       = 'C';

    /**
     * Column Config Values
     */
    private const TYPE     = ColumnConfigInterface::CELL_TYPE_STRING;
    private const HEADER_A = 'Header A';
    private const HEADER_B = 'Header B';
    private const HEADER_C = 'Header C';

    private function getClass(): ConfigInterface
    {
        return new Config();
    }

    /**
     * @test @small
     */
    public function iCanSetAStartingRow(): void
    {
        $config = $this->getClass();
        $config->setStartingRow(self::STARTING_ROW);

        $expected = self::STARTING_ROW;
        $actual   = $config->getStartingRow();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test @small
     *
     * @param string $column
     *
     * @dataProvider iCanAddAColumnDataProvider
     *
     * @throws \ReflectionException
     */
    public function iCanAddAColumn(string $column): void
    {
        $config       = $this->getClass();
        $columnConfig = $this->getColumnConfig();

        $config->addColumn($column, $columnConfig);

        $expected = self::HEADER_A;
        $actual   = $this->getColumn($config, $column)->getHeader();

        $this->assertSame($expected, $actual);
    }

    public function iCanAddAColumnDataProvider(): array
    {
        return [
            ['A'],
            ['AA'],
            ['AZ'],
        ];
    }

    /**
     * @test @small
     */
    public function iCanIterateThroughAllAddedColumns(): void
    {
        $config = $this->getClass();

        $expected = [
            'A' => new ColumnConfig(self::HEADER_A, self::TYPE),
            'C' => new ColumnConfig(self::HEADER_B, self::TYPE),
            'D' => new ColumnConfig(self::HEADER_C, self::TYPE)
        ];

        foreach ($expected as $column => $columnConfig) {
            $config->addColumn($column, $columnConfig);
        }

        /**
         * @var string $column
         * @var ColumnConfigInterface $columnConfig
         */
        foreach ($config->getColumnIterator() as $column => $columnConfig) {
            /** @var ColumnConfigInterface $expectedColumnConfig */
            $expectedColumnConfig = $expected[$column];

            $this->assertSame($expectedColumnConfig->getHeader(), $columnConfig->getHeader());
            $this->assertSame($expectedColumnConfig->getType(), $columnConfig->getType());
        }
    }

    /**
     * @test @small
     *
     * @param string $column
     *
     * @dataProvider iGetAnExceptionIfITryToAddAnInvalidColumnDataProvider
     */
    public function iGetAnExceptionIfITryToAddAnInvalidColumn(string $column): void
    {
        $this->expectException(InvalidColumnException::class);
        $this->expectExceptionMessage("Column '$column' is invalid.");

        $config       = $this->getClass();
        $columnConfig = $this->getColumnConfig();

        $config->addColumn($column, $columnConfig);
    }

    public function iGetAnExceptionIfITryToAddAnInvalidColumnDataProvider(): array
    {
        return [
            ['a'],
            ['1'],
            ['*'],
            ['A-A']
        ];
    }

    /**
     * @test @small
     */
    public function iCorrectlyGenerateTheCsvHeaderFromTheProvidedColumnConfig(): void
    {
        $config = $this->getClass();

        $columnConfigA = $this->getColumnConfig(self::HEADER_A);
        $columnConfigB = $this->getColumnConfig(self::HEADER_B);
        $columnConfigC = $this->getColumnConfig(self::HEADER_C);

        $config->addColumn(self::COLUMN_A, $columnConfigA);
        $config->addColumn(self::COLUMN_B, $columnConfigB);
        $config->addColumn(self::COLUMN_C, $columnConfigC);

        $expected = [
            self::HEADER_A,
            self::HEADER_B,
            self::HEADER_C
        ];

        $actual = $config->getCsvHeader();

        $this->assertSame($expected, $actual);
    }

    private function getColumnConfig(string $header = self::HEADER_A): ColumnConfigInterface
    {
        return new ColumnConfig($header, self::TYPE);
    }

    /**
     * Extract the required column config
     *
     * @param ConfigInterface $config
     * @param string $column
     *
     * @return ColumnConfigInterface
     *
     * @throws \ReflectionException
     */
    private function getColumn(ConfigInterface $config, string $column): ColumnConfigInterface
    {
        $refProp = new \ReflectionProperty(Config::class, 'columns');
        $refProp->setAccessible(true);

        $columns = $refProp->getValue($config);

        return $columns[$column];
    }
}