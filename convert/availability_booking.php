<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class Availability_booking extends WebDriverTestCase {
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
        /* array(
             'browserName' => 'chrome',
             'desiredCapabilities' => array(
                 'platform' => 'Windows 8.1',
             )
         ),*/
    );
    /**
     * Recorded steps.
     */
    public function testSteps() {
        $test = $this; // Workaround for anonymous function scopes in PHP < v5.4.
        $session = $this->prepareSession(); // Make the session available.
        // get
        $this->url("http://wwwdev.ondeficar.com/auth/login");
        // setElementText
        $element = $this->byId("email");
        $element->click();
        $element->clear();
        $element->value("selenium@cloudbeds.com");
        // setElementText
        $element = $this->byId("password");
        $element->click();
        $element->clear();
        $element->value("testTime!");
        // clickElement
        $this->byXPath("//div[@class='form-actions']//button[normalize-space(.)='Login']")->click();
        // waitForCurrentUrl
        // waitForElementPresent
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);
        // waitForEval
        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('body > .progress-bar-background:visible').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
        // waitForElementPresent
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byCssSelector("#layout[data-current_view=dashboard]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);
        // assertElementPresent
        try {
            $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
        } catch (\Exception $e) {
            $boolean = false;
        }
        $test->assertTrue($boolean);
    // clickElement
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
    // storeEval
    $test->back = $test->execute(array('script' => "return js=window.$('[name=\"invRoom[919]\"] .sum_rooms').val()", 'args' => array()));
    // storeEval
    $test->front = $test->execute(array('script' => "return js=window.$('[name=\"invMaxRoom[919]\"] .max_rooms').val()", 'args' => array()));
    // print
    print $test->back;
    // clickElement
        $this->byCssSelector("#snewreservations > a")->click();
        // clickElement
        $this->byId("new_reservation")->click();
        // clickElement
        $this->byId("find-rooms")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#book-choosed-rooms:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    });
    // storeEval
    $test->back_aval = $test->execute(array('script' => "return js=window.$('[data-room=\"919\"] .selected-rooms').data('max_rooms')", 'args' => array()));
    // storeEval
    $test->front_aval = $test->execute(array('script' => "return " . $test->front . " - (" . $test->back . " - " . $test->back_aval . ")", 'args' => array()));
    // get
    $this->url("http://wwwdev.ondeficar.com/reservas/366");
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('[data-room_type_id=\"919\"]').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    });
    // storeEval
    $test->id = $test->execute(array('script' => "return js=window.$('[data-room_type_id=\"919\"]').data(\"id\")", 'args' => array()));
    // print
    print $test->id;
    // storeEval
    $test->front_aval_now = $test->execute(array('script' => "return js=window.$('[name=\"qty_rooms[" . $test->id . "]\"] option').length", 'args' => array()));
    // print
    print $test->front_aval_now;
    // assertEval
    $test->assertEquals(true, $test->execute(array('script' => "return (" . $test->front_aval_now . " -1)  == " . $test->front_aval. "", 'args' => array())));
  }
}
