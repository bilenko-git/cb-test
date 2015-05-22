<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class event_canceletion extends WebDriverTestCase {
    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
      /*  array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'Linux',
            )
        ),*/
        // run Chrome on Linux on Sauce
     array(
             'browserName' => 'chrome',
             'desiredCapabilities' => array(
                 'platform' => 'Linux',
             )
         ),
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
    // clickElement
    $this->byCssSelector("#main_menu #scalendar a")->click();

    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("table.calendar-table") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return window.$('#layout .loading.locked').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("table.calendar-table") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // storeEval
    $test->startDate = $test->execute(array('script' => "return window.$('#layout table.calendar-head:visible th.future:eq(1)').data('date')", 'args' => array()));
    // storeEval
    $test->endDate = $test->execute(array('script' => "return window.$('#layout table.calendar-head:visible th.future:eq(4)').data('date')", 'args' => array()));
    // storeEval
    $test->roomId = $test->execute(array('script' => "return window.$('#layout .calendar-table.calendar-content tr.room_numbers:eq(2)').data('room_id')", 'args' => array()));
    // clickElement
        $this->byXPath('//*[@id="891-2"]/td[6]/div')->click();
    // clickElement
        $this->byXPath('//*[@id="891-2"]/td[8]/div')->click();;
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("#layout .popover.new_event") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .popover.new_event .cancel-popover")->click();
    // !waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("#layout .popover.new_event") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return !$boolean === true ?: null;
    },50000);
    // !assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#layout .popover.new_event") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertFalse($boolean);
    // clickElement
    $this->byXPath("//li[@id='ssettings']/a/span")->click();
    // clickElement
    $this->byXPath("//li[@id='slogout']/a")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("body > .autherization") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
  }
}
