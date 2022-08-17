<?php
if (!defined('_INCODE')) die('Access Deined...');
/**
 * File này chứa chức năng đổi mật khẩu
 */
layout('header-login');
$token = getBody()['token'];
if (!empty($token)) {
    // Truy vấn kiểm tra token với database
    $tokenQuery = firstRaw("SELECT id, email FROM users WHERE forgotToken='$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $email = $tokenQuery['email'];

        if (isPost()) {
            $body = getBody();
            $errors = [];

            // Validate mật khẩu
            if (empty(trim($body['password']))) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (strlen(trim($body['password'])) < 8 ) {
                    $errors['password']['min'] = 'Mật khẩu phải từ 8 ký tự trở lên';
                }
            }

            // Validate nhập lại mật khẩu
            if (empty(trim($body['confirm_password']))) {
                $errors['confirm_password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (strlen(trim($body['password'])) !== strlen(trim($body['confirm_password']))) {
                    $errors['confirm_password']['match'] = 'Hai mật khẩu không khớp nhau';
                }
            }

            if (empty($errors)) {
                // Xử lý update mật khẩu
                $passwordHash = password_hash($body['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'updateAt' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('users', $dataUpdate, "id=$userId");
                if ($updateStatus) {
                    setFlashData('msg', 'Thay đổi mật khẩu thành công');
                    setFlashData('msg_type', 'success');

                    // Gửi email thông báo khi đổi mật khẩu thành công
                    $subject = 'Bạn vừa đổi mật khẩu';
                    $content = 'Chúc mừng bạn đã đổi mật khẩu thành công';
                    sendMail($email, $subject, $content);

                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống, bạn không thể cập nhật mật khẩu!');
                    setFlashData('msg_type', 'danger');
                    redirect("?module=auth&action=reset&token=$token");
                }
            } else {
                setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
                redirect("?module=auth&action=reset&token=$token");
            }
        }

        $msg = getFlashData('msg');
        $msgType = getFlashData('msg_type');
        $errors = getFlashData('errors');
    ?>
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <div class="manager-form">
                    <?php getMsg($msg, $msgType); ?>
                    <h1 class="text-center">Đặt lại mật khẩu</h1>
                    <form class="form" method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control">
                            <?php echo formError('password', $errors) ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control">
                            <?php echo formError('confirm_password', $errors) ?>
                        </div>
                        <input type="hidden" name="token" value="<?php echo $token ?>">
                        <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                        <hr>
                        <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
                        <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
                    </form>
                </div>
            </div>
        </div>
    <?php
    } else {
        getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}
layout('footer-login');
