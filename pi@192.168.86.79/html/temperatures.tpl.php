</div>
<div id ='temp2'>
<?php if ($temperatures) :?>
    <?php if ($temperatures != null) : ?>
    <form action='null' method='get'>
        <table class ='fixedEdit'>
            <caption> <?= $isTemps[16] ?> <?= $now ?> <?= $isTemps[2] ?> <?= $outside ?></caption>
            <thead><tr><th scope='col'><?= $room ?></th><th class = 'w3'><?= $isvalue ?></th>
            <th class = 'w3'><?= $shouldvalue ?></th><th class = 'w3'>Max</th><th class = 'w3'>Min</th>
            <th class = 'w3'><?= $away ?></th><th class = 'w3'><?= $loadcontrol ?></th><th class = 'w3'><?= $on ?></th>
            <th class = 'w3'><?= $off ?></th><th class = 'w3'>Id</th></tr></thead><tbody>

            <tr><th scope='row' class='spec'><?= $temperatures[0]['room'] ?></th>
            <td class = 'w3'><?= $isTemps[$temperatures[0]['id']-1] ?></td>
            <td class = 'w3'><?= $temperatures[0]['home'] ?></td><td class = 'w3'><?= $temperatures[0]['max'] ?></td>
            <td class = 'w3'><?= $temperatures[0]['min'] ?></td><td class = 'w3'><?= $temperatures[0]['away'] ?></td>
            <td class = 'w3'><?= $temperatures[0]['rund'] ?></td>
            <td class = 'w3'><?= $temperatures[0]['at'] ?></td>
            <td class = 'w3'><?= $temperatures[0]['off'] ?></td>
            <td><?= $temperatures[0]['id'] ?></td></tr>

            <tr><th scope='row' class='specalt'><?= $temperatures[1]['room'] ?></th>
            <td class='alt'><?= $isTemps[$temperatures[1]['id']-1] ?></td>
            <td class='alt'><?= $temperatures[1]['home'] ?></td>
            <td class='alt'><?= $temperatures[1]['max'] ?></td>
            <td class='alt'><?= $temperatures[1]['min'] ?></td>
            <td class='alt'><?= $temperatures[1]['away'] ?></td>
            <td class='alt'><?= $temperatures[1]['rund'] ?></td>
            <td class='alt'><?= $temperatures[1]['at'] ?></td>
            <td class='alt'><?= $temperatures[1]['off'] ?></td>
            <td><?= $temperatures[1]['id'] ?></td></tr>

            <tr><th scope='row' class='spec'><?= $temperatures[3]['room'] ?></th>
            <td class = 'w3'><?= $isTemps[$temperatures[3]['id']] ?></td>
            <td class = 'w3'><?= $temperatures[3]['home'] ?></td>
            <td class = 'w3'><?= $temperatures[3]['max'] ?></td>
            <td class = 'w3'><?= $temperatures[3]['min'] ?></td>
            <td class = 'w3'><?= $temperatures[3]['away'] ?></td>
            <td class = 'w3'><?= $temperatures[3]['rund'] ?></td>
            <td class = 'w3'><?= $temperatures[3]['at'] ?></td>
            <td class = 'w3'><?= $temperatures[3]['off'] ?></td>
            <td><?= $temperatures[3]['id'] ?></td></tr>
        </table>
    </form>
    <?php else :?>
        <p>No such data exists.</p>
    <?php endif;?>
    </div>
<?php endif;?>
