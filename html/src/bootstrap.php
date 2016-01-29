<?php
/**
 * Bootstrapping, setting up and loading the core.
 *
 * @package BehovsboboxenCore
 */

/**
 * Enable auto-load of class declarations.
 */
function autoload($aClassName) {
  /*
  if ($_SERVER['HTTPS']){
    print_r(true);
  }else{
    print_r(false);
  }*/
  $classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = BEHOVSBOBOXEN_APPLICATION_PATH . $classFile;
	$file2 = BEHOVSBOBOXEN_INSTALL_PATH . $classFile;
	if(is_file($file1)) {
		require_once($file1);
	} elseif(is_file($file2)) {
		require_once($file2);
	}
}
spl_autoload_register('autoload');


/**
 * Set a default exception handler and enable logging in it.
 */
function exceptionHandler($e) {
  echo "Behovsboboxen: Uncaught exception: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>";
}
set_exception_handler('exceptionHandler');


/**
 * Helper, include a file and store it in a string. Make $vars available to the included file.
 */
function getIncludeContents($filename, $vars=array()) {
  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}


/**
 * Helper, wrap html_entites with correct character encoding
 */
function htmlEnt($str, $flags = ENT_COMPAT) {
  return htmlentities($str, $flags, CBehovsboboxen::Instance()->config['character_encoding']);
}


/**
 * Make clickable links from URLs in text.
 *
 * @param string $text the text that should be formatted.
 * @return string with formatted anchors.
 */
function makeClickable($text) {
  return preg_replace_callback(
    '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',         
    create_function(
      '$matches',
      'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
    ),
    $text
  );
}



/**
  *
  * Simplified translation with datastored list (CMTranslate)
 */
function t($str, $args = array()) {
  $trans = $str;
           
  if(CBehovsboboxen::Instance()->config['language'] == 'sv_SE') { 
      $texts = CBehovsboboxen::Instance()->GetTranslations();

    foreach($texts as $key => $val) {
      if(trim($texts[$key]['eng']) == trim($str)){
        $trans = trim($texts[$key]['swe']);
        break;
      }
    }
  }
  return $trans;
}
