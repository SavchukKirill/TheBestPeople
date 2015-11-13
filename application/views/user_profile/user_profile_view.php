<div id="user-info">

    <?php if($id_exist) { $click = !is_null($estimation);  $vote = $estimation; } ?>
    
        <div class="avatar">
            <?php if($avatar=='no avatar'): ?>
                <?php if($sex==1): ?> 
                    <img src="../uploads/unknown_woman.gif" />
                <?php else: ?>
                    <img src="../uploads/unknown_man.gif" />
                <?php endif;?>
            <?php else: ?>
                <img src="<?=$avatar?>" />
            <?php endif;?>
        </div>
        <div class="user-username">
        <p><?=$username?></p> 
    </div>
    <div>
        <?php if($id_exist && ($user_id==$visitor_id)): ?>
            <p>
                <a href="<?=site_url('edit_profile')?>?id=<?=$user_id?>">
                    Редактировать профиль
                </a>
            </p>
        <?php endif; ?>
    </div>
    
    <div>
        <div  class="user-rating" id="<?=$user_id?>">
            Карма: <span id="user-rating-span"><?=$rating?></span>
        </div>
        <?php if($id_exist && ($user_id!=$visitor_id)): ?>
            <div class="user-buttons">
                <div class="user-button">
                    <button id="button<?=$user_id?>plus" value="<?=$user_id?>"
                        <?php if($click): ?>
                            <?php if($vote==0): // 1 - значит '+', 1 - раньше был сделан клик?>
                                onClick="updateRating(this.value, <?=$page?>, 1, 1);" 
                            <?php endif; ?>
                        <?php else: ?>
                            onClick="updateRating(this.value, <?=$page?>, 1, 0);"
                        <?php endif; ?>            
                        type="button" class="btn btn-primary" <?php if( $click && $vote==1){echo 'disabled';}?>>+
                    </button>
                </div>
                <div class="user-button">
                    <button id="button<?=$user_id?>minus" value="<?=$user_id?>"
                        <?php if($click): ?>
                            <?php if($vote==1): // 0 - значит '-', 1 - раньше пользователь проголовал по-другому?>
                                onClick="updateRating(this.value, <?=$page?>, 0, 1);"  
                            <?php endif; ?>
                        <?php else: ?>
                            onClick="updateRating(this.value, <?=$page?>, 0, 0);"
                        <?php endif; ?>
                        type="button" class="btn btn-primary" 
                        <?php if( $click && $vote==0){echo 'disabled';}?>>&#8211;
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>  
</div>
