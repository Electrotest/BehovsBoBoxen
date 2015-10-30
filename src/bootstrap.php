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
//echo 'BEHOVSBOBOXEN_APPLICATION_PATH' . BEHOVSBOBOXEN_APPLICATION_PATH . $classFile . '<br />';
	$file2 = BEHOVSBOBOXEN_INSTALL_PATH . $classFile;
//echo 'BEHOVSBOBOXEN_INSTALL_PATH: ' . BEHOVSBOBOXEN_INSTALL_PATH . $classFile . '<br />';
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
    * Helper, BBCode formatting converting to HTML.
    *
    * @param string text The text to be converted.
    * @returns string the formatted text.
    */
    function bbcode2html($text) {
      $search = array(
        '/\[b\](.*?)\[\/b\]/is',
        '/\[i\](.*?)\[\/i\]/is',
        '/\[u\](.*?)\[\/u\]/is',
        '/\[img\](https?.*?)\[\/img\]/is',
        '/\[url\](https?.*?)\[\/url\]/is',
        '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
        );   
      $replace = array(
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<u>$1</u>',
        '<img src="$1" />',
        '<a href="$1">$1</a>',
        '<a href="$1">$2</a>'
        );     
      return preg_replace($search, $replace, $text);
    }


/**
* Helper, interval formatting of times. Needs PHP5.3.
*
* All times in database is UTC so this function assumes the starttime to be in UTC, if not otherwise
* stated.
*
* Copied from http://php.net/manual/en/dateinterval.format.php#96768
* Modified (mos) to use timezones.
* A sweet interval formatting, will use the two biggest interval parts.
* On small intervals, you get minutes and seconds.
* On big intervals, you get months and days.
* Only the two biggest parts are used.
*
* @param DateTime|string $start
* @param DateTimeZone|string|null $startTimeZone
* @param DateTime|string|null $end
* @param DateTimeZone|string|null $endTimeZone
* @return string
*/
function formatDateTimeDiff($start, $startTimeZone=null, $end=null, $endTimeZone=null) {
  if(!($start instanceof DateTime)) {
    if($startTimeZone instanceof DateTimeZone) {
      $start = new DateTime($start, $startTimeZone);
    } else if(is_null($startTimeZone)) {
      $start = new DateTime($start);
    } else {
      $start = new DateTime($start, new DateTimeZone($startTimeZone));
    }
  }
  
  if($end === null) {
    $end = new DateTime();
  }
  
  if(!($end instanceof DateTime)) {
    if($endTimeZone instanceof DateTimeZone) {
      $end = new DateTime($end, $endTimeZone);
    } else if(is_null($endTimeZone)) {
      $end = new DateTime($end);
    } else {
      $end = new DateTime($end, new DateTimeZone($endTimeZone));
    }
  }
  
  $interval = $end->diff($start);
  $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals
  //$doPlural = create_function('$nb,$str', 'return $nb>1?$str."s":$str;'); // adds plurals
  
  $format = array();
  if($interval->y !== 0) {
    $format[] = "%y ".$doPlural($interval->y, "year");
  }
  if($interval->m !== 0) {
    $format[] = "%m ".$doPlural($interval->m, "month");
  }
  if($interval->d !== 0) {
    $format[] = "%d ".$doPlural($interval->d, "day");
  }
  if($interval->h !== 0) {
    $format[] = "%h ".$doPlural($interval->h, "hour");
  }
  if($interval->i !== 0) {
    $format[] = "%i ".$doPlural($interval->i, "minute");
  }
  if(!count($format)) {
      return "less than a minute";
  }
  if($interval->s !== 0) {
    $format[] = "%s ".$doPlural($interval->s, "second");
  }
  
  if($interval->s !== 0) {
      if(!count($format)) {
          return "less than a minute";
      } else {
          $format[] = "%s ".$doPlural($interval->s, "second");
      }
  }
  
  // We use the two biggest parts
  if(count($format) > 1) {
      $format = array_shift($format)." and ".array_shift($format);
  } else {
      $format = array_pop($format);
  }
  
  // Prepend 'since ' or whatever you like
  return $interval->format($format);
}

/**
 * i18n, internationalization, send all strings though this function to enable i18n.
 * Inspired by Drupal´s t()-function.
 *
 * @param string $str the string to check up for translation.
 * @param array $args associative array with arguments to be replaced in the $str.
 *   - !variable: Inserted as is. Use this for text that has already been
 *     sanitized.
 *   - @variable: Escaped to HTML using htmlEnt(). Use this for anything
 *     displayed on a page on the site.
 * @return string the translated string.
 */
function t($str, $args = array()) {
  $trans = $str;
           
  if(CBehovsboboxen::Instance()->config['i18n']) { 
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
