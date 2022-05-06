<?php

namespace Hulsia\MVola;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

/**
 * Class MVola
 */
class MVola
{

    /** @var string Version of the API */
    const API_VERSION = '1.0';

    /** @var string Sandbox API Base URL */
    const SANDBOX_BASE = 'https://devapi.mvola.mg/';

    /** @var string Live API Base URL */
    const LIVE_BASE = 'https://api.mvola.mg/';

    /** @var array MVola API config */
    protected array $config = [];

    /** @var Client Guzzle HTTP Client */
    private Client $client;

    /** @var array Access token */
    private array $accessToken = [];

    /**
     * MVola constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!is_array($config)) {
            throw new InvalidArgumentException('Config must be an array');
        }

        $this->config = array_merge([
            'consumerKey' => null,
            'consumerSecret' => null,
            'live' => false,
            'version' => self::API_VERSION
        ], $config);

        $this->client = new Client([
            'base_uri' => $this->getBaseUrl(),
        ]);
    }

    /**
     * @return array MVola API config
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return string Base URL
     */
    private function getBaseUrl(): string
    {
        if (isset($this->config['live']) && $this->config['live']) {
            return self::LIVE_BASE;
        } else {
            return self::SANDBOX_BASE;
        }
    }

    /**
     * @param string|null $scope
     * @return array|null Access token
     * @throws GuzzleException
     */
    public function fetchAccessToken(?string $scope = null): ?array
    {
        if (!$this->config['consumerKey'] || !$this->config['consumerSecret']) {
            throw new InvalidArgumentException('Consumer key and secret are required');
        }

        $req = $this->client->post('token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->config['consumerKey'],
                'client_secret' => $this->config['consumerSecret'],
                'scope' => $scope ?? 'EXT_INT_MVOLA_SCOPE'

            ]
        ]);

        $this->accessToken = json_decode($req->getBody()->getContents(), true);
        return $this->accessToken;
    }

    /**
     * @return ClientInterface Guzzle HTTP Client
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @return array Access token
     * @throws GuzzleException
     */
    public function getAccessToken(): array
    {
        if(!$this->accessToken) {
            $this->fetchAccessToken();
        }
        return $this->accessToken;
    }
}
