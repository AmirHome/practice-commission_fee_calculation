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
        return $this->calculateFee() ;
        Cache::get($this->userId);
    }

    private function calculateFee():float
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
        Cache::put($this->userId, $this->operationAmount);

    }

    private function chargedDepositAmount():float
    {
        return roundUpPercent( $this->operationAmount, 0.03, 2) ;
    }

    private function chargedWithdrawBusinessAmount():float
    {
        return roundUpPercent( $this->operationAmount, 0.5, 2) ;
    }

    private function chargedWithdrawPrivateAmount():float
    {
        return roundUpPercent( $this->operationAmount, 100, 2) ;
    }
}
