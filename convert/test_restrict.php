<?php

namespace MyProject\Tests;

require_once __DIR__ . '/extensions/include.php';
require_once __DIR__ . '/config.php';

use Sauce\Sausage\WebDriverTestCase;

class test_restrict extends WebDriverTestCase
{
    use \Waiters, \Manipulations;

    protected $login_url = 'http://{server}/auth/login';
    protected $logout_url = 'http://{server}/auth/logout';
    protected $cache_url = 'http://{server}/api/tests/getCache';
    protected $server_url = 'wwwdev.ondeficar.com';
    protected $login = 'selenium@cloudbeds.com'; // selenium@cloudbeds.com for OTA
    protected $password = 'testTime!';
    protected $cbApiLogin = 'ofc_front';
    protected $cbApiPass = 'H_6z5DpJ:H@5$';
    protected $property_id = 366; // 479 for ota
    protected $property_reserva_code = 'ZeRY9J'; // 479 for ota
    protected $property_settings = false;
  //  protected $config = $config;
    protected $delta = 0.0001;//delta for assertEquals to compare float values

    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '42',
                'platform' => 'Windows 8.1',
            )
        ),
        // run Chrome on Linux on Sauce
        /*array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Windows 8.1',
            )
        ),*/
    );

    public function __construct(){
        parent::__construct();
        global $config;
        $this->config = $config;
    }

    public function setUp() {
        parent::setUp();

        if (getenv('SELENIUM_LOCAL')) {
            $this->setupSpecificBrowser(array(
                'local' => true,
                'browserName' => 'firefox',
            ));
        }
    }

    public function setUpPage() {
        $this->fileDetector(function($filename) {
            if(file_exists($filename)) {
                return $filename;
            } else {
                return NULL;
            }
        });
    }
