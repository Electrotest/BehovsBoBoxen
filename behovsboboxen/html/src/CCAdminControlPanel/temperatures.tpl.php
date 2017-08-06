</div>
<div id ='temp2'>
<?php if($temperatures):?>
    <?php if ($temperatures != null): ?>
    <form action='null' method='get'>
        <table class ='fixedEdit'>
            <caption> <?= $isTemps[16] ?> <?= $now ?> <?= $isTemps[0] ?> <?= $outside ?></caption>
            <thead><tr><th scope='col'><?= $room ?></th><th class = 'w3'><?= $isvalue ?></th><th class = 'w3'><?= $shouldvalue ?></th><th class = 'w3'>Max</th><th class = 'w3'>Min</th><th class = 'w3'><?= $away ?></th><th class = 'w3'><?= $loadcontrol ?></th><th class = 'w3'><?= $on ?></th><th class = 'w3'><?= $off ?></th><th class = 'w3'>Id</th></tr></thead><tbody>        

            <?php foreach ($temperatures as $val): 
                if ($val['id'] % 2 === 0) {
                $class='specalt';
                $row = "<td class='alt'>";
            } else {
                $class='spec';
                $row = "<td class = 'w3'>";
            }
            if ($val['at'] === NULL) {
                    $at = "Default";
                } else {
                    $at = $val['at'];
                }
                if ($val['off'] === NULL) {
                    $inoff = "Default";
                } else {
                    $inoff = $val['off'];
            }
            ?>
                <tr><th scope='row' class='<?= $class?>'><?= $val['room'] ?></th><?= $row ?><?= $isTemps[$val['id']] ?></td><?= $row ?><?= $val['home'] ?></td><?= $row ?><?= $val['max'] ?></td><?= $row ?><?= $val['min'] ?></td><?= $row ?><?= $val['away'] ?></td><?= $row ?><?= $val['rund'] ?></td><?= $row ?><?= $at ?></td><?= $row ?><?= $inoff ?></td><td><?= $val['id'] ?></td></tr>
            <?php endforeach; ?>
        </table>
    </form>
    <?php else:?>
        <p>No such data exists.</p>
    <?php endif;?>
    </div>
<?php endif;?>