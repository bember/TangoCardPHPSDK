### New SDK Released

We've released version 1 of the TanagoCard RAAS SDK for PHP here: [https://github.com/sourcefuse/TangoCardPHPSDK](https://github.com/sourcefuse/TangoCardPHPSDK)
Please use the new repository for new projects and contributions.

-----

TangoCard RAAS PHP SDK (v.1.0.0)

This repository contains the open source PHP SDK that allows you to access TangoCard RAAS from your PHP app. Except as otherwise noted,
the TangoCard PHP SDK is licensed under the MIT License (MIT).


Usage
-----

The minimal you'll need to have is:

$tangocard = new TangoCard('PLATFORM_ID','PLATFORM_KEY');
'PLATFORM_ID' & 'PLATFORM_KEY' will be provided by TangoCard
$tangocard->setAppMode("sandbox") 

sandbox for Sandbox mode and production for Sandbox mode. By Default it is set to "production"



Raas API Calls:

1) Create an Account 

$tangoCard->createAccount($customer, $accountIdentifier, $email);

2) Get Account Information 

$tangoCard->getAccountInfo($customer, $accountId);

3) Register Credit Card

$tangoCard->registertCreditCard($customer, $accountIdentifier, $ccNumber, $securityCode, $expiration, $fName, $lName, $address, $city, $state, $zip, $country, $email, $clientIp);

4) Fund an Account 

$tangoCard->fundAccount($customer, $accountIdentifier, $amount, $ccToken, $securityCode, $clientIp);

5) Delete a Credit Card

$tangoCard->deleteCreditCard($customer, $accountIdentifier, $ccToken);

5) Get a List of Rewards 

$tangoCard->listRewards();

5) Place an Order 

$tangoCard->placeOrder($customer, $accountIdentifier, $campaign, $rewardFrom, $rewardSubject, $rewardMessage, $sku, $amount, $recipientName, $recipientEmail, $sendReward);

6) Get Order Information 

$tangoCard->getOrderInfo($orderId);

7) Get Order History 

$tangoCard->getOrderHistory($customer, $accountId, $offset, $limit, $startDate, $endDate);
