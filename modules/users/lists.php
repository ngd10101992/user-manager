<?php
if (!defined('_INCODE')) die('Access Deined...');
/**
 * File này chứa danh sách người dùng
 */

// Kiểm tra trạng thái đăng nhập
if (!isLogin()) {
    redirect('?module=auth&action=login');
}
