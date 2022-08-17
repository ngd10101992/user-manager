<?php
if (!defined('_INCODE')) die('Access Deined...');
/**
 * File này chứa chức năng quên mật khẩu
 */

$data = [
    'pageTitle' => 'Đặt lại mật khẩu'
];
layout('header-login', $data);

$body = getBody();

// echo '<pre>';
// print_r($_SERVER['REQUEST_METHOD']);
// echo '</pre>';

// Kiểm tra trạng thái đăng nhập
if (isLogin()) {
    redirect('?module=users');
}

if (isPost()) {
    $body = getBody();
    if (!empty($body['email'])) {
        $email = $body['email'];
        $queryUser = firstRaw("SELECT id, fullname FROM users WHERE email='$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];
            
            // Tạo forgot token
            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                // Tạo link khôi phục
                $linkReset = _WEB_HOST_ROOT.'?module=auth&action=reset&token='.$forgotToken;
                // Thiết lập gửi email
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn: '.$queryUser['fullname'].'<br/>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu. Vui lòng click vào link sau để khôi phục: <br/>';
                $content .= $linkReset.'<br/>';
                $content .= 'Trân trọng!';

                // Tiến hành gửi email
                $sendStatus = sendMail($email, $subject, $content);
                if ($sendStatus) {
                    setFlashData('msg', 'Vui lòng kiểm tra email đế xem hướng dẫn đặt lại mật khẩu !');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống, bạn không thể sử dụng chức năng này !');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống, bạn không thể sử dụng chức năng này !');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Địa chỉ email không tồn tại !');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập địa chỉ email');
        setFlashData('msg_type', 'danger');
    }

    redirect('?module=auth&action=forgot');
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');

?>
    <div class="row justify-content-center align-items-center">
        <div class="col-6">
            <div class="manager-form">
                <?php getMsg($msg, $msgType); ?>
                <h1 class="text-center">Đặt lại mật khẩu</h1>
                <form class="form" method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
                    <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
                </form>
            </div>
        </div>
    </div>
<?php
layout('footer-login');