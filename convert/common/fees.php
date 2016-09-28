<?php

trait Fees {
    private $fees_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    private function fees_add($fee) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .add-new-fee-or-tax', 15000, 'css')->click();
        $context = '#layout .add-fee-or-tax-portlet-box:not(.clonable) ';
        $this->fillForm(array(
            '#type_of' => $fee['type_of'],
            '#tax_name_en' => $fee['name'],
            '#amount_type' => $fee['amount_type'],
            '#amount' => [$fee['amount'], true],
            '.type_'.$fee['type'] => [$fee['type'], false, true]
        ), $context);
        $this->waitForElement('.submit-tax', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function fees_change_name($old_name, $new_name) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .fees-and-taxes-table tr .edit-tax', 15000, 'css');
        $this->byJQ("#layout .fees-and-taxes-table tr:contains('".$old_name."') .edit-tax")->click();
        $this->fillForm(array(
            '#tax_name_en' => [$new_name, true]
        ), '#layout .add-fee-or-tax-portlet-box:not(.clonable) ');
        $this->waitForElement('.submit-tax', 15000, 'css')->click();
        $this->waitForElement('.toast-close-button', 15000, 'css')->click();
    }

    private function fees_remove($name) {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .fees-and-taxes-table tr .delete-tax', 15000, 'css');
        $this->byJQ("#layout .fees-and-taxes-table tr:contains('".$name."') .delete-tax")->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
        $this->waitForElement('#layout .fees-and-taxes-table', 15000, 'css');
    }

}
