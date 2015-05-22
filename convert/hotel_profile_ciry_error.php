<?php

namespace MyProject\Tests;

use Sauce\Sausage\WebDriverTestCase;

class hotel_profile_ciry_error extends WebDriverTestCase {
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
    $this->byCssSelector("#ssettings > a")->click();
    // clickElement
    $this->byCssSelector("#sprofile1 > a")->click();
    // clickElement
    $this->byLinkText("Hotel Profile")->click();
    // clickElement
    $this->byLinkText("Property Profile")->click();
    // setElementText
    $element = $this->byId("hotel_name");
    $element->click();
    $element->clear();
    $element->value("SeleniumTest Hotel");
    // setElementText
    $element = $this->byId("hotel_phone");
    $element->click();
    $element->clear();
    $element->value("(111)555-8899");
    // setElementText
    $element = $this->byId("hotel_contact_name");
    $element->click();
    $element->clear();
    $element->value("Selenium hotelier");
    // setElementText
    $element = $this->byId("hotel_address1");
    $element->click();
    $element->clear();
    $element->value("1 selenium st. Good City");
    // setElementText
    $element = $this->byId("hotel_address_city");
    $element->click();
    $element->clear();
    $element->value("C");
    // setElementText
    $element = $this->byId("hotel_address_zip");
    $element->click();
    $element->clear();
    $element->value("123789");
    // clickElement
    $this->byXPath("//div[@class='pull-line-right']//a[.=' Save']")->click();
    // waitForText

        $this->waitUntil(function() use ($test) {
    try {
        $boolean = ($test->byCssSelector(".validation_error[aria-hidden='false']") instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
    } catch (\Exception $e) {
        $boolean = false;
    }
            return true;
        },50000);
        $test->assertTrue($boolean);
    // clickElement
    }
}
