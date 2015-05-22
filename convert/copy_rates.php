<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class copy_rates extends WebDriverTestCase {
    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'Linux',
            )
        ),
        // run Chrome on Linux on Sauce
//        array(
//            'browserName' => 'chrome',
//            'desiredCapabilities' => array(
//                'platform' => 'Linux',
//            )
//        ),
    );
    /**
     * Recorded steps.
     */
    public function testSteps() {
        $test = $this; // Workaround for anonymous function scopes in PHP < v5.4.
        $session = $this->prepareSession(); // Make the session available.
        // get
        $this->url("http://wwwdev3.ondeficar.com/auth/login");
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
        // store
        $test->hash = "roomRates";
        // clickElement
        $this->byName("arates")->click();
        // clickElement
        $this->byCssSelector("#main_menu #sroomRates a")->click();
        // waitForElementAttribute
        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals($test->hash, $test->byId("layout")->attribute("data-current_view"));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
        // assertElementAttribute
        $test->assertEquals($test->hash, $test->byId("layout")->attribute("data-current_view"));
    // storeEval
    $test->countRoomRates = $test->execute(array('script' => "return js=window.$('.tab-content .tab-pane', '#layout').length", 'args' => array()));
    // print
    print $test->countRoomRates;
    // storeEval
   // $test->nextMove = $test->execute(array('script' => "return if (" . $test->countRoomRates . " > 1 && selenium.isElementPresent('css=#layout #tab_0 .copy_rates') == true) {nextMove = 'openCopyModal'} else {nextMove = 'testingClosed'}", 'args' => array()));
    // print
  //  print $test->nextMove;
    // clickElement
    $this->byCssSelector("a[href='#tab_1']")->click();
    // clickElement
    $this->byCssSelector("#tab_1 a.copy_rates")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector(".room_rate_copy_modal.in") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector(".room_rate_copy_modal.in select") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // storeEval
    $test->availableRates = $test->execute(array('script' => "return window.$('.room_rate_copy_modal.in select option').length", 'args' => array()));
    // print
    print $test->availableRates;
    // assertEval
    $test->assertEquals($test->availableRates, $test->execute(array('script' => "return " . $test->countRoomRates . " - 1", 'args' => array())));
    // clickElement
    $this->byCssSelector(".room_rate_copy_modal .save_changes3")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector(".room_rate_copy_confirm_modal.in") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector(".room_rate_copy_confirm_modal.in .save_changes3") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector(".room_rate_copy_confirm_modal.in .save_changes3")->click();
    // storeEval
    $test->intervalsCount = $test->execute(array('script' => "return window.$('#tab_0 .intervals-table tbody').find('tr[data-temp_id]').length", 'args' => array()));
    // print
    print $test->intervalsCount;
    // clickElement
    $this->byCssSelector("a[href='#tab_1']")->click();
    // storeEval
    $test->currentIntervalsCount = $test->execute(array('script' => "return window.$('#tab_1 .intervals-table tbody').find('tr:visible').length", 'args' => array()));
    // print
    print $test->currentIntervalsCount;
    // assertEval
    $test->assertEquals($test->intervalsCount, $test->currentIntervalsCount);
    // clickElement
   // $this->byCssSelector("a[href='#tab_0']")->click();
    // clickElement

    $this->byCssSelector(".pull-line-right > .btn.green")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("saved", $test->execute(array('script' => "return js=window.$('.savingMsg').attr('data-qe-id')", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // refresh
    $this->refresh();
    // waitForEval
      // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('.progress-bar-background:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    });
    // storeEval
    $test->intervalsCount = $test->execute(array('script' => "return window.$('#tab_0 .intervals-table tbody').find('tr[data-temp_id]').length", 'args' => array()));
    // print
    print $test->intervalsCount;
    // clickElement
    $this->byCssSelector("a[href='#tab_1']")->click();
    // storeEval
    $test->currentIntervalsCount = $test->execute(array('script' => "return window.$('#tab_1 .intervals-table tbody').find('tr:visible').length", 'args' => array()));
    // print
    print $test->currentIntervalsCount;
    // assertEval
    $test->assertEquals($test->intervalsCount, $test->currentIntervalsCount);
    // get
    $this->url("http://wwwdev3.ondeficar.com/auth/logout");
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("div.login") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
  }
}
