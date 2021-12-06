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
        $this->operationType = $row[3];
        $this->operationAmount = $row[4];
        $this->operationCurrency = $row[5];
    }

    public function fee()
    {
        Cache::put($this->userId, $this->operationAmount);
        return $this->operationAmount;
        return Cache::get($this->userId);
    }
}
