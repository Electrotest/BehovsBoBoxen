<h1><?= $install ?></h1>

<p><?= t('The following modules were affected by this action.') ?></p>

<table>
    <caption><?= t('Results from installing modules.') ?></caption>
    <thead>
        <tr><th><?= $mod ?></th><th><?= $res ?></th></tr>
    </thead>
    <tbody>
        <?php foreach ($modules as $module): ?>
            <tr><td><?= $module['name'] ?></td><td><div class='<?= $module['result'][0] ?>'><?= $module['result'][1] ?></div></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>
