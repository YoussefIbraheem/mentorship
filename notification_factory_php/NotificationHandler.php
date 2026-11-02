<?php

use EmailCreator;
use SmsCreator;

class NotificationHandler
{
    public static function sendEmail(
        string $senderEmail,
        string $receipientEmail,
        ?string $subject,
        string $senderName,
        string $receipientName,
        string $body
    ) {
        $mail = new EmailCreator(
            $senderEmail,
            $receipientEmail,
            $subject,
            $senderName,
            $receipientName,
            $body
        );

        $mail->send();
    }

    public static function sendSms(
        string $senderNumber,
        string $receipientNumber,
        string $body
    ) {
        $mail = new SmsCreator(
            $senderNumber,
            $receipientNumber,
            $body
        );

        $mail->send();
    }
}
