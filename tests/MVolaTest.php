<?php


use PHPUnit\Framework\TestCase;

class MVolaTest extends TestCase
{

    /**
     * Test: missing consumer key or consumer secret
     */
    public function test_missing_consumer_key_or_consumer_secret_throw_invalid_argument_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Consumer key and secret are required');
        new Hulsia\MVola\MVola();
    }

    /**
     * Test: fetching access token with invalid consumer key and/or invalid consumer secret
     */
    public function test_fetch_access_token_with_invalid_credentials()
    {
        $this->expectExceptionCode(401);
        $mvola = new Hulsia\MVola\MVola([
            'consumerKey' => 'DUMMY_CONSUMER_KEY',
            'consumerSecret' => 'DUMMY_CONSUMER_SECRET'
        ]);
        $mvola->fetchAccessToken();
    }


}
