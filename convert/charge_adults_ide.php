<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class charge_adults_ide extends WebDriverTestCase {
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
    $this->byCssSelector("a[name=\"arates\"]")->click();
    // clickElement
    $this->byCssSelector("#sroomRates > a")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("roomRates", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
        $this->byName("asettings")->click();
        // clickElement
        $this->byCssSelector("#saccommodations > a")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("roomTypes", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals("roomTypes", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
    // clickElement
    $this->byCssSelector("li.base.active > a")->click();
    // storeEval
    $test->room_type_adults_inBasePrice = $test->execute(array('script' => "return js=window.$('select[name=room_type_adults_inBasePrice]').val()", 'args' => array()));
    // storeEval
    $test->room_type_children_inBasePrice = $test->execute(array('script' => "return js=window.$('select[name=room_type_children_inBasePrice]').val()", 'args' => array()));
    // storeEval
    $test->room_type_max_guests = $test->execute(array('script' => "return js=window.$('select[name=room_type_max_guests]').val()", 'args' => array()));
    // print
    print $test->room_type_adults_inBasePrice;
    // print
    print $test->room_type_children_inBasePrice;
    // clickElement
    $this->byCssSelector("a[name=\"arates\"]")->click();
    // clickElement
    $this->byCssSelector("#sroomRates > a")->click();
    // assertEval
    $test->assertEquals("roomRates", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
    // storeEval
    $test->charge_additional = $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array()));
    // print
    print $test->charge_additional;
    // clickElement
    $this->byCssSelector("input[name^=\"charge_additional\"][value=\"N\"]")->click();
    // assertEval
    $test->assertEquals("N", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
    // clickElement
    $this->byCssSelector("input[name^=\"charge_additional\"][value=\"Y\"]")->click();
    // assertEval
    $test->assertEquals("Y", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("saved", $test->execute(array('script' => "return js=window.$('.savingMsg').attr('data-qe-id')", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // storeEval
    $test->countAdults = $test->execute(array('script' => "return js=window.$('#layout .tab-pane.active .adults .control-group:not(.clonable)').length", 'args' => array()));
    // storeEval
    $test->countKids = $test->execute(array('script' => "return js=window.$('#layout .tab-pane.active .kids .control-group:not(.clonable)').length", 'args' => array()));
    // print
    print $test->countAdults;
    // print
    print $test->countKids;
    // assertEval
    $test->assertEquals($test->countAdults, $test->execute(array('script' => "return " . $test->room_type_max_guests . " - " . $test->room_type_adults_inBasePrice. "", 'args' => array())));
    // assertEval
    $test->assertEquals($test->countKids, $test->execute(array('script' => "return " . $test->room_type_max_guests . " - " . $test->room_type_children_inBasePrice, "", 'args' => array())));
    // refresh
    $this->refresh();
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);

        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('body > .progress-bar-background:visible').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("N", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals("N", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
    // assertElementPresent
    try {
      $boolean = ($test->byCssSelector("#layout .tab-pane.active .charge_additional.hide") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
      $boolean = false;
    }
    $test->assertTrue($boolean);
    // assertEval
    $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .tab-pane.active .adults .control-group:visible').length", 'args' => array())));
    // assertEval
    $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .tab-pane.active .kids .control-group:visible').length", 'args' => array())));
    // clickElement
        $this->byName("asettings")->click();
        // clickElement
        $this->byCssSelector("#saccommodations > a")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("roomTypes", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals("roomTypes", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
    // clickElement
    $this->byCssSelector("li.base.active > a")->click();
    // setElementSelected
        $element = $this->byCssSelector("[name='room_type_max_guests'] [value='3']");
        if (!$element->selected()) {
            $element->click();
        }
        // setElementSelected
        $element = $this->byCssSelector("[name='room_type_adults_inBasePrice'] [value='1']");
        if (!$element->selected()) {
            $element->click();
        }
        // setElementSelected
        $element = $this->byCssSelector("[name='room_type_children_inBasePrice'] [value='1']");
        if (!$element->selected()) {
            $element->click();
        }
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
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
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);

        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('body > .progress-bar-background:visible').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // clickElement
    $this->byCssSelector("a[name=\"arates\"]")->click();
    // clickElement
    $this->byCssSelector("#sroomRates > a")->click();
    // assertEval
    $test->assertEquals("roomRates", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
    // clickElement
    $this->byCssSelector("input[name^=\"charge_additional\"][value=\"Y\"]")->click();
    // assertEval
    $test->assertEquals("Y", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
    // storeEval
    $test->countAdults = $test->execute(array('script' => "return js=window.$('#layout .tab-pane.active .adults .control-group:not(.clonable)').length", 'args' => array()));
    // storeEval
    $test->countKids = $test->execute(array('script' => "return js=window.$('#layout .tab-pane.active .kids .control-group:not(.clonable)').length", 'args' => array()));
    // print
    print $test->countAdults;
    // print
    print $test->countKids;
    // assertEval
    $test->assertEquals("2", $test->execute(array('script' => "return ". $test->countAdults . "", 'args' => array())));
    // assertEval
    $test->assertEquals("2", $test->execute(array('script' => "return ". $test->countKids."" , 'args' => array())));
    // storeEval
    $test->price_adult_1 = $test->execute(array('script' => "return js=window.BET.langs.formatCurrency(10)", 'args' => array()));
    // storeEval
    $test->price_adult_2 = $test->execute(array('script' => "return js=window.BET.langs.formatCurrency(20)", 'args' => array()));
    // storeEval
    $test->price_kid_1 = $test->execute(array('script' => "return js=window.BET.langs.formatCurrency(2)", 'args' => array()));
    // storeEval
    $test->price_kid_2 = $test->execute(array('script' => "return js=window.BET.langs.formatCurrency(4)", 'args' => array()));
    // setElementText
    $element = $this->byXPath("//div[@id='tab_0']/form/div[2]/div[2]/div/div[position() = 2]//input");
    $element->click();
    $element->clear();
    $element->value($test->price_adult_1);
    // storeEval
    $test->adult_1 = $test->execute(array('script' => "return js=window.$('.tab-pane.active .adults .control-group:not(.clonable):eq(0) input', '#layout').val();", 'args' => array()));
    // setElementText
        $element = $this->byXPath("//div[@id='tab_0']/form/div[2]/div[2]/div/div[position() = 3]//input");
    $element->click();
    $element->clear();
    $element->value($test->price_adult_2);
    // storeEval
    $test->adult_2 = $test->execute(array('script' => "return js=window.$('.tab-pane.active .adults .control-group:not(.clonable):eq(1) input', '#layout').val();", 'args' => array()));
    // setElementText
        $element = $this->byXPath("//div[@id='tab_0']/form/div[2]/div[2]/div[2]/div[position() = 2]//input");
    $element->click();
    $element->clear();
    $element->value($test->price_kid_1);
    // storeEval
    $test->kid_1 = $test->execute(array('script' => "return js=window.$('.tab-pane.active .kids .control-group:not(.clonable):eq(0) input', '#layout').val();", 'args' => array()));
    // setElementText
        $element = $this->byXPath("//div[@id='tab_0']/form/div[2]/div[2]/div[2]/div[position() = 3]//input");
    $element->click();
    $element->clear();
    $element->value($test->price_kid_2);
    // storeEval
    $test->kid_2 = $test->execute(array('script' => "return js=window.$('.tab-pane.active .kids .control-group:not(.clonable):eq(1) input', '#layout').val();", 'args' => array()));
    // print
    print $test->adult_1;
    // print
    print $test->adult_2;
    // print
    print $test->kid_1;
    // print
    print $test->kid_2;
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
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
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);

        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('body > .progress-bar-background:visible').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("Y", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals("Y", $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
    // assertEval
    $test->assertEquals($test->adult_1, $test->execute(array('script' => "return js=window.$('.tab-pane.active .adults .control-group:not(.clonable):eq(0) input').val()", 'args' => array())));
    // assertEval
    $test->assertEquals($test->adult_2, $test->execute(array('script' => "return js=window.$('.tab-pane.active .adults .control-group:not(.clonable):eq(1) input').val()", 'args' => array())));
    // assertEval
    $test->assertEquals($test->kid_1, $test->execute(array('script' => "return js=window.$('.tab-pane.active .kids .control-group:not(.clonable):eq(0) input').val()", 'args' => array())));
    // assertEval
    $test->assertEquals($test->kid_2, $test->execute(array('script' => "return js=window.$('.tab-pane.active .kids .control-group:not(.clonable):eq(1) input').val()", 'args' => array())));
    // refresh
    $this->refresh();
    // waitForEval
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);

        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('body > .progress-bar-background:visible').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // clickElement
        $this->byName("asettings")->click();
        // clickElement
        $this->byCssSelector("#saccommodations > a")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("roomTypes", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals("roomTypes", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
    // clickElement
    $this->byCssSelector("li.base.active > a")->click();
    // setElementSelected
    $element = $this->byCssSelector("[name='room_type_max_guests'] [value='".$this->room_type_max_guests. "']");
    if (!$element->selected()) {
      $element->click();
    }
    // setElementSelected
        $element = $this->byCssSelector("[name='room_type_adults_inBasePrice'] [value='".$this->room_type_adults_inBasePrice. "']");
    if (!$element->selected()) {
      $element->click();
    }
    // setElementSelected
        $element = $this->byCssSelector("[name='room_type_children_inBasePrice'] [value='".$this->room_type_children_inBasePrice. "']");
    if (!$element->selected()) {
      $element->click();
    }
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
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
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byId("main_menu") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);

        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('body > .progress-bar-background:visible').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // clickElement
    $this->byCssSelector("a[name=\"arates\"]")->click();
    // clickElement
    $this->byCssSelector("#sroomRates > a")->click();
    // assertEval
    $test->assertEquals("roomRates", $test->execute(array('script' => "return js=window.BET.globals.currentView", 'args' => array())));
    // print
    print $test->charge_additional;
    // clickElement
    $this->byCssSelector("input[name^=\"charge_additional\"][value=\"" . $test->charge_additional . "\"]")->click();
    // assertEval
    $test->assertEquals($test->charge_additional, $test->execute(array('script' => "return js=window.$('input[name^=\"charge_additional\"]:checked', '#layout').val()", 'args' => array())));
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("saved", $test->execute(array('script' => "return js=window.$('.savingMsg').attr('data-qe-id')", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
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
