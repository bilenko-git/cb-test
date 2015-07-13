<?php

namespace MyProject\Tests;

require_once '../tests/selenium/alexanderbrus/extensions/include.php';

use Sauce\Sausage\WebDriverTestCase;

class availability_base_test extends WebDriverTestCase
{
    use \Waiters, \Manipulations;

    protected $login_url = 'http://{server}/auth/login';
    protected $logout_url = 'http://{server}/auth/logout';
    protected $cache_url = 'http://{server}/test/cache/from_cache';
    protected $server_url = 'wwwdev3.ondeficar.com';
    protected $login = 'engineering@cloudbeds.com';
    protected $password = 'cl0udb3ds11';
    protected $property_id = 31;

    protected $availJSON = false;

    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '37',
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

    public function loginToSite(callable $success = null, callable $fail = null)
    {
        $this->url($this->_prepareUrl($this->logout_url));//load url
        $this->waitForLocation($this->login_url);

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
            return $loggedIn ? true : null;
        }, 10000);

        if ($loggedIn) {
            if ($success) call_user_func($success);
        } else {
            if ($fail) call_user_func($fail);
        }
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

    public function getAvailability($room_type_id, $package_id, $date_from, $date_to, $force = false){
        if(!$this->availJSON || $force){
            $this->availJSON = $this->_getAvailabilityJSON();
        }

        //TODO: parse json to ranges, room types, packages etc.
    }

    function _prepareUrl($url){
        return str_replace('{server}', $this->server_url, $url);
    }
    public function _checkLoggedIn(){
        return !in_array($this->getBrowserUrl(), array($this->_prepareUrl($this->login_url), $this->_prepareUrl($this->logout_url)));
    }
    public function _getAvailabilityJSON(){
        $cache_url = $this->_prepareUrl($this->cache_url) . '?' . http_build_query(
                array(
                    'property_id' => $this->property_id,
                    'json' => 1
                )
            );
        return file_get_contents($cache_url);
    }
}
?>