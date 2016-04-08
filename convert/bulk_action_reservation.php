<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class bulk_action_reservation extends base_rates{
    private $reservation_url = 'http://{server}/connect/{property_id}#/reservations';

    public function testSteps(){

        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->reservation_url));
        $this->waitForLocation($this->_prepareUrl($this->reservation_url));
        $this->waitForElement('.list_reservation_table', 15000, 'css');
        $this->waitForElement('.assign-text a:first', 15000, 'jQ')->click();
        $this->execute(array('script' => 'window.$("#layout .assign-text .assign_action:last").click(); return false;', 'args' => array()));
        $this->waitForElement('#validation_error', 15000, 'css');
        $this->waitForElement('#validation_error .btn_ok', 15000, 'css')->click();
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();
        $this->execute(array('script' => 'window.$("[id^=rs_quick_assign_]:eq(1)").click(); return false;', 'args' => array()));
    }

}
?>
