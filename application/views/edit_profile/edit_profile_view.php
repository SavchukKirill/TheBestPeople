<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if($error) {echo '<div class="errors bg-danger">'.$error.'</div>';} ?>
<?=validation_errors()?>
<?= form_open_multipart('edit_profile') ?>
<p> 
    Логин:  <?=$username?> 
</p>
<p>
    <label for="userfile">Аватар: </label>
    <input type="file" name="userfile" size="25"/> 
</p>
<p>
    <select type="option" name="sex">
        <option <?php if($sex==0) { echo ' selected '; } ?> value="0">Мужской</option>
        <option <?php if($sex==1) { echo ' selected '; } ?> value="1">Женский</option>
    </select>    
</p>
<p>
    <div><input class="btn btn-primary" type="submit" name="submit" value="Редактировать" /></div>
</p> 
<?= form_close() ?>