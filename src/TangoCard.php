<?php

/**
 * Copyright 2014 Sourcefuse, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
require_once "TangoCardBase.php";

class TangoCard extends TangoCardBase {

    /**
     * The Application Mode.
     *
     * @var string
     */
    protected $appMode;

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
        $this->appMode = $appMode;
        return $this;
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
     * Set the Platform Name.
     *
     * @param string $platformName The platform Name
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
     * @return string the Platform Name
     */
    public function getPlatformName() {
        return $this->platformName;
    }

    /**
     * Set the Platform key.
     *
     * @param string $platformKey The Platform Key
     *
     * @return BaseTangoCard
     */
    public function setPlatformKey($platfromKey) {
        $this->platformKey = $platfromKey;
        return $this;
    }

    /**
     * Get the App Secret.
     *
     * @return string the App Secret
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
        $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['createAccount'];
        $t = parent::makeRequest($requestUrl, $data, TRUE);
        echo $t;
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
            $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['registerCreditCard'];
            $t = parent::makeRequest($requestUrl, $data, TRUE);
            echo $t;
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
            $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['fundAccount'];
            $t = parent::makeRequest($requestUrl, $data, TRUE);
            echo $t;
        } else {
            //throw exception
        }
    }

    public function deleteCreditCard($customer, $accountIdentifier, $cc_token) {
        $data['customer'] = $customer;
        $data['account_identifier'] = $accountIdentifier;
        $data['cc_token'] = $cc_token;
        if ($data) {
            $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['deleteCreditCard'];
            $t = parent::makeRequest($requestUrl, $data, TRUE);
            echo $t;
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
            $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['placeOrder'];
//            die($requestUrl);
            $t = parent::makeRequest($requestUrl, $data, TRUE);
            echo $t;
        } else {
            //throw exception
        }
    }

    public function getOrderInfo($orderId) {
        $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['getOrderInfo'] . $orderId;
        $t = parent::makeRequest($requestUrl);
        echo $t;
    }

    public function getAccountInfo($customer, $accountId) {
        if ($customer && $accountId) {
            $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['getAccountInfo'] . $customer . '/' . $accountId;
            $t = parent::makeRequest($requestUrl);
            echo $t;
        } else {
            //throw exception
        }
    }

    public function listRewards() {
        $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['listRewards'];
        $t = parent::makeRequest($requestUrl);
        echo $t;
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
        $requestUrl = parent::$apiUrls['sandbox'] . parent::$appVersion . '/' . parent::$url['orderHistory'] . $query;
        $t = parent::makeRequest($requestUrl);
        echo $t;
    }

}
