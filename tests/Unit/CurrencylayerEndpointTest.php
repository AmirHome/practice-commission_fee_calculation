<?php

namespace Tests\Unit;

use App\Classes\CurrencylayerEndpoint;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;


class CurrencylayerEndpointTest extends TestCase
{

    /** @test */
    public function test_it_can_get_data_from_api()
    {
        $currency = new CurrencylayerEndpoint();
        $res = $currency->mockCurrencyConversion(30000, 'JPY');
        $this->assertEquals('231.61', roundUp($res) );

        $currency = new CurrencylayerEndpoint();
        $res = $currency->mockCurrencyConversion(100, 'USD');
        $this->assertEquals('86.98', roundUp($res) );

        $currency = new CurrencylayerEndpoint();
        $res = $currency->mockCurrencyConversion(3000000, 'JPY');
        $this->assertEquals('23160.66', roundUp($res) );
    }

}
