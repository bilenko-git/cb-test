<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class Do_you_charge_a_clean_up_fee extends WebDriverTestCase {
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
        $test->assertEquals("http://wwwdev3.ondeficar.com/connect/366#/dashboard", $test->url());
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byName("arates")->click();
    // clickElement
      $this->byCssSelector("a[href='#/roomRates']")->click();
    // waitForElementAttribute
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("roomRates", $test->byId("layout")->attribute("data-current_view"));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementSelected
    $element = $this->byId("radio-33");
    if (!$element->selected()) {
      $element->click();
    }
    // setElementText
    $element = $this->byName("charge_clean_up_room");
    $element->click();
    $element->clear();
    $element->value("40");
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
    // waitForElementValue
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("Y", $test->byCssSelector("span.checked input")->value());
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
  }
}
