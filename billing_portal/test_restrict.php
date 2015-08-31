<?php

namespace MyProject\Tests;

require_once __DIR__ . '/extensions/include.php';

use Sauce\Sausage\WebDriverTestCase;

class test_restrict extends WebDriverTestCase
{
    use \Waiters, \Manipulations;

    protected $server_url = 'wwwdev.ondeficar.com';
    protected $is_local_test = true;

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

    /*
     * @date in format for strtotime function
     * @custom_format - if need, otherwise will be used property date format from @BET.config.formats.date_format
     * */
    public function convertDateToSiteFormat($date, $custom_format = false, $base_date = 'now'){
        //todo: rewrite
        if(!$this->property_settings){
            $this->property_settings = $this->getJSObject('BET.config');
        }

        $date_format = $custom_format;
        if(!$date_format)
            $date_format = ($this->property_settings?$this->property_settings['formats']['date_format']:'d/m/Y');

        return date($date_format, strtotime($date, strtotime($base_date, mktime(0,0,0))));
    }

    public function loginToSite(callable $success = null, callable $fail = null)
    {
        //todo: rewrite
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
        //todo: check
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
        //todo: check
        $modals = $this->findModals(true);
        foreach($modals as $modal) {
            $buttons = $modal->elements($this->using('css selector')->value('.btn.blue'));
            foreach ($buttons as $btn)
                $btn->click();
        }
    }

    function _prepareUrl($url){
        //todo: revrite
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