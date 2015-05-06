<?php

require_once './vendor/phpunit/phpunit-selenium/PHPUnit/Extensions/SeleniumTestCase.php';
require_once './extensions/include.php';

class PHPUnit_BaseTest extends PHPUnit_Extensions_Selenium2TestCase
{
    use Waiters, Manipulations, Asserts {
        Asserts::__construct as protected __asserts_construct;
    }

    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/screenshoots/';
    protected $screenshotUrl = 'http://localhost/screenshoots';

    protected $login_url = 'http://hotel.acessa.loc/auth/login';
    protected $logout_url = 'http://hotel.acessa.loc/auth/logout';
    protected $login = 'admin@test.test';//'engineering@cloudbeds.com';
    protected $password = '123qwe';//'cl0udb3ds';
    protected $directory;
    protected $logDir = '/logs/';

    public function __construct(){
        $this->screenshotPath = __DIR__ . $this->screenshotPath;
        $this->logDir = __DIR__ . $this->logDir;
        $this->__asserts_construct();
        parent::__construct();
    }

    protected function setUp()
    {
        $this->directory = __DIR__ . '/screenshoots/' . get_class($this) . '/';

        set_error_handler(array($this, '_error_handler'));
        set_exception_handler(array($this, '_exception_handler'));

        $this->setBrowser('firefox');
        $this->setBrowserUrl($this->logout_url);
        $this->listener = new PHPUnit_Extensions_Selenium2TestCase_ScreenshotListener(
            $this->directory
        );
    }

    public function loginToSite(callable $callback = null){
        $this->url($this->logout_url);//load url
        $this->waitForLocation($this->login_url);

        /*login to site*/
        $this->waitForLocation($this->login_url);

        if(($emailField = $this->waitForElement('#email'))){
            $emailField->value($this->login);
        }

        if(($passField = $this->waitForElement('#password'))){
            $passField->value($this->password);
        }

        $this->byXPath("//div[@class='form-actions']//button[normalize-space(.)='Login']")->click();

        $loggedIn = false;
        $this->waitUntil(function() use(&$loggedIn){
            $loggedIn = $this->checkLoggedIn();
            return $loggedIn?true:null;
        }, 10000);

        if($loggedIn) {
            if ($callback) call_user_func($callback);
        }else{
            $this->fail('Logged into site FAILED');
        }
    }
    public function checkLoggedIn(){
        return $this->getBrowserUrl() !== $this->login_url;
    }

    public function takeScreenShoot(){
        if(!file_exists($this->directory)) @mkdir($this->directory, 0777, true);
        $this->listener->addError($this, new Exception(), NULL);
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

    public function saveLogs(){
        $currentLogDir = $this->logDir . get_class($this);
        if(!file_exists($currentLogDir)){
            @mkdir($currentLogDir, 0777, true);
        }
        $logFilePattern = $currentLogDir . '/' . '%type%' . '.log';
        $logFile = '';
        $logs = $this->getLogs();

        foreach($logs as $lt => $lval){
            $logFile = str_replace('%type%', $lt, $logFilePattern);

            file_put_contents($logFile, "\n\n", FILE_APPEND);//empty line to log
            foreach($lval as $l){
                file_put_contents($logFile, date('Y-m-d H:i:s', $l['timestamp']) . '[' . $l['level']. '] ' . $l['message'] . "\n", FILE_APPEND);
            }
        }
    }
    public function getLogs(){
        $logTypes = $this->logTypes();
        $result = array();
        foreach($logTypes as $lt){
            $result[$lt] = $this->log($lt);
        }

        return array_filter($result);
    }
    public function getBETJSErrors(){
        return $this->execute(array('script' => 'return BET.onerror.all();', 'args' => array()));
    }

    public function _exception_handler(Exception $e){
        $this->takeScreenShoot();
    }
    public function _error_handler($e){
        if($e != E_WARNING && $e != E_NOTICE)
            $this->takeScreenShoot();
    }
    public function tearDown(){
        sleep(5);
        $this->takeScreenShoot();
        $this->saveLogs();
    }

}
?>