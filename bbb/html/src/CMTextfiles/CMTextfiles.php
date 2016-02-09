<?php

/**
 * A model for content stored in database.
 *
 * @package BehovsboboxenCore
 */
class CMTextfiles extends CObject implements ArrayAccess/*, IModule */{

    /**
     * Properties
     */
    protected $temperatures;
    public $lists;
    public $clean = array();
    public $semicolonPrices;
    public $commaPrices;
    public $todaysToolsList;
    public $currentMaxPrice;
    public $currentMinPrice;
    public $currentAveragePrice;
    public $todayArray = array(); 
    public $tomorrowArray = array();   

    public $tomclean = array();
    public $tomsemicolonPrices;
    public $tomcommaPrices;
    public $tomToolsList;
    public $tomMaxPrice;
    public $tomMinPrice;
    public $tomAveragePrice;

    public $isTemps;

    public $checkInput;

    public $various = array();

    public $tables = array();

    public $tomorrowsDate;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
            $this->temperatures = new CMTemperatures();
        	$this->LoadLists();
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

    /**
    *   Execute a reading of file and return the result.
    */ 
    public function readSpotPrices() {
        $area = $this->getAreaCode();
        $currency = $this->getCurrency();

        //current day
    	$myFile = $this->config['textbase'] . 'spotprice.txt'; 
        $ourFile = $this->config['textbase'] . 'currentPrices.txt'; 
        $filearray = file($myFile);
        $chosen = "";
        
        //next day (if after 16:00)
        $myFileNew = $this->config['textbase'] . 'spotprice2.txt';  
        $ourFileNew = $this->config['textbase'] . 'newPrices.txt'; 
        $filearrayNew = file($myFileNew);
        $chosen2 = "";

        foreach ($filearray as $line) {
            $pos1 = strpos($line, $area);
            $pos2 = strpos($line, $currency);
            if ($pos1 == true && $pos2 == true) {
                $chosen = $line; // picks out our current data
            }
        } 

        foreach ($filearrayNew as $line2) {
            $pos1 = strpos($line2, $area);
            $pos2 = strpos($line2, $currency);
            if ($pos1 == true && $pos2 == true) {
                $chosen2 = $line2; // picks out our nextdays data
            }
        }  
        file_put_contents($ourFile, $chosen); 
        file_put_contents($ourFileNew, $chosen2);
  
        return $chosen;
    }

    public function getAreaCode(){
        $area = $this->various[0]['area'];
        return $area;
    }

    public function getCurrency(){
        $currency = $this->config['currency'];
        return $currency;
    }

    public function getIsTemps(){
    	$list;
    	$text = "";
    	$filename = $this->config['textbase'] . 'temperature.txt';
    	if($filename){
            $text = file_get_contents($filename);
            $list = explode(";", $text);
        }else{
            $list = 'null';
            return $list;
        }

        return $list;
    }


