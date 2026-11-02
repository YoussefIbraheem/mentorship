<?php

use Email;
use NotificationFactory;


class EmailCreator extends NotificationFactory
{

    public function __construct(
        private string $senderEmail,
        private string $recepientEmail,
        private ?string $subject,
        private string $senderName,
        private string $receipientName,
        private string $body
    ) {}

    protected function formatMessageBody(): string
    {
        return "Dear " . $this->receipientName . "\r\n" .
            $this->body . "\r\n" .
            "Regards,\n" .
            $this->senderName . "\r\n";
    }


    #[Override]
    protected function createMessage(): Email
    {
        return new Email(
            $this->senderEmail,
            $this->recepientEmail,
            $this->subject,
            $this->formatMessageBody(),
        );
    }
}
