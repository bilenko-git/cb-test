<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class s2_check_checkboxes_for_date_range extends WebDriverTestCase {
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
    $this->byCssSelector("a[href=\"#tab_0\"]")->click();
    // clickElement
    $this->byCssSelector("#tab_0 .add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector(".new_interval_form i.icon-calendar")->click();
    // clickElement
    $this->byCssSelector("td.ui-datepicker-days-cell-over.ui-datepicker-today a")->click();
    // storeElementValue
    $test->start_date = $test->byName("start_date")->value();
    // storeElementValue
    $test->end_date = $test->byName("end_date")->value();
    // assertElementValue
    $test->assertEquals($test->start_date, $test->byName("end_date")->value());
    // clickElement
    $this->byCssSelector("a.btn.cancel_add_interval")->click();
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
