<?php

/**
 * A model for an authenticated user.
 * 
 * @package BehovsboboxenCore
 */
class CMUser extends CObject implements IHasSQL, ArrayAccess, IModule {

    /**
     * Properties
     */
    public $profile;

    /**
     * Constructor
     */
    public function __construct($bbb = null) {
        parent::__construct($bbb);
        $this['id'];
        $profile = $this->session->GetAuthenticatedUser();
        $this->profile = is_null($profile) ? array() : $profile;
        $this['isAuthenticated'] = is_null($profile) ? false : true;
        if (!$this['isAuthenticated']) {
            $this['id'] = 1;
            $this['acronym'] = 'anonymous';
            $this['hasRoleAnonomous'] = true;
        }
    }

    /**
     * Implementing ArrayAccess for $this->profile
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->profile[] = $value;
        } else {
            $this->profile[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->profile[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->profile[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->profile[$offset]) ? $this->profile[$offset] : null;
    }

    /**
     * Implementing interface IModule. Manage install/update/deinstall and equal actions.
     *
     * @param string $action what to do.
     */
    public function Manage($action = null) {
        switch ($action) {
            case 'install':
                try {
                    $this->db->ExecuteQuery(self::SQL('drop table user'));
                    $this->db->ExecuteQuery(self::SQL('create table user'));
                    $this->db->ExecuteQuery(self::SQL('insert into user'), array('anonymous', 'Anonymous', '', 'plain', null, null));
                    $password = $this->CreatePassword('root');
                    $this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'bbb@behovsboboxen.com', $password['algorithm'], $password['salt'], $password['password']));
                    $idRootUser = $this->db->LastInsertId();
                    
                      return array('success', t('Successfully created the database tables and created a default admin user as root:root'));
                } catch (Exception$e) {
                    die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn3']);
                }
                break;

