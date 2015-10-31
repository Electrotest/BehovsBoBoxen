<h2><?= $header1 ?></h2>
<?= $form ?>

<h3><?= $header2 ?></h3>
<?= $form2 ?>
      
<script type='text/javascript'>
        <!-- Copyright 2009 Itamar Arjuan jsDatePick is distributed under the terms of the GNU General Public License.-->
        
            window.onload = function() {
                g_globalObject1 = new JsDatePick({
                    useMode: 2,
                    target: 'form-element-from',
                    dateFormat: '%d.%m.%Y',
                    cellColorScheme: 'armygreen'
                });

                g_globalObject2 = new JsDatePick({
                    useMode: 2,
                    target:'form-element-to',
                    dateFormat:'%d.%m.%Y',
                    cellColorScheme:'armygreen',    
                });                     
            };
        </script>
