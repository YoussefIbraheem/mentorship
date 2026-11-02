<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");

use NotificationType;
use Dotenv\Dotenv;
use Twilio\Rest\Client;

class Sms implements NotificationType
{
    private Client $client;
    private Dotenv $dotenv;

    public function __construct(
        private string $senderNumber,
        private string $recepientNumber,
        private string $body,
    ) {
        $this->dotenv = Dotenv::createImmutable(__DIR__);
        $this->dotenv->load();

        $apiKey = $_ENV["TWILIO_API_KEY"];
        $apiSecret = $_ENV["TWILIO_API_SECRET"];
        $sid = $_ENV["TWILIO_ACCOUNT_SID"];

        $this->client = new Client(
            $apiKey,
            $apiSecret,
            $sid
        );
    }


    #[Override]
    public function send()
    {
        try {
            $to = $this->recepientNumber;
            $from = $this->senderNumber;
            $body = $this->body;

            $this->client->messages->create($to, [
                "from" => $from,
                "body" => $body
            ]);

            echo "Message Sent!";
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
