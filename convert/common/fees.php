<?php

trait Fees {
    private $fees_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    private function fees_add_fee($fee) {
        $this->url($this->_prepareUrl($this->fees_url));
        $this->waitForLocation($this->_prepareUrl($this->fees_url));
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

    private function fees_change_fee_name($fee) {
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

    private function fees_remove_fee() {
        $this->byJQ('#layout .tabs_payments a:first')->click();
        $this->waitForElement('#layout .delete-fee', 15000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
    }

}
