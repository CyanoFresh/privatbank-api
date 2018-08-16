<?php

namespace CyanoFresh\PrivatBankAPI\dto;

class Statement
{
    /**
     * @var string
     */
    public $card;

    /**
     * @var string
     */
    public $appcode;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var string
     */
    public $cardamount;

    /**
     * @var string
     */
    public $rest;

    /**
     * @var string
     */
    public $terminal;

    /**
     * @var string
     */
    public $description;

    /**
     * Statement constructor.
     *
     * @param array $params
     */
    public function __construct($params)
    {
        $this->card = $params['card'];
        $this->appcode = $params['appcode'];
        $this->date = $params['trandate'];
        $this->time = $params['trantime'];
        $this->amount = $params['amount'];
        $this->cardamount = $params['cardamount'];
        $this->rest = $params['rest'];
        $this->terminal = $params['terminal'];
        $this->description = $params['description'];
    }

    /**
     * @param $response
     * @return static[]
     */
    public static function arrayFromResponse($response)
    {
        $statements = [];

        if (!$response['data']['info'] or !$response['data']['info']['statements'] or !$response['data']['info']['statements']['statement'] or count($response['data']['info']['statements']['statement']) < 1) {
            return $statements;
        }

        foreach ($response['data']['info']['statements']['statement'] as $statement) {
            $statements[] = self::fromResponse($statement);
        }

        return $statements;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromResponse($data)
    {
        $data = $data['@attributes'];

        return new static($data);
    }
}
