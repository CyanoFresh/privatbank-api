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
     * Retrieves an element within multidimensional array stored on any level by it's keys.
     * @param array $data A multidimensional array with data
     * @param array $keys A list of keys to element stored in $data
     * @return null|mixed Returns null if elements is not found. Element's value otherwise.
     */
    function getElement(array $data, array $keys)
    {
        /** перебираем ключи */
        foreach($keys as $key) {
            if (is_array($data) && array_key_exists($key, $data)) {
                $data = $data[$key];
            } else {
                return false;
            }
        }

        return $data;
    }
    
    /**
     * @param $response
     * @return static[]
     */
    public static function arrayFromResponse($response)
    {
        $statements = [];
        
        $result = $this->getElement($response, ['data', 'info', 'statements', 'statement']);

        if (!$result && count($result) < 1) {
            return $statements;
        }

        foreach ($result as $statement) {
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
