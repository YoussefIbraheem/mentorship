<?php

use NotificationType;


class PushNotification implements NotificationType
{
    public function __construct(
        private ?string $subject,
        private string $recepientName,
        private array $body
    ) {}

    #[Override]
    public function send()
    {
        $subject = $this->subject;
        $recepientName = $this->recepientName;
        $message = $this->body;

        // sending PN mechanism
    }
}
