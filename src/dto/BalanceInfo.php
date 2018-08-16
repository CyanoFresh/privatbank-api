<?php

namespace CyanoFresh\PrivatBankAPI\dto;

class BalanceInfo
{
    /**
     * @var float
     */
    public $balance;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $date;

    /**
     * BalanceInfo constructor.
     *
     * @param float $balance
     * @param string $currency
     * @param string $date
     */
    public function __construct($balance, $currency, $date)
    {
        $this->balance = $balance;
        $this->currency = $currency;
        $this->date = $date;
    }

    /**
     * @param array $response
     * @return static
     */
    public static function fromResponse($response)
    {
        return new static($response['data']['info']['cardbalance']['balance'],
            $response['data']['info']['cardbalance']['card']['currency'],
            $response['data']['info']['cardbalance']['bal_date']);
    }
}
