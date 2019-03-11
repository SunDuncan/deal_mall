<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/27
 * Time: 10:16
 */
namespace Phpmailer;
use think\Exception;

class Email {
    /**
     * @param $to
     * @param $from
     * @param $content
     * @return bool
     */
    public function sendEmail($to, $to_name,$content, $subject = "PHPMailer SMTP test") {
        date_default_timezone_set('PRC');//set time
        if (!$to) {
            return "发送者的邮箱未填写";
        }

        if (!$to_name) {
            return "发送者的名字未填写";
        }

        if (!$content) {
            return "未填写内容";
        }

        try{
            //Create a new PHPMailer instance
            $mail = new Phpmailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            //$mail->SMTPDebug = 2;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = config('email.email_host');
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 25;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = config('email.email_from');
            //Password to use for SMTP authentication
            $mail->Password = config('email.email_pass');
            //Set who the message is to be sent from
            $mail->setFrom(config('email.email_from'), config('email.email_from_name'));
            //Set an alternative reply-to address
            //$mail->addReplyTo('replyto@example.com', 'First Last');
            //Set who the message is to be sent to
            $mail->addAddress($to, $to_name);
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($content);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                return true;
            }
        }catch (phpmailerException $e) {
            return false;
        }

    }

    public function test() {
        date_default_timezone_set('PRC');//set time

//Create a new PHPMailer instance
        try{
            $mail = new Phpmailer();
//Tell PHPMailer to use SMTP
            $mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
//$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
//Set the hostname of the mail server
            $mail->Host = "smtp.163.com";
//Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 25;
//Whether to use SMTP authentication
            $mail->SMTPAuth = true;
//Username to use for SMTP authentication
            $mail->Username = "sun_duncan@163.com";
//Password to use for SMTP authentication
            $mail->Password = "907244758smy";
//Set who the message is to be sent from
            $mail->setFrom('sun_duncan@163.com', 'Duncan');
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
            $mail->addAddress('sun-duncan@qq.com', 'John Doe');
//Set the subject line
            $mail->Subject = 'PHPMailer SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
            $mail->msgHTML("this is a test");
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                return true;
            }
        }catch (phpmailerException $e) {
            return false;
        }


    }
}