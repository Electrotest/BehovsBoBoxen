<div id='ucp-navbar'><?=$navbar?></div>
<?php if($temperatures):?>

    <div id ='temp'>
    	<?php if ($temperatures != null): ?>
	<table><thead><tr><th scope='col'>Rum</th><th>Id</th><th>Ärvärde</th><th>Börvärde</th><th>Max</th><th>Min</th><th>Borta</th><th>Rundstyrtemp</th></tr></thead><tbody>
    <caption>Nu är det <?= $isTemps[1] ?> Grader Celsius utomhus.</caption>
    	<?php foreach ($temperatures as $val): ?>
    		<tr><th scope='row' class='spec'><?= $val['room'] ?></th><td><?= $val['id'] ?></td><td><?= $isTemps[$val['id']] ?></td><td><?= $val['home'] ?></td><td><?= $val['max'] ?></td><td><?= $val['min'] ?></td><td><?= $val['away'] ?></td><td><?= $val['rund'] ?></td><td></tr>
        <?php endforeach; ?>
	</table>
    
            <?php else:?>
      <p>No such data exists.</p>
    <?php endif;?>
    </div>
<?php endif;?>