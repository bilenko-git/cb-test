<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/taxes.php';

class fees_and_taxes extends test_restrict {
    use \Fees, \Taxes;

    private $fee = array(
        'name' => 'Fee Transactions #1',
        'name_changed' => 'Fee Transactions #1 changed',
        'amount' => '10'
    );

    private $tax = array(
        'name' => 'Tax Transactions #1',
        'name_changed' => 'Tax Transactions #1 changed',
        'amount' => '10'
    );

    private function prepare_data() {
        $this->fees_add_fee($this->fee);
        $this->taxes_add_tax($this->tax);
        $this->add_reservation();
        $this->add_transactions();
    }

    private function clear_data() {
        $this->fees_remove_fee();
        $this->taxes_remove_tax();
        $this->remove_reservation();
        $this->remove_transactions();
    }

    private function add_reservation() {
        // $index = $this->execute(array('script' => "return BET.newreservations.newReservation(false)", 'args' => array()));
        // $this->waitForLocation($this->_prepareUrl($this->$reservation_url));
        // $this->waitForElement('.submit-tax', 15000, 'css')->click();
        return false;
    }

    private function add_transactions() {
        return false;
    }

    private function check_transactions() {
        return false;
    }

    private function add_adjustments() {
        return false;
    }

    private function remove_reservation() {
        return false;
    }

    private function remove_transactions() {
        return false;
    }

    /* SECTION OF TESTS */

    public function test_rename_and_change_transactions_descriptions() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->prepare_data();
        $this->fees_change_fee_name($this->fee);
        $this->check_transactions();
        $this->taxes_change_tax_name($this->tax);
        $this->check_transactions();
        $this->add_adjustments();
        $this->check_transactions();
        $this->clear_data();
    }

    // public function test_fee_percentage_per_night() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     $this->fees_add_fee($this->fee);
    //     $this->fees_remove_fee();
    // }
    //
    // public function test_tax_percentage_per_night() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     $this->taxes_add_tax($this->tax);
    //     $this->taxes_remove_tax();
    // }

}
?>