    public function LoadLists(){
        $this->various = $this->temperatures->ListVarious();
    	$this->readSpotPrices();

        $ourFile = $this->config['textbase'] . 'currentPrices.txt';
        $current = file_get_contents($ourFile);
        $cur = explode(";", $current);

        $compare = array();  // start with 23 values (not to get a separator after the last value - (or price))
        $count = 0;
        $clean = array();
        $semicolonPrices = "";	// semicolon-separated string with 24 prices plus average price (25 values)  
        $commaPrices = "";		// the above values in a comma-separated string with square brackets [x,x]
        $todaysToolsList = "";	// the above values in format ['x','x']


        for ($i = 8; $i <= 31; $i++) {
            if(!$cur[$i] ==  ""){
                $price = $cur[$i];
                $replacecomma = str_replace(",", "", $price);
                $formattedPrice = round(((double) $replacecomma / 1000), 1);  
                $clean[$count] = $formattedPrice;
                $semicolonPrices .= $formattedPrice . ';';

                $commaPrices .= $formattedPrice . ',';
                $todaysToolsList .= $formattedPrice . "','";
                $compare[$count] = $formattedPrice;
                $count++;
            }
        }

        $shortCommaPrices = $commaPrices;
		$shortSemicolonPrices = $semicolonPrices;
		$shortTodaysToolsList = $todaysToolsList;

		$price24 = $cur[32];
		$fixComma = str_replace(",", "", $price24);
		$formatPrice = round(((double) $fixComma / 1000), 1);
		$clean[23] = $formatPrice;

		$semicolonPrices .= $formatPrice . ';';
		$commaPrices .= $formatPrice . ',';
		$todaysToolsList .= $formatPrice . "','";
		$compare[23] = $formatPrice;            // Now we add the last value (24)

		$shortCommaPrices .= $formatPrice;
		$shortCommaPrices = '[' . $shortCommaPrices . ']';
		$shortTodaysToolsList .= $formatPrice . "']";
		$shortSemicolonPrices .= $formatPrice;

        // Now 25 values
		$compareLength = count($compare);
		$noComma = str_replace(",", "", $cur[33]);
		$formatprice = round(((double) $noComma / 1000), 1);
		$compare[$compareLength] = $formatprice; 

		// include the last average value
		$clean[$compareLength] = $formatprice;
		$semicolonPrices .= $formatprice;
		$todaysToolsList .= $formatprice . "']";
		$commaPrices .= $formatprice;
		$commaPrices = '[' . $commaPrices . ']';


        $tomorrowFile = $this->config['textbase'] . 'newPrices.txt';
        $newer = file_get_contents($tomorrowFile);
        $tom = explode(";", $newer);

        $tomcompare = array();  // start with 23 values (not to get a separator after the last value - (or price))
        $count = 0;
        $tomclean = array();
        $tomsemicolonPrices = "";
        $tomcommaPrices = "";
        $tomToolsList = "";

        for ($i = 8; $i <= 31; $i++) {
            if(!$tom[$i] ==  ""){
                $price = $tom[$i];
                $replacecomma = str_replace(",", "", $price);
                $formattedPrice = round(((double) $replacecomma / 1000), 1);  
                $tomclean[$count] = $formattedPrice;
                $tomsemicolonPrices .= $formattedPrice . ';';

                $tomcommaPrices .= $formattedPrice . ',';
                $tomToolsList .= $formattedPrice . "','";
                $tomcompare[$count] = $formattedPrice;
                $count++;
            }
        }

        $shortCommaPrices = $tomcommaPrices;
		$shortSemicolonPrices = $tomsemicolonPrices;
		$shortTomToolsList = $tomToolsList;

		$price24 = $tom[32];
		$fixComma = str_replace(",", "", $price24);
		$formatPrice = round(((double) $fixComma / 1000), 1);
		$tomclean[23] = $formatPrice;

		$tomsemicolonPrices .= $formatPrice . ';';
		$tomcommaPrices .= $formatPrice . ',';
		$tomToolsList .= $formatPrice . "','";
		$tomcompare[23] = $formatPrice;            // Now we add the last value (24)

		$shortCommaPrices .= $formatPrice;
		$shortCommaPrices = '[' . $shortCommaPrices . ']';
		$shortTomToolsList .= $formatPrice . "']";
		$shortSemicolonPrices .= $formatPrice;

        // Now 25 values
		$compareLength = count($tomcompare);
		$noComma = str_replace(",", "", $tom[33]);
		$formatprice = round(((double) $noComma / 1000), 1);
		$tomcompare[$compareLength] = $formatprice; 

		// include the last average value
		$tomclean[$compareLength] = $formatprice;
		$tomsemicolonPrices .= $formatprice;
		$tomToolsList .= $formatprice . "']";
		$tomcommaPrices .= $formatprice;
		$tomcommaPrices = '[' . $tomcommaPrices . ']'; 


        $this->tomorrowsDate = $tom[5];
        $now = date("d.m.Y");

		if ($this->tomorrowsDate === $now) {
    		$newUrl = $this->config['textbase'] . 'newPrices.txt';
    		$newFile = file_get_contents($newUrl);
    		$currentUrl = $this->config['textbase'] . 'currentPrices.txt';
    		file_put_contents($currentUrl, $newFile);

            $newSpot = $this->config['textbase'] . 'spotprice2.txt';
            $spotFile = file_get_contents($newSpot);
            $currentSpot = $this->config['textbase'] . 'spotprice.txt';;
            file_put_contents($currentSpot, $spotFile);
		}


		$max = max($clean);
		$min = min($clean);

		$min2 = min($tomclean);
		$max2 = max($tomclean);

       

        $this->lists['clean'] = $clean;
        $this->lists['semicolon'] = $semicolonPrices;
        $this->lists['comma'] = $commaPrices;
        $this->lists['tools'] = $todaysToolsList;
        $this->lists['tomclean'] = $tomclean;
        $this->lists['tomsemicolon'] = $tomsemicolonPrices;
        $this->lists['tomcomma'] = $tomcommaPrices;
        $this->lists['tomtools'] = $tomToolsList;
        $this->lists['todaysDate'] = $now;
        $this->lists['tomorrowsDate'] = $tom[5];
        $this->lists['currentMaxPrice'] = $max;
    	$this->lists['currentMinPrice'] = $min;
    	$this->lists['currentAveragePrice'] = $clean[24];
    	$this->lists['tomMaxPrice'] = $max2;
    	$this->lists['tomMinPrice'] = $min2;
    	$this->lists['tomAveragePrice'] = $tomclean[24];
  
        return $this->lists;   
              
    }

    public function getCurrentSemicolonPrices(){
    	return $this->lists['semicolon'];
    }

    public function getCurrentCleanValues(){
    	return $this->lists['clean'];
    }

        public function getCurrentCommaValues(){
    	return $this->lists['comma'];
    }

        public function getCurrentToolsValues(){
    	return $this->lists['tools'];
    }

    public function getCurrentMaxPrice(){
    	return $this->lists['currentMaxPrice'];
    }

    public function getCurrentMinPrice(){
    	return $this->lists['currentMinPrice'];
    }

    public function getCurrentAveragePrice(){
    	$this->currentAveragePrice = $this->lists['clean'][24];
    	return $this->lists['clean'][24];
    }


    public function getTomSemicolonPrices(){
    	return $this->lists['tomsemicolon'];
    }

    public function getTomCleanValues(){
    	return $this->lists['tomclean'];
    }

        public function getTomCommaValues(){
    	return $this->lists['tomcomma'];
    }

        public function getTomToolsValues(){
    	return $this->lists['tomtools'];
    }

    public function getTomorrowsMaxPrice(){
    	return $this->lists['tomMaxPrice'];
    }

    public function getTomorrowsMinPrice(){
    	return $this->lists['tomMinPrice'];
    }

    public function getTomorrowsAveragePrice(){
    	return $this->lists['tomclean'][24];
    }

    public function getTodaysDate(){
    	$now = date("d.m.Y");
    	return $now;
    }

    public function getTodaysTime(){
        $now = date("H:i");
        $zone = $this->config['datetimezone'];
        $date = new DateTime($now,new DateTimeZone("GMT"));
        $date->setTimezone(new DateTimeZone($zone));
        return $date->format('H:i');
    }

    public function getTomorrowsDate(){
    	$newestDate = $this->tomorrowsDate;
//var_dump($newestDate);
    	return $newestDate;
    }

    public function writeText($file, $string){
    	$url = $this->config['textbase'] . $file;
    	file_put_contents($url, $string);
    }


}               