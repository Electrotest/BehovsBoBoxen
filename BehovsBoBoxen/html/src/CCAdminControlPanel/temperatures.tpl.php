</div>
<div id ='temp'>
<h2><?= $header4 ?></h2>
<?php if($temperatures):?>
    <div id ='temp'>
    <?php if ($temperatures != null): ?>
        <table class ='fixed'>
            <caption><?= $now ?> <?= $isTemps[1] ?> <?= $outside ?></caption>
            <thead><tr><th scope='col'><?= $edit ?></th><th class = 'w3'>Id</th><th class = 'w3'><?= $isvalue ?></th><th class = 'w3'><?= $shouldvalue ?></th><th class = 'w3'>Max</th><th class = 'w3'>Min</th><th class = 'w3'><?= $away ?></th><th class = 'w3'><?= $loadcontrol ?></th></tr></thead><tbody>        

            <?php foreach ($temperatures as $val): 
                if ($val['id'] % 2 === 1) {
                $class='specalt';
                $row = "<td class='alt'>";
            } else {
                $class='spec';
                $row = "<td class = 'w3'>";
            } ?>
                <tr><th scope='row' class='<?= $class?>'><a href='<?= create_url("acp/update/{$val['room']}") ?>'><?= $val['room'] ?></a></th><?= $row ?><?= $val['id'] ?></td><?= $row ?><?= $isTemps[$val['id']] ?></td><?= $row ?><?= $val['home'] ?></td><?= $row ?><?= $val['max'] ?></td><?= $row ?><?= $val['min'] ?></td><?= $row ?><?= $val['away'] ?></td><?= $row ?><?= $val['rund'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php else:?>
        <p>No such data exists.</p>
    <?php endif;?>
    </div>
<?php endif;?>