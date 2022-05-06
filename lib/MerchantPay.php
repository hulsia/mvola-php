<?php

namespace Hulsia\MVola;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

class MerchantPay
{

    const BASE_PATH = '/mvola/mm/transactions/type/merchantpay';

    const VERSION = '1.0.0';

    private array $headers = [];

    private MVola $mvola;

    /**
     * @throws GuzzleException
     */
    public function __construct(MVola $mvola, array $options = [])
    {

        $this->mvola = $mvola;
        $correlationId = $options['correlationId'] ?? Uuid::uuid4()->toString();

        $this->headers = array_merge([
            'Version' => self::VERSION,
            'X-CorrelationID' => $correlationId,
            'UserLanguage' => 'MG',
            'UserAccountIdentifier' => null,
            'partnerName' => null,
            'Cache-Control' => 'no-cache',
            'Authorization' => 'Bearer ' . $mvola->getAccessToken()['access_token'],

        ], $options['headers'] ?? []);
    }

    /**
     * @return string The full path to the MerchantPay endpoint
     */
    private function getEndpoint(): string
    {
        return self::BASE_PATH . '/' . self::VERSION;
    }

    /**
     * @param array $transationDetails
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function initTransaction(array $transationDetails = []): ResponseInterface
    {
        $requestDate = (new DateTime('UTC'))->format('Y-m-d\TH:i:s.000\Z');
        $transationDetails = [
            'amount' => $transationDetails['amount'] ?? null,
            'currency' => $transationDetails['currency'] ?? 'Ar',
            'descriptionText' => $transationDetails['descriptionText'] ?? null,
            'requestingOrganisationTransactionReference' => $transationDetails['requestingOrganisationTransactionReference'] ?? null,
            'requestDate' => $requestDate,
            'originalTransactionReference' => $transationDetails['originalTransactionReference'] ?? null,
            'debitParty' => [
                [
                    'key' => 'msisdn',
                    'value' => $transationDetails['debitParty'] ?? null,
                ],
            ],
            'creditParty' => [
                [
                    'key' => 'msisdn',
                    'value' => $transationDetails['creditParty'] ?? null,
                ],
            ],
            'metadata' => [
                [
                    'key' => 'partnerName',
                    'value' => $transationDetails['partnerName'] ?? null,
                ],
                [
                    'key' => 'fc',
                    'value' => 'USD',
                ],
                [
                    'key' => 'amountFc',
                    'value' => '1',
                ],
            ],
        ];

        return $this->mvola->getClient()->post($this->getEndpoint(), [
            'headers' => $this->headers,
            'json' => $transationDetails,
        ]);
    }

}
