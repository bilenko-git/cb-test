<?php

trait Taxes {
    private $taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    private function taxes_add_tax($tax) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
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

    private function taxes_change_tax_name($old_name, $new_name) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:last')->click();
        $this->waitForElement('#layout .taxes-table tr .edit-tax', 15000, 'css');
        $this->byJQ("#layout .taxes-table tr:contains('".$old_name."') .edit-tax")->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->click();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->clear();
        $this->byJQ('#layout .add-tax-portlet-box:not(.clonable) #tax_name_en')->value($new_name);
        $this->waitForElement('.submit-tax', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function taxes_remove_tax($name) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:last')->click();
        $this->waitForElement('#layout .taxes-table tr .delete-tax', 15000, 'css');
        $this->byJQ("#layout .taxes-table tr:contains('".$name."') .delete-tax")->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

}
