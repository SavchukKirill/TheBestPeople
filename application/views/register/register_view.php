<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php echo '<div class="errors bg-danger">'.validation_errors().'</div>'?>
<?php if($error) {echo '<div class="errors bg-danger">'.$error.'</div>';} ?>

<?= form_open_multipart('register') ?>
<p>
    <label for="username">Логин: </label>
    <input type="text" name="username" value="<?=$this->input->post('username')?>" size="25" /> 
</p>
<p>
    <label for="password">Пароль: </label>
    <input id="password" type="password" name="password" size="25" /> 
    <input id="check" type="checkbox" onchange="showPassword();"/> Показать пароль
</p>
<p>
    <label for="userfile">Аватар: </label>
    <input type="file" name="userfile" size="25"/> 
</p>
<p>
    <select type="option" name="sex">
        <option value="0">Мужской</option>
        <option value="1">Женский</option>
    </select>    
</p>
<p>
    <div><input class="btn btn-primary" type="submit" name="submit" value="Зарегистрироваться" /></div>
</p>     
<?= form_close() ?>