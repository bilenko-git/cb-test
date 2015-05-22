<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class calendar_change_price extends WebDriverTestCase {
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
    // storeEval
    $test->dateToday = $test->execute(array('script' => "return js=window.$('#layout table.calendar-head:visible th.today').data('date')", 'args' => array()));
    // storeEval
    $test->dateFuture = $test->execute(array('script' => "return js=window.$('#layout table.calendar-head:visible th.future:eq(3)').data('date')", 'args' => array()));
    // storeEval
    $test->roomType = $test->execute(array('script' => "return js=window.$('#layout tr.room_type:eq(1)').data('room_type')", 'args' => array()));
    // storeEval
    $test->date = $test->execute(array('script' => "return js='" . $test->dateToday . "'", 'args' => array()));
    // storeEval
    $test->price = $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ').pop()", 'args' => array()));
    // storeEval
    $test->newPrice ='1';
    // clickElement
    $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[4]/div/div[3]/a")->click();

        //*[@id="rt_903"]/td[4]/div/div[3]/a// waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .day_price input:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
        $element = $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[4]/div/div[3]/input");
        $element->click();
        $element->clear();
    $element->value($test->newPrice);
    // clickElement
    $this->byCssSelector("#layout .popover.change_price .cancel-change-price-popover")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ').pop()", 'args' => array())));
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
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ').pop()", 'args' => array())));
    // clickElement
        $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[4]/div/div[3]/a")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .day_price input:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
        $element =  $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[4]/div/div[3]/input");
    $element->click();
    $element->clear();
    $element->value($test->newPrice);
    // clickElement
    $this->byCssSelector("#layout .popover.change_price .confirm-change-price")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals($test->newPrice, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
    // assertEval
    $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .star:visible').length", 'args' => array())));
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
    // assertEval
    $test->assertEquals($test->newPrice, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
    // assertEval
    $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .star:visible').length", 'args' => array())));
    // clickElement
        $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[4]/div/div[3]/a")->click();
        // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .day_price input:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
        $element =  $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[4]/div/div[3]/input");
        $element->click();
    $element->clear();
    $element->value('0');
    // clickElement
    $this->byCssSelector("#layout .popover.change_price .confirm-change-price")->click();

    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ').pop()", 'args' => array())));
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
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ').pop()", 'args' => array())));
    // storeEval
    $test->date = $test->execute(array('script' => "return js='" . $test->dateFuture . "'", 'args' => array()));
    // storeEval
    $test->price = $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array()));
    // storeEval
    $test->newPrice = $test->execute(array('script' => "return js=" . $test->price . " + 1", 'args' => array()));
    // clickElement
        $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[8]/div/div[3]/a")->click();  // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .day_price input:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
        $element = $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[8]/div/div[3]/input");
        $element->click();
    $element->clear();
    $element->value($test->newPrice);
    // clickElement
    $this->byCssSelector("#layout .popover.change_price .cancel-change-price-popover")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
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
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
    // clickElement
        $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[8]/div/div[3]/a")->click();  // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .day_price input:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
        $element =  $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[8]/div/div[3]/input");
        $element->click();
    $element->clear();
    $element->value($test->newPrice);
    // clickElement
    $this->byCssSelector("#layout .popover.change_price .confirm-change-price")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals($test->newPrice, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
    // assertEval
    $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .star:visible').length", 'args' => array())));
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
    // assertEval
    $test->assertEquals($test->newPrice, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
    // assertEval
    $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .star:visible').length", 'args' => array())));
    // clickElement
        $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[8]/div/div[3]/a")->click();
         // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .day_price input:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // setElementText
        $element = $this->byXPath("//*[@id='rt_" . $test->roomType ."']/td[8]/div/div[3]/input");
        $element->click();
    $element->clear();
    $element->value('0');
    // clickElement
    $this->byCssSelector("#layout .popover.change_price .confirm-change-price")->click();
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("0", $test->execute(array('script' => "return js=window.$('#layout .popover.change_price:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // waitForEval
    $this->waitUntil(function() use ($test) {
      try {
        $test->assertEquals("1", $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link:visible').length", 'args' => array())));
      } catch(\Exception $e) {
        return null;
      }
      return true;
    },50000);
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
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
    // assertEval
    $test->assertEquals($test->price, $test->execute(array('script' => "return js=window.$('#layout tr#rt_" . $test->roomType . " td[data-date=" . $test->date . "] .change-price-link').text().split(' ')['0']", 'args' => array())));
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
