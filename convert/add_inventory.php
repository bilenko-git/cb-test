<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class add_inventory extends WebDriverTestCase {
    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '37',
                'platform' => 'Windows 8.1',
                ''
            )
        ),
        // run Chrome on Linux on Sauce
        array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Windows 8.1',
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
    // waitForText
    // get
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
    $element = $this->byCssSelector(".invRoom .sum_rooms");
    $element->click();
    $element->clear();
    $element->value("10");
        $test->execute(array('script' => "window.Metro.scrollTo(window.$('.saveButtons'), true)", 'args' => array()));

        // clickElement
        $this->byCssSelector(".pull-line-right > .btn.green")->click();
    // waitForText
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("Saved", $test->byCssSelector(".savingMsg")->text());
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
        $this->byCssSelector("a[name=\"asettings\"] > span.arrow")->click();
    // clickElement
        $this->byCssSelector("a.logout_link")->click();
  }
}
