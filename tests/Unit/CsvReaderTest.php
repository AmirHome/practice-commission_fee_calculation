<?php

namespace Tests\Unit;

use App\Classes\CSVReader;
use Tests\TestCase;

class CsvReaderTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_csv_reader()
    {
        $csvReader = new CSVReader;
        $input = $csvReader->read('input.csv');

        $this->assertEmpty($input['error']);
        $this->assertNotEmpty($input['data']);

    }
}
