<?php
// Lightweight mailer wrapper. Uses PHPMailer if available, otherwise falls back to PHP mail().
// To enable authenticated SMTP (recommended), install PHPMailer via Composer:
//    composer require phpmailer/phpmailer
// and ensure composer's autoloader is required by your front controller (index.php) or bootstrap.

class Mailer
{
    /**
     * Send an email.
     * @param string $to
     * @param string $subject
     * @param string $body HTML body
     * @param string $altBody Plain text alternative
     * @return bool
     */
    public static function send($to, $subject, $body, $altBody = '')
    {
        // If PHPMailer is available, use SMTP with the app config constants.
        if (class_exists('\PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                // Server settings
                $mail->isSMTP();
                $mail->Host = defined('SMTP_HOST') ? SMTP_HOST : 'localhost';
                $mail->SMTPAuth = true;
                $mail->Username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
                $mail->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                // Use STARTTLS by default if port suggests TLS
                if (defined('SMTP_PORT') && in_array(intval(SMTP_PORT), [587, 25])) {
                    if (defined('PHPMailer\\PHPMailer\\PHPMailer::ENCRYPTION_STARTTLS')) {
                        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    } else {
                        $mail->SMTPSecure = 'tls';
                    }
                }
                $mail->Port = defined('SMTP_PORT') ? SMTP_PORT : 25;

                // From
                $from = defined('SMTP_USERNAME') && SMTP_USERNAME ? SMTP_USERNAME : ('no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
                $fromName = defined('APP_NAME') ? APP_NAME : 'App';
                $mail->setFrom($from, $fromName);

                // Recipients
                $mail->addAddress($to);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                if ($altBody) $mail->AltBody = $altBody;

                return $mail->send();
            } catch (Exception $e) {
                error_log('Mailer error: ' . $e->getMessage());
                return false;
            }
        }

        // Fallback to PHP mail() with basic headers (no SMTP auth)
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $from = defined('SMTP_USERNAME') && SMTP_USERNAME ? SMTP_USERNAME : ('no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        $headers .= "From: " . $from . "\r\n";

        return mail($to, $subject, $body, $headers);
    }
}
