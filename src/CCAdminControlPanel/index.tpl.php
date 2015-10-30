</div>
<div id ='temp'>
<h2><?= $header ?></h2>

<?php if($various):?>
    
    <?php if ($various != null): $count = 0;$row = "";?>
        <table class ='fixedacp'><thead><tr><th scope='col'><?= $edit ?></th><th><?= $value ?></th></tr></thead><tbody>
            <?php foreach ($various as $value) {
                if ($count % 2 === 1) {
                $row = "<td class='alt'>";
            } else {
                $row = "<td class = 'w3'>";
            }
            }?>

            <tr><th scope='row' class='spec'><a href='<?= create_url("acp/smallsettings") ?>'><?= $areacode ?></a></th><?= $row ?><?= $various[0]['area'] ?></td></tr>
            <tr><th scope='row' class='specalt'><?= $nrof ?></th><?= $row ?><?= $various[0]['nrofrooms'] ?></td></tr>
            <tr><th scope='row' class='spec'><?= $load ?></th><?= $row ?><?= $various[0]['load'] ?></td></tr>
            <tr><th scope='row' class='specalt'><?= $percent ?></th><?= $row ?><?= $various[0]['percent'] ?></td></tr>
            <tr><th scope='row' class='spec'><a href='<?= create_url("acp/percentlevel") ?>'><?= $percentlevel ?></th><?= $row ?><?= $various[0]['percentlevel'] ?></td></tr>
            <tr><th scope='row' class='specalt'><a href='<?= create_url("acp/holiday") ?>'><?= $awayfrom ?></a></th><?= $row ?><?= $various[0]['fromdate'] ?></td></tr>
            <tr><th scope='row' class='spec'><?= $awayto ?></th><?= $row ?><?= $various[0]['todate'] ?></td></tr>
            <!--<tr><th scope='row' class='specalt'><a href='<?= create_url("modules/install") ?>'title='<?= $startagain ?>'><?= $database ?></a></th><?= $row ?></td></tr>--></tbody>
        </table>
    <?php else:?>
        <p>No such data exists.</p>
    <?php endif;?>
    </div>
<?php endif;?>


<h2><?= $header5 ?></h2>
<?php if ($users != null): ?>

<table class ='fixedlogin'><thead>
    <tr><th scope='col'><?= $memberedit ?></th><th>Id</th><th><?= $acronym ?></th><th><?= $name ?></th><th>Email</th><th><?= $algoritm ?></th><th><?= $created ?></th><th><?= $updated ?></th></tr></thead><tbody>
    <?php foreach ($users as $val): if($val['id'] > 1){?>       
    <tr><th scope='row'><a href='<?= create_url("acp/edit/{$val['id']}") ?>'><?= $edit ?></a></th><td class = 'w3'><?= $val['id'] ?></td><td class = 'w3'><?= esc($val['acronym']) ?></td><td class = 'w3'><?= $val['name'] ?></td><td class = 'w3'><?= $val['email'] ?></td><td class = 'w3'><?= $val['algorithm'] ?></td><td class = 'w3'><?= $val['created'] ?></td><td class = 'w3'><?= $val['updated'] ?></td>
	</tr>
    <?php } endforeach; ?>
    </tbody>
</table>

<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>


</div>

