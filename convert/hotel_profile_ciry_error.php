<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class add_interval_date_error extends test_restrict
{
    private $hotel_profile_url = 'http://{server}/connect/{property_id}#/profileHotel';

    public function testSteps()
    {
        $this->setupInfo('PMS_user');
        $this->loginToSite();

        $this->url($this->_prepareUrl($this->hotel_profile_url));
        $this->waitForLocation($this->_prepareUrl($this->hotel_profile_url));
        // clickElement
        $this->waitForElement("#layout #hotel_name", 15000, 'jQ');
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
