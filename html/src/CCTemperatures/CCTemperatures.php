<?php

/**
 * A temperature controller to manage temperatures.
 *
 * @package BehovsboboxenCore
 */
class CCTemperatures extends CObject implements IController {

    protected $textfiles;
    protected $temperatures;
    public $nrOfActiveRooms;
    public $list;
    public $various;
    public $theActiveRooms = array();

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->textfiles = new CMTextfiles();
        $this->temperatures = new CMTemperatures();
        $this->list = $this->temperatures->ListAll();
        $this->various = $this->temperatures->ListVarious();
//var_dump($this->list);
    }

    /**
     * Show a listing of all temperatures.
     */
    public function Index() {

        $this->views->SetTitle('Temperatur Controller')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                    'temperatures' => $this->getActiveRooms(),
                    'isTemps' => $this->textfiles->getIsTemps(),
                    'navbar' => $this->CreateMenu('navbar-ucp'),
        ));
    }

    /***************************************************************************************
*
*/

    public function getActiveRooms(){

        $this->nrOfActiveRooms = $this->various[0]['nrofrooms'];
        for($i = 0; $i < 16; $i++){
            if( ((int)$this->list[$i]['id']) <= $this->nrOfActiveRooms){
                array_push($this->theActiveRooms,$this->list[$i]);
            }
        }
        return $this->theActiveRooms;
    }


    }