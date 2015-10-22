<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class Do_you_charge_for_additional_adults_and_or_children extends test_restrict{
    private $dashboard_url = 'http://{server}/connect/{property_id}#/dashboard';
    private $roomrate_url = 'http://{server}/connect/{property_id}#/RoomRate';

    public function testSteps(){

        $this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->dashboard_url));
        $this->waitForLocation($this->_prepareUrl($this->dashboard_url));
        $this->waitForElement('.dashboard-current-date', 50000, 'css');
    $this->byName("arates")->click();
    // clickElement
      $this->byCssSelector("#main_menu #sroomRates a")->click();
    // waitForElementAttribute
        $this->waitForLocation($this->_prepareUrl($this->roomrate_url));

    // setElementSelected
    $element = $this->waitForElement(".hide_show_content_below label:first", 15000, 'jQ');
      $element->click();
    // waitForElementPresent
    // setElementText
    $element = $this->waitForElement("#layout .charge_additional .adults input:visible", 15000, 'jQ');
    $element->click();
    $element->clear();
    $element->value("10");
    // setElementText
        $element = $this->waitForElement("#layout .charge_additional .kids input:visible:first", 15000, 'jQ');
        $element->click();
        $element->clear();
    $element->value("99");
    // setElementText
    // clickElement
      $this->save();
    $this->refresh();
    // waitForElementAttribute
        $this->waitForLocation($this->_prepareUrl($this->roomrate_url));
    // clickElement
        $this->waitForElement(".hide_show_content_below label:first", 15000, 'jQ');
    // assertElementValue
    $this->assertEquals("10.00 руб", $this->waitForElement("#layout .charge_additional .adults input:visible", 15000, 'jQ')->value());
    // setElementSelected
        $element = $this->waitForElement(".hide_show_content_below label:eq(1)", 15000, 'jQ');
        $element->click();
    // clickElement
        $this->save();
    // waitForElementAttribute

  }
}
