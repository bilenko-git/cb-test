<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class cash_drawers extends test_restrict
{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';
    private $name = 'new cash drawer';

    public function testSteps()
    {

        $this->setupInfo('PMS_user');
        $this->loginToSite();

        $this->waitForElement("[name='acashier_system']", 15000, 'css')->click();
        $this->waitForElement("#scash_drawers a", 15000, 'css')->click();
        $this->waitForElement(".cash_drawers_list", 15000, 'css');
        $this->waitForElement(".add-cash-drawer-btn", 15000, 'css')->click();
        $el = $this->waitForElement("#add-cash-drawer #drawer_name", 15000, 'css');
        $el->clear();
        $el->value($this->name);
        $el = $this->waitForElement("#add-cash-drawer #starting_balance", 15000, 'css');
        $el->clear();
        $el->value('77');

        $this->waitForElement("#save_drawer_btn", 15000, 'css')->click();
        $this->betLoaderWaiting();

        $id = $this->execute(array('script' => 'return window.$(".table-cash-drawers .cd-name:contains(' . $this->name . ')").closest("tr").attr("rel");', 'args' => array()));

        $this->waitForElement("[name='acashier_system']", 15000, 'css')->click();
        $this->waitForElement("#sopen_cash_drawer a", 15000, 'css')->click();

        $this->waitForElement("#open-cash-drawer .single-multiple-select button", 15000, 'css')->click();

        $this->execute(array('script' => 'window.$("#open-cash-drawer .single-multiple-select li input[value=' . $id . ']").click(); return false', 'args' => array()));
        $this->execute(array('script' => 'window.$("#open-cash-drawer .single-multiple-select li input[value=' . $id . ']").click(); return false', 'args' => array()));

        $this->waitForElement("#open-cash-drawer #open-cash-drawer-btn", 15000, 'css')->click();
        $this->betLoaderWaiting();

        $this->waitForElement("[name='acashier_system']", 15000, 'css')->click();
        $this->waitForElement("#sclose_cash_drawer a", 15000, 'css')->click();
        $el = $this->waitForElement("#close-cash-drawer #drawer_balance_st1", 15000, 'css');
        $el->clear();
        $el->value('77');
        $this->waitForElement("#close-cash-drawer .continue-to-step", 15000, 'css')->click();
        $this->waitForElement("#close-cash-drawer .continue-to-step", 15000, 'css')->click();
        $el = $this->waitForElement("#close-cash-drawer #cash_drop", 15000, 'css');
        $el->clear();
        $el->value('77');
        $this->waitForElement("#close-cash-drawer .close-drawer-footer-2 .continue-to-step", 15000, 'css')->click();
        $this->waitForElement("#close-cash-drawer #close-drawer", 15000, 'css')->click();
        $this->betLoaderWaiting();

        $el = $this->execute(array('script' => 'return window.$(".table-cash-drawers .cd-name:contains(' . $this->name . ')").closest("tr").find(".actions-column a:eq(1)").get(0);', 'args' => array()));
        $el = $this->elementFromResponseValue($el);
        $el->click();
        $this->waitForElement("#delete-cash-drawer-btn", 15000, 'css')->click();
        $this->betLoaderWaiting();
        $l = $this->execute(array('script' => 'return window.$(".table-cash-drawers .cd-name:contains(' . $this->name . ')").length;', 'args' => array()));
        $this->assertEquals(0, $l);
    }

}

?>
