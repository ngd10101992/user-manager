<?php
if (!defined('_INCODE')) die('Access Deined...');

$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];
layout('header-login', $data);

// Xử lý đăng ký
if (isPost()) {
    $body = getBody();
    $errors = [];

    // Validate fullname
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['fullname'])) < 5) {
            $errors['fullname']['min'] = 'Họ tên phải từ 5 ký tự';
        }
    }

    // Validate phone
    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'SDT bắt buộc phải nhập';
    } else {
        if (!isPhone(trim($body['phone']))) {
            $errors['phone']['isPhone'] = 'SDT không hợp lệ';
        }
    }

    // Validate email
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!isEmail($body['email'])) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        } else {
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email='$email'";
            if (getRaws($sql) > 0) {
                $errors['email']['unique'] = 'Email đã tồn tại';
            }
        }
    }

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

    // Kiểm tra mảng $errors
    if (empty($errors)) {
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'createAt' => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('users', $dataInsert);

        if ($insertStatus) {
            // Tạo link kích hoạt
            $linkActive = _WEB_HOST_ROOT.'?module=auth&action=active&token='.$activeToken.'';

            // Thiết lập gửi mail
            $subject = $body['fullname'].' vui lòng kích hoạt tài khoản';
            $content = 'Chào bạn: '.$body['fullname'].'<br/>';
            $content .= 'Vui lòng click vào link dưới đây để kích hoạt tài khoản: <br/>';
            $content .= $linkActive.'<br/>';
            $content .= 'Trân trọng !';

            // Tiến hành gửi email
            $sendStatus = sendMail($body['email'], $subject, $content);
            if ($sendStatus) {
                setFlashData('msg', 'Đăng ký tài khoản thành công, vui lòng kiểm tra email để kích hoạt tài khoản');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }

        
        redirect('?module=auth&action=register'); // Load lại trang đăng ký
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('?module=auth&action=register'); // Load lại trang đăng ký
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>
    <div class="row justify-content-center align-items-center">
        <div class="col-6">
            <div class="manager-form">
                <?php getMsg($msg, $msgType); ?>
                <h1 class="text-center">Đăng ký</h1>
                <form class="form" method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="fullname" value="<?php echo old('fullname', $old) ?>">
                        <?php echo formError('fullname', $errors) ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo old('phone', $old) ?>">
                        <?php echo formError('phone', $errors) ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" value="<?php echo old('email', $old) ?>">
                        <?php echo formError('email', $errors) ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                        <?php echo formError('password', $errors) ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password">
                        <?php echo formError('confirm_password', $errors) ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng ký</button>
                    <hr>
                    <p class="text-center">Đến trang <a href="?module=auth&action=login">đăng nhập</a></p>
                </form>
            </div>
        </div>
    </div>
<?php
layout('footer-login');