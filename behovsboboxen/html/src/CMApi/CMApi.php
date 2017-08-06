<?php

/**
 * A model for content stored in textfiles.
 *
 * @package BehovsboboxenCore
 */
class CMApi extends CObject implements ArrayAccess/*, IModule */{

    protected $texfiles;
    public $filecurrent;
    public $filenew;

        /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
            $this->textfiles = new CMTextfiles();
            $this->filecurrent = $this->textfiles->returnSpotCurr();
            $this->filenew = $this->textfiles->returnSpotNew();
            //var_dump($this->filecurrent);
    }

    /**
     * Implementing ArrayAccess for $this->lists
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->lists[] = $value;
        } else {
            $this->lists[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->lists[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->lists[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->lists[$offset]) ? $this->lists[$offset] : null;
    }

    public function LoadLists(){

    }

    public function ToDo() {
            // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
        $input = json_decode(file_get_contents('php://input'),true);
    }

    public function returnCurrent() {
        return json_encode($this->filecurrent);
    }

    public function returnNew() {
        return json_encode($this->filenew);
    }

}