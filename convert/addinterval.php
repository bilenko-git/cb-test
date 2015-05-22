<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class add_interval extends WebDriverTestCase {
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
    $this->byXPath("//*[@id='srates']/a")->click();
    // clickElement
    $this->byXPath("//*[@id='sroomRates']/a")->click();
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
    // clickElement
    $this->byCssSelector("#tab_1 .add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_1 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector("#tab_1  .cancel_add_interval")->click();
  }
}
