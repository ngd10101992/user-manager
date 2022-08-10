<?php
if (!defined('_INCODE')) die('Access Deined...');

$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];
layout('header-login', $data);
?>
    <div class="row justify-content-center align-items-center">
        <h1 class="text-center">Đăng ký</h1>
        <div class="col-6">
            <div class="manager-form">
                <form class="form" method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control">
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