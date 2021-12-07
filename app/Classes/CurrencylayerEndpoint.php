<?php

namespace App\Classes;

use App\Exceptions\CurrencylayerException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CurrencylayerEndpoint
{
//    protected $client;
//    protected $baseUri;

//    public function __construct(Client $client, $baseUri)
//    {
//        $this->client = $client;
//        $this->baseUri = $baseUri;
//    }

    public function getCurrencyConversion() {

//        $client = new Client();
//        $response = $client->get('https://api.github.com/users');
//
//        $response = $client->get($this->baseUri);
//
//        echo $response->getStatusCode();
//        $response = json_decode($response->getBody(), true);
//
//        dd($response['1']['login']);
//        return collect($response);


//        try {

            $response = $this->client->get($this->baseUri);

            dd($response);

            $response = json_decode($response->getBody(), true);

            return $response;
//        } catch (ClientException $e) {
//            $this->throwException(sprintf('Failed to get currency data.'));
//        }

/*        $client = new Client();
        $response = $client->get('https://api.github.com/users');
        echo $response->getStatusCode();
        $response = json_decode($response->getBody(), true);

        dd($response['1']['login']);
        return collect($response);*/
    }

    public function mockCurrencyConversion($amount, $currency , $reverse = false)
    {
        $mock = new MockHandler([
            $response = new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents('tests/Stubs/response_currencylayer_endpoint_200.json'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $response = $client->get('/');

        $response = json_decode($response->getBody(), true);

        if ($reverse){
            return $amount * $response['quotes']["EUR{$currency}"];

        }else{
            return $amount / $response['quotes']["EUR{$currency}"];
        }


    }
}
