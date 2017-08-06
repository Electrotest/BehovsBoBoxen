<!doctype html>
<html lang='en'> 
    <head>
        <meta charset='utf-8'/>
        <title><?= $title ?></title>
        <link rel='shortcut icon' href='<?= $favicon ?>'/>
        <?=modernizr_include()?>
    </head>
    <body>
        <div id='primary'><?= render_views('primary') ?><?= render_views() ?></div>
    </body>
</html>