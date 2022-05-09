<?php

include '../vendor/autoload.php';

/**
 * @see https://www.mvola.mg/devportal/home to get your credentials
 * @return \Hulsia\MVola\MVola mvola API
 */
$mvola = new \Hulsia\MVola\MVola([
    'consumerKey' => 'YOUR_CONSUMER_KEY',
    'consumerSecret' => 'YOUR_CONSUMER_SECRET',
    'live' => false, // by default, the library will use the sandbox environment
]);

/**
 * Configuration of the merchant pay API
 * @return \Hulsia\MVola\MerchantPay merchant API instance
 */
$merchantPay = new \Hulsia\MVola\MerchantPay($mvola, [
    'UserLanguage' => 'MG',
    'UserAccountIdentifier' => 'msisdn;0343500004',
    'partnerName' => 'MVola',
]);

/**
 * Example of transaction charge with the merchant pay API
 * @return array The response from the MVola API
 */
$transtation = $merchantPay->initTransaction([
    'amount' => '10000',
    'currency' => 'Ar', // currency must be 'Ar'
    'descriptionText' => 'Test transaction',
    'requestingOrganisationTransactionReference' => '123456789',
    'originalTransactionReference' => '123456789',
    'debitParty' => '0343500003',
    'creditParty' => '0343500004',
    'partnerName' => 'MVola',
]);
