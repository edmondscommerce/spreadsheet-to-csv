<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Small\Helper;

use EdmondsCommerce\SpreadsheetToCsv\Helper\DateHelper;
use EdmondsCommerce\SpreadsheetToCsv\Helper\Exceptions\UnableToConvertExcelDateException;
use EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\AbstractTestCase;

/**
 * Class DateHelperTest
 *
 * @package EdmondsCommerce\SpreadsheetToCsv\Tests\Small\Helper
 *
 * @covers \EdmondsCommerce\SpreadsheetToCsv\Helper\DateHelper
 */
class DateHelperTest extends AbstractTestCase
{
    private const INVALID_TIMEZONE = 'invalid_timezone';

    private function getClass(): DateHelper
    {
        return new DateHelper();
    }

    /**
     * @test @small
     */
    public function iCanConvertAnExcelDateValueSuccessfully(): void
    {
        $dateHelper = $this->getClass();
        $dateValue  = 0;

        $expected = '1970-01-01';
        $actual   = $dateHelper->excelToDateTimeObject($dateValue)->format('Y-m-d');

        $this->assertSame($expected, $actual);
    }

    /**
     * @test @small
     */
    public function iGetAnExceptionIfIProvideAnInvalidValue(): void
    {
        $this->expectException(UnableToConvertExcelDateException::class);
        $this->expectExceptionMessageRegExp(
            "#Unable to convert excel date '.*' into DateTime object: .*#"
        );

        $dateHelper = $this->getClass();

        $dateHelper->excelToDateTimeObject(0, self::INVALID_TIMEZONE);
    }
}