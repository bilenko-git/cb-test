<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class additional_payment_methods extends test_restrict
{
    private $paymentOptions_url = 'http://{server}/connect/{property_id}#/paymentOptions';
    private $reservation_url = 'http://{server}/connect/{property_id}#/newreservations/7354';
    private $house_account_url = 'http://{server}/connect/{property_id}#/house_accounts/46';

    private $payment_method_name = array(
        'name' => 'new_method',
        'edit_name' => 'new_method_edit'
    );

    public function testSteps() {
       // $this->setupInfo('wwwdev3.ondeficar.com', '', '', 366);
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->prepare_data();
    }

    private function prepare_data() {
        $this->add_additional_payment_method();
    }

    private function add_additional_payment_method() {
        $this->url($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForLocation($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForElement('#layout .payment-options-tabs', 15000, 'css');
        $this->byJQ('#layout .payment-options-tabs li:eq(3) a', 15000, 'css')->click();
        $this->waitForElement('#layout .add_payment_methods', 15000, 'css')->click();
        $this->byJQ('.modal_additional_payment_methods #new_payment_method_name')->value($this->payment_method_name['name']);
        $this->byJQ('.modal_additional_payment_methods .modal-footer .btn-primary')->click();

        $this->waitForElement('#layout .table_additional_payment_methods tbody tr', 15000, 'css');
        $record =  $this->byJQ('#layout .table_additional_payment_methods tbody tr:last() .name_method')->text();

        if ($record == $this->payment_method_name['name']) {
            $this->add_payment_on_reservation();
        } else {
            $this->setExpectedException('Method is not added!');
        }
    }

    private function add_payment_on_reservation() {
        $this->url($this->_prepareUrl($this->reservation_url));
        $this->waitForLocation($this->_prepareUrl($this->reservation_url));
        $this->waitForElement('#layout .payments_block .btn-add-payment', 15000, 'css');
        $this->byJQ('#layout .payments_block:eq(0) .btn-add-payment', 15000, 'css')->click();

        $this->byJQ('#layout .payments_block:eq(0) .booking-payments-add-form tr .ch_input', 15000, 'css');

        $element = $this->byJQ("#layout .payments_block .booking-payments-add-form tr [name='payment_type'] option:last()");
        $element->selected();
        $element->click();

        $this->byJQ("#layout .payments_block:eq(0) .booking-payments-add-form tr .autoCurrency")->value(11);
        $this->byJQ('#layout .btn-save-payment:eq(0)', 15000, 'css')->click();
        $this->byJQ('#confirm_modal .btn_ok', 15000, 'css')->click();
        //$this->byJQ('#open-cash-drawer-prepare #proceed-without-opening-btn', 15000, 'css')->click();

        $this->byJQ('#layout #reservation-summary .payments_block .booking-payments-table tbody tr:last() .payment-type', 15000, 'css');
        $record = $this->byJQ('#layout #reservation-summary .payments_block .booking-payments-table tbody tr:last() .payment-type')->text();

        if ($record == $this->payment_method_name['name']) {
            $this->add_payment_on_house_accounts();
        } else {
            $this->setExpectedException('Payment failed!');
        }
    }

    private function add_payment_on_house_accounts() {
        $this->url($this->_prepareUrl($this->house_account_url));
        $this->waitForLocation($this->_prepareUrl($this->house_account_url));
        $this->waitForElement('#layout .add-payment-btn', 15000, 'css');
        $this->waitForElement('#layout .add-payment-btn', 15000, 'css')->click();

        $this->byJQ("#layout .house_account #ha_payment_box .portlet-body [name='payment_type'] option:last()", 15000, 'css');
        $element = $this->byJQ("#layout .house_account #ha_payment_box .portlet-body [name='payment_type'] option:last()");
        $element->selected();
        $element->click();

        $paid = $this->byJQ("#layout .house_account #ha_payment_box .autoCurrency");
        $paid->clear();
        $paid->value('14');

        $this->byJQ('#layout .save_ha_payment:eq(0)', 15000, 'css')->click();
        //$this->byJQ('#open-cash-drawer-prepare #proceed-without-opening-btn', 15000, 'css')->click();

        $this->byJQ("#layout #house_accounts_folio .house-account-transactions tbody .payment:last() td:eq(2)", 20000, 'css');
        $record = $this->byJQ("#layout #house_accounts_folio .house-account-transactions tbody .payment:last() td:eq(2)")->text();

        if ($record == $this->payment_method_name['name']) {
            $this->edit_additional_payment_method();
        } else {
            $this->setExpectedException('Payment failed for house account! ('.$record.')');
        }
    }

    private function edit_additional_payment_method() {
        $this->url($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForLocation($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForElement('#layout .payment-options-tabs', 15000, 'css');
        $this->byJQ('#layout .payment-options-tabs li:eq(3) a', 15000, 'css')->click();

        $this->byJQ('#layout .table_additional_payment_methods tbody tr:eq(0) .edit_add_payment_methods', 15000, 'css')->click();
        $this->byJQ('.modal_additional_payment_methods #new_payment_method_name')->clear();
        $this->byJQ('.modal_additional_payment_methods #new_payment_method_name')->value($this->payment_method_name['edit_name']);
        $this->byJQ('.modal_additional_payment_methods .modal-footer .btn-primary')->click();
        $this->byJQ('#layout #tab_payment_4 .table_additional_payment_methods tbody tr:last() .name_method', 15000, 'css');
        $name_method = $this->byJQ("#layout #tab_payment_4 .table_additional_payment_methods tbody tr:last() .name_method")->text();

        if ($name_method == $this->payment_method_name['edit_name']) {
            $this->checking_editing_method_on_reservation();
        }
    }

    private function checking_editing_method_on_reservation() {
        $this->url($this->_prepareUrl($this->reservation_url));
        $this->waitForLocation($this->_prepareUrl($this->reservation_url));
        $this->waitForElement('#layout .payments_block .btn-add-payment', 15000, 'css');

        $this->byJQ('#layout #reservation-summary .payments_block .booking-payments-table tbody tr:last()', 15000, 'css');
        $record = $this->byJQ('#layout #reservation-summary .payments_block .booking-payments-table tbody tr:last() .payment-type')->text();

        if ($record != $this->payment_method_name['edit_name']) {
            $this->setExpectedException('Method is not changed for house reservation!');
        } else {
            $this->checking_editing_method_on_house_account();
        }
    }

    private function checking_editing_method_on_house_account() {
        $this->url($this->_prepareUrl($this->house_account_url));
        $this->waitForLocation($this->_prepareUrl($this->house_account_url));
        $this->waitForElement('#layout .add-payment-btn', 15000, 'css');

        $this->byJQ("#layout .house_account .house-account-transactions tbody .payment:last() td:eq(2)", 15000, 'css');
        $record = $this->byJQ("#layout .house_account .house-account-transactions tbody .payment:last() td:eq(2)")->text();

        if ($record == $this->payment_method_name['edit_name']) {
            $this->delete_additional_payment_method();
        } else {
            $this->setExpectedException('Method is not changed for house account!');
        }
    }

    private function delete_additional_payment_method() {
        $this->url($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForLocation($this->_prepareUrl($this->paymentOptions_url));
        $this->waitForElement('#layout .payment-options-tabs', 15000, 'css');

        $this->byJQ('#layout .table_additional_payment_methods tbody tr:eq(0) .del_add_payment_methods', 15000, 'css')->click();
        $this->byJQ('#modal_additional_payment_method_delete .modal-footer .btn_delete')->click();
    }

    private function delete_payment_on_reservation() {
        /*
        $this->byJQ('#layout .payments_block:eq(0) .booking-payments-table tr:last() .void-booking-payment', 15000, 'css')->click();
        $this->byJQ('#void-modal .button-void-payment', 15000, 'css')->click();
        */
    }
}
?>
