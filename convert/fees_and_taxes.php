<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/taxes.php';

class fees_and_taxes extends test_restrict {
    use \Fees, \Taxes;

    private $fees = array(
        'percentage' => array(
            'name' => 'Fee Transactions #1',
            'name_changed' => 'Fee Transactions #1 changed',
            'amount_type' => 'percentage',
            'amount' => '10'
        ),
        'fixed' => array(
            'name' => 'Fee Transactions #1',
            'name_changed' => 'Fee Transactions #1 changed',
            'amount_type' => 'fixed',
            'amount' => '10'
        ),
        'fixed_per_accomodation' => array(
            'name' => 'Fee Transactions #1',
            'name_changed' => 'Fee Transactions #1 changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10'
        ),
        'fixed_per_reservation' => array(
            'name' => 'Fee Transactions #1',
            'name_changed' => 'Fee Transactions #1 changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10'
        )
    );

    private $taxes = array(
        'percentage' => array(
            'name' => 'Tax Transactions #1',
            'name_changed' => 'Tax Transactions #1 changed',
            'amount_type' => 'percentage',
            'amount' => '10'
        ),
        'fixed' => array(
            'name' => 'Tax Transactions #1',
            'name_changed' => 'Tax Transactions #1 changed',
            'amount_type' => 'fixed',
            'amount' => '10'
        ),
        'fixed_per_accomodation' => array(
            'name' => 'Tax Transactions #1',
            'name_changed' => 'Tax Transactions #1 changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10'
        ),
        'fixed_per_reservation' => array(
            'name' => 'Tax Transactions #1',
            'name_changed' => 'Tax Transactions #1 changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10'
        )
    );

    private function prepare_data() {
        $this->fees_add_fee($this->fees['percentage']);
        $this->taxes_add_tax($this->taxes['percentage']);
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

    // public function test_rename_and_change_transactions_descriptions() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     $this->prepare_data();
    //     $this->fees_change_fee_name($this->fees['percentage']);
    //     $this->check_transactions();
    //     $this->taxes_change_tax_name($this->taxes['percentage']);
    //     $this->check_transactions();
    //     $this->add_adjustments();
    //     $this->check_transactions();
    //     $this->clear_data();
    // }

    public function test_fee_percentage_per_night() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->fees_add_fee($this->fees['percentage']);
        $this->fees_remove_fee();
    }

    public function test_tax_percentage_per_night() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->taxes_add_tax($this->taxes['percentage']);
        $this->taxes_remove_tax();
    }

}
?>
