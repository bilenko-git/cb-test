<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class upload_photo extends WebDriverTestCase {
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
    // clickElement
    $this->byName("asettings")->click();
    // clickElement
    $this->byCssSelector("#saccommodations > a")->click();
    // clickElement
   // clickElement
    $this->byCssSelector(".room-type-featured-file-uploader-clonable")->click();
        $this->byCssSelector(".dropzone.dz-clickable")->click();

    // setElementText
    $element = $this->byCssSelector("body input[type=file]:last");
    $element->click();
    $element->value("/home/philipp/Рабочий стол/145.jpg");
    // waitForElementPresent
    $this->waitUntil(function() use ($test) {
      try {
        $boolean = ($test->byCssSelector("#drop_zone_modal  .step_2 .done:not(.hide)") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
      } catch (\Exception $e) {
        $boolean = false;
      }
      return $boolean === true ?: null;
    },50000);
    // clickElement
    $this->byCssSelector("#drop_zone_modal  .step_2 .done")->click();
    // clickElement
    $this->byCssSelector(".save-uploader")->click();
    // clickElement
    $this->byCssSelector(".pull-line-right > .btn.green")->click();
        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("Saved", $test->byCssSelector(".savingMsg")->text());
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // refresh
    $this->refresh();
    // assertAlertPresent
        try {
            $boolean = ($test->byCssSelector(".featured_photo_container .img_upload") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
        } catch (\Exception $e) {
            $boolean = false;
        }
        $test->assertTrue($boolean);

        $this->byCssSelector(".featured_photo_container .deleteimg")->click();
    // clickElement
    $this->byCssSelector(".pull-line-right > .btn.green")->click();
        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("Saved", $test->byCssSelector(".savingMsg")->text());
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    // clickElement
    $this->byName("asettings")->click();
    // clickElement
        $this->byCssSelector("a.logout_link")->click();
  }
}
