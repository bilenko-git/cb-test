<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class tax_rename_change_previous_transactions extends test_restrict {
    private $fees_and_taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    private $transactions_url = 'http://{server}/connect/{property_id}#/report_transactions';
    private $fee = array(
        'name' => 'Fee Transactions #1',
        'amount' => '10'
    );

    public function testSteps() {
        $this->setupInfo('wwwdev3.ondeficar.com', '', '', 366);
        $this->loginToSite();

        $this->prepare_data();
        $this->change_fee_name();
        $this->check_transactions();
        $this->change_tax_name();
        $this->check_transactions();
        $this->add_adjustments();
        $this->check_transactions();
        $this->clear_data();
    }

    private function prepare_data() {
        $this->add_fee();
        $this->add_tax();
        $this->add_reservation();
        $this->add_transactions();
    }

    private function clear_data() {
        $this->remove_fee();
        $this->remove_tax();
        $this->remove_reservation();
        $this->remove_transactions();
    }

    private function add_fee() {
        $this->url($this->_prepareUrl($this->fees_and_taxes_url));
        $this->waitForLocation($this->_prepareUrl($this->fees_and_taxes_url));
        $this->waitForElement('#layout .add-new-fee', 15000, 'css')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name')->value($this->fee['name']);
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_amount')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_amount')->clear();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_amount')->value($this->fee['amount']);
        $this->waitForElement('.submit-fee', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function add_tax() {
        return false;
    }

    private function add_reservation() {
        return false;
    }

    private function add_transactions() {
        return false;
    }

    private function change_fee_name() {
        return false;
    }

    private function change_tax_name() {
        return false;
    }

    private function check_transactions() {
        return false;
    }

    private function add_adjustments() {
        return false;
    }

    private function remove_fee() {
        $this->waitForElement('#layout .delete-fee', 15000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

    private function remove_tax() {
        return false;
    }

    private function remove_reservation() {
        return false;
    }

    private function remove_transactions() {
        return false;
    }

}
?>
