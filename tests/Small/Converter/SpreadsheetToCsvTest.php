<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Converter;

use EdmondsCommerce\SpreadsheetToCsv\Config\Config;
use EdmondsCommerce\SpreadsheetToCsv\Config\Interfaces\ConfigInterface;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\AbstractTestCase;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\Fakes\Helper\DateHelperFake;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\Fakes\IO\FileReaderFake;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\Fakes\IO\FileWriterFake;

/**
 * Class SpreadsheetToCsvTest
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Converter
 *
 * @covers \EdmondsCommerce\SpreadsheetToCsv\Converter\SpreadsheetToCsv
 */
class SpreadsheetToCsvTest extends AbstractTestCase
{
    private const PATH_DIR      = 'some/path/to';
    private const PATH_FILENAME = 'file';
    private const PATH_INPUT    = self::PATH_DIR . '/' . self::PATH_FILENAME . '.xlsx';
    private const PATH_OUTPUT   = self::PATH_DIR . '/' . self::PATH_FILENAME . '.csv';

    /**
     * @var FileReaderFake
     */
    private $readerFake;

    /**
     * @var FileWriterFake
     */
    private $writerFake;

    /**
     * @var ConfigInterface
     */
    private $config;

    private function getClass(): SpreadsheetToCsv
    {
        $this->readerFake = new FileReaderFake();
        $this->writerFake = new FileWriterFake();
        $this->config     = new Config();
        $dateHelperFake   = new DateHelperFake();

        return new SpreadsheetToCsv(
            $this->readerFake,
            $this->writerFake,
            $this->config,
            $dateHelperFake
        );
    }

    /**
     * @test @small
     */
    public function iCreateTheCorrectOutputPath(): void
    {
        $converter = $this->getClass();

        $expected = self::PATH_OUTPUT;
        $actual   = $converter->convert(self::PATH_INPUT);

        $this->assertSame($expected, $actual);
    }

    public function iCorrectlyHandleGetCellExceptions(): void
    {

    }

    public function iCorrectlyHandleMissingCells(): void
    {

    }

    public function iCorrectlyHandleCalculationErrors(): void
    {

    }
}