/*
    public function setupInfo1($server_url, $login, $pass, $property_id, $browsersInfo = false){
        if($server_url)
            $this->server_url = $server_url;
        if($login)
            $this->login = $login;
        if($pass)
            $this->password = $pass;
        if($property_id)
            $this->property_id = $property_id;
        if($property_reserva_code)
            $this->property_reserva_code = $property_reserva_code;
        if($browsersInfo)
            $this->browsers = $browsersInfo;
    }*/
    public function setupInfo($setup){
      /*  if (!isset($this->config[$setup])){
            $setup = $setup2;
        }*/
        if($this->config[$setup]['server'])
            $this->server_url = $this->config[$setup]['server'];
        if($this->config[$setup]['login'])
            $this->login = $this->config[$setup]['login'];
        if($this->config[$setup]['password'])
            $this->password = $this->config[$setup]['password'];
        if($this->config[$setup]['property_id'])
            $this->property_id = $this->config[$setup]['property_id'];
        if($this->config[$setup]['property_reserva_code'])
            $this->property_reserva_code = $this->config[$setup]['property_reserva_code'];
        if($this->config[$setup]['browser_info'])
            $this->browsers = $this->config[$setup]['browser_info'];
    }


    /*
     * @date in format for strtotime function
     * @custom_format - if need, otherwise will be used property date format from @BET.config.formats.date_format
     * */
    public function convertDateToSiteFormat($date, $custom_format = false, $base_date = 'now'){
        if(!$this->property_settings){
            $this->property_settings = $this->getJSObject('BET.config');
        }

        $date_format = $custom_format;
        if(!$date_format)
            $date_format = ($this->property_settings?$this->property_settings['formats']['date_format']:'d/m/Y');

        return date($date_format, strtotime($date, strtotime($base_date, mktime(0,0,0))));
    }
    public function save(){
        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'jQ');
        if (getenv('SELENIUM_LOCAL')) {
            //It seems the click event handler is not assigned yet in some cases, so we need the delay.
            sleep(1);
        }
        $save->click();
        $this->waitForElement('.toast-bottom-left');
    }

    public function loginToSite(callable $success = null, callable $fail = null)
    {
        $this->currentWindow()->maximize();
        $this->url($this->_prepareUrl($this->logout_url));//load url

        /*login to site*/
        $this->waitForLocation($this->_prepareUrl($this->login_url));

        if (($emailField = $this->waitForElement('#email'))) {
            $emailField->value($this->login);
        }

        if (($passField = $this->waitForElement('#password'))) {
            $passField->value($this->password);
        }

        $this->byXPath("//div[@class='form-actions']//button[normalize-space(.)='Login']")->click();

        $loggedIn = false;
        $this->waitUntil(function () use (&$loggedIn) {
            $loggedIn = $this->_checkLoggedIn();
            return $loggedIn;
        }, 10000);

        if ($loggedIn) {
            if ($success) call_user_func($success);
        } else {
            if ($fail) call_user_func($fail);
        }
        
        if ($this->login !== 'engineering@cloudbeds.com' && $this->login !== 'admin@test.test') {  //and if not SADMIN engineering@cloudbeds.com and not SADMIN minidb
            $this->waitForBETLoaded();
        }
        
        return $loggedIn;
    }

    public function waitForBETLoaded(){
        $el = $this->waitForElement(".progress-bar-background", 15000, 'jQ');
        $this->waitUntilVisible($el, 30000);
        echo PHP_EOL . 'BET loaded' . PHP_EOL;
    }

    public function getJSObject($name = ''){
        if($name){
            return $this->execute(array(
                'script' => 'return '.$name.';',
                'args'   => array()
            ));
        }

        return false;
    }
    
    public function execJS($script = ''){
        if($script){
            return $this->execute(array(
                'script' => $script,
                'args'   => array()
            ));
        }

        return false;
    }

    /**
     * @visible true|false|null(all)
     */
    public function findModals($visible = null){
        $result = array();
        $modals = $this->elements($this->using('css selector')->value('.modal'));
        echo PHP_EOL . 'find Modals: ' . count($modals) . PHP_EOL;
        foreach($modals as $i => $modal) {
            if ($modal instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
                echo PHP_EOL . 'modal#' . $i . '[id='.$modal->attribute('id') . ';class=' .$modal->attribute('class').']: ' . ($modal->displayed() ? 'visible' : 'hidden') . PHP_EOL;
                if (is_null($visible) || $modal->displayed() === $visible) {
                    $result[] = $modal;
                }
            }
        }

        return $result;
    }
    public function closeModals(){
        $modals = $this->findModals(true);
        foreach($modals as $modal) {
            $buttons = $modal->elements($this->using('css selector')->value('.btn.blue'));
            foreach ($buttons as $btn)
                $btn->click();
        }
    }

    /*
     * @date_from format Y-m-d
     * @date_to format Y-m-d
     * */
    public function getAvailability($date_from, $date_to, $room_type_id = false, $package_id = false, $asArray=false){
        $params = array(
            'property_id' => $this->property_id
        );

        if($date_from){
            $params['start'] = $date_from;
            if($date_to){
                $params['days'] = intval((strtotime($date_to) - strtotime($date_from)) / 86400);
            }
        }

        if($room_type_id) $params['room_type_id'] = $room_type_id;
        if($package_id) $params['package_id'] = $package_id;

        return $this->apiCall($this->cache_url, $params, $asArray);
    }

    function _prepareUrl($url){
        $url = str_replace(
                array(
                        '{server}', 
                        '{property_id}',
                        '{property_reserva_code}'
                     ),
                array(
                        $this->server_url, 
                        $this->property_id,
                        $this->property_reserva_code
                ),
                $url);
        
        return $url;
    }
    
    public function _checkLoggedIn(){
        return !in_array($this->getBrowserUrl(), array($this->_prepareUrl($this->login_url), $this->_prepareUrl($this->logout_url)));
    }

    public function apiCall($url, $params, $asArray = true){
        $preparedUrl = $this->_prepareUrl($url) . '?' . http_build_query($params);
        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode($this->cbApiLogin.':'.$this->cbApiPass)
            )
        ));
        $data = file_get_contents($preparedUrl, false, $context);
        return json_decode($data, $asArray);
    }

    public function confirm_delete_modal(){
        $this->waitForElement('#confirm_delete', 15000);//delete confirmation almost all over site we can you this method to confim deleting something
        $this->waitForElement('.btn_delete', 5000)->click();
    }

    public function selectPickerValue($name, $val) {
        $optional = '';
        if(!is_null($val)) {
            $optional = ', ["'.implode('","', $val).'"]';
        }

        $this->execute(array(
            'script' => '$(\'[name="'.$name.'"]\').selectpicker("val"'.$optional.').trigger(\'change\');',
            'args' => array()
        ));
    }

    protected function getCurrentMonthYear($dt) {
        if($dt instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $els = $dt->elements($this->using('css selector')->value('[data-year]'));
            return array($els->attribute('data-year'), $els->attribute('data-month'));
        }

        return array(date('m'), date('Y'));
    }

    public function fillDate($name, $val) {
        $el = $this->waitForElement('[name=\''.$name.'\']');
        $el->click();
        $dt = $this->byId('ui-datepicker-div');

        $el->clear();
        $el->value($val);
        $dt->byCssSelector('.ui-state-active')->click();
        /*
        $prev = $dt->byCssSelector('.ui-datepicker-prev');
        $next = $dt->byCssSelector('.ui-datepicker-next');

        list($mon, $yr) = $this->getCurrentMonthYear($dt);
        $nmon = date('m', $val);
        $nyr = date('Y', $val);
        $nday = date('d', $val);

        $diffMon = $yr * 12 + $mon - $nyr * 12 - $nmon;
        $chg_btn = $diffMon > 0 ? $prev : $next;
        for($i = 0; $i < abs($diffMon); $i++) {
            $chg_btn->click();
            usleep(100000);// sleep 100ms
        }

        $a = $dt->byLinkText($nday);
        $a->click();
        */

        /*if($el instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $el->click();
            $el->clear();
            $el->value($val);
        }*/

        /*$this->execute(array(
            'script' => '$(\'[name="' . $name . '"]\').datepicker("setDate", new Date(\'' . $val . '\')).trigger(\'change\');',
            'args' => array()
        ));*/
    }
}
?>
