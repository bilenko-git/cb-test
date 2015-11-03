<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class add_inventory_error_booking_rooms extends test_restrict{
    private $dashboard_url = 'http://{server}/connect/{property_id}#/dashboard';
    private $roomrate_url = 'http://{server}/connect/{property_id}#/RoomRate';

    public function testSteps(){

        //$this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        $this->setupInfo('PMS_user');

        $this->loginToSite();
        $this->url($this->_prepareUrl($this->dashboard_url));
        $this->waitForLocation($this->_prepareUrl($this->dashboard_url));
        $this->waitForElement('.dashboard-current-date', 50000, 'css');


    $this->url("http://wwwdev.ondeficar.com/connect/366#/inventory");
    // waitForText
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("Inventory Allocation", $test->byCssSelector(".page-title")->text());
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
    $element = $this->byCssSelector(".invRoom .max_rooms");
    $element->click();
    $element->clear();
    $element->value("1000");
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector(".invRoom .inventory_error") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
        $this->byCssSelector("a[name=\"asettings\"] > span.arrow")->click();
        // clickElement
        $this->byCssSelector("a.logout_link")->click();
  }
}
