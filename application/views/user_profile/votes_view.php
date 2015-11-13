<div id="votes">
    <h2>История</h2>
    <?php if($votes): ?>
        <?php foreach($votes as $item): ?>
        <p <?=($item['estimation']==1)? 'class="bg-success"' :'class="bg-danger"'?>>
            <?=$item['date']?> 
            <a href="<?=site_url('user_profile')?>?id=<?=$item['user_id']?>">
                <?=$item['username']?>
            </a> поставил<?php if($item['sex']==1) {echo 'а';} ?> 
            <?=($item['estimation']==1)? '+' :'-'?>
        </p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>История пуста. За пользователя никто не голосовал.</p>
    <?php endif; ?>
</div>

