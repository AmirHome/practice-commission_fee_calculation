<?php

namespace App\Classes;

use Illuminate\Support\Facades\Cache;
use App\Classes\CurrencylayerEndpoint;

class Commission
{

    /**
     * @param array $row
     */
    public function __construct(array $row)
    {
        $this->operationDate = $row[0];
        $this->userId = $row[1];
        $this->userType = $row[2];
        $this->operationType = $row[3]; //
        $this->operationAmount = $row[4];
        $this->operationCurrency = $row[5];
        $this->currency = new CurrencylayerEndpoint;
    }

    public function fee(): float
    {
        return $this->calculateFee();
    }

    private function calculateFee(): float
    {

        $fee = 0;
        // Realize Operation Type
        switch (strtolower($this->operationType)) {
            case 'withdraw':
                // Realize User Type
                switch (strtolower($this->userType)) {
                    case 'private':
                        $fee = $this->chargedWithdrawPrivateAmount();
                        break;
                    case 'business':
                        $fee = $this->chargedWithdrawBusinessAmount();
                        break;

                }
                break;
            case 'deposit':
                $fee = $this->chargedDepositAmount();
                break;

        }

        return $fee;
    }

    private function chargedWithdrawPrivateAmount(): float
    {
        //return roundUpPercent($this->operationAmount, 100, 2);
        // Set Limit and Expire Date of free commission
        // Free Commission until 1000 EUR
        $amount = 0;
        $userFreeCommission['limit'] = 1000;//EUR
        $attemptsPerWeek = 1;

        if (Cache::has($this->userId)) {
            // Calculate Limit and Expire Date of free commission
            $userFreeCommission = Cache::get($this->userId);
            $attemptsPerWeek = $this->getAttemptsPerWeek();
            // New Week
            if($attemptsPerWeek == 1) { $userFreeCommission['limit'] = 1000;}
        }


        $amountEuro = ('EUR' == $this->operationCurrency) ? $this->operationAmount : $this->currency->mockCurrencyConversion($this->operationAmount, $this->operationCurrency );

        if ($userFreeCommission['limit'] <=  $amountEuro) {
            $limit = 0;
        } else {
            $limit = $userFreeCommission['limit'] - $amountEuro;
        }

        Cache::put($this->userId, ['limit' => $limit,
            'expire_week' => getWeekendDate($this->operationDate),
            'attempt_week' => $attemptsPerWeek]);

        if (0 == $limit) {
            if (0 == $userFreeCommission['limit']){
                $amount = $this->operationAmount;
            }else{
                $amount = $amountEuro - $userFreeCommission['limit'];
                $amount = ('EUR' == $this->operationCurrency) ? $amount : $this->currency->mockCurrencyConversion($amount,  $this->operationCurrency ,true);
            }

        }
        if(4 <= $attemptsPerWeek){
            $amount = $this->operationAmount;
        }

        return roundUpPercent($amount, 0.3, ('JPY' != $this->operationCurrency) ?? 0);
    }

    private function chargedWithdrawBusinessAmount(): float
    {
        return roundUpPercent($this->operationAmount, 0.5, 2);
    }

    private function chargedDepositAmount(): float
    {
        return roundUpPercent($this->operationAmount, 0.03, 2);
    }

    private function getAttemptsPerWeek():int
    {
        $attempts = 0;
        $userFreeCommission = Cache::get($this->userId);
        if ($this->operationDate < $userFreeCommission['expire_week']){
            $attempts = $userFreeCommission['attempt_week'];
        }
        return $attempts+1;
    }
}
