<?php

/**
 * A model for content stored in database.
 *
 * @package BehovsboboxenCore
 */
class CMTemperatures extends CObject implements IHasSQL, ArrayAccess, IModule {

    /**
     * Properties
     */
    public $temps;
    public $lists;


    /**
     * Constructor
     */
    public function __construct($room = null) {
        parent::__construct();
        
        if ($room) {
            $this->LoadByRoom($room);
        } else {
           
            $this->temps = array();
        }
    }

    /**
     * Implementing ArrayAccess for $this->temps
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->temps[] = $value;
        } else {
            $this->temps[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->temps[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->temps[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->temps[$offset]) ? $this->temps[$offset] : null;
    }

    /**
     * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
     *
     * @param string $key the string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null, $args = null) {
        $order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
        $order_by = isset($args['order-by']) ? $args['order-by'] : 'id';
        $queries = array(
            'drop table roomsettings' => "DROP TABLE IF EXISTS roomsettings;",
            'table exist' => "SELECT name FROM sqlite_master WHERE type='table' AND name='roomsettings';",
            'create table roomsettings' => "CREATE TABLE IF NOT EXISTS roomsettings (id INTEGER PRIMARY KEY NOT NULL, room TEXT, home DOUBLE, max DOUBLE, min DOUBLE, away DOUBLE, rund DOUBLE, at INTEGER, off INTEGER, fromdate DATETIME, todate DATETIME);",
            'insert roomsettings' => "INSERT INTO roomsettings (room, home, max, min, away, rund, at, off) VALUES (?,?,?,?,?,?,?,?);",
            'select all' => "SELECT id, room, home, max, min, away, rund, at, off, fromdate, todate FROM roomsettings;",
            'update roomsettings' => "UPDATE roomsettings SET home = ?, max = ?, min = ?, away = ?, rund = ?, room = ?, at = ?, off = ? WHERE id = ?;",  
            'update roomname' => "UPDATE roomsettings SET room = ? WHERE id = ?;",
            'select * by room' => "SELECT id, home, max, min, away, rund, at, off, fromdate, todate FROM roomsettings WHERE room = ?",

            'drop table smallsettings' => "DROP TABLE IF EXISTS smallsettings;",
            'create table smallsettings' => "CREATE TABLE IF NOT EXISTS smallsettings (id INTEGER PRIMARY KEY NOT NULL, area TEXT, nrofrooms INTEGER, load INTEGER, percent INTEGER, percentlevel INTEGER, fromdate DATETIME, todate DATETIME);",
            'insert smallsettings' => "INSERT INTO smallsettings (area, nrofrooms, load, percent, percentlevel, fromdate, todate) VALUES (?,?,?,?,?,?,?);",
            'select allsmall' => "SELECT id, area, nrofrooms, load, percent, percentlevel, fromdate, todate FROM smallsettings;",
            'update smallsettings' => "UPDATE smallsettings SET area = ?,nrofrooms = ?,load = ?, percent = ?, percentlevel = ?, fromdate = ?, todate = ? WHERE id = 1;",  
            'update percentlevel' => "UPDATE smallsettings SET percentlevel = ? WHERE id = 1;",
            'update dates' => "UPDATE smallsettings SET fromdate = ?, todate = ?;",

        );
        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }

    /**
     * Implementing interface IModule. Manage install/update/deinstall and equal actions.
     */
    public function Manage($action = null) {
    $dir = BEHOVSBOBOXEN_INSTALL_PATH;

        switch ($action) {
            case 'install':
                try {
                    $this->db->ExecuteQuery(self::SQL('drop table roomsettings'));
                    $this->db->ExecuteQuery(self::SQL('create table roomsettings'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum1', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum2', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum3', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum4', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum5', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum6', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum7', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum8', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum9', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum10', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum11', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum12', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum13', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum14', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum15', '21', '21', '21', '21', '10'));
                    $this->db->ExecuteQuery(self::SQL('insert roomsettings'), array('Rum16', '21', '21', '21', '21', '10'));
                    
                    $this->db->ExecuteQuery(self::SQL('drop table smallsettings'));
                    $this->db->ExecuteQuery(self::SQL('create table smallsettings'));
                    $this->db->ExecuteQuery(self::SQL('insert smallsettings'), array('SE1', 8, 0, 0, 0, '', ''));

                     return array('success', t('Successfully created the database tables and created a default Temperaturetable, owned by you.'));
                } catch (Exception$e) {
                    die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
                }
                break;

            default:
                throw new Exception('Unsupported action for this module.');
                break;
        }
    }  

    /**
     * List all content.
     *
     * @param $args array with various settings for the request. Default is null.
     * @returns array with listing or null if empty.
     */
    public function ListAll($args = null) {
        try {
            if (isset($args)) {
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all', $args));
            } else {
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all', $args));
            }
        }catch (Exception $e) {
            echo $e;
            return null;
        }
    } 

    public function tableExists($args = null){
        try {
            $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('table exist', array($args)));
            return $res;
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }

    /**
     * List all content.
     *
     * @param $args array with various settings for the request. Default is null.
     * @returns array with listing or null if empty.
     */
    public function ListVarious() {
        $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select allsmall'));
        if(empty($res)){
            $this->Manage('install');
        }
        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select allsmall'));
    }

    /**
     * Load content by room.
     *
     * @param room string the 'id' of the content.
     * @returns boolean true if success else false.
     */
    public function LoadByRoom($room) {
        $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by room'), array($room));
        if (empty($res)) {
            $this->AddMessage('error', "Failed to load content with room '$room'.");
            return false;
        } else {
            $this->temps = $res[0];
            return $res[0];
        }
        //return true;
    }

     /**
     * Save the edited room-content. With a room id update current entry.
     *
     * @returns boolean true if success else false.
     */
    public function Update($home, $max, $min, $away, $rund, $room, $on, $off, $id) {
        $msg = null;
        if ($room) {
            $this->db->ExecuteQuery(self::SQL('update roomsettings'), array($home, $max, $min, $away, $rund, $room, $on, $off, $id));
            $msg = 'updated';
        } 
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', t("Successfully updated the room."));
        } else {
            $this->session->AddMessage('error', t("The room was not updated."));
        }
        return $rowcount === 1;
    }


     /**
     * Save the edited roomName-content. With a room id update current entry.
     *
     * @returns boolean true if success else false.
     */
    public function UpdateName($newroom, $id) {
        $msg = null;
        $newroom = (string)$newroom;
        if ($newroom) {
            $this->db->ExecuteQuery(self::SQL('update roomname'), array($newroom, $id));
            $msg = 'updated';
        } 
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', t("Successfully updated a room."));
        } else {
            $this->session->AddMessage('error', t("The room was not updated."));
        }
        return $rowcount === 1;
    }

    public function UpdateDates($from, $to){
        $msg = null;

        if ($from && $to) {
            $this->db->ExecuteQuery(self::SQL('update dates'), array($from, $to));
            $msg = 'updated';
        } 
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', t("Successfully updated the dates."));
        } else {
            $this->session->AddMessage('error', t("The dates were not updated."));
        }
        return $rowcount === 1;
    }

    public function UpdateVarious($area, $nrofrooms, $load, $percent, $percentlevel, $awayfrom, $awayto){
        $msg = null;
        
        $this->db->ExecuteQuery(self::SQL('update smallsettings'), array($area, $nrofrooms, $load, $percent, $percentlevel, $awayfrom, $awayto));
        $msg = 'updated';
        
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', t("Successfully updated the various settings."));
        } else {
            $this->session->AddMessage('error', t("The various settings were not updated."));
        }
        return $rowcount === 1;
    }

    public function UpdatePercentlevel($percentlevel){
        $this->db->ExecuteQuery(self::SQL('update percentlevel'), array($percentlevel));
        $msg = 'updated';
        
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', t("Successfully updated the percentlevel."));
        } else {
            $this->session->AddMessage('error', t("The percentlevel was not updated."));
        }
        return $rowcount === 1;
    }
}