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
        $el = $this->waitForElement('.add-reserva-btn', 15000, 'css');
        $el->click();
        $this->waitForElement('.bulk-import-table table tr', 15000, 'css');

        $el = $this->waitForElement('[name^=first_name_]', 15000, 'css');
        $el->value('test');
        $el = $this->waitForElement('[name^=last_name_]', 15000, 'css');
        $el->value('name');
        $el = $this->waitForElement('[name^=email_]', 15000, 'css');
        $el->value('test@gmail.com');
        $el = $this->waitForElement('[name^=checkin_date_]', 15000, 'css');
        $el->value($this->startDate);
        $el = $this->waitForElement('[name^=checkout_date_]', 15000, 'css');
        $el->value($this->endDate);
        $el = $this->waitForElement('[name^=room_rate_]', 15000, 'css');
        $el->clear();
        $el->value('10');
        $element = $this->waitForElement("[name^=room-type_] option:eq(1)", 15000, 'jQ');
        $element->selected();
        $element->click();
        $element = $this->waitForElement("[name^=adults_] option:eq(1)", 15000, 'jQ');
        $element->selected();
        $element->click();
        $element = $this->waitForElement("[name^=children_] option:eq(1)", 15000, 'jQ');
        $element->selected();
        $element->click();
        $element = $this->waitForElement("[name^=source_] option:eq(3)", 15000, 'jQ');
        $element->selected();
        $element->click();
        $element = $this->waitForElement("[name^=status_] option:eq(3)", 15000, 'jQ');
        $element->selected();
        $element->click();
        $element = $this->waitForElement("[name^=country_] option:eq(3)", 15000, 'jQ');
        $element->selected();
        $element->click();

        $this->save();
    }
}