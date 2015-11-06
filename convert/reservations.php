<?php
/**
 * User: Alex Manko
 * Date: 08.09.2015
 * Time: 16:09
 */

namespace MyProject\Tests;
require_once 'test_restrict.php';

class reservations extends test_restrict{
    private $reservationsUrl = 'http://{server}/reservas/{property_reserva_code}';

    public function testSteps() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        //check find
        $this->byCssSelector('.search-form a.btn.submit')->click();
        $this->waitForElement('.search-form.open');
        $this->byCssSelector('.search-form input.search-input')->click()->value('!@#$%^&*()~');
        $this->keysSpecial('enter');
        $this->waitForLocation($this->_prepareUrl($this->reservationsUrl));
        $this->assertEquals(1, 1);
    }
}