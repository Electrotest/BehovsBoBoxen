<?php

/**
 * A user controller  to manage login and view edit the user profile.
 * 
 * @package BehovsboboxenCore
 */
class CCUser extends CObject implements IController {

    /**
     * Constructor
     */
    private $date;

    public function __construct() {
        parent::__construct();
        $this->date = date('Y-m-d H:i:s');
    }

    /**
     * Show profile information of the user.
     */
    public function Index() {
        $this->Overview();
    }

    /**
     * Show profile information of the user if logged in, or redirect to login.
     */
    public function Overview() {
        $if = new CInterceptionFilter();
        $if->IsRegularUserOrForbidden()->AuthenticatedOrLogin();
        $link = false;

        if($this->SpecialAccess() == true){
            $link = true;
        }
        $data = array(
            'header' => __DIR__ . '/header.tpl.php',
            'user' => $this->user,
            'navbar' => $this->CreateMenu('navbar-ucp'),
            'link' => $link,
        );

        $this->views->SetTitle(t('User Control Panel'))
                ->AddClassToRegion('primary', 'ucp')
                ->AddIncludeToRegion('primary', (__DIR__ . '/index.tpl.php'), $data);
    }
    
    public function SpecialAccess(){

        $msg = isset($msg) ? $msg : t('You do not have privileges to access this content.');
        if (!$this->user->IsAuthorised()) {
            return false;
        }else{
            return true;
        }
    
    }

    /**
     * View and edit user profile.
     */
    public function Profile() {
        $if = new CInterceptionFilter();
        $if->IsRegularUserOrForbidden();

        $form = new CForm(array(), array(
            'acronym' => array(
                'type' => 'hidden',
                'value' => $this->user['acronym'],
            ),
            'akronym' => array(
                'type' => 'text',
                'label' => t('Acronym:'),
                'value' => $this->user['acronym'],
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'name' => array(
                'type' => 'text',
                'label' => t('Name:'),
                'value' => $this->user['name'],
                'required' => true,
                'autofocus' => true,
                'validation' => array('not_empty'),
            ),
            'doSave' => array(
                'type' => 'submit',
                'value' => t('Save'),
                'callback' => function($f) {
                    return CBehovsboboxen::Instance()->user->ChangeOwnProfile($f->Value('acronym'), $f->Value('akronym'), $f->Value('name'));
                }
            ),
                )
        );


        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The profile could not be saved.'));
            $this->updated = "";
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            $this->AddMessage('success', t('Saved profile at ' . $this->date));
            $this->RedirectToControllerMethod();
        }

        $data = array(
            'header' => __DIR__ . '/header.tpl.php',
            'user' => $this->user,
            'navbar' => $this->CreateMenu('navbar-ucp'),
            'form' => $form->GetHTML(),
        );

        $this->views->SetTitle(t('Edit profile'))
                ->AddClassToRegion('primary', 'ucp')
                ->AddIncludeToRegion('primary', __DIR__ . '/profile.tpl.php', $data)
                ->AddIncludeToRegion('sidebar', __DIR__ . '/profileside.tpl.php', $data)
        ;
    }

