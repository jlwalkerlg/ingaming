<?php

namespace App\Lib\Mail;

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;

// Alias the League Google OAuth2 provider class
use League\OAuth2\Client\Provider\Google;

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

/**
 * Gmail class.
 *
 * Uses PHPMailer to send an Email via Gmail.
 * Taken from: https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail_xoauth.phps
 */
class Gmail
{
    /** @var PHPMailer $mail PHPMailer instance. */
    private $mail;

    private $clientId;
    private $clientSecret;
    private $refreshToken;
    private $address;


    public function __construct()
    {
        $this->clientId = getenv('GMAIL_CLIENT_ID');
        $this->clientSecret = getenv('GMAIL_CLIENT_SECRET');
        $this->refreshToken = getenv('GMAIL_REFRESH_TOKEN');
        $this->address = getenv('GMAIL_ADDRESS');

        // Create a new PHPMailer instance
        $this->mail = new PHPMailer;

        // Tell PHPMailer to use SMTP
        $this->mail->isSMTP();

        // Set the hostname of the mail server
        $this->mail->Host = 'smtp.gmail.com';

        // Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->mail->Port = 587;

        // Set the encryption system to use - ssl (deprecated) or tls
        $this->mail->SMTPSecure = 'tls';

        // Whether to use SMTP authentication
        $this->mail->SMTPAuth = true;

        // Set AuthType to use XOAUTH2
        $this->mail->AuthType = 'XOAUTH2';

        // Create a new OAuth2 provider instance
        $provider = new Google([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        // Pass the OAuth provider instance to PHPMailer
        $this->mail->setOAuth(
            new OAuth([
                'provider' => $provider,
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
                'refreshToken' => $this->refreshToken,
                'userName' => $this->address,
            ])
        );

        // Set who the message is to be sent from
        // For gmail, this generally needs to be the same as the user you logged in as
        $this->mail->setFrom($this->address, SITE_NAME);

        $this->mail->CharSet = 'utf-8';
    }


    public function compose($to, $subject, $msg, $altMsg = null)
    {
        // Set who the message is to be sent to
        $this->mail->addAddress($to);

        // Set the subject line
        $this->mail->Subject = $subject;

        // Read an HTML message body from an external file, convert referenced images to embedded,
        // convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($msg);
        // $this->mail->msgHTML(file_get_contents('contentsutf8.html'), __DIR__);

        if (isset($altMsg)) {
            // Replace the plain text body with one created manually
            $this->mail->AltBody = 'This is a plain-text message body';
        }
    }


    public function send()
    {
        return $this->mail->send();
    }
}
