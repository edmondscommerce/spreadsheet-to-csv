<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Helper;

use EdmondsCommerce\SpreadsheetToCsv\Helper\Exceptions\UnableToConvertExcelDateException;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DateHelper
{
    /**
     * @param int $value
     * @param string $timeZone
     *
     * @return \DateTime
     */
    public function excelToDateTimeObject(int $value, string $timeZone = 'UTC'): \DateTime
    {
        try {
            return Date::excelToDateTimeObject($value, new \DateTimeZone($timeZone));
        } catch (\Exception $e) {
            throw new UnableToConvertExcelDateException(
                "Unable to convert excel date '$value' into DateTime object: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}