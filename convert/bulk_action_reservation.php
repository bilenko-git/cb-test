<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class bulk_action_reservation extends base_rates{
    private $reservations_url = 'http://{server}/connect/{property_id}#/reservations';
    private $reservation_url = 'http://{server}/connect/{property_id}#/reservations/';

    public function testSteps(){

        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->reservations_url));
        $this->waitForLocation($this->_prepareUrl($this->reservations_url));

        // click action with no checked reservation
        $this->waitForElement('.list_reservation_table', 15000, 'css');
        $this->waitForElement('.assign-text a:first', 15000, 'jQ')->click();
        $this->execute(array('script' => 'window.$("#layout .assign-text .assign_action:last").click(); return false;', 'args' => array()));
        $this->waitForElement('#validation_error', 15000, 'css');
        $this->waitForElement('#validation_error .btn_ok', 15000, 'css')->click();

        // delete last reservation
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();
        $this->execute(array('script' => 'window.$("[id^=rs_quick_assign_]:eq(1)").click(); return false;', 'args' => array()));

        $id = $this->execute(array('script' => 'return window.$("#layout .reservations-table tbody tr:first .view_summary").data("id");', 'args' => array()));

        $this->waitForElement('.assign-text a:first', 15000, 'jQ')->click();
        $this->execute(array('script' => 'window.$("#layout .assign-text .assign_action:last").click(); return false;', 'args' => array()));
        $this->waitForElement('#assign-actions-modal .btn-action', 15000, 'jQ')->click();
        $this->url($this->_prepareUrl($this->reservation_url.$id));
        $this->waitForElement('#validation_error', 15000, 'jQ');
        $this->waitForElement('#validation_error .btn_ok', 15000, 'jQ')->click();

        // add payment to last reservation
        // add  custom payment to last reservation

        $this->execute(array('script' => 'window.$("[id^=rs_quick_assign_]:eq(1)").click(); return false;', 'args' => array()));

        $id = $this->execute(array('script' => 'return window.$("#layout .reservations-table tbody tr:first .view_summary").data("id");', 'args' => array()));

        $this->waitForElement('.assign-text a:first', 15000, 'jQ')->click();
        $this->execute(array('script' => 'window.$("#layout .assign-text .assign_action:eq(2)").click(); return false;', 'args' => array()));
        $el = $this->waitForElement('#assign-actions-modal #as_payment_amount', 15000, 'jQ');
        $el->clear();
        $el->value('1');
        $this->waitForElement('#assign-actions-modal .btn-action', 15000, 'jQ')->click();
        $this->url($this->_prepareUrl($this->reservation_url.$id));
        $this->waitForElement('#reservation-tabs li:eq(1)', 15000, 'jQ')->click();
        $this->betLoaderWaiting();
        $val = $this->waitForElement('.rs-transactions-table tbody tr:first .credit', 15000, 'jQ')->text();
        $this->assertEquals(1, $val);
        // add full payment to last reservation

        $this->url($this->_prepareUrl($this->reservations_url));
        $this->waitForLocation($this->_prepareUrl($this->reservations_url));

        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();

        $this->execute(array('script' => 'window.$("[id^=rs_quick_assign_]:eq(1)").click(); return false;', 'args' => array()));

        $id = $this->execute(array('script' => 'return window.$("#layout .reservations-table tbody tr:first .view_summary").data("id");', 'args' => array()));

        $this->waitForElement('.assign-text a:first', 15000, 'jQ')->click();
        $this->execute(array('script' => 'window.$("#layout .assign-text .assign_action:eq(2)").click(); return false;', 'args' => array()));
        $this->waitForElement('#assign-actions-modal #as_payment_amount', 15000, 'jQ');

        $element = $this->waitForElement("#assign-actions-modal #as_payment_amount_type option:eq(1)", 15000, 'jQ');
        $element->selected();
        $element->click();

        $amount = $this->waitForElement("#assign-actions-modal .modal_res_table tbody tr:first td:eq(3) span", 15000, 'jQ')->text();
        $amount = $this->execute(array('script' => 'return window.BET.langs.parseCurrency("'.$amount.'");', 'args' => array()));
        if ((int)$amount !== 0) {
            $this->waitForElement('#assign-actions-modal .btn-action', 15000, 'jQ')->click();
            $this->url($this->_prepareUrl($this->reservation_url . $id));
            $this->waitForElement('#reservation-tabs li:eq(1)', 15000, 'jQ')->click();
            $this->betLoaderWaiting();
            $val = $this->waitForElement('.rs-transactions-table tbody tr:first .credit', 15000, 'jQ')->text();
            $this->assertEquals($amount, $val);
        }
    }
}
?>
