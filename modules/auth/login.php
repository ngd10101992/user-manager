<?php
if (!defined('_INCODE')) die('Access Deined...');
/**
 * File này chứa chức năng login
 */
$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
];
layout('header-login', $data);
?>
    <div class="row justify-content-center align-items-center">
        <h1 class="text-center">Đăng nhập</h1>
        <div class="col-6">
            <div class="manager-form">
                <form class="form" method="post" action="">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
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