            default:
                throw new Exception('Unsupported action for this module.');
                break;
        }
    }

    /**
     * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
     *
     * @param string $key the string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null) {
        $queries = array(
            'table name user' => "User",
            'drop table user' => "DROP TABLE IF EXISTS User;",
            'table exist' => "SELECT name FROM sqlite_master WHERE type='table' AND name='User';",
            'create table user' => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now', 'localtime')), updated DATETIME default NULL);",
            'insert into user' => 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
            'check user password' => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
            'select user by id' => 'SELECT * FROM User WHERE id=?;',
            'select all users' => 'SELECT * FROM User;',
            //'update adminpassword' => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now', 'localtime') WHERE id=?;",
            'update member' => "UPDATE User SET acronym=?,name=?,email=?,algorithm=?, salt=?, password=?,  updated=datetime('now', 'localtime') WHERE id=?;",
            //'update names' => "UPDATE User SET acronym=?, name=?, email=?, updated=datetime('now', 'localtime') WHERE id=?;",
            'update nopass' => "UPDATE User SET acronym=?, name=?, email=?, updated=datetime('now', 'localtime') WHERE id=?;",
            );
        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
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
     * Load member content by id.
     *
     * @param id integer the id of the content.
     * @returns boolean true if success else false.
     */
    public function GetMemberById($id) {
        $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select user by id'), array($id));
        if (empty($res)) {
            $this->session->AddMessage('error', "Failed to load member with id '$id'.");
        } else {
            return $res[0];
        }
    }

    /**
     * Login by autenticate the user and password. Store user information in session if success.
     *
     * Set both session and internal properties.
     *
     * @param string $akronymOrEmail the emailadress or user akronym.
     * @param string $password the password that should match the akronym or emailadress.
     * @returns booelan true if match else false.
     */
    public function Login($akronymOrEmail, $password) {
        if (!($user = $this->VerifyUserAndPassword($akronymOrEmail, $password))) {
            return false;
        }
        unset($user['algorithm']);
        unset($user['salt']);
        unset($user['password']);
        if ($user) {
            $user['isAuthenticated'] = true;
            $user['hasRoleAdmin'] = true;

            $this->profile = $user;
            $this->session->SetAuthenticatedUser($this->profile);
        }
        return ($user != null);
    }

    /**
     * Verify if user and password matches.
     *
     * @param string $akronymOrEmail the emailadress or user akronym.
     * @param string $password the password that should match the akronym or emailadress.
     * @return array with the user details as returned from the database.
     */
    private function VerifyUserAndPassword($akronymOrEmail, $password) {
        if (empty($akronymOrEmail) || empty($password)) {
            return false;
        }
        $user = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($akronymOrEmail, $akronymOrEmail));
        $user = (isset($user[0])) ? $user[0] : null;
        if (!$user) {
            return false;
        } else if (!$this->CheckPassword($password, $user['algorithm'], $user['salt'], $user['password'])) {
            return false;
        }
        return $user;
    }

    /**
     * Logout. Clear both session and internal properties.
     */
    public function Logout() {
        $this->session->UnsetAuthenticatedUser();
        $this->profile = array();
        $this->session->AddMessage('success', t("You have logged out."));
    }

    /**
   * Check if user has admin role.
   *
   * @return boolean true or false.
   */
  public function IsAdmin() {
    return $this['hasRoleAdmin'];
  }
  
  
  /**
   * Check if user is authenticated.
   *
   * @return boolean true or false.
   */
  public function IsAuthenticated() {
    return $this['isAuthenticated'];
  }
  

  /**
   * Check if user is anonomous.
   *
   * @return boolean true or false.
   */
  public function IsAnonomous() {
    return $this['hasRoleAnonomous'];
  }


    /**
     * Check if user is a regular user.
     *
     * @return boolean true or false.
     */
    public function IsUser() {
        return $this['hasRoleUser'];
    }


    /**
     * Create password.
     *
     * @param $plain string the password plain text to use as base.
     * @param $algorithm string stating what algorithm to use, plain, md5, md5salt, sha1, sha1salt. 
     * defaults to the settings of site/config.php.
     * @returns array with 'salt' and 'password'.
     */
    public function CreatePassword($plain, $algorithm = null) {
        $password = array(
            'algorithm' => ($algorithm ? $algorithm : CBehovsboboxen::Instance()->config['hashing_algorithm']),
            'salt' => null
        );
        switch ($password['algorithm']) {
            case 'sha1salt': $password['salt'] = sha1(microtime());
                $password['password'] = sha1($password['salt'] . $plain);
                break;
            case 'md5salt': $password['salt'] = md5(microtime());
                $password['password'] = md5($password['salt'] . $plain);
                break;
            case 'sha1': $password['password'] = sha1($plain);
                break;
            case 'md5': $password['password'] = md5($plain);
                break;
            case 'plain': $password['password'] = $plain;
                break;
            default: throw new Exception('Unknown hashing algorithm');
        }
        return $password;
    }

    /**
     * Check if password matches.
     *
     * @param $plain string the password plain text to use as base.
     * @param $algorithm string the algorithm mused to hash the user salt/password.
     * @param $salt string the user salted string to use to hash the password.
     * @param $password string the hashed user password that should match.
     * @returns boolean true if match, else false.
     */
    public function CheckPassword($plain, $algorithm, $salt, $password) {
        switch ($algorithm) {
            case 'sha1salt': return $password === sha1($salt . $plain);
                break;
            case 'md5salt': return $password === md5($salt . $plain);
                break;
            case 'sha1': return $password === sha1($plain);
                break;
            case 'md5': return $password === md5($plain);
                break;
            case 'plain': return $password === $plain;
                break;
            default: throw new Exception('Unknown hashing algorithm');
        }
    }
    
     /**
     * Update and save the edited member-content.
     *
     * @returns boolean true if success else false.
     */
    public function Update($acronym, $name, $email, $id) {
        $msg = null;
        if ($id) {
            $this->db->ExecuteQuery(self::SQL('update names'), array($acronym, $name, $email, $id));
            $msg = 'updated';
        } 
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', t("Successfully updated a member."));
        } else {
            $this->session->AddMessage('error', t("The member was not updated."));
        }
        return $rowcount === 1;
    }
    
     /**
     * Change user password.
     *
     * @param $passwordnew string text of the new password
      * @param int $identifier 
     * @returns boolean true if success else false.
     */
    public function ChangePasswordAdmin($passwordnew, $identifier) {
        $password = $this->CreatePassword($passwordnew);
        $this->db->ExecuteQuery(self::SQL('update adminpassword'), array($password['algorithm'], $password['salt'], $password['password'], $identifier));
        return $this->db->RowCount() === 1;
    }

    public function ChangePasswordAdminVerify($acronym, $new1, $new2, $id) {
        if($new1 == $new2){
            return $this->ChangePasswordAdmin($new1, $id);
        } else{
            $this->session->AddMessage('error', "The passwords didn't match.");
        }      
        
    }

    public function UpdateMember($acronym, $pass1, $pass2, $name, $email, $id){
        if($pass1){
            if($pass1 == $pass2){
                $password = $this->CreatePassword($pass1);
                $this->db->ExecuteQuery(self::SQL('update member'), array($acronym, $name, $email, $password['algorithm'], $password['salt'], $password['password'], $id));
                $rowcount = $this->db->RowCount();
                if ($rowcount) {
                    $this->session->AddMessage('success', t("Successfully updated password and names."));
                } else {
                    $this->session->AddMessage('error', t("Names and password were not updated."));
                }
                return $this->db->RowCount() === 1;

            } else{
                $this->session->AddMessage('error', "The passwords didn't match.");
            }
        }else{
            $this->db->ExecuteQuery(self::SQL('update nopass'), array($acronym, $name, $email, $id));
            $rowcount = $this->db->RowCount();
            if ($rowcount) {
                $this->session->AddMessage('success', t("Successfully updated the names"));
            } else {
                $this->session->AddMessage('error', t("The names were not updated."));
            }
            return $this->db->RowCount() === 1;


        }
    }

    /**
     * Get all registred users
     *
     * 
     * @return array with details or false.
     */
    public function ListAllUsers($args = null) {
        try {
            $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select all users', array($args)));
            return $res;
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }
}