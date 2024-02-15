<?php
if (stripos($_SERVER['SCRIPT_FILENAME'], '/sender-email-configuration.php') !== false) {
  header('HTTP/1.0 403 Forbidden');
  die();
}
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->SMTPAutoTLS = false;
$mail->Host = '';
$mail->Port = 587;
$mail->Username = '';
$mail->Password = '';
$mail->Sender = '';
