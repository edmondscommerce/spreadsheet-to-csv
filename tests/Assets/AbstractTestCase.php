<?php declare(strict_types=1);

namespace EdmondsCommerce\SpreadsheetToCsv\Tests\Assets;

use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    protected function copyFixture(string $from, string $to): void
    {
        copy($from, $to);
    }
}