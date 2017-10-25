<?php

namespace Helper;

require ROOT_DIR.DS.'libs/phpmailer/class.phpmailer.php';
require ROOT_DIR.DS.'libs/phpmailer/class.smtp.php';
require ROOT_DIR.DS.'libs/phpmailer/class.pop3.php';

class Email
{
    /**
     * @var string
     */
    public static $lastError = '';

    /**
     * Send an email
     *
     * @param mixed $params
     *      to => string | string[]
     *      cc => string | string[]
     *      bcc => string | string[]
     *      from => string
     *      replyTo => string
     *      subject => string
     *      text => string
     *      html => string
     *      attachments => string[]
     * @return bool
     */
    public static function send($params = array())
    {
        $to = isset($params['to']) ? $params['to'] : array();
        if (!is_array($to)) {
            $to = array($to);
        }

        $cc = isset($params['cc']) ? $params['cc'] : array();
        if (!is_array($cc)) {
            $cc = array($cc);
        }

        $bcc = isset($params['bcc']) ? $params['bcc'] : array();
        if (!is_array($bcc)) {
            $bcc = array($bcc);
        }

        $from = isset($params['from']) ? $params['from'] : 'contact@test.com';

        $replyTo = isset($params['replyTo']) ? $params['replyTo'] : $from;

        $subject = isset($params['subject']) ? $params['subject'] : '';

        $text = isset($params['text']) ? $params['text'] : '';

        $html = isset($params['html']) ? $params['html'] : '';

        $attachments = isset($params['attachments']) ? $params['attachments'] : array();
        if (!is_array($attachments)) {
            $attachments = array($attachments);
        }

        $mail = new \PHPMailer();

        $mail->isMail();
        // $mail->Host = 'smtp.xxx.xx';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'xxx@xxx.xx';
        // $mail->Password = 'xxx';
        // $mail->SMTPSecure = 'tls';
        // $mail->Port = 465;

        $mail->CharSet = 'utf-8';

        foreach ($to as $x) {
            $addresses = $mail->parseAddresses($x);
            foreach ($addresses as $a) {
                $mail->addAddress($a['address'], $a['name']);
            }
        }

        foreach ($cc as $x) {
            $addresses = $mail->parseAddresses($x);
            foreach ($addresses as $a) {
                $mail->addCC($a['address'], $a['name']);
            }
        }

        foreach ($bcc as $x) {
            $addresses = $mail->parseAddresses($x);
            foreach ($addresses as $a) {
                $mail->addBCC($a['address'], $a['name']);
            }
        }

        $addresses = $mail->parseAddresses($from);
        $mail->setFrom($addresses[0]['address'], $addresses[0]['name']);

        $addresses = $mail->parseAddresses($replyTo);
        $mail->addReplyTo($addresses[0]['address'], $addresses[0]['name']);

        $mail->Subject = $subject;

        if ($html != '') {
            $mail->isHTML(true);
            $mail->Body = $html;
            $mail->AltBody = $text;
        } else {
            $mail->Body = $text;
            $mail->AltBody = $text;
        }

        foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment);
        }

        self::$lastError = '';
        $res = $mail->send();
        if (!$res) {
            self::$lastError = $mail->ErrorInfo;
        }
        return $res;
    }
}
