<?php

use NotificationType;

abstract class NotificationFactory
{
    protected abstract function createMessage(): NotificationType;
    protected abstract function formatMessageBody(): string|array;

    public function send()
    {
        $notification = $this->createMessage();

        $notification->send();
    }
}
