<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class Do_you_charge_for_additional_adults_and_or_children extends WebDriverTestCase {
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
       /* array(
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
    // clickElement
    $this->byName("arates")->click();
    // clickElement
      $this->byCssSelector("#main_menu #sroomRates a")->click();
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
    // setElementSelected
    $element = $this->byCssSelector("input[name='charge_additional?tab_1'][value='Y']");
    if (!$element->selected()) {
      $element->click();
    }
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector(".charge_additional") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // setElementText
    $element = $this->byXPath("//div[@id='tab_1']/form/div[2]/div[2]/div[1]/div[2]/div/input");
    $element->click();
    $element->clear();
    $element->value("10");
    // setElementText
    $element = $this->byXPath("//div[@id='tab_1']/form/div[2]/div[2]/div[2]/div[3]/div/input");
    $element->click();
    $element->clear();
    $element->value("99");
    // setElementText
    $element = $this->byXPath("//div[@id='tab_1']/form/div[2]/div[2]/div[2]/div[2]/div/input");
    $element->click();
    $element->clear();
    $element->value("10");
    // setElementText
    $element = $this->byXPath("//div[@id='tab_1']/form/div[2]/div[2]/div[1]/div[3]/div/input");
    $element->click();
    $element->clear();
    $element->value("98");
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
