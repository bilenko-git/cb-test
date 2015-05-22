<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class comon_features extends WebDriverTestCase {
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
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
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
    // clickElement
    $this->byCssSelector("#layout .calendar-head .calendar-nav .prev")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .calendar-head .calendar-nav .next")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .calendar-head .calendar-nav .next")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout #current-calendar-date")->click();
    // clickElement
    $this->byXPath("//*[@id=\"layout\"]//*[@id=\"current-calendar-date\"]")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("#layout #calendar-datepicker table.ui-datepicker-calendar") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // storeText
    $test->newDate = $test->byXPath("//*[@id=\"layout\"]//*[@id=\"calendar-datepicker\"]//table[@class=\"ui-datepicker-calendar\"]//tr[4]//td[2]")->text();
    // clickElement
    $this->byXPath("//*[@id=\"layout\"]//*[@id=\"calendar-datepicker\"]//table[@class=\"ui-datepicker-calendar\"]//tr[4]//td[2]")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals($test->newDate, $test->execute(array('script' => "return js=window.$('#layout .calendar-wrapper .calendar-head th:eq(3) .day_name').text().split(' ').pop()", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout #assignments_btn")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .assignments-wrapper .assignments_close")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout #assignments_btn")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals(true, $test->execute(array('script' => "return js=window.$('#layout .calendar-wrapper .calendar-head th:eq(1)').hasClass('today')", 'args' => array())));
    // assertEval
    $test->assertEquals(true, $test->execute(array('script' => "return js=window.$('#layout .calendar-wrapper .calendar-head th:eq(1) .day_name').text().split(' ').pop() == window.$('#layout .assignments-wrapper #assignments-datepicker button span').text().split(',').shift().split(' ').pop()", 'args' => array())));
    // assertEval
    $test->assertEquals(true, $test->execute(array('script' => "return js=window.$('#layout .calendar-wrapper .calendar-head th:eq(1) .day_name').text().split(' ').shift() == window.$('#layout .assignments-wrapper #assignments-datepicker button span').text().split(',').shift().split(' ').shift()", 'args' => array())));
    // clickElement
    $this->byCssSelector("#layout .assignments-wrapper #assignments-datepicker button")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper #assignments-datepicker .ui-datepicker:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .assignments-wrapper #assignments-datepicker button")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper #assignments-datepicker .ui-datepicker:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .assignments-wrapper #assignments-datepicker button")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper #assignments-datepicker .ui-datepicker:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // storeText
    $test->newDate = $test->byXPath("//*[@id='layout']//*[@class='assignments-wrapper']//*[@id='assignments-datepicker']//table[@class=\"ui-datepicker-calendar\"]//tr[3]//td[2]")->text();
    // clickElement
    $this->byXPath("//*[@id='layout']//*[@class='assignments-wrapper']//*[@id='assignments-datepicker']//table[@class=\"ui-datepicker-calendar\"]//tr[3]//td[2]")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals($test->newDate, $test->execute(array('script' => "return js=window.$('#layout .calendar-wrapper .calendar-head th:eq(1) .day_name').text().split(' ').pop()", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // clickElement
    $this->byCssSelector("#layout .assignments-wrapper .assignments_close")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .loading:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .assignments-wrapper:visible').length", 'args' => array())));
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
