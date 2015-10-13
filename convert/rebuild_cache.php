<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class rebuild_cache extends test_restrict{
    private $dashboard_url = 'http://{server}/connect/{property_id}#/dashboard';

    public function testSteps(){

        $this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->dashboard_url));
        $this->waitForLocation($this->_prepareUrl($this->dashboard_url));

        $this->waitForElement("#cache_rebuild_button", 15000, 'css')->click();
        $save = $this->waitForElement('#validation_error .btn_ok', 15000, 'jQ');
        $save->click();
    }
}
?>
