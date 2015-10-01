<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class guest_status extends test_restrict{
    private $guest_status_url = 'http://{server}/connect/{property_id}#/guest_status';
    private $customer_url = 'http://{server}/connect/{property_id}#/customers';

    public function testSteps(){

        $this->loginToSite();
        $this->url($this->_prepareUrl($this->guest_status_url));
        $this->waitForLocation($this->_prepareUrl($this->guest_status_url));

        $this->waitForElement(".add-guest-status-btn", 15000, 'css')->click();
        $this->waitForElement("#status_name", 15000, 'css')->value('selenium status');

        $save = $this->waitForElement('.save-guest-status', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');

        $this->url($this->_prepareUrl($this->customer_url));

        $el = $this->waitForElement('#layout .table_customer tbody tr:eq(0) td:eq(0) a', 20000, 'jQ');
        $customer_id = $this->execute(array('script' => "return js=window.$('#layout .table_customer tbody tr:eq(0) td:eq(0) a').data('id')", 'args' => array()));
        $el->click();
        $this->waitForLocation($this->_prepareUrl($this->customer_url.'/'.$customer_id));

        $this->waitForElement('.customer_status > button', 15000, 'css')->click();

        $el = $this->execute(array('script' => 'return window.$(".customer_status li .text:contains(selenium status)").closest("a").get(0)', 'args' => array()));
        $element = $this->elementFromResponseValue($el);
        $element->click();
        $this->url($this->_prepareUrl($this->guest_status_url));
        $this->waitForLocation($this->_prepareUrl($this->guest_status_url));
        sleep(1);
        $el = $this->execute(array('script' => 'return window.$(".table-guest-status tr td:contains(selenium status)").closest("tr").find(".remove-status").get(0)', 'args' => array()));
        $element = $this->elementFromResponseValue($el);
        $element->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
        sleep(3);

        $this->url($this->_prepareUrl($this->customer_url.'/'.$customer_id));
        $this->waitForLocation($this->_prepareUrl($this->customer_url.'/'.$customer_id));

        //$this->waitForElement('.customer_status > button', 15000, 'css')->click();
        $class = $this->execute(array('script' => 'return window.$(".customer_status li:first").hasClass("selected")', 'args' => array()));
        $this->assertEquals('1', $class);

    }

}
?>
