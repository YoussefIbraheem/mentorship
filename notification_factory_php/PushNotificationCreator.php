<?php

use NotificationFactory;
use PushNotification;

class PushNotificationCreator extends NotificationFactory
{
    public function __construct(
        private ?string $subject,
        private string $receipientName,
        private string $body
    ) {}

    protected function formatMessageBody(): array
    {
        return [
            "name" => $this->receipientName,
            "subject" => $this->subject,
            "body" => $this->body
        ];
    }

    #[Override]
    protected function createMessage(): PushNotification
    {
        return new PushNotification(
            $this->subject,
            $this->receipientName,
            $this->formatMessageBody(),
        );
    }
}
