<?php

trait Fees {
    private $fees_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    private function fees_add_fee($fee) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .add-new-fee-or-tax', 15000, 'css')->click();
        $context = '#layout .add-fee-or-tax-portlet-box:not(.clonable) ';
        $this->fillForm(array(
            '#type_of' => 'fee',
            '#tax_name_en' => $fee['name'],
            '#amount_type' => $fee['amount_type'],
            '#amount' => [$fee['amount'], true]
        ), $context);
        $this->waitForElement('.submit-tax', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function fees_change_fee_name($old_name, $new_name) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .tabs_payments a', 15000, 'css');
        $this->byJQ('#layout .tabs_payments a:first')->click();
        $this->waitForElement('#layout .fees-table tr .edit-fee', 15000, 'css');
        $this->byJQ("#layout .fees-table tr:contains('".$old_name."') .edit-fee")->click();
        $this->byJQ('#layout .add-fee-or-tax-portlet-box:not(.clonable) #fee_name_en')->click();
        $this->byJQ('#layout .add-fee-or-tax-portlet-box:not(.clonable) #fee_name_en')->clear();
        $this->byJQ('#layout .add-fee-or-tax-portlet-box:not(.clonable) #fee_name_en')->value($new_name);
        $this->waitForElement('.submit-fee', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function fees_remove_fee($name) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->byJQ('#layout .tabs_payments a:first')->click();
        $this->waitForElement('#layout .fees-table tr .delete-fee', 15000, 'css');
        $this->byJQ("#layout .fees-table tr:contains('".$name."') .delete-fee")->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

}
