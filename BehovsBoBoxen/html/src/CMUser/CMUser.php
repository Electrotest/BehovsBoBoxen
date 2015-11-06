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
                    $this->db->ExecuteQuery(self::SQL('insert into user'), array('anonymous', 'Anonymous, not authenticated', null, 'plain', null, null));
                    $password = $this->CreatePassword('root');
                    $this->db->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $password['algorithm'], $password['salt'], $password['password']));
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
            //'drop table group' => "DROP TABLE IF EXISTS Groups;",
            //'drop table user2group' => "DROP TABLE IF EXISTS User2Groups;",
            'create table user' => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now', 'localtime')), updated DATETIME default NULL);",
            'insert into user' => 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
            'check user password' => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
            'select user by id' => 'SELECT * FROM User WHERE id=?;',
            'select all users' => 'SELECT * FROM User;',
            'select user by acronym' => 'SELECT * FROM User WHERE acronym=?;',
            'update profile' => "UPDATE User SET name=?, email=?, updated=datetime('now', 'localtime') WHERE id=?;",
            'update password' => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now', 'localtime') WHERE id=?;",
            'update adminpassword' => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now', 'localtime') WHERE id=?;",
            'update names' => "UPDATE User SET acronym=?, name=?, email=?, updated=datetime('now', 'localtime') WHERE id=?;",
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
            $user['groups']['id']=1;
            $user['hasRoleAdmin'] = true;

            $this->profile = $user;
            $this->session->SetAuthenticatedUser($this->profile);
        }
        return ($user != null);
    }

    /**
     * Logout. Clear both session and internal properties.
     */
    public function Logout() {
        $this->session->UnsetAuthenticatedUser();
        $this->profile = array();
        $this->session->AddMessage('success', "You have logged out.");
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
   * Check if user has secrets authorisation.
   *
   * @return boolean true or false.
   */
  public function IsAuthorised() {
    return $this['hasRoleSecrets'];
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
   * Check if user is a known visitor.
   *
   * @return boolean true or false.
   */
  public function IsVisitor() {
    return $this['hasRoleVisitor'];
  }
  

    /**
     * Create new user.
     *
     * @param $acronym string the acronym.
     * @param $password string the password plain text to use as base. 
     * @param $name string the user full name.
     * @param $email string the user email.
     * @returns boolean true if user was created or else false and sets failure message in session.
     */
    public function Create($acronym, $password, $name, $email) {
        $pwd = $this->CreatePassword($password);
        $this->db->ExecuteQuery(self::SQL('insert into user'), array($acronym, $name, $email, $pwd['algorithm'], $pwd['salt'], $pwd['password']));
        if ($this->db->RowCount() == 0) {
            $this->session->AddMessage('error', "Failed to create user.");
            return false;
        }
        $idUser = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array($idUser, 2));
        return true;
        return true;
    }
    
     /**
     * Create new group.
     *
     * @param $acronym string the acronym.
     * @param $name string the groups full name.
     * @returns boolean true if user was created or else false and sets failure message in session.
     */
    public function CreateGroup($acronym, $name) {
        $this->db->ExecuteQuery(self::SQL('insert into group'), array($acronym, $name));
         $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', "Successfully created a group.");
        } else {
            $this->session->AddMessage('error', "Failed to create group.");
            return false;
        }
        $idGroup = $this->db->LastInsertId();
        $this->db->ExecuteQuery(self::SQL('insert into user2group'), array(2, $idGroup));
        return true;
        return true;
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
     * Save user profile to database and update user profile in session.
     *
     * @returns boolean true if success else false.
     */
    public function Save() {
        $this->db->ExecuteQuery(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
        $this->session->SetAuthenticatedUser($this->profile);
        return $this->db->RowCount() === 1;
    }
    
    
     /**
     * Save the edited member-content. With an id update current entry or else insert new member.
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
            $this->session->AddMessage('success', "Successfully updated a member.");
        } else {
            $this->session->AddMessage('error', "The member was not updated.");
        }
        return $rowcount === 1;
    }
    
      
     /**
     * Delete a member. 
     *
      * @param int id as identifier 
      * @param string  acronym form messages 
     * @returns boolean true if success else false.
     */
    public function DeleteMember($acronym, $id) {
        $msg = null;
        //echo 'Inne i Deletemember';
        //echo $id;
        if ($id) {
            $this->db->ExecuteQuery(self::SQL('delete member'), array($id));
            $msg = 'deleted from user';
        } 
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->session->AddMessage('success', "Successfully deleted member $id $acronym from User");
        } else {
            $this->session->AddMessage('error', "The member $acronym with id $id was not deleted.");
        }
        
        if ($id) {
            $this->db->ExecuteQuery(self::SQL('delete membergroups'), array($id));
            $msg .= 'deleted from user2group';
        } 
        $rowcount2 = $this->db->RowCount();
        if ($rowcount2) {
            $this->session->AddMessage('success', "Successfully deleted member $id $acronym from User2Group");
        } else {
            $this->session->AddMessage('error', "The member $acronym with id $id was not deleted.");
        }
        return $rowcount === 1;
    }

    /**
     * Change user password when in user session.
     *
     * @param $plain string plaintext of the new password
     * @returns boolean true if success else false.
     */
    public function ChangePassword($plain, $id) {
        $password = $this->CreatePassword($plain);
        $this->db->ExecuteQuery(self::SQL('update password'), array($password['algorithm'], $password['salt'], $password['password'], $id));
        $this['salt'] = $password['salt'];
        $this['password'] = $password['password'];
        return $this->db->RowCount() === 1;
    }
    
     /**
     * Change user password.
     *
     * @param $password3 string text of the new password
      * @param int $identifier 
     * @returns boolean true if success else false.
     */
    public function ChangePasswordAdmin($password3, $identifier) {
        $password = $this->CreatePassword($password3);
        $this->db->ExecuteQuery(self::SQL('update adminpassword'), array($password['algorithm'], $password['salt'], $password['password'], $identifier));
        return $this->db->RowCount() === 1;
    }

    /**
     * User changes own password if successful verification.
     *
     * @param string $acronym current user according to controller
     * @param string $current password,
     * @param string $new1 new password,
     * @param string $new2 new password again,
     * @return boolean true if success else false.
     */
    public function ChangeOwnPasswordVerify($acronym, $current, $new1, $new2, $id) {
        if (CInterceptionFilter::Instance()->SessionUserMatches($acronym) === false) {
            return false;
        }       
        return $this->ChangePassword($new1, $id);
    }

    public function ChangePasswordAdminVerify($acronym, $new1, $new2, $id) {
        if($new1 == $new2){
            return $this->ChangePasswordAdmin($new1, $id);
        } else{
            $this->session->AddMessage('error', "The passwords didn't match.");
        }      
        
    }

    /**
     * User changes own mail.
     *
     * @param string $acronym current user according to controller
     * @param string $email the users email adress.
     * @return boolean true if success else false.
     */
    public function ChangeOwnEmail($acronym, $email) {

        if (CInterceptionFilter::Instance()->SessionUserMatches($acronym) === false) {
            return false;
        }

        $this['email'] = $email;
        return $this->Save();
    }

    /**
     * User changes own profile.
     *
     * @param string $acronym current user according to controller
     * @param string $acronym of the user.
     * @param string $name of the user.
     * @return boolean true if success else false.
     */
    public function ChangeOwnProfile($acronym, $acronym, $name) {

        if (CInterceptionFilter::Instance()->SessionUserMatches($acronym) === false) {
            return false;
        }

        $this['acronym'] = $acronym;
        $this['name'] = $name;
        return $this->Save();
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
     * Get details for a user by its id.
     *
     * @param integer $id as the users id.
     * @return array with details or false.
     */
    public function GetUserbyId($id) {
        try {
            $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select user by id'), array($id));

        } catch (Exception $e) {
            echo $e;
            return false;
        }
        return $res[0];
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


    /**
     * Get details for a user by its acronym.
     *
     * @param string $acronym as the users acronym.
     * @return array with details or false.
     */
    public function GetUserbyAcronym($acronym) {
        try {
            $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select user by acronym'), array($acronym));
        } catch (Exception $e) {
            return false;
        }
        return $res[0];
    }

}

