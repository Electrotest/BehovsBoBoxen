<?php
/**
 * Site configuration, this file is changed by user per site.
 *
 */

/**
 * Set level of error reporting
 */
error_reporting(-1);
ini_set('display_errors', 1);


/**
 * Set what to show as debug or developer information in the get_debug() theme helper.
 */
$bbb->config['debug']['behovsboboxen'] = false;
$bbb->config['debug']['session'] = false;
$bbb->config['debug']['timer'] = false;
$bbb->config['debug']['db-num-queries'] = false;
$bbb->config['debug']['db-queries'] = false;
$bbb->config['debug']['memory'] = false;
$bbb->config['debug']['timestamp'] = false;


/**
 * Set database(s).
 */
$bbb->config['database'][0]['dsn'] = 'sqlite:' . BEHOVSBOBOXEN_APPLICATION_PATH . '/data/.ht.sqlite3';
//var_dump($bbb->config['database'][0]['dsn']);

/**
 * Set textbase(s).
 */
$bbb->config['textbase'] = BEHOVSBOBOXEN_APPLICATION_PATH . '/textfile/';

/**
 * Set currency.
 */
$bbb->config['currency'] = 'SEK';



/**
 * What type of urls should be used?
 * 
 * default      = 0      => index.php/controller/method/arg1/arg2/arg3
 * clean        = 1      => controller/method/arg1/arg2/arg3
 * querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
 */
$bbb->config['url_type'] = 1;


/**
 * Set base urls to use another than the default calculated
 *
 * base_url   = The base url to this installation.
 * static_url = Base url for cookie-less domain for all static assets, like images, css and js files.
 */
$bbb->config['base_url'] = null;
$bbb->config['static_url'] = null;


/**
 * How to hash password of new users, choose from: plain, md5salt, md5, sha1salt, sha1.
 */
$bbb->config['hashing_algorithm'] = 'sha1salt';


/**
 * Allow or disallow creation of new user accounts.
 */
$bbb->config['create_new_users'] = true;


/**
 * Define session name
 */
$bbb->config['session_name'] = preg_replace('/[:\.\/-_]/', '', __DIR__);
$bbb->config['session_key']  = 'behovsboboxen';
    
/**
 * Define server timezone
 */
$bbb->config['timezone'] = 'Europe/Stockholm';


/**
 * Define internal character encoding
 */
$bbb->config['character_encoding'] = 'UTF-8';

/**
 * Define language
 *
 * langugage: the language of the webpage and locale, settings for i18n, 
 *            internationalization supporting multilanguage.
 *            change to en_GB
 */
$bbb->config['language'] = 'sv_SE';


/**
 * Define what Javascript librarys to be included. Set the url to the source-file, use
 * relative link to be relative base_url, else set absolute url.
 */
$bbb->config['javascript']['modernizr'] = 'js/modernizr/2.6.1_smallest.js';
$bbb->config['javascript']['canvas'] = 'js/canvasjs.min.js';
$bbb->config['javascript']['jquery'] = 'js/jquery.min.js';
$bbb->config['javascript']['jquery7'] = 'js/jquery-1.7.1.min.js';
$bbb->config['javascript']['jqueryui'] = 'js/jquery-ui-1.8.17.custom.min.js';
$bbb->config['javascript']['jquery10'] = 'js/jquery-1.10.2.js';
$bbb->config['javascript']['jquery11'] = 'js/_1.11.4_jquery-ui.js';
$bbb->config['javascript']['script'] = 'js/script.js';
$bbb->config['javascript']['less'] = 'js/less.js';


/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'user/profile' would instantiate the controller with the key "user", that is 
 * CCUser and call the method "profile" in that class. This process is managed in:
 * $bbb->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$bbb->config['controllers'] = array(
    'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
    //'temperatures'    => array('enabled' => true,'class' => 'CCTemperatures'),
    'spotprices'    => array('enabled' => true,'class' => 'CCSpotprices'),
    'index'     => array('enabled' => true,'class' => 'CCIndex'),   
    'user'      => array('enabled' => true,'class' => 'CCUser'),
    'presentation'   => array('enabled' => true,'class' => 'CCPresentation'),
);

/**
* Define a routing table for urls.
*
* Route custom urls to a defined controller/method/arguments
*/
$bbb->config['routing'] = array(
    'home' => array('enabled' => true, 'url' => 'index/index'),
);
    
/**
 * Append site label after all titels, seo related, so it looks nice in the search engine
 * results.
 */
$bbb->config['title_append'] = 'BehovsBoBoxen';
$bbb->config['title_separator'] = ' - ';    

  /**
 * Define menus.
 *
 * Create hardcoded menus and map them to a theme region through $bbb->config['theme'].
 */
$bbb->config['menus'] = array(
    'navbar' => array(
        'presentation' => array('label' => t('Presentation'), 'url' => 'presentation'),
        'spotprices' => array('label' => t('Spotprices'), 'url' => 'spotprices'),
        'temperatures' => array('label' => t('Temperatures'), 'url' => 'acp/temperatures'),
        'acp' => array('label' => t('AdminControlPanel'), 'url' => 'acp'),
        'logout' => array('label' => t('Login/Logout'), 'url' => 'user/logout'),
    ),
);

/**
 * Settings for the theme. The theme may have a parent theme.
 *
 * Template files can reside in the parent or current theme, the CBehovsboboxen::ThemeEngineRender()
 * looks for the template-file in the current theme first, then it looks in the parent theme.
 *
 * There are two useful theme helpers defined in themes/functions.php.
 *  theme_url($url): Prepends the current theme url to $url to make an absolute url.
 *  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url.
 *
 * path: Path to current theme, relativly BEHOVSBOBOXEN_INSTALL_PATH, for example themes/grid or application/themes/mytheme.
 * parent: Path to parent theme, same structure as 'path'. Can be left out or set to null.
 * stylesheet: The stylesheet to include, always part of the current theme, use @import to include the parent stylesheet.
 * template_file: Set the default template file, defaults to default.tpl.php.
 * regions: Array with all regions that the theme supports.
 * data: Array with data that is made available to the template file as variables.
 *
 * The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made
 * available to the template files.
 */
$bbb->config['theme'] = array(
    'path' => 'themes/bb',
    'parent' => 'themes/bb',
    'template_file' => 'index.tpl.php',
    'regions' => array('header','navbar', 'my-navbar', 'flash', 'featured-first', 'featured-middle', 'featured-last',
        'primary', 'custom', 'sidebar', 'triptych-first', 'triptych-middle', 'triptych-last',
        'footer-column-one', 'footer-column-two', 'footer-column-three', 'footer-column-four',
        'footer'),
    'menu_to_region' => array('navbar' => 'navbar'),
    'data' => array(
        'header' => 'BehovsBoBoxen',
        'slogan' => t('makes your house smart!'),
        'favicon' => 'smallbox.jpg',
        'logo' => 'box.jpg',
        'logo_width' => 88,
        'logo_height' => 88,
        'stylesheet' => 'style.css',
        'footer' => '<p>BehovsBoBoxen &copy; Anders Kjellström, <a href="http://www.behovsbo.se">www.behovsbo.se</a></p>',
    ),
);
