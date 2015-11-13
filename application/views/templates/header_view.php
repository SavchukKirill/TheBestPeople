<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8">
	<title><?=$title_page?></title>
        <link rel="stylesheet" href="<?=site_url("../assets/css/bootstrap.min.css"); ?>" />
        <link rel="stylesheet" href="<?=site_url("../assets/css/jumbotron-narrow.css"); ?>" />
        <link rel="stylesheet" href="<?=site_url("../assets/css/style.css"); ?>" />
        
        <script type="text/javascript" src="<?=site_url("../assets/js/ajax.js");?>"> </script>
        <script type="text/javascript" src="<?=site_url("../assets/js/jquery-1.11.3.min.js");?>"> </script>
        <script type="text/javascript" src="<?=site_url("../assets/js/update_rating.js");?>"> </script>
        <script type="text/javascript" src="<?=site_url("../assets/js/bootstrap.min.js");?> "> </script>        
    </head>
<body>
    <div class="container-fluid">
        <div class="header"> 
            <?php if(!$id_exist): ?>
                <ul class="nav nav-pills pull-right">
                    <li <?php if($page==1) {echo 'class="active"';} ?>><a href="<?=site_url('main');?>">Главная</a></li>
                    <li <?php if($page==2) {echo 'class="active"';} ?>><a href="<?=site_url('login');?>">Войти</a></li>
                    <li <?php if($page==3) {echo 'class="active"';} ?>><a href="<?=site_url('register');?>">Зарегистрироваться</a></li>       
                </ul>                
            <?php else: ?>
                <ul class="nav nav-pills pull-right">
                    <li <?php if($page==1) {echo 'class="active"';} ?>><a href="<?=site_url('main');?>">Главная</a></li>
                    <li <?php if($page==4) {echo 'class="active"';} ?>><a href="<?=site_url('user_profile');?>?id=<?=$userID?>">Мой профиль</a></li>
                </ul>  
            <?php endif; ?>
            <h3 class="text-muted"><?=$title_page?></h3>
            <?php if($id_exist): ?>
                <div  id="menu_user">    
                    <div>Привет, <a href="<?=site_url("user_profile");?>?id=<?=$userID?>"><?=$username?></a></div>
                    <?php if($avatar=='no avatar'): ?>
                        <?php if($sex==0): ?>
                            <div id="img"><img src="../uploads/unknown_man.gif" /> </div>
                        <?php else: ?>
                            <div id="img"><img src="../uploads/unknown_woman.gif" /> </div>
                        <?php endif;?>
                    <?php else: ?>
                        <img src="<?=$avatar?>" />
                    <?php endif;?>
                        <div id="rating_user"><b><?=$rating?></b></div>
                        <div id="logout"><a href="<?=site_url('logout')?>">Выйти</a></div>
                </div>
            <?php endif; ?>
        </div>