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
                    'header' => t('Various settings'),
                    'various' => $this->various,
                    'users' => $this->user->ListAllUsers(),
                    'header5' => t('The users'),
                    'acronym' => t('Acronym'),
                    'name' => t('Name'),
                    'algoritm' => t('Algorithm'),
                    'created' => t('Created'),
                    'updated' => t('Updated'),
                    'memberedit' => t('Edit member'),
                    'edit' => t('Edit'),
                    'value' => t('Value'),
                    'areacode' => t('Areacode'),
                    'nrof' => t('Your nr of rooms'),
                    'load' => t('Allow load?'),
                    'percent' => t('Percent on?'),
                    'percentlevel' => t('Percentlevel'),
                    'awayfrom' => t('Away from'),
                    'awayto' => t('Away to'),
                    'database' => t('Reinitiate database'),
                    'startagain' => t('Here you can start fresh again'),

        ));
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
                    'header4' => t('Temperatures'),
                    'outside' => t(' degrees Celsius outside.'),
                    'now' => t('Now it is '),
                    'edit' => t('Edit'),
                    'isvalue' => t('Isvalue'),
                    'shouldvalue' => t('ShouldValue'),
                    'away' => t('Away'),
                    'loadcontrol' => t('Loadcontrol'),
        ));
    }

