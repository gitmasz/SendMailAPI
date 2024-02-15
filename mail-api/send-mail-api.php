<?php
header("Content-Type: application/json; charset=utf-8");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('HTTP/1.0 405 Method Not Allowed');
  $data = new \stdClass();
  $data->status = http_response_code(405);
  $data->message = 'Method Not Allowed!';
  $sendresponseJSON = json_encode($data);
  echo $sendresponseJSON;
  die();
} else {
  $httpOrigin = $_SERVER['HTTP_ORIGIN'];
  $acceptedURL = array('http://localhost',);
  foreach ($acceptedURL as $url) {
    if (strpos($httpOrigin, $url) !== false) {
      $urlPassed = true;
      break;
    } else {
      $urlPassed = false;
    };
  };
  if ($urlPassed !== true) {
    header('HTTP/1.0 401 Unauthorized');
    $data = new \stdClass();
    $data->status = http_response_code(401);
    $data->message = 'Unauthorized!';
    $sendresponseJSON = json_encode($data);
    echo $sendresponseJSON;
    die();
  } else {
    @include 'form-validation.php';
    if ($formValid == false) {
      header('HTTP/1.0 400 Bad Request');
      $data = new \stdClass();
      $data->status = http_response_code(400);
      $data->message = 'Bad Request!';
      $sendresponseJSON = json_encode($data);
      echo $sendresponseJSON;
      die();
    } else {
      header("Access-Control-Allow-Origin: $httpOrigin");
      date_default_timezone_set("Europe/Warsaw");
      $theDate = date("Y-m-d");
      $theTime = date("Y-m-d H:i:s");
      $senderName = 'Sender Name'; // displayed sender name
      $senderEmail = 'sender@gmail.com'; // displayed sender email
      $receiverEmail = $formData['receiver'];
      $receiverCCEmail = '';
      $emailSubject = 'Test email: ' . $formData['fullName'];
      $emailMessageHTML = 'New email from: ' . $formData['fullName'] . ' ( ' . $formData['email'] . ' )<br>Message date: ' . $theTime . '<br>Consent given: ' . $formData['regulationConsent'] . '<br><br><b>Warning:</b> by replying to this email you will send a message to ' . $formData['fullName'] . ' ( ' . $formData['email'] . ' ).';
      $emailMessageTXT = str_replace('<br>', '\\r\\n', strip_tags($emailMessageHTML, '<br>'));
      @include 'send-mail-function.php';
      SendMail($senderName, $senderEmail, $receiverEmail, $receiverCCEmail, $emailSubject, $emailMessageHTML, $emailMessageTXT, $formData['fullName'], $formData['email']);
    };
  };
};
