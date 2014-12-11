<?php

/**
 * Copyright 2014 Sourcefuse, Inc.
 *    
 *    The MIT License (MIT)
 *
 *    Copyright (c) 2014 SourceFuse
 *
 *    Permission is hereby granted, free of charge, to any person obtaining a copy
 *    of this software and associated documentation files (the "Software"), to deal
 *    in the Software without restriction, including without limitation the rights
 *    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *    copies of the Software, and to permit persons to whom the Software is
 *    furnished to do so, subject to the following conditions:
 *
 *    The above copyright notice and this permission notice shall be included in all
 *    copies or substantial portions of the Software.
 *
 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *    SOFTWARE.

 */
require_once "TangoCardBase.php";

class TangoCard extends TangoCardBase {

    /**
     * The Application Mode.
     *
     * @var string
     */
    protected $appMode = "production";

    /**
     * The Application ID.
     *
     * @var string
     */
    protected $platformName;

    /**
     * The Application App Secret.
     *
     * @var string
     */
    protected $platformKey;

    /**
     * $appVersion defines tangocard RAAS api version
     *
     * @var string
     */
    protected $tangoCardApiVersion = 'v1';

    /**
     * set application Configurations.
     */

    /**
     * Set the Application Mode.
     *
     * @param string $appMode The application mode
     *
     * @return BaseTangoCard
     */
    public function setAppMode($appMode) {
        if (in_array($appMode, array_keys(self::$appModes)))
            $this->appMode = $appMode;
        else
            throw new TangoCardAppModeInvalidException();

        return $this;
    }

    public static $appModes = array(
        'sandbox' => 'https://sandbox.tangocard.com/raas',
        'production' => 'https://integration-api.tangocard.com/raas'
    );

    /**
     * $url contains available tangocard api's url
     *
     * @var array
     */
    public static $url = array(
        'createAccount' => 'accounts',
        'getAccountInfo' => 'accounts/',
        'registerCreditCard' => 'cc_register',
        'fundAccount' => 'cc_fund',
        'deleteCreditCard' => 'cc_unregister',
        'listRewards' => 'rewards',
        'placeOrder' => 'orders',
        'getOrderInfo' => 'orders/',
        'orderHistory' => 'orders?'
    );

    public function getRequestUrl($request_type) {
        $request_types=array_keys(self::$url);
        if(!in_array($request_type,  $request_types)) {
            throw new TangoCardRequestTypeInvalidException();
        }
        $tangoCardApiUrl = self::$appModes[$this->appMode];
        $requestEndpoint = self::$url[$request_type];
        $url=$tangoCardApiUrl . "/" . $this->tangoCardApiVersion . "/" . $requestEndpoint;
        return $url;
    }

    /**
     * Get the Applicaton Mode.
     *
     * @return string the application Mode
     */
    public function getAppMode() {
        return $this->appMode;
    }

    /**
     * Set the Tango Card Api Version.
     *
     * @param string $apiVersion contains TangoCard Raas Api version
     *
     * @return BaseTangoCard
     */
    public function setTangoCardApiVersion($apiVersion) {
        $this->tangoCardApiVersion = $apiVersion;
        return $this;
    }

    /**
     * Get the Tango Card Api Version.
     *
     * @return string the Tangocard RAAS api version
     */
    public function getTangoCardApiVersion() {
        return $this->tangoCardApiVersion;
    }

    /**
     * Set the Platform Name.
     *
     * @param string $platformName The platform Name provided by Tango Card
     *
     * @return BaseTangoCard
     */
    public function setPlatformName($platformName) {
        $this->platformName = $platformName;
        return $this;
    }

    /**
     * Get the Platform Name.
     *
     * @return string the Platform Name provided by Tango Card
     */
    public function getPlatformName() {
        return $this->platformName;
    }

    /**
     * Set the Platform key.
     *
     * @param string $platformKey The Platform Key  (app secret) Provided by Tango Card
     *
     * @return BaseTangoCard
     */
    public function setPlatformKey($platfromKey) {
        $this->platformKey = $platfromKey;
        return $this;
    }

    /**
     * Get the Platform Key.
     *
     * @return string The Platform key (app secret) provided by Tango Card
     */
    public function getPlatformKey() {
        return $this->platformKey;
    }

    public function __construct($platformName, $platformKey) {
        $this->setPlatformName($platformName);
        $this->setPlatformKey($platformKey);
    }

    public function createAccount($customer, $accountIdentifier, $email) {
        $data['customer'] = $customer;
        $data['identifier'] = $accountIdentifier;
        $data['email'] = $email;
        $requestUrl = $this->getRequestUrl('createAccount');
        $response = parent::makeRequest($requestUrl, $data, TRUE);
        return json_decode($response);

    }

