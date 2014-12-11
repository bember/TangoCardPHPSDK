<?php

/**
 * Copyright 2014 SourceFUse Technologies.
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
if (!function_exists('curl_init')) {
    throw new Exception('TangoCard needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('TangoCard needs the JSON PHP extension.');
}

/**
 * Thrown when an API call returns an exception.
 *
 * @author nitesh srivastava <nitesh.srivastava@sourcefuse.com>
 */
class TangoCardException extends Exception {

    /**
     * The result from the API server that represents the exception information.
     *
     * @var mixed
     */
    protected $result;

    /**
     * Make a new API Exception with the given result.
     *
     * @param array $result The result from the API server
     */
    public function __construct($result) {
        $this->result = $result;

        //test first
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        die();
        //end test
        $status = false;
        if (isset($result['success']) && $result['success'] = false) {
            if (isset($result['error_message'])) {
                $msg = $result['error_message'];
            } else {
                $msg = 'Unknown Error. Check getResult()';
            }
        }
        parent::__construct($msg, $status);
    }

    /**
     * Return the associated result object returned by the API server.
     *
     * @return array The result from the API server
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * To make debugging easier.
     *
     * @return string The string representation of the error
     */
    public function __toString() {
        $str = $this->getType() . ': ';
        if ($this->code != 0) {
            $str .= $this->code . ': ';
        }
        return $str . $this->message;
    }

}
