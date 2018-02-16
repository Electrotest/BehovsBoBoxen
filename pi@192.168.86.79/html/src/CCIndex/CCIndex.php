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
           $this->RedirectTo('presentation'); 
    }
}

