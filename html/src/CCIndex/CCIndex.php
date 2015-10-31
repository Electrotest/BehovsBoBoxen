<?php

/**
 * Standard controller layout.
 * 
 * @package BehovsboboxenCore
 */
class CCIndex extends CObject implements IController {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Implementing interface IController. All controllers must have an index action.
     */
    public function Index() {
        $this->RedirectTo(user,login);
/*        
        $text = t('Some text');
        $this->views->SetTitle('Index')
             ->AddInclude(__DIR__ . '/index.tpl.php', array(
                    'text' => $text,
                    ), 'primary');*/
    }
}

