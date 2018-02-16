</div>
<div id ='temp2'>

<?php if($various):?>
    
    <?php if ($various != null): $count = 0;$row = "";?>
        <table class ='fixedacp'><thead><tr><th scope='col'><?= $edit ?></th><th><?= $val ?></th></tr></thead><tbody>
            <?php foreach ($various[0] as $value) {
                if ($count % 2 === 1) {
                    $row = "<td class='alt'>";
                } else {
                    $row = "<td class = 'w3'>";
                }
            }?>

            <tr><th scope='row' class='spec'><?= $areacode ?></th><?= $row ?><?= $various[0]['area'] ?></td></tr>
            <tr><th scope='row' class='specalt'><?= $nrof ?></th><?= $row ?><?= $various[0]['nrofrooms'] ?></td></tr>
            <tr><th scope='row' class='spec'><?= $load ?></th><?= $row ?><?= $various[0]['load'] ?></td></tr>
            <tr><th scope='row' class='specalt'><?= $percent ?></th><?= $row ?><?= $various[0]['percent'] ?></td></tr>
            <tr><th scope='row' class='spec'><?= $percentlevel ?></th><?= $row ?><?= $various[0]['percentlevel'] ?></td></tr>
            <tr><th scope='row' class='thawayfrom'><?= $awayfrom ?></th><td class = 'awayfrom' name = 'awayfrom'><?= $various[0]['fromdate'] ?></td></tr>
            <tr><th scope='row' class='thawayto'><?= $awayto ?></th><td class = 'awayto'><?= $various[0]['todate'] ?></td></tr>

        </tbody></table>
    <?php else:?>
        <p>No such data exists.</p>
    <?php endif;?>    
<?php endif;?> 


<?php if ($users != null): ?>

<table class ='fixedlogin'><thead>
    <tr><th scope='col'><?= $acronym ?></th><th><?= $pass1 ?></th><th><?= $pass2 ?></th><th><?= $name ?></th><th>Email</th><th>Id</th></tr></thead><tbody>
    <?php foreach ($users as $user): if($user['id'] > 1){?>       
    <tr><td class = 'w3'><?= esc($user['acronym']) ?></td><td class = 'w3'></td><td class = 'w3'></td><td class = 'w3'><?= esc($user['name']) ?></td><td class = 'w3'><?= esc($user['email']) ?></td><td class = 'w3'><?= $user['id'] ?></td></td>
    </tr>
<?php } endforeach; ?>
    </tbody>
</table>

<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>

</div>