<?php

namespace CyanoFresh\PrivatBankAPI;

use CyanoFresh\PrivatBankAPI\dto\BalanceInfo;
use CyanoFresh\PrivatBankAPI\dto\Statement;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Merchant
{
    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var ClientInterface
     */
    protected $client;

    const API_URL = 'https://api.privatbank.ua/p24api';
    const DATE_FORMAT = 'd.m.Y';

    /**
     * Merchant constructor.
     *
     * @param string|int $id
     * @param string $password
     * @param string $country
     */
    public function __construct($id, $password, $country = 'UA')
    {
        $this->id = $id;
        $this->password = $password;
        $this->country = $country;

        $this->client = new Client();
    }

    /**
     * @param string $card Card Number
     * @return array
     */
    public function fetchBalanceInfo($card)
    {
        $data = '<oper>cmt</oper>
            <wait>90</wait>
            <test>0</test>
            <payment id="">
                <prop name="cardnum" value="' . $card . '" />
                <prop name="country" value="' . $this->country . '" />
            </payment>';

        return $this->request('/balance', $data);
    }

    /**
     * @param string $card
     * @return BalanceInfo
     */
    public function getBalanceInfo($card)
    {
        $balanceInfo = $this->fetchBalanceInfo($card);

        return BalanceInfo::fromResponse($balanceInfo);
    }

    /**
     * @param string $card Card Number
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @return array
     */
    public function fetchStatements($card, $dateStart, $dateEnd)
    {
        $dateStartString = $dateStart->format(self::DATE_FORMAT);
        $dateEndString = $dateEnd->format(self::DATE_FORMAT);

        $data = '<oper>cmt</oper>
            <wait>90</wait>
            <test>0</test>
            <payment id="">
                <prop name="sd" value="' . $dateStartString . '"/>
                <prop name="ed" value="' . $dateEndString . '"/>
                <prop name="cardnum" value="' . $card . '"/>
                <prop name="country" value="' . $this->country . '" />
            </payment>';

        return $this->request('/rest_fiz', $data);
    }

    /**
     * @param string $card
     * @param \DateTime $dateStart The day before, if not passed
     * @param \DateTime $dateEnd Today, if not passed
     * @return Statement[]
     */
    public function getStatements($card, $dateStart = null, $dateEnd = null)
    {
        if (!$dateEnd) {
            $dateEnd = new \DateTime();
        }

        if (!$dateStart) {
            $dateStart = clone $dateEnd;
            $dateStart->modify('-1 day');
        }

        $response = $this->fetchStatements($card, $dateStart, $dateEnd);

        return Statement::arrayFromResponse($response);
    }

    /**
     * @param string $url
     * @param string $data
     * @return array
     */
    protected function request($url, $data)
    {
        $signature = $this->sign($data);

        $requestData = '<?xml version="1.0" encoding="UTF-8"?>
            <request version="1.0">
                <merchant>
                    <id>' . $this->id . '</id>
                    <signature>' . $signature . '</signature>
                </merchant>
                <data>' . $data . '</data>
            </request>';

        $response = $this->client->post(self::API_URL . $url, [
            'body' => $requestData,
        ]);

        return $this->parseXml($response->getBody());
    }

    /**
     * @param string $xmlString
     * @return array
     */
    protected function parseXml($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);

        return json_decode($json, true);
    }

    /**
     * @param string $data
     * @return string
     */
    protected function sign($data)
    {
        return sha1(md5($data . $this->password));
    }
}
