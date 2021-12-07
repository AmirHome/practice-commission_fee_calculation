<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelperGeneralTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_general_round_up()
    {
        $this->assertEquals(0.03,roundUp(0.023, 2));

        $this->assertEquals(1025,roundUp(1024.654321, 0));
        $this->assertEquals(1024.7,roundUp(1024.654321, 1));
        $this->assertEquals(1024.66,roundUp(1024.654321, 2));
        $this->assertEquals(1024.655,roundUp(1024.654321, 3));
        $this->assertEquals(1024.6544,roundUp(1024.654321, 4));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_general_round_up_percent()
    {
        $this->assertEquals('0.06',roundUpPercent(200, 0.03, 2));
        $this->assertEquals('3.00',roundUpPercent(10000, 0.03, 2));

        $this->assertEquals('1.50',roundUpPercent(300.00, 0.5, 2));

        $this->assertEquals('120.00',roundUpPercent(1200, 10, 2));

        $this->assertEquals('1,200.10',number_format(roundUpPercent(12001, 10, 2),2));

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_general_get_weekend_date()
    {

        /* https://www.calculator.net/day-of-the-week-calculator.html?today=12%2F31%2F2014&x=72&y=20 */
        $this->assertEquals('2015-01-05',getWeekendDate('2014-12-31'));
    }
}
