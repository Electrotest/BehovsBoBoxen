<?php

/**
 * Admin Control Panel to manage admin stuff.
 * 
 * @package BehovsboboxenCore
 */
class CCAdminControlPanel extends CObject implements IController {

    /**
     * properties
     */

    protected $user;
    protected $temperatures;
    protected $textfiles;
    private $nrOfRooms;
    private $nrOfActiveRooms;
    private $theActiveRooms = array();
    private $roomsInfo;
    private $selectedRoom;
    private $fromDate;
    private $toDate;
    private $todaysDate;
    private $various = array();

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->user = new CMUser();
        $this->temperatures = new CMTemperatures();
        $this->textfiles = new CMTextfiles();
        $this->Lists();
    }


    /**
     * Show admin information.
     */
    public function Index() {
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();
        $this->views->SetTitle(t('ACP: Admin Control Panel'))
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                    'various' => $this->various,
                    'users' => $this->user->ListAllUsers(),
                    'acronym' => t('Acronym'),
                    'name' => t('Name'),
                    'algoritm' => t('Algorithm'),
                    'created' => t('Created'),
                    'updated' => t('Updated'),
                    'memberedit' => t('Member'),
                    'pass1' => t('new password'),
                    'pass2' => t('new password confirmed'),
                    'edit' => t('Edit'),
                    'val' => t('Value'),
                    'value' => t('Value'),
                    'areacode' => t('Areacode'),
                    'nrof' => t('Your nr of rooms'),
                    'load' => t('Allow load?'),
                    'percent' => t('Percent on?'),
                    'percentlevel' => t('Percentlevel'),
                    'awayfrom' => t('Away from'),
                    'awayto' => t('Away to'),                   

        ));
    }

/*****************************************************************************************************
*create some usable variables for the page
*/

    public function Lists() {
        $list = $this->user->ListAllUsers();

        $rooms = $this->temperatures->ListAll();
        $this->nrOfRooms = count($rooms);
        $this->roomsInfo = $rooms;

        $this->various = $this->temperatures->ListVarious();
        if($this->various[0]['load'] == 1){
            $this->various[0]['load']= 'JA';
        }else{
            $this->various[0]['load']= 'NEJ';
        }
        if($this->various[0]['percent'] == 1){
            $this->various[0]['percent']= 'JA';
        }else{
            $this->various[0]['percent']= 'NEJ';
        }

        $this->nrOfActiveRooms = $this->various[0]['nrofrooms'];
        $this->getActiveRooms();

        $this->fromDate = $this->various[0]['fromdate'] ? $this->various[0]['fromdate'] : "";
        $this->toDate = $this->various[0]['todate'] ? $this->various[0]['todate'] : "";

        $this->todaysDate = $this->textfiles->getTodaysdate();

    }

/***************************************************************************************
*
*/

    public function getActiveRooms(){
        for($i = 0; $i < 16; $i++){
            if( ((int)$this->roomsInfo[$i]['id']) <= $this->nrOfActiveRooms){
                array_push($this->theActiveRooms,$this->roomsInfo[$i]);
            }
        }
        return $this->theActiveRooms;
    }

/*****************************************************************************************************
* Show admin information.
*/
    public function Temperatures() {
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();
        $this->views->SetTitle(t('Temperatures: Edit'))
                ->AddInclude(__DIR__ . '/temperatures.tpl.php', array(
                    'temperatures' => $this->theActiveRooms,
                    'isTemps' => $this->textfiles->getIsTemps(),
                    'outside' => t(' degrees celsius outside.'),
                    'now' => t('Now it is '),
                    'edit' => t('Edit'),
                    'room' => t('Room'),
                    'isvalue' => t('Isvalue'),
                    'shouldvalue' => t('ShouldValue'),
                    'away' => t('Away'),
                    'loadcontrol' => t('Loadcontrol'),
                    'on' => t('On'),
                    'off' => t('Off'),
                    'edit' => t('Edit'),
                    'save' => t('Save'),
        ));
    }

