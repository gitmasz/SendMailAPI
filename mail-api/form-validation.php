<?php
if (stripos($_SERVER['SCRIPT_FILENAME'], '/form-validation.php') !== false) {
  header('HTTP/1.0 403 Forbidden');
  die();
}
$formData = array(
  'fullName' => (isset($_POST['fullName']) ? $_POST['fullName'] : 'error'),
  'email' => (isset($_POST['email']) ? (preg_match('/^[\-0-9a-zA-Z\.\+_]+@[\-0-9a-zA-Z\.\+_]+\.[a-zA-Z]{2,5}$/', $_POST['email']) ? $_POST['email'] : 'error') : 'error'),
  'receiver' => (isset($_POST['receiver']) ? (preg_match('/^[\-0-9a-zA-Z\.\+_]+@[\-0-9a-zA-Z\.\+_]+\.[a-zA-Z]{2,5}$/', $_POST['receiver']) ? $_POST['receiver'] : 'error') : 'error'),
  'regulationConsent' => (isset($_POST['regulationConsent']) ? ($_POST['regulationConsent'] != 'yes' ? 'error' : $_POST['regulationConsent']) : 'error'),
);
$formValid = '';
foreach ($formData as $dataValue) {
  if (!empty($dataValue) && strpos('error', $dataValue) !== false) {
    $formValid = false;
    break;
  } else {
    $formValid = true;
  };
};
