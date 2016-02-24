<?php

trait Taxes {
    private $taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    private function taxes_add_tax($tax) {
        $this->url($this->_prepareUrl($this->taxes_url));
        $this->waitForLocation($this->_prepareUrl($this->taxes_url));
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

    private function taxes_change_tax_name($tax) {
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

    private function taxes_remove_tax() {
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:last')->click();
        $this->waitForElement('#layout .delete-tax', 15000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

}