    public function registertCreditCard($customer, $accountIdentifier, $ccNumber, $securityCode, $expiration, $fName, $lName, $address, $city, $state, $zip, $country, $email) {
        $data['customer'] = $customer;
        $data['account_identifier'] = $accountIdentifier;
        $data['client_ip'] = $_SERVER['REMOTE_ADDR'];
        $ccInfo['number'] = $ccNumber;
        $ccInfo['expiration'] = $expiration;
        $ccInfo['security_code'] = $securityCode;
        $billingInfo['f_name'] = $fName;
        $billingInfo['l_name'] = $lName;
        $billingInfo['email'] = $email;
        $billingInfo['address'] = $address;
        $billingInfo['city'] = $city;
        $billingInfo['state'] = $state;
        $billingInfo['country'] = $country;
        $billingInfo['zip'] = $zip;
        $ccInfo['billing_address'] = $billingInfo;
        $data['credit_card'] = $ccInfo;
        if ($data) {
            $requestUrl = $this->getRequestUrl('registerCreditCard');
            $response = parent::makeRequest($requestUrl, $data, TRUE);
            return json_decode($response);
        } else {
            //throw exception
        }
    }

    public function fundAccount($customer, $accountIdentifier, $amount, $cc_token, $security_code) {
        $data['customer'] = $customer;
        $data['account_identifier'] = $accountIdentifier;
        $data['amount'] = $amount;
        $data['cc_token'] = $cc_token;
        $data['security_code'] = $security_code;
        $data['client_ip'] = $_SERVER['REMOTE_ADDR'];
        if ($data) {
            $requestUrl = $this->getRequestUrl('fundAccount');
            $response = parent::makeRequest($requestUrl, $data, TRUE);
            echo $response;
        } else {
            //throw exception
        }
    }

    public function deleteCreditCard($customer, $accountIdentifier, $cc_token) {
        $data['customer'] = $customer;
        $data['account_identifier'] = $accountIdentifier;
        $data['cc_token'] = $cc_token;
        if ($data) {
            $requestUrl = $this->getRequestUrl('deleteCreditCard');
            $response = parent::makeRequest($requestUrl, $data, TRUE);
            return json_decode($response);
        } else {
            //throw exception
        }
    }

    public function placeOrder($customer, $accountIdentifier, $campaign, $rewardFrom, $rewardSubject, $rewardMessage, $Sku, $recipientName, $recipientEmail) {
        $data['customer'] = $customer;
        $data['account_identifier'] = $accountIdentifier;
        $data['campaign'] = $campaign;
        $data['reward_from'] = $rewardFrom;
        $data['reward_subject'] = $rewardSubject;
        $data['reward_message'] = $rewardMessage;
        $data['send_reward'] = TRUE;
        $data['sku'] = $Sku;
        $data['recipient']['name'] = $recipientName;
        $data['recipient']['email'] = $recipientEmail;
        if ($data) {
            $requestUrl = $this->getRequestUrl('placeOrder');
//            die($requestUrl);
            $response = parent::makeRequest($requestUrl, $data, TRUE);
            return json_decode($response);
        } else {
            //throw exception
        }
    }

    public function getOrderInfo($orderId) {
        $requestUrl = $this->getRequestUrl('getOrderInfo') . $orderId;
        $response = parent::makeRequest($requestUrl);
        return json_decode($response);
    }

    public function getAccountInfo($customer, $accountId) {
        if ($customer && $accountId) {
            $requestUrl = $this->getRequestUrl('getAccountInfo') . $customer . '/' . $accountId;
            $response = parent::makeRequest($requestUrl);
            return json_decode($response);
            return json_decode($t);

        } else {
            //throw exception
        }
    }

    public function listRewards() {
        $requestUrl = $this->getRequestUrl('listRewards');
        $response = parent::makeRequest($requestUrl);
        return json_decode($response);
    }

    public function getOrderHistory($customer, $accountId, $offset = NULL, $limit = NULL, $startDate = NULL, $endDate = NULL) {
        $query = '';
        if ($customer) {
            $query.='customer=' . $customer;
        }
        if ($accountId) {
            $query.='&account_identifier=' . $accountId;
        }

        if ($offset || $offset == 0) {
            $query.='&offset=' . $offset;
        }
        if ($limit) {
            $query.='&limit=' . $limit;
        }
        if ($startDate) {
            $query.='&start_date=' . $startDate;
        }
        if ($endDate) {
            $query.='&end_date=' . $endDate;
        }
        $requestUrl = $this->getRequestUrl('orderHistory') . $query;
        $response = parent::makeRequest($requestUrl);
        return json_decode($response);
    }

}
