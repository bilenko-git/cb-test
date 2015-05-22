<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class s2_interval_rename extends WebDriverTestCase {
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
        /*array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Linux',
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
    // clickElement
    $this->byCssSelector("li.base.active > a")->click();
    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[1]/i")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // store
    $test->new_interval_name = "Selenium Interval";
    // storeElementValue
    $test->old_interval_name = $test->byName("interval_name")->value();
    // setElementText
    $element = $this->byName("interval_name");
    $element->click();
    $element->clear();
    $element->value($test->new_interval_name);
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
    // waitForTextPresent

    // assertText
    $test->assertEquals($test->new_interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
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
    // print
    print $test->new_interval_name;
    // print
    print $test->old_interval_name;
    // assertText
    $test->assertEquals($test->new_interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[1]/i")->click();
    // setElementText
    $element = $this->byName("interval_name");
    $element->click();
    $element->clear();
    $element->value($test->old_interval_name);
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
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
    // assertText
    $test->assertEquals($test->old_interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
    // clickElement
    $this->byName("asettings")->click();
    // clickElement
    $this->byCssSelector("a.logout_link")->click();
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
