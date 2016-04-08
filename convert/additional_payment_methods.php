<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class additional_payment_methods extends test_restrict
{
    private $paymentOptions_url = 'http://{server}/connect/{property_id}#/paymentOptions';
    private $reservation_url = 'http://{server}/connect/{property_id}#/newreservations';
    private $house_account_url = 'http://{server}/connect/{property_id}#/house_accounts';

    private $payment_method_name = '';

    public function testSteps() {
       // $this->setupInfo('wwwdev3.ondeficar.com', '', '', 366);
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->prepare_data();
    }

    private function prepare_data() {
        $this->add_additional_payment_method();
        $this->assertEquals($this->payment_method_name, $this->add_payment_on_reservation());
        $this->assertEquals($this->payment_method_name, $this->add_payment_on_house_accounts());
        $this->delete_additional_payment_method();
        $this->assertNotEquals($this->payment_method_name, $this->checking_editing_method_on_reservation());
        $this->assertNotEquals($this->payment_method_name, $this->checking_editing_method_on_house_account());
    }

    private function add_additional_payment_method() {
        $this->url($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForLocation($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForElement('#layout .payment-options-tabs', 15000, 'css');
        $this->waitForElement('#layout .payment-options-tabs li:eq(3) a', 15000, 'jQ')->click();
        $this->waitForElement('#layout .add_payment_methods', 15000, 'css')->click();
        //$this->byJQ('.modal_additional_payment_methods #new_payment_method_name')->value($this->payment_method_name['name']);
        $this->waitForElement('.modal_additional_payment_methods .modal-footer .btn-primary', 15000, 'jQ')->click();

        $this->betLoaderWaiting();

        $this->waitForElement('#layout .table_additional_payment_methods tbody tr', 15000, 'css');
        $this->payment_method_name =  $this->byJQ('#layout .table_additional_payment_methods tbody tr:last .name_method')->text();

      /*  if ($record == $this->payment_method_name['name']) {

        } else {
            $this->setExpectedException('Method is not added!');
        }*/
    }

    private function add_payment_on_reservation() {
        $this->url($this->_prepareUrl($this->reservation_url));
        $this->waitForLocation($this->_prepareUrl($this->reservation_url));
        $this->waitForElement('.view_summary:visible:first', 15000, 'jQ')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('[href=\'#rs-folio-tab\']', 5000, 'jQ', true)->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.add-payment-btn', 5000, 'jQ', true)->click();

/*        $this->byJQ('#layout .payments_block:eq(0) .booking-payments-add-form tr .ch_input');*/

        $element = $this->waitForElement("#add-new-payment-modal [name='payment_type'] option:last", 15000, 'jQ');
        $element->selected();
        $element->click();
        $record = $element->text();

        $element = $this->waitForElement("#add-new-payment-modal select.payment_inputs.payment_base option:eq(0)", 15000, 'jQ');
        $element->selected();
        $element->click();
        $this->waitForElement("#add-new-payment-modal [name='paid']", 15000, 'jQ')->clear();
        $this->waitForElement("#add-new-payment-modal [name='paid']", 15000, 'jQ')->value(11);
        $this->waitForElement('#add-new-payment-modal .add-new-payment-usual', 15000, 'jQ')->click();

        $this->betLoaderWaiting();
       // $this->waitForElement('#confirm_modal .btn_ok', 15000, 'jQ')->click();
        //$this->byJQ('#open-cash-drawer-prepare #proceed-without-opening-btn', 15000, 'css')->click();

        //$this->waitForElement('#layout rs-transactions-table td:eq(3)', 15000, 'jQ');
        //$this->waitForElement('#layout .rs-transactions-table tr:first td:eq(4)', 15000, 'jQ')->text();
        return $record;
    }

    private function add_payment_on_house_accounts() {
        $this->url($this->_prepareUrl($this->house_account_url));
        $this->waitForLocation($this->_prepareUrl($this->house_account_url));
        $this->waitForElement('.view_details:visible:last', 15000, 'jQ')->click();
      //  $this->waitForElement('#layout .add-payment-btn', 15000, 'css');
        $this->waitForElement('#layout .add-payment-btn', 15000, 'css')->click();

        $this->waitForElement("#layout .house_account #ha_payment_box .portlet-body [name='payment_type'] option:last", 15000, 'jQ');
        $element = $this->waitForElement("#layout .house_account #ha_payment_box .portlet-body [name='payment_type'] option:last", 1500, 'jQ');
        $element->selected();
        $element->click();

        $paid = $this->waitForElement("#layout .house_account #ha_payment_box .autoCurrency", 1500, 'jQ');
        $paid->clear();
        $paid->value('14');

        $this->execute(array('script' => 'window.$("#ha_payment_box .save_ha_payment").click(); return false;', 'args' => array()));
        $this->betLoaderWaiting();
        $this->refresh();
        $this->url($this->_prepareUrl($this->house_account_url));
        $this->waitForLocation($this->_prepareUrl($this->house_account_url));
        $this->betLoaderWaiting();
        $this->waitForElement('#layout .view_details:visible:last', 15000, 'jQ')->click();
        $this->betLoaderWaiting();
        //$this->byJQ('#open-cash-drawer-prepare #proceed-without-opening-btn', 15000, 'css')->click();

        $record = $this->waitForElement("#layout #house_accounts_folio .house-account-transactions tbody .payment:last td:eq(2)", 20000, 'jQ')->text();
        return $record;
    }

    private function delete_additional_payment_method() {
        $this->url($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForLocation($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForElement('#layout .payment-options-tabs', 15000, 'css');
        $this->waitForElement('#layout .payment-options-tabs li:eq(3) a', 15000, 'jQ')->click();

        $this->assertEquals($this->payment_method_name,$this->waitForElement('.name_method:visible:last', 1500, 'jQ')->text());
        $this->waitForElement('.table_additional_payment_methods tr:visible:last td:last i', 15000, 'jQ')->click();
        $this->waitForElement('#modal_additional_payment_method_delete', 15000, 'jQ');
        $this->waitForElement('#modal_additional_payment_method_delete .btn_delete', 15000, 'jQ')->click();
        $this->betLoaderWaiting();
       /* if ($name_method == $this->payment_method_name['edit_name']) {
            $this->checking_editing_method_on_reservation();
        }*/
    }

    private function checking_editing_method_on_reservation() {
        $this->url($this->_prepareUrl($this->reservation_url));
        $this->waitForLocation($this->_prepareUrl($this->reservation_url));
        $this->waitForElement('.view_summary:visible:first', 15000, 'jQ')->click();
        $this->waitForElement('#layout #reservation-summary', 15000, 'jQ');
        $this->betLoaderWaiting();
        $this->waitForElement('[href=\'#rs-folio-tab\']', 5000, 'jQ', true)->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.add-payment-btn', 5000, 'jQ', true)->click();
        $record = $this->waitForElement("#add-new-payment-modal [name='payment_type'] option:last", 15000, 'jQ')->text();
        $this->waitForElement("#add-new-payment-modal .default", 15000, 'jQ')->click();
        $this->waitForElement('#layout .rs-transactions-table tr:first td:last .show-popup-vs-dropdown', 15000, 'jQ')->click();
        $this->waitForElement('.void-transaction', 15000, 'jQ')->click();
        $this->waitForElement('.button-void-payment', 15000, 'jQ')->click();

        $this->betLoaderWaiting();
        return $record;
    }

    private function checking_editing_method_on_house_account() {
        $this->url($this->_prepareUrl($this->house_account_url));
        $this->waitForLocation($this->_prepareUrl($this->house_account_url));
        $this->waitForElement('.view_details:visible:last', 15000, 'jQ')->click();
        $this->waitForElement('#layout .add-payment-btn', 15000, 'css')->click();

        $record = $this->waitForElement("#layout .house_account #ha_payment_box .portlet-body [name='payment_type'] option:last", 15000, 'jQ')->text();
        return $record;
    }
/*
    private function delete_additional_payment_method() {
        $this->url($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForLocation($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForElement('#layout .payment-options-tabs', 15000, 'css');

        $this->byJQ('#layout .table_additional_payment_methods tbody tr:eq(0) .del_add_payment_methods', 15000, 'css')->click();
        $this->byJQ('#modal_additional_payment_method_delete .modal-footer .btn_delete')->click();
    }*/

    private function delete_payment_on_reservation() {
        /*
        $this->byJQ('#layout .payments_block:eq(0) .booking-payments-table tr:last .void-booking-payment', 15000, 'css')->click();
        $this->byJQ('#void-modal .button-void-payment', 15000, 'css')->click();
        */
    }
}
?>
