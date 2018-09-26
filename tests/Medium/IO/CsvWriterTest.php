<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Medium\IO;

use EdmondsCommerce\SpreadsheetToCsv\IO\CsvWriter;
use EdmondsCommerce\SpreadsheetToCsv\IO\Exceptions\FileWriterException;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\AbstractTestCase;

/**
 * Class CsvWriterTest
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Tests\Medium\IO
 *
 * @covers \EdmondsCommerce\SpreadsheetToCsv\IO\CsvWriter
 */
class CsvWriterTest extends AbstractTestCase
{
    private const FIXTURES_DIR = __DIR__ . '/../../Assets/Fixtures/Medium/IO';
    private const FIXTURE_CSV  = self::FIXTURES_DIR . '/expected.csv';
    private const FIXTURE_READ_ONLY_CSV = self::FIXTURES_DIR . '/read_only.csv';

    private const OUTPUT_DIR = __DIR__ . '/../../../var';
    private const OUTPUT_FILE = self::OUTPUT_DIR . '/actual.csv';

    private function getClass(): CsvWriter
    {
        return new CsvWriter();
    }

    /**
     * @test @medium
     */
    public function iCanWriteCsvDataToFile(): void
    {
        $writer = $this->getClass();

        $csv = [
            ['HEADER_ONE', 'HEADER_TWO'],
            ['data_one', 'data_two'],
            ['data one', 'data two'],
            [10, 10.2],
            ['01/01/2001', '2001-01-01'],
        ];

        $writer->write(self::OUTPUT_FILE, $csv);

        $expected = self::FIXTURE_CSV;
        $actual   = self::OUTPUT_FILE;

        $this->assertFileEquals($expected, $actual);
    }

    public function iGetAnExceptionIfICannotOpenFileForWriting(): void
    {

    }

    public function iGetAnExceptionIfIFindInvalidData(): void
    {

    }
}