    /**
     * Give a form to the user so the user can change the password.
     *
     */
    public function ChangePassword() {
        $if = new CInterceptionFilter();
        $if->IsRegularUserOrForbidden();

        $form = new CForm(array(), array(
            'acronym' => array(
                'type' => 'hidden',
                'value' => $this->user['acronym'],
            ),
            'password1' => array(
                'type' => 'password',
                'label' => t('Current password:'),
                'required' => true,
                'autofocus' => true,
                'validation' => array('not_empty'),
            ),
            'id' => array(
                'type' => 'text',
                'label' => t('Your id:'),
                'value' => $this->user['id'],
                'required' => true,
                'readonly' => true,
                'validation' => array('not_empty'),
            ),
            'password2' => array(
                'type' => 'password',
                'label' => t('New password:'),
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'password3' => array(
                'type' => 'password',
                'label' => t('New password again:'),
                'required' => true,
                'validation' => array('not_empty', 'match' => 'password2'),
            ),
            'doChange' => array(
                'type' => 'submit',
                'value' => t('Change password'),
                'callback' => function($f) {
                    return CBehovsboboxen::Instance()->user->ChangeOwnPasswordVerify($f->Value('acronym'), $f->Value('password1'), $f->Value('password2'), $f->Value('password3'), $f->Value('id'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The password could not be changed, ensure that all fields match and the current password is correct.'));
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            $updated = date('now', 'localtime');
            $this->AddMessage('success', t('Saved new password at ' . $this->date));
            $this->RedirectToControllerMethod();
        }


        $data = array(
            'header' => __DIR__ . '/header.tpl.php',
            'user' => $this->user,
            'navbar' => $this->CreateMenu('navbar-ucp'),
            'form' => $form->GetHTML(),
        );

//echo $this->user['id'];
        $this->views->SetTitle(t('Change password'))
                ->AddClassToRegion('primary', 'ucp')
                ->AddIncludeToRegion('primary', __DIR__ . '/change_password.tpl.php', $data);
    }

    /**
     * Give a form to the user so the user modify their emailadress.
     *
     */
    public function Email() {
        $if = new CInterceptionFilter();
        $if->IsRegularUserOrForbidden();

        $form = new CForm(array(), array(
            'acronym' => array(
                'type' => 'hidden',
                'value' => $this->user['acronym'],
            ),
            'mail' => array(
                'type' => 'text',
                'label' => t('Current email adress:'),
                'value' => $this->user['email'],
                'required' => true,
                'autofocus' => true,
                'validation' => array('not_empty', 'email_adress'),
            ),
            'doSave' => array(
                'type' => 'submit',
                'value' => t('Save'),
                'callback' => function($f) {
                    return CBehovsboboxen::Instance()->user->ChangeOwnEmail($f->Value('acronym'), $f->Value('mail'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The email adress could not be saved.'));
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            $this->AddMessage('success', t('Saved email adress.'));
            $this->RedirectToControllerMethod();
        }

        $data = array(
            'header' => __DIR__ . '/header.tpl.php',
            'user' => $this->user,
            'navbar' => $this->CreateMenu('navbar-ucp'),
            'form' => $form->GetHTML(),
        );

        $this->views->SetTitle(t('Change password'))
                ->AddClassToRegion('primary', 'ucp')
                ->AddIncludeToRegion('primary', __DIR__ . '/email.tpl.php', $data);
    }

    /**
     * Display information of what groups a user belongs to.
     */
    public function Groups() {
        $if = new CInterceptionFilter();
        $if->IsRegularUserOrForbidden();
        $data = array(
            'header' => __DIR__ . '/header.tpl.php',
            'user' => $this->user,
            'navbar' => $this->CreateMenu('navbar-ucp'),
        );

        $this->views->SetTitle(t('Groups'))
                ->AddClassToRegion('primary', 'ucp')
                ->AddIncludeToRegion('primary', __DIR__ . '/groups.tpl.php', $data);
    }

    /**
     * Change the password.
     */
    public function DoChangePassword($form) {
        if ($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
            $this->AddMessage('error', 'Password does not match or is empty.');
        } else {
            $ret = $this->user->ChangePassword($form['password']['value']);
            $this->AddMessage($ret, 'Saved new password.', 'Failed updating password.');
        }
        $this->RedirectToController('profile');
    }

    /**
     * Save updates to profile information.
     */
    public function DoProfileSave($form) {
        $this->user['name'] = $form['name']['value'];
        $this->user['email'] = $form['email']['value'];
        $ret = $this->user->Save();
        $this->AddMessage($ret, 'Saved profile.', 'Failed saving profile.');
        $this->RedirectToController('profile');
    }

    /**
     * Authenticate and login a user.
*
      *     $ch = curl_init();
     *      curl_setopt($ch, CURLOPT_URL, $url);
*   //*        Set so curl_exec returns the result instead of outputting it.
*           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
*   //*        Get the response and close the channel.
*           $response = curl_exec($ch);
*           curl_close($ch);
     */

    public function Login() {
        $form = new CFormUser();
        $form->CreateLogin($this->user);
        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('Failed to login, user or password does not match.'));
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            //$whereTo = $this->session->GetFlash('redirect_on_success');
            /*
            if ($whereTo) {
                $this->RedirectTo($whereTo);
            } else {
                $this->RedirectToController();
            }*/
            $this->RedirectTo(spotprices);
        }

        // remember where we going on success
        $whereTo = $this->session->GetFlash('redirect_on_success');
        if ($whereTo) {
            $this->session->SetFlash('redirect_on_success', $whereTo);
        }

        $this->views->SetTitle(t('Login'))
                ->AddIncludeToRegion('primary', __DIR__ . '/login.tpl.php', array(
                    'login_form' => $form,
                    //'allow_create_user' => CBehovsboboxen::Instance()->config['create_new_users'],
                    //'create_user_url' => $this->CreateUrlToController('create'),
        ));
    }

    /**
     * Perform a login of the user as callback on a submitted form.
     */
    public function DoLogin($form) {
        if ($this->user->Login($form['acronym']['value'], $form['password']['value'])) {
            $this->AddMessage('success', "Welcome {$this->user['name']}.");
            $this->RedirectToController('profile');
        } else {
            $this->AddMessage('notice', "Failed to login, user does not exist or password does not match.");
            $this->RedirectToController('login');
        }
    }

    /**
     * Logout a user.
     */
    public function Logout() {
        $this->user->Logout();
        $this->RedirectToController('login');
    }

    /**
     * Create a new user.
     */
    public function Create() {
        $if = new CInterceptionFilter();
        $if->CreateNewUserOrForbidden();

        $form = new CFormUser();
        $form->CreateUserCreate($this->user);
        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The account could not be created.'));
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            $this->AddMessage('success', t('Your have successfully created a new account.'));
            $this->RedirectToController('index');
        }

        $this->views->SetTitle('Create user')
                ->AddIncludeToRegion('primary', __DIR__ . '/create.tpl.php', array('form' => $form->GetHTML()));
    }

    /**
     * Perform a creation of a user as callback on a submitted form.
     *
     * @param $form CForm the form that was submitted
     */
    public function DoCreate($form) {
        if ($form['password']['value'] != $form['password1']['value'] || empty($form['password']['value']) || empty($form['password1']['value'])) {
            $this->AddMessage('error', 'Password does not match or is empty.');
            $this->RedirectToController('create');
        } else if ($this->user->Create($form['acronym']['value'], $form['password']['value'], $form['name']['value'], $form['email']['value']
                )) {
            $this->AddMessage('success', "Welcome {$this->user['name']}. Your have successfully created a new account.");
            $this->user->Login($form['acronym']['value'], $form['password']['value']);
            $this->RedirectToController('profile');
        } else {
            $this->AddMessage('notice', "Failed to create an account.");
            $this->RedirectToController('create');
        }
    }

    /**
     * Init the user database.
     */
    public function Init() {
        $this->user->Init();
        $this->RedirectToController();
    }

}

