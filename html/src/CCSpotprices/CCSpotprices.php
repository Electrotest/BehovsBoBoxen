<?php

/**
 * A temperature controller to manage temperatures.
 *
 * @package BehovsboboxenCore
 */
class CCSpotprices extends CObject implements IController {

    protected $textfiles;
    protected $temperatures;
    public $list;
    public $rooms;
    public $various;
    public $setpoints;
    public $isTemps;
    public $fromDate;
    public $toDate;
    public $todaysDate;
    public $tomorrowsDate;
    public $todayArray;
    public $tomorrowArray;
    public $translate;


    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->textfiles = new CMTextfiles();
        $this->temperatures = new CMTemperatures();
        $this->translate = new CMTranslate();
        $this->list = $this->temperatures->ListAll();
        $this->various = $this->temperatures->ListVarious();
        $this->isTemps = $this->textfiles->getIsTemps();
        $this->fromDate = $this->getFromDate();
        $this->toDate = $this->getToDate();
        $this->todaysDate = $this->textfiles->getTodaysDate();
        $this->tomorrowsDate = $this->textfiles->getTomorrowsDate();
    }

    /**
     * Show a listing of all temperatures.
     */
    public function Index() {
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();
        $next = t("The next days spotprice is released 16:00 from NordPool's ftp server. Here we show the setvalues per hour to avoid buying electricity when the price is high.");
        $get = t('Get current spotprice');
        $translate = t('Update translations');
        $this->views->SetTitle('Spotprice Controller')
                ->AddInclude(__DIR__ . '/index.tpl.php', array(
                    'html' => $this->createTable(),
                    'isTemps' => $this->textfiles->getIsTemps(),
                    'tablespot' => $this->createSpot(),
                    'header' => null,
                    'todaysDate' => $this->todaysDate,
                    'todayArray' => $this->textfiles->getCurrentCommaValues(),
                    'tomorrowsDate' => $this->tomorrowsDate,
                    'tomorrowArray' => $this->textfiles->getTomCommaValues(),
                    'text' => $next,
                    'get' => $get,
                    'translate' => $translate,
        ));
    }

    public function createTable(){
        $html = "";
        $head = "";
        $content = "";
        $spotprices = $this->textfiles->getCurrentCleanValues();
        $average = $this->textfiles->getCurrentAveragePrice();
        $smallvar = $this->temperatures->ListVarious();
        $nrOf = $smallvar[0]['nrofrooms'];
        $room = "";
        $rooms = array();
        $setpoints = array();
        $textRoom = array();
        $pickedPercent = $smallvar[0]['percentlevel'];
        $havePercentValue = $smallvar[0]['percent'];
        $current = t("Current averageprice "); 
        $strRoom = t('Hour');
        $currentRed = t('Current price');
        $choice = t('Your chosen percentlevel:');


        $head = "<table class ='fixed'>";
        $head .= "<caption>" . $current . ' ' . $this->textfiles->getTodaysDate() . ": <span class ='red'>" . 
            $this->textfiles->getCurrentAveragePrice() . "</span>. " . $choice . " " . $pickedPercent . ".</caption>";
        $head .= "<thead><tr><th scope='col'>" . $strRoom . "</th>";
            for ($i = 0; $i < 24; $i++) {
                $head .= "<th class = 'w3'>" . ($i) . "</th>";
            }
        $head .= "</tr></thead><tbody>";


        for ($i = 0; $i < 16; $i++) {
            if($this->list[$i]['id'] <= $nrOf){
            $row = "";

            $room = $this->list[$i]['room'];
            $this->rooms[$room]['rum'] = $this->list[$i]['room'];
            $this->rooms[$room]['min'] = $this->list[$i]['min'];
            $this->rooms[$room]['max']=$this->list[$i]['max'];
            $this->rooms[$room]['rund'] = $this->list[$i]['rund'];
            $this->rooms[$room]['home'] = $this->list[$i]['home'];
            $this->rooms[$room]['away'] = $this->list[$i]['away'];
            $this->rooms[$room]['isTemp'] = $this->isTemps[$i+1];
            $this->setpoints[$room] = array();    // Each room gets 24 hour list of temperatures
            $textRoom[$i] = $this->setpoints[$room];  

            $percentValue = ($pickedPercent / 100) + 1;
            $gone = false;

            $cutOffArray = array();
                for ($t = 0; $t < 24; $t++) {
                $cutOffArray[$t] = false;
            }
            for ($q = 0; $q < 24; $q++) {
                if ($spotprices[$q] > ($percentValue * $average)) {
                $cutOffArray[$q] = true;
                }
            }

            if ($i % 2 === 1) {
                $content .= "<tr><th scope='row' class='specalt'>" . $this->rooms[$room]['rum'] . "</th>";
                $row = "<td class='alt'>";
            } else {
                $content .= "<tr><th scope='row' class='spec'>" . $this->rooms[$room]['rum'] . "</th>";
                $row = "<td class = 'w3'>";
            }


    for ($q = 0; $q < 24; $q++) {
        $redmax1 = $q+1;
        $redmax2 = $q+2;
        $this->setpoints[$room] = "";  // temperatures for 24 hours for each room     
        $textRoom[$i][$q] = $this->setpoints[$room] . ",";   // The temperatures with commas


        if (($this->toDate && $this->fromDate)  &&  (strtotime($this->toDate)  >= strtotime($this->fromDate)) && (strtotime($this->toDate) >= strtotime($this->todaysDate)) && (strtotime($this->fromDate) <= strtotime($this->todaysDate))) {                
                    $this->setpoints[$room] = $this->rooms[$room]['away'];
                    $content .= "<td class ='opacity'>" . $this->setpoints[$room] . "</td>";
                    $textRoom[$i][$q] = $this->setpoints[$room] . ",";
        }elseif ($havePercentValue == 1) {
            if($spotprices[$q] > ($percentValue * $average)){
                $this->setpoints[$room] = $this->rooms[$room]['min'];
                $content .= "<td class ='opacityblue'>" . $this->setpoints[$room] . "</td>";
                $textRoom[$i][$q] = $this->setpoints[$room] . ",";
            }elseif($redmax1 < 24 && $cutOffArray[$redmax1] == true || $redmax2 < 24 && $cutOffArray[$redmax2] == true){
                $this->setpoints[$room] = $this->rooms[$room]['max'];
                $content .= "<td class ='redmax'>" . $this->setpoints[$room] . "</td>";
                $textRoom[$i][$q] = $this->setpoints[$room] . ",";
            } else {
                $this->setpoints[$room] = $this->rooms[$room]['home'];
                $textRoom[$i][$q] = $this->setpoints[$room] . ",";
                $content .= $row . $this->setpoints[$room];
            }
        } else {
            $this->setpoints[$room] = $this->rooms[$room]['home'];
            $textRoom[$i][$q] = $this->setpoints[$room] . ",";
            $content .= $row . $this->setpoints[$room] . "</td>"; 
        }
    }
    $textRoom[$i][24] = '[ ' . $this->todaysDate . ' ]';
    $nr = (string)$i;
    $roomtextfile = 'room' . $nr . '.txt';
    $this->textfiles->writeText($roomtextfile, $textRoom[$i]);
    }

        
    }



        $content .= "<tr><th scope='row' abbr='<?= $currentRed ?>' class='specaltred spec'>" . $currentRed . "</th>";
            for ($k = 0; $k < 24; $k++) {
                $content .= "<td class = 'red'>" . $spotprices[$k] . "</td>";
            }
        $content .= "</tr>";
        $content .= "</table>";
        $percentchoice = "<span class ='smalltext'>" . t('Percentlevel: ') . "$pickedPercent</span>";
        $html = $head . $content;

        return $html;

    }

    public function createSpot(){
        $tablespot = "";
        $summary = t('Min and max values for two comparing dates.');
        $max = t('Maxprice');
        $min = t('Minprice');
        $average = t('Average');
        $date = t('Date');
        $tablespot .= "<table id='data-table' summary='<?= $summary ?>'>";
            $tablespot .= "<thead><tr><th scope='col'>" . $date . "</th><th scope='col'>" . $max . "</th><th scope='col'>" . $min . "</th><th scope='col'>" . $average . "</th></tr></thead><tbody>";
                if ($this->todaysDate != $this->tomorrowsDate) {
            $tablespot .= "<tr><td><b><span class ='tableblue'>" . $this->todaysDate . "</span></b></td><td><b><span class ='tableblue'>" . $this->textfiles->getCurrentMaxPrice() . "</span></b></td><td><b><span class ='tableblue'>" . $this->textfiles->getCurrentMinPrice() . "</span></b></td><td><b><span class ='tableblue'>" . $this->textfiles->getCurrentAveragePrice() . "</span></b></td></tr>";
            $tablespot .= "<tr><td><b><span class ='orange'>" . $this->tomorrowsDate . "</span></b></td><td><b><span class ='orange'>" . $this->textfiles->getTomorrowsMaxPrice() . "</span></b></td><td><b><span class ='orange'>" . $this->textfiles->getTomorrowsMinPrice() . "</span></b></td><td><b><span class ='orange'>" . $this->textfiles->getTomorrowsAveragePrice() . "</span></b></td></tr>";
        }else{
            $tablespot .= "<tr><td><b><span class ='orange'>" . $this->todaysDate . "</span></b></td><td><b><span class ='orange'>" . $this->textfiles->getCurrentMaxPrice() . "</span></b></td><td><b><span class ='orange'>" . $this->textfiles->getCurrentMinPrice() . "</span></b></td><td><b><span class ='orange'>" . $this->textfiles->getCurrentAveragePrice() . "</span></b></td></tr>";
        }
    $tablespot .= "</tbody></table></div>";
    return $tablespot;
    }

    public function getNrOfRooms(){
        $count = 0;
        foreach($this->list as $val){
            $count++;
        }
        return $count;
    }

    public function getFromDate(){
        $this->fromDate = $this->various[0]['fromdate'];
        return $this->fromDate;
    }

    public function getToDate(){
        $this->toDate = $this->various[0]['todate'];
        return $this->toDate;
    }

    public function getSpot(){
        $myFile = $this->config['textbase'] . 'spotprice2.txt';
        $filename = "ftp://spot:spo1245t@ftp.nordpoolspot.com/spotprice.sdv";
            $current = file_get_contents($filename);
            file_put_contents($myFile, $current);
            $this->RedirectTo('spotprices');
    }

    public function updateTranslations(){
        $this->translate->Manage('install');
        $this->RedirectTo('spotprices');
    }

    public function getSpotforCron(){
        $myFile = $this->config['textbase'] . 'spotprice2.txt';
        $filename = "ftp://spot:spo1245t@ftp.nordpoolspot.com/spotprice.sdv";
        $current = file_get_contents($filename);
        file_put_contents($myFile, $current); 
    }

    public function recalculateforCron(){
        $this->createTable();        
    }


}
