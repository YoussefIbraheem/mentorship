<?php

use Sms;
use NotificationFactory;


class SmsCreator extends NotificationFactory
{

    public function __construct(
        private string $senderNumber,
        private string $recepientNumber,
        private string $body
    ) {}


    protected function formatMessageBody(): string
    {
        return $this->body . "\r\n";
    }


    #[Override]
    protected function createMessage(): Sms
    {
        return new Sms(
            $this->senderNumber,
            $this->recepientNumber,
            $this->body,
        );
    }
}
