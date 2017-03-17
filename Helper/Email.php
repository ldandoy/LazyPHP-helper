<?php

namespace system\helpers;

require SYSTEM_DIR.DS.'libs/phpmailer/class.phpmailer.php';
require SYSTEM_DIR.DS.'libs/phpmailer/class.smtp.php';
require SYSTEM_DIR.DS.'libs/phpmailer/class.pop3.php';

class Email
{
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

        $from = isset($params['from']) ? $params['from'] : 'contact@localhost';

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
        // $mail->Host = 'smtp.sfr.fr';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'laurent.comex@sfr.fr';
        // $mail->Password = 'lcxsfr';
        // $mail->SMTPSecure = 'tls';
        // $mail->Port = 465;

        foreach ($to as $address) {
            $mail->addAddress($address);
        }

        foreach ($cc as $address) {
            $mail->addCC($address);
        }

        foreach ($bcc as $address) {
            $mail->addBCC($address);
        }

        $mail->setFrom($from);
        $mail->addReplyTo($replyTo);

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

        return $mail->send();
    }
}