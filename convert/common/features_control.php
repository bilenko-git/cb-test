<?php

trait FeaturesControl {

    private $crmAccountsUrl = 'http://wwwcrm.{server}/crm/accounts';
    protected $account_features_url = 'http://{server}/api/tests/getFeatures';
    protected $account_features_auto_values_url = 'http://{server}/api/tests/getFeaturesAutoValues';
    protected $billing_packages_features_auto_values_url = 'http://{server}/api/tests/getBillingPackagesFeaturesAutoValues';

    public function getAccountFeaturesAutoValues($account_id, $asArray = true) {
        $params = array('account_id' => $account_id);
        return $this->apiCall($this->account_features_auto_values_url, $params, $asArray);
    }
    public function getAccountFeatures($account_id, $asArray = true) {
        $params = array('account_id' => $account_id);
        return $this->apiCall($this->account_features_url, $params, $asArray);
    }
    public function getBillingPackageFeaturesAutoValues($billing_packages, $platform = 'PMS', $asArray = true){
        $params = array('billing_packages_id' => $billing_packages, 'platform' => $platform);
        return $this->apiCall($this->billing_packages_features_auto_values_url, $params, $asArray);
    }

    public function editAccount($account_row) {
        if($account_row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            $account_row->byCssSelector('.account-edit-lnk')->click();
            $editModal = $this->waitForElement('.modal:visible', 15000, 'jQ');
            return $editModal;
        }

        return false;
    }

    public function getAccountId($account_row){
        if($account_row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            return $account_row->byCssSelector('td.sorting_1 a')->attribute('account');
        }

        return false;
    }

    private function filter_accounts($filter = false) {
        if(!empty($filter) && is_array($filter)){
            foreach($filter as $selector => $value){
                $input = $this->waitForElement($selector, 5000, 'jQ');
                $this->execJS('$(\''.$selector.'\').val(\'\');');
                $input->value($value);
            }
        }

        $search_button = $this->waitForElement('.search-account-btn');
        $search_button->click();

        $account_row = $this->waitForElement('#accountTable tbody tr:nth-child(1)');
        return $account_row;
    }

    public function toggleBillingPackages($account_id, $packages) {
        throw new \Exception('Not Implemented yet');
    }

    public function toggleFeature($account_id, $feature, $value) {
        throw new \Exception('Not Implemented yet');
    }

    public function toggleFeatures($val = 0) {
        $this->execJS('$(\'.modal:visible .radio-switcher button[value=\"'.$val.'\"]\').click();');
        return $this->elements($this->using('css selector')->value('.modal .row-fluid:not(.hide) .radio-switcher input'));
    }

    public function openFeaturesTab($account_row){
        $editModal = false;
        if(($editModal = $this->editAccount($account_row))) {
            if ($editModal instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
                echo PHP_EOL . 'edit account modal found' . PHP_EOL;

                $editModal->byCssSelector('.nav.nav-tabs li:nth-child(3) a')->click();//go to features tab
            }
        }

        return $editModal;
    }

    public function switchPlatform($platform = 'PMS'){
        $platform_input = $this->waitForElement('#acc-m-ma-platform', 15000, 'jQ');
        if($platform_input instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            $platform_input_value = $platform_input->value();
            if($platform_input_value != $platform) {
                $platform_input->value($platform);
                $this->execJS('$(\'.modal:visible\').focus();');
                try {
                    $this->acceptAlert();
                    sleep(1);
                } catch (\Exception $e) {

                }
            }
        }
    }
}