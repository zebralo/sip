<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Response\ResponseMessage;

class AsteriskService
{
    protected $client;

    public function __construct()
    {
        $options = [
            'host' => env('ASTERISK_AMI_HOST', '127.0.0.1'),
            'scheme' => 'tcp://',
            'port' => env('ASTERISK_AMI_PORT', 5038),
            'username' => env('ASTERISK_AMI_USERNAME', 'laravel'),
            'secret' => env('ASTERISK_AMI_SECRET', 'yourpassword'),
            'connect_timeout' => 10000,
            'read_timeout' => 10000
        ];

        $this->client = new ClientImpl($options);
        $this->client->open();
    }

    public function testConnection()
    {
        $response = $this->client->send(new PingAction());
        $this->client->close();
        return $response;
    }

    public function originateCall($from, $to)
    {
        $originateMsg = new OriginateAction("SIP/$from");
        $originateMsg->setContext('from-internal');
        $originateMsg->setExtension($to);
        $originateMsg->setPriority(1);
        $originateMsg->setCallerId($from);
        $originateMsg->setTimeout(30000);

        $response = $this->client->send($originateMsg);
        $this->client->close();

        return $response;
    }
}
