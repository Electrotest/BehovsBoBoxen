<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 
/**
* Get list of tools.
*/
function get_tools() {
  global $bbb;
  return <<<EOD
<p><?= t('Visit: ') ?>
<a href="http://www.behovsbo.se</a>
</p>
EOD;
}


/**
 * Print debuginformation from the framework.
 */
function get_debug() {
 
  $bbb = CBehovsboboxen::Instance(); 
  // Only if debug is wanted.
  if(empty($bbb->config['debug'])) {
    return;
  }
  
  // Get the debug output
  $html = null;
  if(isset($bbb->config['debug']['db-num-queries']) && $bbb->config['debug']['db-num-queries'] && isset($bbb->db)) {
    $flash = $bbb->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $bbb->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($bbb->config['debug']['db-queries']) && $bbb->config['debug']['db-queries'] && isset($bbb->db)) {
    $flash = $bbb->session->GetFlash('database_queries');
    $queries = $bbb->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }    
  if(isset($bbb->config['debug']['timer']) && $bbb->config['debug']['timer']) {
    $now = microtime(true);
    //echo 'now: ' . $now . '<br />';
    $flash = $bbb->session->GetFlash('timer');
    //echo 'flash: ' . $flash . '<br />';
    if($flash){
    $redirect = $flash ? round($flash['redirect'] - $flash['first'], 3) . ' secs + x + ' : null;
    echo 'redirect: ' . $redirect . '<br />';
    $total = $flash ? round($now - $flash['first'], 3) . ' secs. Per page: ' : null;
    echo 'total: ' . $total . '<br />';
    $html .= "<p>Page was loaded in {$total}{$redirect}" . round($now - $bbb->timer['first'], 3) . " secs.</p>";
  }}
  if(isset($bbb->config['debug']['memory']) && $bbb->config['debug']['memory']) {
    $flash = $bbb->session->GetFlash('memory');
    $flash = $flash ? round($flash/1024/1024, 2) . ' Mbytes + ' : null;
    $html .= "<p>Peek memory consumption was $flash" . round(memory_get_peak_usage(true)/1024/1024, 2) . " Mbytes.</p>";
  } 
  if(isset($bbb->config['debug']['behovsboboxen']) && $bbb->config['debug']['behovsboboxen']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CBehovsboboxen:</p><pre>" . htmlent(print_r($bbb, true)) . "</pre>";
  }    
  if(isset($bbb->config['debug']['session']) && $bbb->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of CBehovsboboxen->session:</p><pre>" . htmlent(print_r($bbb->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }
  if(isset($bbb->config['debug']['timestamp']) && $bbb->config['debug']['timestamp']) {
    $html .= $bbb->log->TimestampAsTable();
    $html .= $bbb->log->PageLoadTime();
    $html .= $bbb->log->MemoryPeak();
  }
  return "<div class='debug'>$html</div>";
}


/**
 * Get messages stored in flash-session.
 */
function get_messages_from_session() {
  $messages = CBehovsboboxen::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      //$trans = t($val['message']);
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}

function login_menu() {
  $bbb = CBehovsboboxen::Instance();
  if(isset($bbb->config['menus']['login'])) {
    if($bbb->user->isAuthenticated()) {
      $item = $bbb->config['menus']['login']['items']['ucp'];
      $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'><img class='gravatar'  alt=''> " . $bbb->user['acronym'] . "</a> ";
      if($bbb->user['hasRoleAdmin']) {
        $item = $bbb->config['menus']['login']['items']['acp'];
        $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
      }
      $item = $bbb->config['menus']['login']['items']['logout'];
      $items .= "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    } else {
      $item = $bbb->config['menus']['login']['items']['login'];
      $items = "<a href='" . create_url($item['url']) . "' title='{$item['title']}'>{$item['label']}</a> ";
    }
    return "<nav>$items</nav>";
  }
  return null;
}



/**
 * Escape data to make it safe to write in the browser.
 *
 * @param $str string to escape.
 * @returns string the escaped string.
 */
function esc($str) {
  return htmlEnt($str);
}

/**
 * Prepend the base_url.
 */
function base_url($url=null) {
    return CBehovsboboxen::Instance()->request->base_url . trim($url, '/');
}


/**
 * Create a url to an internal resource.
 *
 * @param string the whole url or the controller. Leave empty for current controller.
 * @param string the method when specifying controller as first argument, else leave empty.
 * @param string the extra arguments to the method, leave empty if not using method.
 */
function create_url($urlOrController=null, $method=null, $arguments=null) {
    return CBehovsboboxen::Instance()->CreateUrl($urlOrController, $method, $arguments);
}

/**
 * Prepend the theme_url, which is the url to the current theme directory.
 *
 * @param $url string the url-part to prepend.
 * @returns string the absolute url.
 */
function theme_url($url) {
    return create_url(CBehovsboboxen::Instance()->themeUrl . "/{$url}");
}

/**
 * Prepend the theme_parent_url, which is the url to the parent theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_parent_url($url) {
    return create_url(CBehovsboboxen::Instance()->themeParentUrl . "/{$url}"); 
}


/**
 * Return the current url.
 */
function current_url() {
    return CBehovsboboxen::Instance()->request->current_url;
}


/**
* Render all views.
*
* @param $region string the region to draw the content in.
*/
function render_views($region='default') {
    return CBehovsboboxen::Instance()->views->Render($region);
}

/**
 * Check if region has views. Accepts variable amount of arguments as regions.
 *
 * @param $region string the region to draw the content in.
 */
function region_has_content($region = 'default' /* ... */) {
    return CBehovsboboxen::Instance()->views->RegionHasView(func_get_args());
}

/**
 * Create menu.
 *
 * @param array $menu array with details to generate menu.
 * @return string with formatted HTML for menu.
 */
function create_menu($menu) {
  return CBehovsboboxen::Instance()->CreateMenu($menu);
}

/**
 * Get language as defined in config.
 *
 * @returns string the language.
 */
function get_language() {
  return CBehovsboboxen::Instance()->config['language'];
}

/**
 * Prepend the static_url to url, static_url is the url to a cookie-less domain for assets like 
 * img, css and js-files.
 *
 * @param $url string the url-part to prepend.
 * @return string the absolute url.
 */
function static_url($url) {
  return CBehovsboboxen::Instance()->PrependUrl('static_url', $url);
}

/**
 * Include the javascript library modernizer, if defined.
 *
 * @return string if modernizr path is defined, else null.
 */
function modernizr_include() {
  global $bbb;
 return isset($bbb->config['javascript']['modernizr']) ? "<script src='" . static_url($bbb->config['javascript']['modernizr']) . "'></script>" : null;
}


/**
 * Add modernizer related class 'no-js' but only if modernizr is defined.
 *
 * @return string if modernizr is defined, else null.
 */
function modernizr_no_js() {
  global $bbb;
  return isset($bbb->config['javascript']['modernizr']) ? 'no-js' : null;
}

/**
 * Include the javascript library canvasjs, if defined.
 *
 * @return string if canvasjs path is defined, else null.
 */
function canvas_include() {
  global $bbb;
  return isset($bbb->config['javascript']['canvas']) ? "<script src='" . static_url($bbb->config['javascript']['canvas']) . "'></script>" : null;
}

/**
 * Include the javascript library jsdatepick, if defined.
 *
 * @return string if jsdatepick path is defined, else null.
 */
function jsdatepick_include() {
  global $bbb;
  return isset($bbb->config['javascript']['jsdatepick']) ? "<script src='" . static_url($bbb->config['javascript']['jsdatepick']) . "'></script>" : null;
}

/**
 * Include the javascript library jquery, if defined.
 *
 * @return string if jquery path is defined, else null.
 */
function jquery_include() {
  global $bbb;
  return isset($bbb->config['javascript']['jquery']) ? "<script src='" . static_url($bbb->config['javascript']['jquery']) . "'></script>" : null;
}

