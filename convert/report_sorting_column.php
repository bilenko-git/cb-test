<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class report_sorting_column extends test_restrict{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';
    private $reports_url = array(
        'http://{server}/connect/{property_id}#/report_transactions',
        'http://{server}/connect/{property_id}#/report_adjustments',
        'http://{server}/connect/{property_id}#/report_paymentLedger',
        'http://{server}/connect/{property_id}#/rates_plans_report',
        'http://{server}/connect/{property_id}#/account_balances_report',
        'http://{server}/connect/{property_id}#/cashier_report',
        'http://{server}/connect/{property_id}#/front_desk_report/arrivals',
        'http://{server}/connect/{property_id}#/front_desk_report/departures',
        'http://{server}/connect/{property_id}#/front_desk_report/inhouse',
        'http://{server}/connect/{property_id}#/front_desk_report/noshow',
        'http://{server}/connect/{property_id}#/front_desk_report/cancellations',
    );

    public function testSteps(){
        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        for ($i = 0; $i < count($this->reports_url); $i++){
            $this->url($this->_prepareUrl($this->reports_url[$i]));
            $this->waitForLocation($this->_prepareUrl($this->reports_url[$i]));
            $this->betLoaderWaiting();
            $this->waitForElement('.table', 15000, 'css');
            $l = $this->execute(array('script' => "return window.$('#layout .table:visible thead tr th').length", 'args' => array()));
            for ($j= 0; $j < $l; $j++) {
                $this->waitForElement('#layout .table:visible thead tr th:eq('.$j.')', 15000, 'jQ')->click();
                $this->betLoaderWaiting();
            }
        }
    }
}
?>
