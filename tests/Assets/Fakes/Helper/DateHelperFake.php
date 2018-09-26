<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Assets\Fakes\Helper;

use EdmondsCommerce\SpreadsheetToCsv\Helper\DateHelper;

class DateHelperFake extends DateHelper
{
    /**
     * @var \DateTime
     */
    private $dateTime;

    public function __construct()
    {
        $this->dateTime = new \DateTime();
    }

    public function excelToDateTimeObject(int $value, string $timeZone = 'UTC'): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}