<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php echo '<div class="errors bg-danger">'.validation_errors().'</div>'?>
<?= form_open("login") ?>
    <label for="username">Логин: </label>
    <input type="text" name="username" value="<?=$this->input->post('username')?>" /> <br />
    
    <label for="password">Пароль: </label>
    <input type="password" name="password" />
    <br />
    
    <img src="../captcha/<?=$filename?>" />
    <label for="username">Текст с картинками: </label>
    <input type="text" name="captcha" /> <br />
<input class="btn btn-primary" type="submit" name="submit" value="Войти" />
<?= form_close()?>



