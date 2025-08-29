<?php

namespace TheFramework\Config;

use Exception as GlobalException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TheFramework\App\Config;

// WE NEED TO INSTALL PHPMailer VIA COMPOSER
// composer require phpmailer/phpmailer

// HOW TO USE IN CONTROLLER
// use TheFramework\Config\EmailHandler;

// $result = EmailHandler::send(
//     'user@example.com',
//     'Test Email',
//     '<h1>Hello!</h1><p>This is a test email.</p>'
// );

// if ($result !== true) {
//     echo EmailHandler::getErrorMessage($result);
// } else {
//     echo "Email terkirim!";
// }


class EmailHandler
{
    private static array $errorMessages = [
        'missing_to'      => 'Recipient address is required.',
        'missing_subject' => 'Subject is required.',
        'missing_body'    => 'Email body is required.',
        'send_failed'     => 'Failed to send email.',
        'invalid_email'   => 'Invalid email address format.',
    ];

    public static function getErrorMessage(string $code): string
    {
        return self::$errorMessages[$code] ?? 'Unknown email error.';
    }

    /**
     * Kirim email menggunakan konfigurasi ENV.
     * Variabel ENV yang digunakan:
     *  MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM, MAIL_FROM_NAME
     */
    public static function send(
        string $to,
        string $subject,
        string $body,
        array $options = []
    ): bool|string {
        Config::loadEnv();

        if (empty($to)) return 'missing_to';
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) return 'invalid_email';
        if (empty($subject)) return 'missing_subject';
        if (empty($body)) return 'missing_body';

        // Ambil konfigurasi dari ENV atau fallback ke options
        $mailHost = Config::get('MAIL_HOST') ? Config::get('MAIL_HOST') : 'smtp.mailtrap.io';
        $mailUser = Config::get('MAIL_USERNAME') ? Config::get('MAIL_USERNAME') : 'no-reply@example.com';
        $mailPass = Config::get('MAIL_PASSWORD') ? Config::get('MAIL_PASSWORD') : '';
        $mailPort = Config::get('MAIL_PORT') ? Config::get('MAIL_PORT') : 587;
        $mailFrom = Config::get('MAIL_FROM') ? Config::get('MAIL_FROM') : $mailUser;
        $mailFromName = Config::get('MAIL_FROM_NAME') ? Config::get('MAIL_FROM_NAME') : 'No Reply';
        $isHTML = $options['is_html'] ?? true;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $mailHost;
            $mail->SMTPAuth = true;
            $mail->Username = $mailUser;
            $mail->Password = $mailPass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mailPort;

            $mail->setFrom($mailFrom, $mailFromName);
            $mail->addAddress($to);

            if (!empty($options['cc'])) {
                foreach ((array)$options['cc'] as $cc) $mail->addCC($cc);
            }
            if (!empty($options['bcc'])) {
                foreach ((array)$options['bcc'] as $bcc) $mail->addBCC($bcc);
            }
            if (!empty($options['attachments'])) {
                foreach ((array)$options['attachments'] as $filePath) {
                    if (file_exists($filePath)) $mail->addAttachment($filePath);
                }
            }

            $mail->isHTML($isHTML);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (GlobalException $e) {
            return 'send_failed';
        }
    }
}