/*****************************************************************************************************
* Show admin information.
*/
    public function Tableservice() {
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();

        $home; $max; $min; $away; $rund; $room; $on; $off; $id;
        if (isset($_POST) && ($_POST)) {
            $home = $_POST['home'];
            $max = $_POST['max'];
            $min = $_POST['min'];
            $away = $_POST['away'];
            $rund = $_POST['rund'];
            $room = $_POST['room'];
            $on = $_POST['on'];
            $off = $_POST['off'];
            $id = $_POST['id'];
        } else{
            echo 'no post<br />';
        }
        
        if ($on == '0' || $off == '0' || $on == '' || $off == '' || $on == None || $off == None) {
            $on = '0';
            $off = '0';
        }
 
        $this->temperatures->Update($home, $max, $min, $away, $rund, $room, $on, $off, $id);
        $this->Lists();
        $this->calculateSetpoints();

    }

    public function calculateSetpoints() {
        $spotprices = $this->textfiles->getCurrentCleanValues();
        $nrOfHours = count($spotprices) - 1;
        $average = $this->textfiles->getCurrentAveragePrice();
        $this->various = $this->temperatures->ListVarious();
        $nrOf = $this->nrOfActiveRooms;
        $room = "";
        $rooms = array();
        $setpoints = array();
        $textRoom = array();
        $pickedPercent = $this->various[0]['percentlevel'];
        $havePercentValue = $this->various[0]['percent'];

        for ($i = 0; $i < $nrOf; $i++) {
            $room = $this->roomsInfo[$i]['room'];
            $this->rooms[$room]['rum'] = $this->roomsInfo[$i]['room'];
            $this->rooms[$room]['home'] = $this->roomsInfo[$i]['home'];
            $this->rooms[$room]['min'] = $this->roomsInfo[$i]['min'];
            $this->rooms[$room]['max'] = $this->roomsInfo[$i]['max'];
            $this->rooms[$room]['rund'] = $this->roomsInfo[$i]['rund'];
            $this->rooms[$room]['away'] = $this->roomsInfo[$i]['away'];

            $this->setpoints[$room] = array();    // Each room gets 24 hour list of temperatures
            $textRoom[$i] = $this->setpoints[$room];  

            $percentValue = ($pickedPercent / 100) + 1;
            $gone = false;

            $cutOffArray = array();
            for ($t = 0; $t < $nrOfHours; $t++) {
                $cutOffArray[$t] = false;
            }
            for ($q = 0; $q < $nrOfHours; $q++) {
                if ($spotprices[$q] > ($percentValue * $average)) {
                    $cutOffArray[$q] = true;
                }
            }
        for ($q = 0; $q < $nrOfHours; $q++) {
            $redmax1 = $q+1;
            $redmax2 = $q+2;
            $this->setpoints[$room] = "";  // temperatures for 24 hours for each room     
            $textRoom[$i][$q] = $this->setpoints[$room] . ",";   // The temperatures with commas


            if (($this->toDate && $this->fromDate)  &&  (strtotime($this->toDate)  >= strtotime($this->fromDate)) && (strtotime($this->toDate) >= strtotime($this->todaysDate)) && (strtotime($this->fromDate) <= strtotime($this->todaysDate))) {                
                        $this->setpoints[$room] = $this->rooms[$room]['away'];
                        $textRoom[$i][$q] = $this->setpoints[$room] . ",";
            } elseif ($havePercentValue == '1') {
                if($spotprices[$q] > ($percentValue * $average)){
                    $this->setpoints[$room] = $this->rooms[$room]['min'];
                    $textRoom[$i][$q] = $this->setpoints[$room] . ",";
                }elseif($redmax1 < $nrOfHours && $cutOffArray[$redmax1] == true || $redmax2 < $nrOfHours && $cutOffArray[$redmax2] == true){
                    $this->setpoints[$room] = $this->rooms[$room]['max'];
                    $textRoom[$i][$q] = $this->setpoints[$room] . ",";
                } else {
                    $this->setpoints[$room] = $this->rooms[$room]['home'];
                    $textRoom[$i][$q] = $this->setpoints[$room] . ",";
                }
            } else {
                $this->setpoints[$room] = $this->rooms[$room]['home'];
                $textRoom[$i][$q] = $this->setpoints[$room] . ","; 
            }
        }
        $textRoom[$i][$nrOfHours] = '[ ' . $this->todaysDate . ' ]';
        $nr = (string)$i + 1;
        $roomtextfile = 'room' . $nr . '.txt';
        $this->textfiles->writeText($roomtextfile, $textRoom[$i]);
        }       
    }

/*****************************************************************************************************
* Show admin information.
*/
    public function Acpservice() {
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();

        $area; $nrofrooms; $load; $percent; $percentlevel; $awayfrom; $awayto;

        if (isset($_POST) && ($_POST)) {
            $area = $_POST['area'];
            $nrofrooms = $_POST['nrofrooms'];
            $load = $_POST['load'];
            $percent = $_POST['percent'];
            $percentlevel = $_POST['percentlevel'];
            $awayfrom = $_POST['awayfrom'];
            $awayto = $_POST['awayto'];

        } else{
            echo 'no post<br />';
        }

        $this->temperatures->UpdateVarious($area, $nrofrooms, $load, $percent, $percentlevel, $awayfrom, $awayto);
        $this->calculateSetpoints();
     
    }

/*****************************************************************************************************
* Show admin information.
*/
    public function Passwordservice() {
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();

        $acronym; $pass1; $pass2; $name; $email; $id;

        if (isset($_POST) && ($_POST)) {
            $acronym = $_POST['acronym'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $id = $_POST['id'];

        } else{
            echo 'no post<br />';
        }

        $this->user->UpdateMember($acronym, $pass1, $pass2, $name, $email, $id);
    }
}
