<?php
if (!defined('_INCODE')) die('Access Deined...');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Unicode'; ?></title>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css?ver=<?php echo rand(); ?>">
</head>
<body>
    <div class="backround"></div>
    <div class="backround-caro"></div>
    <div class="container">
        <div class="main">