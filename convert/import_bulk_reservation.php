<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

/**
 * Test restrictions:
 * - Should be at minimum 1 available room
 *
 */
class import_bulk_reservation extends test_restrict{
    private $import_bulk_reservation_url = 'http://{server}/connect/{property_id}#/import_bulk_reservation';

    public function testSteps() {


        $test = $this;
        //$this->setupInfo('wwwdev.ondeficar.com', 'selenium@test.com', '123qwe', 366);
        $this->setupInfo('PMS_user');
        $this->loginToSite();

        $this->startDate = date('Y-m-d', strtotime('+10 days'));
        $this->endDate = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

        $this->url($this->_prepareUrl($this->import_bulk_reservation_url));
        $this->waitForLocation($this->_prepareUrl($this->import_bulk_reservation_url));

        $this->waitForElement('.bulk-import', 15000, 'css');
        $el = $this->waitForElement('.remove_reservation', 15000, 'css');
        $el->click();
        $length = $this->execute(array('script' => "return window.$('.bulk-import-table table tr').length", 'args' => array()));
        $this->assertEquals($length, 0);
    }
}