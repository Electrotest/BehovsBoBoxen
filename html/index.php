<?php

/**
 * All requests routed through here. This is an overview of what actaully happens during
 * a request.
 *
 * @package BehovsboboxenCore
 */
// ---------------------------------------------------------------------------------------
//
// PHASE: INIT
//

define('BEHOVSBOBOXEN_INSTALL_PATH', dirname(__FILE__));
#echo 'BEHOVSBOBOXEN_INSTALL_PATH: ' . BEHOVSBOBOXEN_INSTALL_PATH . '<br />';
define('BEHOVSBOBOXEN_APPLICATION_PATH', BEHOVSBOBOXEN_INSTALL_PATH . '/application');
#echo 'BEHOVSBOBOXEN_APPLICATION_PATH: ' . BEHOVSBOBOXEN_APPLICATION_PATH . '<br />';
require(BEHOVSBOBOXEN_INSTALL_PATH.'/src/bootstrap.php');

$bbb = CBehovsboboxen::Instance()->Init();

/* ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
*/

CBehovsboboxen::Instance()->FrontControllerRoute();
/*---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
*/

CBehovsboboxen::Instance()->ThemeEngineRender();