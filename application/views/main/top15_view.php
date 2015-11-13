<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $i=0;?>
<?php $url= "'".  site_url('main') . "'"; ?>
<div class="row" id="main">
<?php foreach($top as $item): ?>
    <div class="top-block col-md-4 col-lg-4 col-sm-4" id="block<?=$i?>">  
        <?php if($id_exist) { 
            $click = !is_null($item['estimation']);  $vote = $item['estimation']; 
        } ?>
        <div class="rank"><?=$i+1?></div>  <!--место в топе-->
        <div class="avatar" id="avatar<?=$i?>">
            <?php if($item['avatar']=='no avatar'): ?> <!--Если нет аватара, ставим заглушку-->
                <?php if($item['sex']==1): ?> 
                    <img src="../uploads/unknown_woman.gif" />
                 <?php else: ?>
                    <img src="../uploads/unknown_man.gif" />
                 <?php endif;?>
            <?php else: ?>
                <img src="<?=$item['avatar']?>" />
            <?php endif;?>
        </div>
        <div class="rating" id="rating<?=$i?>"><?=$item['rating']?></div>
        <div class="username" id="username<?=$i?>">
            <a href="<?=site_url('user_profile')?>?id=<?=$item['user_id']?>">
                <?=$item['username']?>
            </a>
        </div>
        <div class="buttons" id="buttons<?=$i?>">
            <?php if($id_exist && ($item['user_id']!=$user_id)): ?>       
                <button id="button<?=$item['user_id']?>plus" value="<?=$item['user_id']?>"
                    <?php if($click): //Если раньше голосовал за данного пользователя?>
                        <?php if($vote==0): //1(+),1(раньше голосовал)?>   
                            onClick="updateRating(this.value, <?=$page?>, 1, 1, <?=$url?>);" 
                        <?php endif; ?>
                    <?php else: //Если раньше не голосовал за данного пользователя?>
                        onClick="updateRating(this.value, <?=$page?>, 1, 0, <?=$url?>);"
                    <?php endif; ?>            
                    type="button" class="button btn btn-primary" 
                    <?php if( $click && $vote==1){echo 'disabled';}?>>+
                </button>
                <button id="button<?=$item['user_id']?>minus" value="<?=$item['user_id']?>" 
                    <?php if($click): ?>
                        <?php if($vote==1): // 0 (-), 1 - раньше пользователь проголовал по-другому?>
                            onClick="updateRating(this.value, <?=$page?>, 0, 1, <?=$url?>);"  
                        <?php endif; ?>
                    <?php else: ?>
                        onClick="updateRating(this.value, <?=$page?>, 0, 0, <?=$url?>);"
                    <?php endif; ?>
                    type="button" class="button btn btn-primary" 
                    <?php if( $click && $vote==0){echo 'disabled';}?>>&#8211;
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php $i++; ?>
<?php endforeach; ?>
</div>
