<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class add_check_tax_fee extends test_restrict{
    private $tax_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    public function testSteps(){

        //$this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        $this->loginToSite();
        $this->addFee('fee1', 30);

    }

    private function addFee($name, $am){
        $this->url($this->_prepareUrl($this->tax_url));
        $this->waitForLocation($this->_prepareUrl($this->tax_url));

        $new = $this->waitForElement(".add-new-fee", 15000, 'css');
        $fee_count = $this->execute(array('script' => 'window.$("td.fee-name").length', 'args' => array()));
        $new->click();
        $this->waitForElement('#layout .add-fee-portlet-box [id^=fee_name_]:visible', 15000, 'jQ')->value($name);
        $amount = $this->waitForElement('#layout #fee_amount:visible', 15000, 'jQ');
        $amount->clear();
        $amount->value($am);
        $this->save();
    }

    private function addTax($name, $am){
        $this->url($this->_prepareUrl($this->tax_url));
        $this->waitForLocation($this->_prepareUrl($this->tax_url));

        $new = $this->waitForElement(".add-new-fee", 15000, 'css');
        $fee_count = $this->execute(array('script' => 'window.$("td.fee-name").length', 'args' => array()));
        $new->click();
        $this->waitForElement('#layout .add-fee-portlet-box [id^=fee_name_]:visible', 15000, 'jQ')->value($name);
        $amount = $this->waitForElement('#layout #fee_amount:visible', 15000, 'jQ');
        $amount->clear();
        $amount->value($am);
        $this->save();
    }
}
?>
