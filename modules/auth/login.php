<?php
if (!defined('_INCODE')) die('Access Deined...');
/**
 * File này chứa chức năng login
 */
$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
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
    if(!empty(trim($body['email'])) && !empty(trim($body['password']))) {
        // Kiểm tra đăng nhập
        $email = $body['email'];
        $password = $body['password'];

        // Truy vấn lấy thông tin user theo email

        $userQuery = firstRaw("SELECT id, password FROM users WHERE email='$email'");

        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            if (password_verify($password, $passwordHash)) {
                // Tạo token login
                $tokenLogin = sha1(uniqid().time());
                $dataToken = [
                    'userId' => $userQuery['id'],
                    'token' => $tokenLogin,
                    'createAt' => date('Y-m-d H:s:i')
                ];
                
                $insertTokenStatus = insert('login_token', $dataToken);
                if ($insertTokenStatus) {
                    // Lưu loginToken vào session
                    setSession('loginToken', $tokenLogin);
                    // Chuyển hướng qua trang quản lý users
                    redirect('?module=users');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống, bạn không thể đăng nhập vào lúc này !');
                    setFlashData('msg_type', 'danger');
                }

            } else {
                setFlashData('msg', 'Mật khẩu không chính xác !');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Email không tồn tại trong hệ thống !');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=login');
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');

?>
    <div class="row justify-content-center align-items-center">
        <div class="col-6">
            <div class="manager-form">
                <?php getMsg($msg, $msgType); ?>
                <h1 class="text-center">Đăng nhập</h1>
                <form class="form" method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input">
                        <label class="form-check-label">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
                    <p class="text-center">Đến trang <a href="?module=auth&action=register">đăng ký tài khoản</a></p>
                </form>
            </div>
        </div>
    </div>
<?php
layout('footer-login');