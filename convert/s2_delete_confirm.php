<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class s2_delete_confirm extends WebDriverTestCase {
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
    // store
    $test->hash = "roomRates";
    // clickElement
    $this->byCssSelector("a[href=\"#tab_0\"]")->click();
    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[1]")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // storeElementValue
    $test->interval_name = $test->byName("interval_name")->value();
    // storeElementValue
    $test->start_date = $test->byName("start_date")->value();
    // storeElementValue
    $test->end_date = $test->byName("end_date")->value();
    // storeElementValue
    $test->min_los = $test->byName("min_los")->value();
    // storeElementValue
    $test->max_los = $test->byName("max_los")->value();
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
    // clickElement
    $this->byCssSelector("#tab_0  .cancel_add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form.hide") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector("a.btn.add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
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
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
    // assertElementPresent
        $this->waitUntil(function() use ($test) {
    try {
      $boolean = ($test->byCssSelector("#rate_interval_overlapped") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
            return $boolean === true ?: null;
        },50000);
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector("#rate_interval_overlapped > div.modal-footer > a.btn.btn_no")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#rate_interval_overlapped.hide") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_0\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_1\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_2\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_3\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_4\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_5\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("td > input.week_days_text[name=\"day_6\"].valid") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector("#tab_0  .cancel_add_interval")->click();
    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[2]")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byId("confirm_delete") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byCssSelector("#confirm_delete > div.modal-footer > a.btn.btn_cancel")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#confirm_delete.hide") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[2]")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byId("confirm_delete") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // clickElement
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byCssSelector("#confirm_delete > div.modal-footer > a.btn.btn_delete") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);
    $this->byCssSelector("#confirm_delete > div.modal-footer > a.btn.btn_delete")->click();

        try {
            $boolean = ($test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
        } catch (\Exception $e) {
            $boolean = false;
        }

        if ($boolean) {
            // !assertText
            $test->assertNotEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());

        }  // clickElement
    $this->byName("acustomers")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byId("confirm_modal") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // clickElement
    $this->byXPath("//div[@id='confirm_modal']//a[.='Continue Without Saving']")->click();
    // waitForElementAttribute
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("customers", $test->byId("layout")->attribute("data-current_view"));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byName("arates")->click();
    // clickElement
    $this->byCssSelector("#sroomRates > a")->click();
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
    // refresh
    $this->refresh();
    // assertText
    $test->assertEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
    // clickElement
    $this->byXPath("//div[@id='tab_0']/form/div[6]/div/div[2]/table/tbody/tr[4]/td[7]/a[2]")->click();
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
    // !assertText
        try {
            $boolean = ($test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
        } catch (\Exception $e) {
            $boolean = false;
        }

        if ($boolean) {
            // !assertText
            $test->assertNotEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());

        }  // clickElement  // refresh
    $this->refresh();
    // !assertText
        try {
            $boolean = ($test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
        } catch (\Exception $e) {
            $boolean = false;
        }

        if ($boolean) {
            // !assertText
            $test->assertNotEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());

        }  // clickElement // clickElement
    $this->byCssSelector("a.btn.add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
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
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
    // assertText
    $test->assertEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
    // clickElement
    $this->byName("acustomers")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byId("confirm_modal") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // clickElement
    $this->byXPath("//div[@id='confirm_modal']//a[.='Continue Without Saving']")->click();
    // waitForElementAttribute
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("customers", $test->byId("layout")->attribute("data-current_view"));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byName("arates")->click();
    // clickElement
    $this->byCssSelector("#sroomRates > a")->click();
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
    // refresh
    $this->refresh();
    // !assertText
        try {
            $boolean = ($test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
        } catch (\Exception $e) {
            $boolean = false;
        }

        if ($boolean) {
            // !assertText
            $test->assertNotEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());

        }  // clickElement  // clickElement
    $this->byCssSelector("a.btn.add_interval")->click();
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#tab_0 .new_interval_form:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
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
    // clickElement
    $this->byCssSelector("#tab_0  .save_add_interval")->click();
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
    // print
    print $test->interval_name;
    // assertText
    $test->assertEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
    // refresh
    $this->refresh();
    // assertText
    $test->assertEquals($test->interval_name, $test->byCssSelector("#tab_0 .intervals-table tr:not(.clonable):not(.empty) .interval_name .interval_text")->text());
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
