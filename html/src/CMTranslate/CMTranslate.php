<?php

/**
 * A model for content stored in database.
 *
 * @package BehovsboboxenCore
 */
class CMTranslate extends CObject implements IHasSQL, ArrayAccess, IModule {

    /**
     * Properties
     */
    public $text;


    /**
     * Constructor
     */
    public function __construct($key = null) {
        parent::__construct();
        if ($key) {
            $this->LoadByKey($key);
        } else {
           
            $this->text = array();
        }
    }

    /**
     * Implementing ArrayAccess for $this->temps
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->text[] = $value;
        } else {
            $this->text[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->text[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->text[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->text[$offset]) ? $this->text[$offset] : null;
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
            'drop table translate' => "DROP TABLE IF EXISTS translate;",
            'table exist' => "SELECT name FROM sqlite_master WHERE type='table' AND name='translate';",
            'create table translate' => "CREATE TABLE IF NOT EXISTS translate (id INTEGER PRIMARY KEY, eng TEXT KEY, swe TEXT);",
            'insert translate' => "INSERT INTO translate (eng, swe) VALUES (?,?);",
            'select all' => "SELECT id, eng, swe FROM translate;",
            'update translate' => "UPDATE translate SET eng = ?,swe = ? WHERE id = ?;",  
            'update swedish' => "UPDATE translate SET swe = ? WHERE eng = ?;",
            'update english' => "UPDATE translate SET eng = ? WHERE swe = ?;",
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
                    $this->db->ExecuteQuery(self::SQL('drop table translate'));
                    $this->db->ExecuteQuery(self::SQL('create table translate'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Spotprices', 'Spotpriser'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Temperatures', 'Temperaturer'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('AdminControlPanel', 'Administrationspanel'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Logout', 'Logga ut'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Some text', 'Lite text'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Current averageprice', 'Aktuellt medelpris'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Room', 'Rum'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Current price', 'Aktuellt dagspris'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Min and max values for two comparing dates.', 'Min och maxvärden för två jämförande datum.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Maxprice', 'Maxpris'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Minprice', 'Minpris'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Average', 'Medel'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Date', 'Dag'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array("The next days spotprice is released 16:00 from NordPool's ftp server. Here we show the setvalues per hour to avoid buying electricity when the price is high.", 'Morgondagens spotpris släpps kl 16.00 från NordPools ftp server. Här visar vi ärvärden per timma för att undvika att köpa el när priset är högt.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Get current spotprice', 'Hämta aktuellt spotpris'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Isvalue', 'Ärvärde'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('ShouldValue', 'Börvärde'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Away', 'Borta'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Loadcontrol', 'Rundstyrning'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Now it is ', 'Nu är det '));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array(' degrees celsius outside.', ' grader celsius ute.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Edit', 'Ändra'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Various settings', 'Några inställningar'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Value', 'Värde'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Areacode', 'Områdeskod'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Your nr of rooms', 'Ditt antal rum'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Allow load?', 'Tillåt rundstyrning?'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Percent on?', 'Använda procent?'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Percentlevel', 'Procentnivå'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Away from', 'Borta från'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Away to', 'Borta till'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Reinitiate database', 'Installera om databas'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The users', 'Användare'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Member', 'Medlem'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Acronym', 'Användarnamn'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Name', 'Namn'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Algorithm', 'Algoritm'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Created', 'Skapad'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Updated', 'Uppdaterad'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('area', 'Område'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('nrOfRooms', 'Antal rum'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Do you accept loadcontrol?', 'Accepterar du rundstyrning?'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('You wish to control heat by pricepercent?', 'Du vill begränsa med procent?'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('percentlevel', 'Procentnivå'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Here you can start fresh again', 'Här kan du starta om databasen'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Create a new user here:', 'Skapa en ny användare här:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Member id:', 'Medlemsid:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('New email:', 'Ny emailadress:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Delete', 'Kasta'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Update', 'Uppdatera'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('DoCreate', 'Skapa'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Password:', 'Lösenord:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Password again:', 'Lösenord igen:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Current password:', 'Aktuellt lösenord:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('New password:', 'Nytt lösenord:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('New password again:', 'Nytt lösenord igen:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Change password', 'Ändra lösenord'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The password could not be changed, ensure that all fields match and the current password is correct.', 'Lösenordet kunde inte ändras, se till att alla fält matchar och att aktuellt lösenord är korrekt.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Saved new password.', 'Sparade det nya lösenordet.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Edit name, acronym or email-address here:', 'Redigera namn, akronym eller email-adress här:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Edit password here', 'Redigera lösenord här'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The editform could not be processed.', 'Redigeringsformuläret kunde inte genomföras.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The CreateUserForm could not be processed.', 'Formuläret kunde inte hanteras.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The roomeditform could not be processed.', 'Formuläret kunde inte hanteras.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Chosen room:', 'Valt rum:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Edit the temperatures here:', 'Redigera temperaturerna här:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('from', 'från'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('to', 'till'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Dates', 'Datum'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The dateseditform could not be processed.', 'Formuläret kunde inte hanteras.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Edit dates dd.mm.yyyy:', 'Skriv i formatet: dd.mm.yyyy:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The percentform could not be processed.', 'Formuläret kunde inte hanteras.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The variousform could not be processed.', 'Formuläret kunde inte hanteras.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Settings', 'Uppdatera'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('on', 'på'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('off', 'av'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Areacode:', 'Områdeskod:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('nr of rooms:', 'Antal rum:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Load:', 'Rundstyrning:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('have percent:', 'Välja procent:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('percentlevel:', 'Procentnivå:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated a member.', 'Medlemmen uppdaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The member was not updated.', 'Medlemmen uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('You have logged out.', 'Du har loggats ut.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Login using your acronym or email.', 'Logga in med din akronym eller emailadress.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Login', 'Logga in'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('login', 'Logga in'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('acronym', 'Akronym'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('password', 'Lösenord'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Password', 'Lösenord'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Failed to login, user or password does not match.', 'Kunde inte logga in, användarnamn eller lösenord stämmer inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('New name:','Nytt namn:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Away:','Borta:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('load:','Rundstyrning:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The room was not updated.','Rummet uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated the room:','Rummet uppaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Isvalue:','Börvärde:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Your chosen percentlevel:','Din valda procentnivå:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated the percentlevel.','Procentnivån uppdaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The percentlevel was not updated.','Procentnivån uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated the various settings.','Inställningarna uppdaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The various settings were not updated.','Inställningarna uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated the dates.','Datumen uppdaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The dates were not updated.','Datumen uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated a room.','Rummet uppdaterades.')); 
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The room was not updated.','Rummet uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated the room.','Rummet uppdaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Restricted content','Begränsad åtkomst'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('You are trying to reach restricted content.','Du måste logga in för att se sidan.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Detailed message:','Detaljer:'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('403, restricted content','403, begränsat innehåll'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('You need admin-privileges to access this content.','Du behöver adminrättigheter för att se sidan.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('404, Page not found.','404, sidan finn inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Header code is not valid.','Headerkoden är inte giltig.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The page you are looking for is not here.','Sidan du söker finns inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The following modules were affected by this action.','Följande moduler påverkades av aktionen.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Results from installing modules.','Resultatet av installationen.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Module','Modul'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Result','Resultat'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully created the database tables and created a default Translatetable, owned by you.','Skapade en översättningstabell ägd av dig.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully created the database tables and created a default Temperaturetable, owned by you.','Skapade en temperaturtabell ägd av dig.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully created the database tables and created a default admin user as root:root','Skapade en användartabell med rättigheter som root:root ägd av dig.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Install modules','Installera moduler'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('This is a demo. Login with root:root.','Detta är en demo. Logga in med root:root.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('404, Page not found','404, Sidan finns inte'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Update translations','Updatera översättningstabell'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('You have logged out.','Du är utloggad.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Room','Rum'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated password and names.','Uppdaterade lösenord och användarnamn'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Names and password were not updated.','Lösenord och namn uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Successfully updated the names.','Namnen uppdaterades.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('The names were not updated.','Namnen uppdaterades inte.'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('new password','nytt lösenord'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('new password confirmed','bekräfta nytt lösnord'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Welcome ','Välkommen '));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('makes your house smart!','gör hus smarta!'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Login/Logout','Logga In/Ut'));
                    $this->db->ExecuteQuery(self::SQL('insert translate'), array('Hour','Timma'));

                     return array('success', t('Successfully created the database tables and created a default Translatetable, owned by you.'));
                } catch (Exception$e) {
                    die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
                }
                break;

            default:
                throw new Exception('Unsupported action for this module.');
                break;
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
     * Get all registred users
     *
     * 
     * @return array with details or false.
     */
    public function ListAllTexts($args = null) {
        try {

            $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all', array($args)));

            return $res;
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }
}