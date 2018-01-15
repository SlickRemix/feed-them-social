<?php

namespace feedthemsocial;

/**
 *  SteemLayer - PHPSteemtools transport class
 *
 * This class is responisble for making the RPC calls to the blockchain
 * it can use cURL or the WebSocket client (recommended)
 *
 * @author dragosroua@gmail.com
 */
class SteemLayer {

    private $debug = false;
    // replace with your own seed / rpc node URL
    private $webservice_url = 'api.steemit.com/';
    private $throw_exception = false;
    private $scheme = 'https://';

    public function __construct($config = array()) {

        if (array_key_exists('debug', $config)) {
            $this->debug = $config['debug'];
        }
        if (array_key_exists('webservice_url', $config)) {
            $this->webservice_url = $config['webservice_url'];
        }
        if (array_key_exists('throw_exception', $config)) {
            $this->throw_exception = $config['throw_exception'];
        }
    }

    public function call($method, $params = array(), $transport = 'curl') {
        $request = $this->getRequest($method, $params);
        $response = '';
        if ($transport == 'curl') {
            $response = $this->curl($request);
        } else if ($transport == 'websocket') {
            $response = $this->websocket($request);
        }
        if (array_key_exists('error', $response)) {
            if ($this->throw_exception) {
                throw new Exception($response['error']);
            } else {
                return $response;
            }
        }
        return $response['result'];
    }

    public function getRequest($method, $params) {
        $request = array(
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params,
            "id" => 0
        );

        $request_json = json_encode($request);

        if ($this->debug) {
            echo "<pre><br>request_json<br/>";
            print $request_json . "\n";
            echo "</pre>";
        }
        return $request_json;
    }

    public function curl($data) {
        $ch = curl_init();
        $this->scheme = 'https://';
        curl_setopt($ch, CURLOPT_URL, $this->scheme . $this->webservice_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        if ($this->debug) {
            echo "<pre><br>result<br>";
            print $result . "\n";
            echo "</pre>";
        }

        $result = json_decode($result, true);

        return $result;
    }

    public function websocket($data) {
        $this->scheme = 'wss://';
        $client = new Client($this->scheme . $this->webservice_url);
        $client->send($data);
        $result = $client->receive();
        if ($this->debug) {
            echo "<pre><br>result<br>";
            print $result . "\n";
            echo "</pre>";
        }

        $result = json_decode($result, true);

        return $result;
    }

}