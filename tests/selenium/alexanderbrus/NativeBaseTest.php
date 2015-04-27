<?php

require_once './vendor/phpunit/phpunit-selenium/PHPUnit/Extensions/SeleniumTestCase.php';

class NativeBaseTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/screenshoots/';
    protected $screenshotUrl = 'http://localhost/screenshoots';

    protected $login_url = 'http://hotel.acessa.loc/auth/login';
    protected $logout_url = 'http://hotel.acessa.loc/auth/logout';
    protected $login = 'engineering@cloudbeds.com';
    protected $password = 'cl0udb3ds';
    protected $directory;
    protected $logDir = '/logs/';

    /*protected $_map = array(
        'dashboard' => array('action' => 'click', 'css selector' => '#main_menu [name="adashboard"]'),
        'calendar' => array('action' => 'click', 'css selector' => '#main_menu [name="acalendar"]'),
        'newreservations' => array('action' => 'click', 'css selector' => '#main_menu [name="anewreservations"]'),
        'house_accounts' => array('action' => 'click', 'css selector' => '#main_menu [name="ahouse_accounts"]'),

        'rates' => '#main_menu [name="arates"]',//not a link
        'roomrates' => array(array('action' => 'click', 'css selector' => '#main_menu [name="arates"]'), array('action' => 'click', 'css selector' => '#main_menu #sroomRates > a')),
        'packages' => array(array('action' => 'click', 'css selector' => '#main_menu [name="arates"]'), array('action' => 'click', 'css selector' => '#main_menu #spackages > a')),
        'roomblocks' => array(array('action' => 'click', 'css selector' => '#main_menu [name="arates"]'), array('action' => 'click', 'css selector' => '#main_menu #sroomblocks > a')),

        'customers' => array('action' => 'click', 'css selector' => '#main_menu [name="acustomers"]'),

        'reports' => array('action' => 'click', 'css selector' => '#main_menu [name="areports"]'),

        'production_reports_group' => '#main_menu #sproduction_reports_group > a',//not a link
        'revpar_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sproduction_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #sRevPar_report > a')),
        'occupancy_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sproduction_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #soccupancy_report > a')),
        'adr_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sproduction_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #sADR_report')),
        'channel_production' => array(array('action' => 'click', 'css selector' => '#main_menu #sproduction_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #schannel_production > a')),

        'financial_reports_group' => '#main_menu #sfinancial_reports_group > a',//not a link
        'commission_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sfinancial_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #scommission_report > a')),
        'report_transactions' => array(array('action' => 'click', 'css selector' => '#main_menu #sfinancial_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #sreport_transactions > a')),
        'daily_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sfinancial_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #sdaily_report > a')),

        'daily_activity_reports_group' => '#main_menu #sdaily_activity_reports_group > a',//not a link
        'account_balances_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sdaily_activity_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #saccount_balances_report > a')),
        'cashier_report' => array(array('action' => 'click', 'css selector' => '#main_menu #sdaily_activity_reports_group > a'), array('action' => 'click', 'css selector' => '#main_menu #scashier_report > a')),

        'settings' => array('action' => 'click', 'css selector' => '#ssettings > a')
    );*/
    protected $_map= array(
        'dashboard' => array('action' => 'click', 'css selector' => '#main_menu [name="adashboard"]'),
        'calendar' => array('action' => 'click', 'css selector' => '#main_menu [name="acalendar"]'),
        'newreservations' => array('action' => 'click', 'css selector' => '#main_menu [name="anewreservations"]'),
        'house_accounts' => array('action' => 'click', 'css selector' => '#main_menu [name="ahouse_accounts"]'),

        'rates' => '#main_menu [name="arates"]',
        'roomrates' => array('action' => 'click', 'css selector' => '#main_menu #sroomRates > a', 'parent' => 'rates'),
        'packages' => array('action' => 'click', 'css selector' => '#main_menu #spackages > a', 'parent' => 'rates'),
        'roomblocks' => array('action' => 'click', 'css selector' => '#main_menu #sroomblocks > a', 'parent' => 'rates'),

        'customers' => array('action' => 'click', 'css selector' => '#main_menu [name="acustomers"]'),

        'reports' => array('action' => 'click', 'css selector' => '#main_menu [name="areports"]'),

        'production_reports_group' => array('action' => 'click', '#main_menu #sproduction_reports_group > a', 'parent' => 'reports'),
        'revpar_report' => array('action' => 'click', 'css selector' => '#main_menu #sRevPar_report > a', 'parent' => 'production_reports_group'),
        'occupancy_report' => array('action' => 'click', 'css selector' => '#main_menu #soccupancy_report > a', 'parent' => 'production_reports_group'),
        'adr_report' => array('action' => 'click', 'css selector' => '#main_menu #sADR_report', 'parent' => 'production_reports_group'),
        'channel_production' => array('action' => 'click', 'css selector' => '#main_menu #schannel_production > a', 'parent' => 'production_reports_group'),

        'financial_reports_group' => array('action' => 'click', 'css selector' => '#main_menu #sfinancial_reports_group > a', 'parent' => 'reports'),//not a link
        'commission_report' => array('action' => 'click', 'css selector' => '#main_menu #scommission_report > a', 'parent' => 'financial_reports_group'),
        'report_transactions' => array('action' => 'click', 'css selector' => '#main_menu #sreport_transactions > a', 'parent' => 'financial_reports_group'),
        'daily_report' => array('action' => 'click', 'css selector' => '#main_menu #sdaily_report > a', 'parent' => 'financial_reports_group'),

        'daily_activity_reports_group' => array('action' => 'click', 'css selector' => '#main_menu #sdaily_activity_reports_group > a', 'parent' => 'reports'),
        'account_balances_report' => array('action' => 'click', 'css selector' => '#main_menu #saccount_balances_report > a', 'parent' => 'daily_activity_reports_group'),
        'cashier_report' => array('action' => 'click', 'css selector' => '#main_menu #scashier_report > a', 'parent' => 'daily_activity_reports_group'),

        'settings' => array('action' => 'click', 'css selector' => '#ssettings > a'),
        'profile' => array('action' => 'click', 'css selector' => '#sprofile1 > a', 'parent' => 'settings'),
            'profileuser' => array('action' => 'click', 'css selector' => '#sprofileUser > a', 'parent' => 'profile'),
            'profilehotel' => array('action' => 'click', 'css selector' => '#sprofileHotel > a', 'parent' => 'profile'),
        'users' => array('action' => 'click', 'css selector' => '#susers > a', 'parent' => 'support'),
        'registrationcards' => array('action' => 'click', 'css selector' => '#sregistrationCards > a', 'parent' => 'support'),

        'support' => array('action' => 'click', 'css selector' => '#ssupport > a'),

        'scashier_system' => array('action' => 'click', 'css selector' => '#scashier_system > a')
    );

    public function __construct(){
        $this->screenshotPath = __DIR__ . $this->screenshotPath;
        $this->logDir = __DIR__ . $this->logDir;
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

    /**@element is object of PHPUnit_.._Element or selector (use @selector_type to use xpath or other type of selectors)*/
    public function hover($element, $selector_type = 'css selector'){
        if(is_string($element) && !empty($selector_type)){
            $element = $this->element($this->using($selector_type)->value($element));
        }

        if($element->displayed()){
            $size = $element->size();
            $offsetX = intval($size['width'] / 2);
            $offsetY = intval($size['height'] / 2);
            $this->moveto(array(
                'element' => $element,
                'xoffset' => $offsetX,
                'yoffset' => $offsetY
            ));
        }
    }

    public function setAttribute($element, $name, $value){
        $this->execute(array(
            'script' => 'arguments[0].setAttribute("'.$name.'", "'.$value.'")',
            'args' => array($element->toWebDriverObject())
        ));
    }

    public function waitUntilVisible($element, $timeout = 1000){
        $this->waitUntil(function() use($element){
            return $element->displayed();
        }, $timeout);
    }

    public function goToLocation($location){
        $path = $this->getLocationPath($location);

        if(!empty($path)){
            $path_length = count($path);
            foreach($path as $index => $p){
                $element = $this->element($this->using('css selector')->value($p['css selector']));
                if($element->displayed()) {
                    if ($p['action'] == 'click' && ($index != 1 && $path_length > 2)) {
                        $element->click();
                    } else {
                        $this->hover($element);
                    }
                }else{
                    $this->waitUntilVisible($element, 500);
                    if(!$element->displayed()) return false;
                }
            }

            return true;
        }

        return false;
    }
    public function getLocationPath($location){
        $path = array();
        if(isset($this->_map[$location])){
            $currentLocation = $this->_map[$location];
            $path[] = $currentLocation;

            if(isset($currentLocation['parent'])) {
                $path = array_merge($this->getLocationPath($currentLocation['parent']), $path);
            }

            return $path;
        }

        return array();
    }

    public function waitForLocation($url, $timeout = 1000){
        $this->waitUntil(function($case) use ($url, $timeout) {return $case->getBrowserUrl() == $url;}, $timeout);
    }

    public function loginToSite(callable $callback = null){
        $this->url($this->logout_url);//load url
        $this->waitForLocation($this->login_url);

        /*login to site*/
        $this->waitForLocation($this->login_url);
        $this->byId('email')->value($this->login);
        $this->byId('password')->value($this->password);
        $this->byXPath("//div[@class='form-actions']//button[normalize-space(.)='Login']")->click();
        $this->waitUntil(array($this, 'checkLoggedIn'), 1000);

        if($callback) call_user_func($callback);
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