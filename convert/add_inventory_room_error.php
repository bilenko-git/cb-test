<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class add_inventory_room_error extends test_restrict{
    private $roomType_url = 'http://{server}/connect/{property_id}#/roomTypes';

    public function testSteps(){
        $br =  array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Windows 8.1',
            ));
        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->roomType_url));
        $this->waitForLocation($this->_prepareUrl($this->roomType_url));

        $el = $this->waitForElement("[name=room_type_capacity]", 15000, 'css');
        $el->clear();
        $el->value("10000");
        $this->waitForElement('.inventory_type', 15000, 'css')->click();
        $this->waitForElement('.modal-content', 15000, 'jQ');
        $this->waitForElement('.modal-content .btn_ok', 15000, 'jQ')->click();

        $el = $this->waitForElement("[name=room_type_max_rooms]", 15000, 'css');
        $el->clear();
        $el->value("10000");
        $this->waitForElement('.inventory_type', 15000, 'css')->click();
        $this->waitForElement('.modal-content', 15000, 'jQ');
        $this->waitForElement('.modal-content .btn_ok', 15000, 'jQ')->click();

        $el = $this->waitForElement("[name=room_type_bookable_limit]", 15000, 'css');
        $el->clear();
        $el->value("10000");
        $this->waitForElement('.inventory_type', 15000, 'css')->click();
        $this->waitForElement('.modal-content', 15000, 'jQ');
        $this->waitForElement('.modal-content .btn_ok', 15000, 'jQ')->click();
    }

}
?>