/*****************************************************************************************************
*create some usable variables for the page
*/

    public function Lists() {
        $list = $this->user->ListAllUsers();
        $this->nrOfUsers = count($list);

        $rooms = $this->temperatures->ListAll();
        $this->nrOfRooms = count($rooms);
        $this->roomsInfo = $rooms;

        $this->various = $this->temperatures->ListVarious();
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

    /*****************************************************************************
     * Edit a selected member.
     *
     * @param id integer the id of the member.
     */
    public function Edit($id = null) {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $thisuser = $this->user->GetMemberById($id);

        $form = new CForm(array(), array(
            'id' => array(
                'type' => 'text',
                'value' => $thisuser['id'],
                'label' => t('Member id:'),
                'readonly' => true,
                'validation' => array('not_empty'),
            ),
            'acronym' => array(
                'type' => 'text',
                'value' => $thisuser['acronym'],
                'label' => t('Acronym'),
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'name' => array(
                'type' => 'text',
                'value' => $thisuser['name'],
                'label' => t('Name'),
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'email' => array(
                'type' => 'text',
                'value' => $thisuser['email'],
                'label' => t('New email:'),
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'doUpdateNames' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Update'),
                'callback' => function($f) {
                    return CBehovsboboxen::Instance()->user->Update($f->Value('acronym'), $f->Value('name'), $f->Value('email'), $f->Value('id'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The editform could not be processed.'));
            $this->RedirectToController('edit', $id);
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $passwordform = new CForm(array(), array(


        

            'acronym' => array(
                'type'  => 'hidden',
                'readonly' => true,
                'value' => $this->user['acronym'],
            ),        
            'password1' => array(
                'type' => 'password',
                'value' => $thisuser['password'],
                'label' => t('Current password:'),
                'readonly' => true,
                'autofocus'   => true,
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
            'acronym' => array(
                'type' => 'text',
                'readonly' => true,
                'value' => $thisuser['acronym'],
            ),
            'id' => array(
                'type' => 'text',
                'readonly' => true,
                'value' => $thisuser['id'],
                'label' => t('Member id:'),
            ),
            'doChange' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Change password'),
                'callback' => function($f) {
                    return CBehovsboboxen::Instance()->user->ChangePasswordAdminVerify($f->Value('acronym'), $f->Value('password2'), $f->Value('password3'), $f->Value('id'));
                }
            ),
                )
        );

        $status2 = $passwordform->Check();
        if ($status2 === false) {
            $this->AddMessage('notice', t('The password could not be changed, ensure that all fields match and the current password is correct.'));
        } else if ($status2 === true) {
            $this->AddMessage('success', (t('Saved new password.')));
            $this->RedirectTo('acp');
        }



        $this->views->SetTitle(t('Update member: '))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisuser,
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => $passwordform->GetHTML(array('class' => 'admin-edit')),
                    'header1' => t('Edit name, acronym or email-address here:'),
                    'header2' => t('Edit password here'),
        ));
    }

    /*******************************************************************************************
     * Updates selected rooms temperatures.
     *
     * @param room string the id of the chosen room.
     */
    public function Update($room = null) {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $room = urldecode($room);

        $temp = array();
        $temp = $this->temperatures->LoadByRoom($room);

        $form = new CForm(array(), array(
            'room' => array(
                'type' => 'text',
                'value' => $room,
                'label' => t('Chosen room:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'id' => array(
                'type' => 'text',
                'value' => $temp['id'],
                'readonly' => true,
                'validation' => array('not_empty'),
            ),
            'home' => array(
                'type' => 'text',
                'value' => $temp['home'],
                'label' => t('Isvalue:'),
                'required' => true,
                'validation' => array('not_empty','numeric'),
            ),
            'max' => array(
                'type' => 'text',
                'value' => $temp['max'],
                'required' => true,
                'validation' => array('not_empty','numeric'),
            ),
            'min' => array(
                'type' => 'text',
                'value' => $temp['min'],
                'required' => true,
                'validation' => array('not_empty','numeric'),
            ),
            'away' => array(
                'type' => 'text',
                'value' => $temp['away'],
                'label' => t('Away:'),
                'required' => true,
                'validation' => array('not_empty','numeric'),
            ),
            'rund' => array(
                'type' => 'text',
                'value' => $temp['rund'],
                'label' => t('Load:'),
                'required' => true,
                'validation' => array('not_empty','numeric'),
            ),
            'roomNew' => array(
                'type' => 'text',
                'value' => $room,
                'label' => t('New name:'),
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'doUpdateRooms' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Update'),
                'callback' => function($f) {
//$f->AddOutput("<pre>What we post: " . print_r($_POST, true) . "</pre>");
                    return CBehovsboboxen::Instance()->temperatures->Update($f->Value('home'), $f->Value('max'), $f->Value('min'), $f->Value('away'), $f->Value('rund'), $f->Value('roomNew'), $f->Value('id'));
                }
            ),
        ));

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The roomeditform could not be processed.'));
            $this->RedirectToController('update', $room);
        } else if ($status === true) {
            $this->RedirectTo('acp/temperatures');
        }

        $this->views->SetTitle(t('Update room'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'header1' => t('Edit the temperatures here:'),
                    'header2' => null,
        ));

    }

/******************************************************************************************
* Form to set dates away from home
*/
     public function Holiday() {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $from = $this->fromDate;
        $to = $this->toDate;

        if(strtotime($this->toDate) < strtotime($this->todaysDate)){
            $from = "";
            $to = "";
        }

        $dateForm = new CForm(array(), array(
            'from' => array(
                'type' => 'text',
                'label' => t('from'),
                'value' => $from,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'to' => array(
                'type' => 'text',
                'label' => t('to'),
                'value' => $to,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'doSetDates' => array(
                'type' => 'submit',
                'value' => t('Dates'),
                'callback' => function($f) {
//$f->AddOutput("<pre>What we post: " . print_r($_POST, true) . "</pre>");
                    return CBehovsboboxen::Instance()->temperatures->UpdateDates($f->Value('from'), $f->Value('to'));
                }
            ),
        ));
        $status = $dateForm->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The dateseditform could not be processed.'));
            $this->RedirectToController('holiday');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $this->views->SetTitle(t('Holiday dates'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $dateForm->GetHTML(array('class' => 'admin-edit')),
                    'header1' => t('Edit dates dd.mm.yyyy:'),
                    'form2' => null,
                    'header2' => null,
        ));
    }

/******************************************************************************************
* Form to set dates away from home
*/
     public function Percentlevel() {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $percentOn = $this->various[0]['percent'];

if($percentOn == 1){

        $percentForm = new CForm(array(), array(
            'percentlevel' => array(
                'type' => 'select',
                'label' => t('percentlevel'),
                'options' => array(
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    ),
                'validation' => array('not_empty'),
            ),
            'doSet' => array(
                'type' => 'submit',
                'value' => t('Percentlevel'),
                'callback' => function($f) {
//$f->AddOutput("<pre>What we post: " . print_r($_POST, true) . "</pre>");
                    return CBehovsboboxen::Instance()->temperatures->UpdatePercentlevel($f->Value('percentlevel'));
                }
            ),
        ));
        $status = $percentForm->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The percentform could not be processed.'));
            $this->RedirectToController('percentlevel');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }
//var_dump($this->various);

        
            $percentlevel = $this->various[0]['percentlevel'];
            $text = t('Percentlevel') . ': ' . $percentlevel;

            $form = $percentForm->GetHTML(array('class' => 'admin-edit'));
        }else{
            $text = t('Percent is off');
            $form = t('You need to allow percent first.');
        }

        $this->views->SetTitle(t('Set percentlevel'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form,
                    'header1' => null,
                    'form2' => $text,
                    'header2' => null,
        ));

    }


    /*********************************************************************************************
    * Various settings for Behovsboboxen
    */
         public function Smallsettings() {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $variousForm = new CForm(array(), array(
            'area' => array(
                'type' => 'select',
                'label' => t('area'),
                'options' => array(
                    'SE1' => 'SE1',
                    'SE2' => 'SE2',
                    'SE3' => 'SE3',
                    'SE4' => 'SE4',
                    ),
                'validation' => array('not_empty'),
            ),
            'nrofrooms' => array(
                'type' => 'select',
                'label' => t('nrOfRooms'),
                'options' => array(
                    5 => '5',
                    6 => '6',
                    7 => '7',
                    8 => '8',
                    9 => '9',
                    10 => '10',
                    11 => '11',
                    12 => '12',
                    13 => '13',
                    14 => '14',
                    15 => '15',
                    16 => '16',
                    ),
                'validation' => array('not_empty'),
            ),
            'load' => array(
                'type' => 'select',
                'label' => t('load'),
                'options' => array(
                    0 => t('off'),
                    1 => t('on'),
                    ),
                'label' => t('Do you accept loadcontrol?'),
                'validation' => array('not_empty'),
                ),
            'percent' => array(
                'type' => 'select',
                'label' => t('percent'),
                'options' => array(
                    0 => t('off'),
                    1 => t('on'),
                    ),
                'label' => t('You wish to control heat by pricepercent?'),
                'validation' => array('not_empty'),
                ),
            'percentlevel' => array(
                'type' => 'select',
                'label' => t('percentlevel'),
                'options' => array(
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    ),
                'validation' => array('not_empty'),
            ),
            'doSet' => array(
                'type' => 'submit',
                'value' => t('Settings'),
                'callback' => function($f) {
//$f->AddOutput("<pre>What we post: " . print_r($_POST, true) . "</pre>");
                    return CBehovsboboxen::Instance()->temperatures->UpdateVarious($f->Value('area'), $f->Value('nrofrooms'), $f->Value('load'), $f->Value('percent'), $f->Value('percentlevel'));
                }
            ),
        ));

        $status = $variousForm->Check();
        if ($status === false) {
            $this->AddMessage('notice', t('The variousform could not be processed.'));
            $this->RedirectToController('smallsettings');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }
//var_dump($this->various);
        $area = $this->various[0]['area'];
        $roomnr = $this->various[0]['nrofrooms'];
        $percentlevel = $this->various[0]['percentlevel'];
        $loadcontrol = ($this->various[0]['load'] == '1' ? t('on') : t('off'));
        $havepercent = ($this->various[0]['percent'] == '1' ? t('on') : t('off'));
        $text = t('Areacode:') . ' ' . $area . ', '  . t('nr of rooms:') . ' ' . $roomnr . ', '  . t('load:') . ' ' . $loadcontrol . ', '  . t('have percent:') . ' ' . $havepercent . ', '  . t('percentlevel:') . ' ' . $percentlevel;

        $this->views->SetTitle(t('Various settings'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/smallsettings.tpl.php', array(
                    'form' => $variousForm->GetHTML(array('class' => 'admin-edit')),
                    'header1' => null,
                    'form2' => $text,
                    'header2' => null,
        ));

    }

}
