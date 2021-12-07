<?php

namespace App\Classes;

use Illuminate\Support\Facades\Cache;

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
        // Set Limit and Expire Date of free commission
        // Free Commission until 1000 EUR
        $amount = 0;
        $userFreeCommission['limit'] = 1000;
        $attemptsPerWeek = 1;

        if (Cache::has($this->userId)) {
            // Calculate Limit and Expire Date of free commission
            $userFreeCommission = Cache::get($this->userId);
            $attemptsPerWeek = $this->getAttemptsPerWeek();
            // New Week
            if($attemptsPerWeek == 1) { $userFreeCommission['limit'] = 1000;}
        }

        switch ($this->operationCurrency) {
            case 'EUR':
                if ($userFreeCommission['limit'] <= $this->operationAmount) {
                    $limit = 0;
                } else {
                    $limit = $userFreeCommission['limit'] - $this->operationAmount;
                }
                break;
            default:

        }

        Cache::put($this->userId, ['limit' => $limit,
            'expire_week' => getWeekendDate($this->operationDate),
            'attempt_week' => $attemptsPerWeek]);

        if (0 == $limit) {
            $amount = $this->operationAmount - $userFreeCommission['limit'];
        }
        if(4 <= $attemptsPerWeek){
            $amount = $this->operationAmount;
        }

        if (false){
            print_r([Cache::get($this->userId),
                $userFreeCommission,
                $limit,
                $amount,
                roundUpPercent($amount, 0.3, 2)
            ]);
            dd('chargedWithdrawPrivateAmount');
        }
        return roundUpPercent($amount, 0.3, 2);
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
