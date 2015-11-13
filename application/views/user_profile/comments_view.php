<div id="comments">
    <h2>Комментарии</h2>
    <?php if($comments): ?>
        <?php foreach($comments as $item): ?>
            <p><?=$item['contents']?></p>
            <p class="text-muted">Написал<?php if($item['sex']==1) {echo 'а';}?> 
            <a href="<?=site_url('user_profile')?>?id=<?=$item['user_id']?>">
                <?=$item['username']?>
            </a> 
            <?=$item['date']?>
            </p> 
        <?php endforeach; ?>
    <?php else: ?>
        <p>Комментариев нет</p>
    <?php endif; ?>
    <?php if ($id_exist): ?>
        <div>
            <label for="comment">Оставить комментарий:</label> <br/>
            <textarea id="comment" rows="5" cols="30" wrap="soft"></textarea> 
            <br/>
            <button value="<?=$user_id?>" onClick="addComment(this.value);">
                Написать
            </button>
        </div>
    <?php endif; ?>      
</div>