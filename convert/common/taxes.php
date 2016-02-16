<?php
trait Taxes {
    private $fees_and_taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    private $reservation_url = 'http://{server}/connect/{property_id}#/newreservation';
    private $transactions_url = 'http://{server}/connect/{property_id}#/report_transactions';

    private function add_fee($fee) {
        $this->url($this->_prepareUrl($this->fees_and_taxes_url));
        $this->waitForLocation($this->_prepareUrl($this->fees_and_taxes_url));
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:first')->click();
        $this->waitForElement('#layout .add-new-fee', 15000, 'css')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name_en')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name_en')->value($fee['name']);
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_amount')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_amount')->clear();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_amount')->value($fee['amount']);
        $this->waitForElement('.submit-fee', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function add_tax($tax) {
        $this->url($this->_prepareUrl($this->fees_and_taxes_url));
        $this->waitForLocation($this->_prepareUrl($this->fees_and_taxes_url));
        $this->byJQ('#layout .tabs_payments a:last')->click();
        $this->waitForElement('#layout .add-new-tax', 15000, 'css')->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->value($tax['name']);
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_amount')->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_amount')->clear();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_amount')->value($tax['amount']);
        $this->waitForElement('.submit-tax', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function change_fee_name($fee) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:first')->click();
        $this->waitForElement('#layout .edit-fee', 15000, 'css')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name_en')->click();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name_en')->clear();
        $this->byJQ('#layout .add-fee-portlet-box:not(.clonable) #fee_name_en')->value($fee['name_changed']);
        $this->waitForElement('.submit-fee', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
        return false;
    }

    private function change_tax_name($tax) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:last')->click();
        $this->waitForElement('#layout .edit-tax', 15000, 'css')->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->clear();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->value($tax['name_changed']);
        $this->waitForElement('.submit-tax', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
        return false;
    }

    private function remove_fee() {
        $this->byJQ('#layout .tabs_payments a:first')->click();
        $this->waitForElement('#layout .delete-fee', 15000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

    private function remove_tax() {
        $this->byJQ('#layout .tabs_payments a:last')->click();
        $this->waitForElement('#layout .delete-tax', 15000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

}
