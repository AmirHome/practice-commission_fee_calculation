<?php

namespace Tests\Unit;

use App\Classes\Commission;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_fee()
    {
        $row = ['2014-12-31',4,'private','withdraw',1200.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('0.60', $fee);

        $row = ['2015-01-01',4,'private','withdraw',1000.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('3.00', $fee);

        $row = ['2016-01-05',4,'private','withdraw',1000.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('0.00', $fee);

        /* */
        $row = ['2016-01-06',5,'private','withdraw',200.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('0.00', $fee);

        $row = ['2016-01-07',5,'private','withdraw',200.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('0.00', $fee);

        $row = ['2016-01-08',5,'private','withdraw',200.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('0.00', $fee);

        $row = ['2016-01-10',5,'private','withdraw',200.00,'EUR'];
        $commission = new Commission($row);
        $fee = $commission->fee();
        $this->assertEquals('0.60', $fee);
    }
}
