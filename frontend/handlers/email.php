<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email_address, $email_username, $subject, $body, $altbody) {
    
    $subject = mb_convert_encoding($subject, "HTML-ENTITIES", 'UTF-8');
    $body = mb_convert_encoding($body, "HTML-ENTITIES", 'UTF-8');
     
     global $mail;
  
           $mail = new PHPMailer();
    
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = $_ENV['smtp_Host_Server'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['smtp_Host_Username'];
                $mail->Password = $_ENV['smtp_Host_Password'];
                $mail->SMTPSecure = 'ssl';
                $mail->Port = $_ENV['smtp_Host_Port'];
  
     $mail->setFrom('cloud@jspc.co.uk', 'JSPC Cloud Services');

          foreach($email_address as $email_add){
            $email_add = filter_var($email_add, FILTER_SANITIZE_EMAIL);
        if (filter_var($email_add, FILTER_VALIDATE_EMAIL)) {
            $mail->AddAddress($email_add, $email_add); }
          }
  
            $mail->addReplyTo('cloud@jspc.co.uk', 'JSPC Cloud Services');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altbody;

  
              if(!$mail->send()) 
            {
                return "E-Mail Send Error";
            } 
            else 
            {
                return "E-Mail Sent Successfully";
            }
          
        }



function verifyEmail($recipient,$code) {

$recipients[] = $recipient;

$subject = 'JSPC Cloud Services - Confirm your account';

$body = '<p style="background: #352d39; height: 12px; margin: 0;"> </p><div style="padding: 32px;"><h2 style="margin: 0;">JSPC Cloud Services</h2><p style="margin: 24px 0;">Please click the link below to confirm your account.</p><p style="margin: 24px 0 48px 0;">If you didn\'t request this, please disregard this message.</p><a href="https://cloud.jspc.co.uk/register?verify=' . $code . '" style="padding: 13px 16px; background: #352d39; color: #fff; border-radius: 4px; font-size: 1.25em; text-decoration: none;">Confirm Your Account</a><p style="margin: 0;"> </p></div><p style="background: #352d39; height: 12px; margin: 0;"></p>';

$altBody = 'JSPC Cloud Services - Please click the link to confirm your account - https://cloud.jspc.co.uk/register?verify=' . $code;

sendMail($recipients, $recipients, $subject, $body, $altBody);

}

function passphraseResetEmail($recipient,$code) {

    $recipients[] = $recipient;
    
    $subject = 'JSPC Cloud Services - Reset your passphrase';
    
    $body = '<p style="background: #352d39; height: 12px; margin: 0;"> </p><div style="padding: 32px;"><h2 style="margin: 0;">JSPC Cloud Services</h2><p style="margin: 24px 0;">Please click the link below to reset your passphrase.</p><p style="margin: 24px 0 48px 0;">If you didn\'t request this, please disregard this message.</p><a href="https://cloud.jspc.co.uk/forgot?reset=' . $code . '" style="padding: 13px 16px; background: #352d39; color: #fff; border-radius: 4px; font-size: 1.25em; text-decoration: none;">Reset Your Passphrase</a><p style="margin: 0;"> </p></div><p style="background: #352d39; height: 12px; margin: 0;"></p>';
    
    $altBody = 'JSPC Cloud Services - Please click the link to reset your passphrase - https://cloud.jspc.co.uk/forgot?reset=' . $code;
    
    sendMail($recipients, $recipients, $subject, $body, $altBody);
    
    }
