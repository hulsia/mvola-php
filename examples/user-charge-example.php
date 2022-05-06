<?php

/**
 * @see https://www.mvola.mg/devportal/home to get your credentials
 */
$mvola = new \Hulsia\MVola\MVola([
    'consumerKey' => 'YOUR_CONSUMER_KEY',
    'consumerSecret' => 'YOUR_CONSUMER_SECRET',
    'live' => true, // by default, the library will use the sandbox environment
]);

$merchantPay = new \Hulsia\MVola\MerchantPay($mvola, [
    'UserLanguage' => 'MG',
    'UserAccountIdentifier' => 'msisdn;0343500004',
    'partnerName' => 'MVola',
]);

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
