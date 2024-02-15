<?php
if (stripos($_SERVER['SCRIPT_FILENAME'], '/send-mail-function.php') !== false) {
  header('HTTP/1.0 403 Forbidden');
  die();
}
function SendMail($senderName, $senderEmail, $receiverEmail, $receiverCCEmail, $emailSubject, $emailMessageHTML, $emailMessageTXT, $replayName, $replayEmail)
{
  require_once $_SERVER['DOCUMENT_ROOT'] . '/SendMailAPI/PHPMailer/PHPMailer.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/SendMailAPI/PHPMailer/Exception.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/SendMailAPI/PHPMailer/SMTP.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer;
  require_once $_SERVER['DOCUMENT_ROOT'] . '/SendMailAPI/mail-api/sender-email-configuration.php';
  // $mail->setLanguage('pl', $_SERVER['DOCUMENT_ROOT'] . '/SendMailAPI/PHPMailer/language/'); // PHPMailer language
  $mail->IsHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->FromName = $senderName;
  $mail->From = $senderEmail;
  $mail->setFrom($senderEmail, $senderName);
  $mail->AddAddress($receiverEmail);
  if ($receiverCCEmail != '') {
    $mail->AddCC($receiverCCEmail);
  };
  $mail->Subject = ($emailSubject);
  $mail->Body = ($emailMessageHTML);
  $mail->AltBody = ($emailMessageTXT);
  if ($replayName != '' && $replayEmail != '') {
    $mail->AddReplyTo($replayEmail, $replayName);
  };
  if (!$mail->Send()) {
    header('HTTP/1.0 502 Bad Gateway');
    $data = new \stdClass();
    $data->status = http_response_code(502);
    $data->message = 'Message not sent!';
    $data->info = $mail->ErrorInfo;
    $sendresponseJSON = json_encode($data);
    echo $sendresponseJSON;
  } else {
    $data = new \stdClass();
    $data->status = http_response_code(200);
    $data->message = 'Message sent!';
    $sendresponseJSON = json_encode($data);
    echo $sendresponseJSON;
  }
};
