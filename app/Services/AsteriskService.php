<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Response\ResponseMessage;
use PAMI\Exception\PAMIException; // Don't forget to use the exception class

class AsteriskService
{
    protected $options;

    public function __construct()
    {
        $this->options = [
            'host' => env('ASTERISK_AMI_HOST', '127.0.0.1'),
            'scheme' => 'tcp://',
            'port' => env('ASTERISK_AMI_PORT', 5038),
            'username' => env('ASTERISK_AMI_USERNAME', 'laravel'),
            'secret' => env('ASTERISK_AMI_SECRET', 'yourpassword'),
            'connect_timeout' => 10000,
            'read_timeout' => 10000
        ];
    }

    protected function getClient()
    {
        $client = new ClientImpl($this->options);
        try {
            $client->open();
            return $client;
        } catch (PAMIException $e) {
            Log::error("Error opening PAMI connection: " . $e->getMessage());
            return null;
        }
    }

    protected function releaseClient(?ClientImpl $client)
    {
        if ($client && $client->isConnected()) {
            try {
                $client->close();
            } catch (PAMIException $e) {
                Log::error("Error closing PAMI connection: " . $e->getMessage());
            }
        }
    }

    public function testConnection()
    {
        $client = $this->getClient();
        if (!$client) {
            return null; // Or handle the error
        }
        try {
            $response = $client->send(new PingAction());
            return $response;
        } catch (PAMIException $e) {
            Log::error("PAMI Exception during testConnection: " . $e->getMessage());
            return null;
        } finally {
            $this->releaseClient($client);
        }
    }

    public function originateCall($from, $to)
    {
        $client = $this->getClient();
        if (!$client) {
            return null; // Or handle the error
        }
        try {
            $originateMsg = new OriginateAction("SIP/$from");
            $originateMsg->setContext('from-internal');
            $originateMsg->setExtension($to);
            $originateMsg->setPriority(1);
            $originateMsg->setCallerId($from);
            $originateMsg->setTimeout(30000);

            $response = $client->send($originateMsg);
            return $response;
        } catch (PAMIException $e) {
            Log::error("PAMI Exception during originateCall: " . $e->getMessage());
            return null;
        } finally {
            $this->releaseClient($client);
        }
    }
}
