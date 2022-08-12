<?php
if (!defined('_INCODE')) die('Access Deined...');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($layoutName='header', $data = []) {
    if (file_exists(_WEB_PATH_TEMPLATE.'/layouts/'.$layoutName.'.php')) {
        require_once _WEB_PATH_TEMPLATE.'/layouts/'.$layoutName.'.php';
    }
}

function sendMail($to, $subject, $content) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'nguyen.dat@avithglobals.com';                     //SMTP username
        $mail->Password   = 'buuygdfixtziqlmi';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress($to);     //Add a recipient
        
        //Content
        $mail->isHTML(true);                               //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $content;

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức POST
function isPost() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }

    return false;
}

// Kiểm tra phương thức GET
function isGet() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }

    return false;
}

// Lấy giá trị phương thức POST, GET
function getBody() {

    $bodyArray = [];

    if (isGet()) {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);

                if (is_array($value)) {
                    $bodyArray[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArray[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);

                if (is_array($value)) {
                    $bodyArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    return $bodyArray;
}

// Kiểm tra email
function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Kiểm tra số nguyên
function isNumberInt($number, $range = []) {

    if (!empty($range)) {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT, ['options' => $range]);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }

    return $checkNumber;
}

// Kiểm tra số thực
function isNumberFloat($number, $range = []) {

    if (!empty($range)) {
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, ['options' => $range]);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    }

    return $checkNumber;
}

function isPhone($phone) {
    $checkFirstZero = false;
    $checkNumberLast = false;

    if ($phone[0] == '0' ) {
        $checkFirstZero = true;
        $phone = substr($phone, 1);
    }

    if (isNumberInt($phone) && strlen($phone) == 9 ) {
        $checkNumberLast = true;
    }

    if ($checkFirstZero && $checkNumberLast) {
        return true;
    }

    return false;
}

// Hàm tạo thông báo
function getMsg($msg, $type = 'success') {
    if (!empty($msg)) {
        echo '<div class="alert alert-'.$type.'" role="alert">';
        echo $msg;
        echo '</div>';
    }
}

// Hàm chuyển hướng
function redirect($path) {
    header("Location: $path");
    exit();
}

// Hàm thông báo lỗi
function formError($fieldName, $errors) {
    return (!empty($errors[$fieldName])) ? '<span class="text-danger">'.reset($errors[$fieldName]).'</span>' : null;
}

// Hàm hiển thị dữ liệu cũ
function old($fieldName, $old, $default = null) {
    return (!empty($old[$fieldName])) ? $old[$fieldName] : $default;
}

// Kiểm tra trạng thái đăng nhập
function isLogin() {
    $checkLogin = false;
    if (getSession('loginToken')) {
        $tokenLogin = getSession('loginToken');
        $queryToken = firstRaw("SELECT userId FROM login_token WHERE token='$tokenLogin'");
        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('loginToken');
        }
    }

    return $checkLogin;
}