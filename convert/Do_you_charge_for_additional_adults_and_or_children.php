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
    $element = $this->byJQ("input[name^='charge_additional'][value='Y']:visible");
      $element->click();
    // waitForElementPresent
    // setElementText
    $element = $this->byJQ(".charge_additional .adults input");
    $element->click();
    $element->clear();
    $element->value("10");
    // setElementText
        $element = $this->byJQ(".charge_additional .kids input");
        $element->click();
        $element->clear();
    $element->value("99");
    // setElementText
    // clickElement
      $this->byCssSelector(".pull-line-right > .btn.green")->click();
    // waitForElementAttribute
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("saved", $test->byCssSelector(".savingMsg")->attribute("data-qe-id"));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // refresh
    $this->refresh();
    // waitForElementAttribute
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("roomRates", $test->byId("layout")->attribute("data-current_view"));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byXPath("//div[@id='layout']//a[.='Econom']")->click();
    // assertElementValue
    $test->assertEquals("Y", $test->byCssSelector("span.checked input[name='charge_additional?tab_1'][value='Y']")->value());
    // setElementSelected
    $element = $this->byCssSelector("input[name='charge_additional?tab_1'][value='N']");
    if (!$element->selected()) {
      $element->click();
    }
    // clickElement
      $this->byCssSelector(".pull-line-right > .btn.green")->click();
    // waitForElementAttribute
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("saved", $test->byCssSelector(".savingMsg")->attribute("data-qe-id"));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
  }
}
