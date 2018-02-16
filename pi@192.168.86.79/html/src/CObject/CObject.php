<?php
/**
 * Holding an instance of CBehovsboboxen to enable use of $this in subclasses and provide some helpers.
 *
 * @package BehovsboboxenCore
 */
class CObject {
    /**
    * Members
    */
    protected $bbb;
    protected $config;
    protected $request;
    protected $data;
    protected $db;
    protected $views;
    protected $session;
    protected $user;
    protected $temperatures;
    protected $textfiles;
    protected $translate;
    protected $api;

    /**
    * Constructor, can be instantiated by sending in the $bbb reference.
    */
    protected function __construct($bbb=null) {
        if(!$bbb) {
            $bbb = CBehovsboboxen::Instance();
        } 
        $this->bbb      = &$bbb;
        $this->config   = &$bbb->config;
        $this->request  = &$bbb->request;
        $this->data     = &$bbb->data;
        $this->db       = &$bbb->db;
        $this->views    = &$bbb->views;
        $this->session  = &$bbb->session;
        $this->user     = &$bbb->user;
        $this->temperatures = &$bbb->temperatures;
        $this->textfiles = &$bbb->textfiles;
        $this->translate = &$bbb->translate;
        $this->api       = &$bbb->api;
    }


    /**
    * Wrapper for same method in CBehovsboboxen. See there for documentation.
    */
    protected function RedirectTo($urlOrController=null, $method=null, $arguments=null) {
        $this->bbb->RedirectTo($urlOrController, $method, $arguments);
    }
        


    /**
    * Wrapper for same method in CBehovsboboxen. See there for documentation.
    */
    protected function RedirectToController($method = null, $arguments = null) {
        $this->bbb->RedirectToController($method, $arguments);
    }

    /**
     * Wrapper for same method in CBehovsboboxen. See there for documentation.
     */
    protected function RedirectToControllerMethod($controller = null, $method = null, $arguments = null) {
        $this->bbb->RedirectToControllerMethod($controller, $method, $arguments);
    }

    /**
     * Wrapper for same method in CBehovsboboxen. See there for documentation.
     */
    protected function AddMessage($type, $message, $alternative = null) {
        return $this->bbb->AddMessage($type, $message, $alternative);
    }

    /**
     * Wrapper for same method in CBehovsboboxen. See there for documentation.
     */
    protected function CreateUrl($urlOrController = null, $method = null, $arguments = null) {
        return $this->bbb->CreateUrl($urlOrController, $method, $arguments);
    }
    
    /**
   * Wrapper for same method in CBehovsboboxen. See there for documentation.
   */
  protected function CreateCleanUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->bbb->CreateCleanUrl($urlOrController, $method, $arguments);
  }


  /**
   * Wrapper for same method in CBehovsboboxen. See there for documentation.
   */
  protected function CreateUrlToController($method=null, $arguments=null) {
    return $this->bbb->CreateUrlToController($method, $arguments);
  }


  /**
   * Wrapper for same method in CBehovsboboxen. See there for documentation.
   */
  protected function CreateUrlToControllerMethod($arguments=null) {
    return $this->bbb->CreateUrlToControllerMethod($arguments);
  }



  /**
   * Wrapper for same method in CBehovsboboxen. See there for documentation.
   */
  protected function CreateUrlToControllerMethodArguments() {
    return $this->bbb->CreateUrlToControllerMethodArguments();
  }


    /**
     * Wrapper for same method in CBehovsboboxen. See there for documentation.
     */
    
    protected function CreateMenu($options) {
        return $this->bbb->CreateMenu($options);
    }

    /**
     * Wrapper for same method in CBehovsboboxen. See there for documentation.
     */
    
    protected function DrawMenu($options) {
        return $this->bbb->DrawMenu($options);
    }
    
     /**
	 * Wrapper for same method in CBehovsboboxen. See there for documentation. Tries to find view 
	 * related to class, if it fails it tries to find view related to parent class.
   */
  protected function LoadView($view) {
    $file = $this->bbb->LoadView(get_class($this), $view);
    if(!$file) {
      $file = $this->bbb->LoadView(get_parent_class($this), $view);
    }
    if(!$file) {
      throw new Exception(t('No such view @viewname.', array('@viewname' => $view)));
    }
    return $file;
  }
  
  /**
   * Wrapper for same method in CBehovsboboxen. See there for documentation.
   * 
   */

   protected function SetLocale() {
    return $this->bbb->SetLocale();
  }


}

