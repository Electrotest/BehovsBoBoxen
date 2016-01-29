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

    public function __construct() {
        parent::__construct();
        
    }

    /**
     * Show profile information of the user.
     */
    public function Index() {
    }

    /**
     * Authenticate and login a user.
     */

    public function Login() {
        $form = new CFormUser();
        $form->CreateLogin($this->user);

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('Failed to login, user or password does not match.'));
            $this->RedirectToControllerMethod();
        } else if ($status === true) {
            $this->AddMessage('success', t("Welcome ") . t(" {$this->user['name']}."));
            $this->RedirectTo('spotprices');
        }


        $this->views->SetTitle(t('Login'))
                ->AddIncludeToRegion('primary', __DIR__ . '/login.tpl.php', array(
                    'login_form' => $form,
        ));
    }



    /**
     * Logout a user.
     */
    public function Logout() {
        $this->user->Logout();
        $this->RedirectToController('login');
    }

}

