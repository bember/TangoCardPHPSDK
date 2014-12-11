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
require_once "tangocard_exception.php";

class TangoCardBase {

    /**
     * Version.
     */
    const VERSION = '1.0.0';

    /**
     * Default options for curl.
     *
     * @var array
     */
    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_USERAGENT => 'tangocard-php-1.0',
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => TRUE
    );

    /**
     * $appVersion defines tangocard version
     *
     * @var string
     */
    public static $appVersion = 'v1';

    /**
     * $apiUrls defines wether the app is in sandbox or production
     *
     * @var array
     */
    public static $apiUrls = array(
        'sandbox' => 'https://sandbox.tangocard.com/raas/',
//        'production' => 'https://integration-api.tangocard.com/raas/'
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
        'orderHistory' => 'orders?',
    );

    protected function makeRequest($requestUrl, $params = False, $isPost = FALSE) {
        $ch = curl_init();
        $opts = self::$CURL_OPTS;
        curl_setopt_array($ch, $opts);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tangocard_digicert_chain.pem');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization:Basic '. base64_encode($this->platformName.':'.$this->platformKey)
        ));
        $result = curl_exec($ch);
        $error = curl_error($ch);
        //to configure error msg array
        if ($result === false) {
            curl_close($ch);
            throw $error;
        }
//end error array config
        curl_close($ch);
        return $result;
    }

}

?>