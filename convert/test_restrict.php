<?php

namespace MyProject\Tests;

require_once './extensions/include.php';

use Sauce\Sausage\WebDriverTestCase;

class test_restrict extends WebDriverTestCase
{
    use \Waiters, \Manipulations;

    protected $login_url = 'http://{server}/auth/login';
    protected $logout_url = 'http://{server}/auth/logout';
    protected $cache_url = 'http://{server}/api/tests/getCache';
    protected $server_url = 'wwwdev3.ondeficar.com';
    protected $login = 'selenium@cloudbeds.com';
    protected $password = 'testTime!';
    protected $cbApiLogin = 'ofc_front';
    protected $cbApiPass = 'H_6z5DpJ:H@5$';
    protected $property_id = 366;
    protected $property_settings = false;
    protected $delta = 0.0001;//delta for assertEquals to compare float values

    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '39',
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

    public function setupInfo($server_url, $login, $pass, $property_id, $browsersInfo = false){
        if($server_url)
            $this->server_url = $server_url;
        if($login)
            $this->login = $login;
        if($pass)
            $this->password = $pass;
        if($property_id)
            $this->property_id = $property_id;
        if($browsersInfo)
            $this->browsers = $browsersInfo;
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

        return date($date_format, strtotime($date, strtotime($base_date)));
    }

    public function loginToSite(callable $success = null, callable $fail = null)
    {
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

        return $loggedIn;
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
        foreach($modals as $modal) {
            if ($modal instanceof PHPUnit_Extensions_Selenium2TestCase_Element) {
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

        $cache_url = $this->_prepareUrl($this->cache_url) . '?' . http_build_query($params);
        
        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode($this->cbApiLogin.':'.$this->cbApiPass)
            )
        ));
        $data = file_get_contents($cache_url, false, $context);

        return json_decode($data, $asArray);
    }

    function _prepareUrl($url){
        $url = str_replace(
                array(
                        '{server}', 
                        '{property_id}'
                     ),
                array(
                        $this->server_url, 
                        $this->property_id
                ),
                $url);
        
        return $url;
    }
    public function _checkLoggedIn(){
        return !in_array($this->getBrowserUrl(), array($this->_prepareUrl($this->login_url), $this->_prepareUrl($this->logout_url)));
    }
}
?>