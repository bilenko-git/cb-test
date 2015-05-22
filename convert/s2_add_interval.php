<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class s2_add_interval extends WebDriverTestCase {
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
    $this->byCssSelector(".nav.nav-tabs li.base > a")->click();
    // clickElement
    $this->byCssSelector("a.btn.add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertText
    $test->assertEquals("Add Interval", $test->byCssSelector("#tab_0 .new_interval_form h4")->text());
    // assertElementPresent
    try {
      $boolean = ($test->byName("interval_name") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byName("start_date") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byName("end_date") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byName("min_los") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byName("max_los") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_0\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_0\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_1\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_1\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_2\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_2\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_3\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_3\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_4\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_4\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_5\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_5\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("input.week_days_checkbox[name=\"day_6\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_6\"]") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // store
    $test->interval_name = "Selenium Interval 2017";
    // store
    $test->start_date = "05/01/2017";
    // store
    $test->end_date = "05/07/2017";
    // store
    $test->min_los = "2";
    // store
    $test->max_los = "3";
    // store
    $test->day_0 = "50.00";
    // store
    $test->day_1 = "31.00";
    // store
    $test->day_2 = "32.00";
    // store
    $test->day_3 = "33.00";
    // store
    $test->day_4 = "34.00";
    // store
    $test->day_5 = "35.00";
    // store
    $test->day_6 = "60.00";
    // setElementText
    $element = $this->byName("interval_name");
    $element->click();
    $element->clear();
    $element->value($test->interval_name);
    // setElementText
    $element = $this->byName("start_date");
    $element->click();
    $element->clear();
    $element->value($test->start_date);
    // setElementText
    $element = $this->byName("end_date");
    $element->click();
    $element->clear();
    $element->value($test->end_date);
    // setElementText
    $element = $this->byName("min_los");
    $element->click();
    $element->clear();
    $element->value($test->min_los);
    // setElementText
    $element = $this->byName("max_los");
    $element->click();
    $element->clear();
    $element->value($test->max_los);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_0\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_0);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_1\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_1);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_2\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_2);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_3\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_3);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_4\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_4);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_5\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_5);
    // setElementText
    $element = $this->byCssSelector("td > input.week_days_text[name=\"day_6\"]");
    $element->click();
    $element->clear();
    $element->value($test->day_6);
    // storeElementValue
    $test->day_0 = $test->byCssSelector("td > input.week_days_text[name=\"day_0\"]")->value();
    // storeElementValue
    $test->day_1 = $test->byCssSelector("td > input.week_days_text[name=\"day_1\"]")->value();
    // storeElementValue
    $test->day_2 = $test->byCssSelector("td > input.week_days_text[name=\"day_2\"]")->value();
    // storeElementValue
    $test->day_3 = $test->byCssSelector("td > input.week_days_text[name=\"day_3\"]")->value();
    // storeElementValue
    $test->day_4 = $test->byCssSelector("td > input.week_days_text[name=\"day_4\"]")->value();
    // storeElementValue
    $test->day_5 = $test->byCssSelector("td > input.week_days_text[name=\"day_5\"]")->value();
    // storeElementValue
    $test->day_6 = $test->byCssSelector("td > input.week_days_text[name=\"day_6\"]")->value();
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
    // assertTextPresent

    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[1]")->click();

    // print
    print $test->interval_name;
    // print
    print $test->min_los;
    // print
    print $test->max_los;
    // print
    print $test->day_0;
    // print
    print $test->day_1;
    // print
    print $test->day_2;
    // print
    print $test->day_3;
    // print
    print $test->day_4;
    // print
    print $test->day_5;
    // print
    print $test->day_6;
    // assertText
    $test->assertEquals("Edit Interval", $test->byCssSelector("#tab_0 .new_interval_form h4")->text());
    // assertElementValue
    $test->assertEquals($test->interval_name, $test->byName("interval_name")->value());
    // assertElementValue
    $test->assertEquals($test->start_date, $test->byName("start_date")->value());
    // assertElementValue
    $test->assertEquals($test->end_date, $test->byName("end_date")->value());
    // assertElementValue
    $test->assertEquals($test->min_los, $test->byName("min_los")->value());
    // assertElementValue
    $test->assertEquals($test->max_los, $test->byName("max_los")->value());
    // assertElementValue
    $test->assertEquals($test->day_0, $test->byCssSelector("td > input.week_days_text[name=\"day_0\"]")->value());
    // assertElementValue
    $test->assertEquals($test->day_1, $test->byCssSelector("td > input.week_days_text[name=\"day_1\"]")->value());
    // assertElementValue
    $test->assertEquals($test->day_2, $test->byCssSelector("td > input.week_days_text[name=\"day_2\"]")->value());
    // assertElementValue
    $test->assertEquals($test->day_3, $test->byCssSelector("td > input.week_days_text[name=\"day_3\"]")->value());
    // assertElementValue
    $test->assertEquals($test->day_4, $test->byCssSelector("td > input.week_days_text[name=\"day_4\"]")->value());
    // assertElementValue
    $test->assertEquals($test->day_5, $test->byCssSelector("td > input.week_days_text[name=\"day_5\"]")->value());
    // assertElementValue
    $test->assertEquals($test->day_6, $test->byCssSelector("td > input.week_days_text[name=\"day_6\"]")->value());
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form.hide") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
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
    // assertTextPresent

    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[2]/i")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byId("confirm_delete") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector("#confirm_delete > div.modal-footer > a.btn.btn_delete")->click();
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
    // !assertTextPresent

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
