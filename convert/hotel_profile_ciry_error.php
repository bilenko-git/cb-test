<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class add_interval_date_error extends test_restrict
{
    private $roomrate_url = 'http://{server}/connect/{property_id}#/roomRates';

    public function testSteps()
    {
        $br = array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Windows 8.1',
            ));
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        // waitForEval
        // clickElement
        $this->byName("arates")->click();
        // clickElement
        $this->byCssSelector("#main_menu #sroomRates a")->click();
        // waitForElementAttribute
        $this->url($this->_prepareUrl($this->roomrate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomrate_url));
        // clickElement
        $this->byCssSelector("#ssettings > a")->click();
        // clickElement
        $this->url("http://wwwdev.ondeficar.com/connect/366#/profileHotel");
        //$this->byCssSelector("#sprofile1 > a")->click();
        // clickElement
        $this->byCssSelector("a[data-tutorial='propertyProfile']")->click();
        // clickElement
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
        $this->save();
        $this->waitForElement("#error_modal", 15000, 'css');
        $this->waitForElement("#error_modal .default", 15000, 'css')->click();

        $element = $this->byId("hotel_address_city");
        $element->click();
        $element->clear();
        $element->value("Cccc");

        $this->save();
    }
}
