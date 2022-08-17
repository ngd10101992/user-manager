<?php
if (!defined('_INCODE')) die('Access Deined...');
/**
 * File này chứa chức năng logout
 */
if (isLogin()) {
	$token = getSession('loginToken');
	delete('login_token', "token='$token'");
	removeSession('login_token');
	redirect('?module=auth&action=login');
}