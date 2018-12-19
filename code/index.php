<?php
require_once __DIR__ .'/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

$res = sendMessage();
var_dump($res);

function sendMessage() {

    // apps/device token generated from firebase iOS/Android sdk
	$token = ''; 
    // server key getting from.. Firebase Console > Setting > Cloud Messaging Tab
	$key = ''; 
	$body = [
        'to' => $token,
        'priority' => 'high',
        'notification' => [
        	"body" => "Awesome repos has been created since early this year", 
        	"title" => "Breaking News Github Channel",
        ],
    ];

    try {
        // set headers and body according to firebase docs 
        // https://firebase.google.com/docs/cloud-messaging/server#implementing-http-connection-server-protocol
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . $key,
        ];
        
        // send request
        $requestUrl = 'https://fcm.googleapis.com/fcm/send';

        $client = new Client;
        
        $response = $client->request('POST', $requestUrl, [
            'headers' => $headers,
            'json' => $body
        ]);
        
        $responseData = json_decode($response->getBody());
        
        return [
            'data' => $responseData,
            'statusCode' => $response->getStatusCode()
        ];
    }
    catch (\GuzzleHttp\Exception\ClientException $e){
        // handle request exception to firebase service here
        return [
            'statusCode' => $e->getCode(),
            'data' => $e->getResponse()->getBody()->getContents(), // content currently in html
        ];            
    }
    catch (Exception $e) {
        return [
            'statusCode' => $e->getCode(),
            'data' => $e->getMessage(),
        ];
    }
}


        