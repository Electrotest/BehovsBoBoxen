<h1><?= t('Login') ?></h1>

<p><?= t('This is a demo. Login with root:root.') ?></p>
<?=$login_form->GetHTML(array('start'=>true))?>
  <fieldset>
    <?=$login_form['acronym']->GetHTML()?>
    <?=$login_form['password']->GetHTML()?> 
    <?=$login_form['login']->GetHTML()?>
  </fieldset>
</form>


