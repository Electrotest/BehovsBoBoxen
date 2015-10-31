<div id='sidecolor'>
    <?= $area ?>
    <?= $rund ?>
    <?= $holiday ?>
    <?= $percent1 ?>
    <?= $percent2 ?>
<h4><?= $header2 ?></h4>
<ul>
    <li><a href='<?= create_url('acp/createuser') ?>' title='Create a new user account'><?= $text2 ?></a></li>
</ul>

<h4><?= $header3 ?></h4>
<ul>
    <li><a href='<?= create_url('acp/creategroup') ?>' title='Create a new group account'><?= $text3 ?></a></li>
</ul>

<h4><?= $header4 ?></h4>
<ul>
    <li><a href='<?= create_url('modules/install') ?>' title='Here you can start fresh again'><?= $text4 ?></a>
</ul>
</div>