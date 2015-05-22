<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class availability extends WebDriverTestCase {
    // run FF15 on Windows 8 on Sauce
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

        $this->byCssSelector("#snewreservations > a")->click();
    // clickElement
    $this->byId("new_reservation")->click();
    // clickElement
    $this->byId("find-rooms")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector(".table-available-rooms .room-types") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // storeEval
    $test->max_rooms = $test->execute(array('script' => "return js=window.$('#book-choosed-rooms .room-types:first .selected-rooms').data('max_rooms')", 'args' => array()));
    // print
    print $test->max_rooms;
    // setElementSelected
    $element = $this->byCssSelector("#book-choosed-rooms .room-types .selected-rooms [value='1']");
    if (!$element->selected()) {
      $element->click();
    }
    // clickElement
    $this->byId("reservation-book")->click();
    // setElementText
    $element = $this->byId("simple_r_first_name");
    $element->click();
    $element->clear();
    $element->value("test");
    // setElementText
    $element = $this->byId("simple_r_last_name");
    $element->click();
    $element->clear();
    $element->value("test");
    // setElementText
    $element = $this->byId("simple_r_email");
    $element->click();
    $element->clear();
    $element->value("test@bk.ru");
    // setElementSelected
    $element = $this->byCssSelector("#simple_r_source [value='1']");
    if (!$element->selected()) {
      $element->click();
    }
    // clickElement
    $this->byCssSelector(".payment_check_true_radio_label")->click();
    // clickElement
    $this->byCssSelector(".save-billing-info")->click();
    // clickElement
    $this->byCssSelector(".dont-send-reservation-confirmation-modal")->click();
    // waitForElementPresent

        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byCssSelector("#reservation-summary .user_name") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);
        print $test->byCssSelector("#reservation-summary .user_name")->text();

    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector(".reservation_number") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // clickElement
        $this->byCssSelector("#snewreservations > a")->click();
    // clickElement
    $this->byId("new_reservation")->click();
    // clickElement
    $this->byId("find-rooms")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector(".table-available-rooms .room-types") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // assertEval
    $test->assertEquals($test->max_rooms - 1, $test->execute(array('script' => "return js=window.$('#book-choosed-rooms .room-types:first .selected-rooms').data('max_rooms')", 'args' => array())));
    // goBack
    $this->back();

        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byCssSelector("#reservation-summary .user_name") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);


        // waitForElementPresent
        $this->waitUntil(function() use ($test) {
            try {
                $boolean = ($test->byCssSelector(".reservation_number") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
            } catch (\Exception $e) {
                $boolean = false;
            }
            return $boolean === true ?: null;
        },50000);
    // clickElement
    $this->byCssSelector(".status")->click();
    // clickElement
    $this->byCssSelector("a[data-status='no_show']")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("#confirm-send-email-modal") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // clickElement
    $this->byCssSelector("#confirm-send-email-modal .btn_ok.button-send-email")->click();
    // clickElement
        $this->byCssSelector("#snewreservations > a")->click();;
    // clickElement
    $this->byId("new_reservation")->click();
    // clickElement
    $this->byId("find-rooms")->click();
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector(".table-available-rooms .room-types") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // assertEval
    $test->assertEquals($test->max_rooms, $test->execute(array('script' => "return js=window.$('#book-choosed-rooms .room-types:first .selected-rooms').data('max_rooms')", 'args' => array())));
    // clickElement
        $this->byName("asettings")->click();
        // clickElement
        $this->byCssSelector("a.logout_link")->click();
  }
}
