<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use DTApi\Helpers\TeHelper;

final class WillExpireAtTest extends TestCase
{
        private $now;

    protected function setUp(): void
    {
        $this->now = Carbon::now();
    }

    public function testWillExpireAtWhenDifferenceIsLessOrEqualTo90()
    {
        $due = $this->now->copy()->addHours(90);
        $this->assertEquals($due->format('Y-m-d H:i:s'), TeHelper::willExpireAt($due, $this->now));
    }

    public function testWillExpireAtWhenDifferenceIsLessOrEqualTo24()
    {
        $due = $this->now->copy()->addHours(24);
        $expectedTime = $this->now->copy()->addMinutes(90)->format('Y-m-d H:i:s');
        $this->assertEquals($expectedTime, TeHelper::willExpireAt($due, $this->now));
    }

    public function testWillExpireAtWhenDifferenceIsGreaterThan24AndLessOrEqualTo72()
    {
        $due = $this->now->copy()->addHours(72);
        $expectedTime = $this->now->copy()->addHours(16)->format('Y-m-d H:i:s');
        $this->assertEquals($expectedTime, TeHelper::willExpireAt($due, $this->now));
    }

    public function testWillExpireAtWhenDifferenceIsGreaterThan72()
    {
        $due = $this->now->copy()->addHours(73);
        $expectedTime = $due->copy()->subHours(48)->format('Y-m-d H:i:s');
        $this->assertEquals($expectedTime, TeHelper::willExpireAt($due, $this->now));
    }
}