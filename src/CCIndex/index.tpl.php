<?php if($text != null):?>
<h1><?= $text ?></h1>
   <a href='<?= create_url('modules/install') ?>' title='Here you can start fresh again'>Install datamodules</a>
<?php endif;?>
<pre>
<?php
    $modules = apache_get_modules();
    echo in_array('mod_rewrite', $modules) ? "mod_rewrite module is enabled" : "mod_rewrite module is not enabled";
?>
</pre>