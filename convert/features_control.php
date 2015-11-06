<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class features_control extends test_restrict
{
    private $crmAccountsUrl = 'http://wwwcrm.{server}/crm/accounts';
    protected $server_url = 'hotel.acessa.loc';
    protected $account_features_url = 'http://{server}/api/tests/getFeatures';
    protected $account_features_auto_values_url = 'http://{server}/api/tests/getFeaturesAutoValues';
    protected $billing_packages_features_auto_values_url = 'http://{server}/api/tests/getBillingPackagesFeaturesAutoValues';
    protected $account_filter = array(
        '#status' => '',
        '#hotelName' => 'Ukraine Hotel'
    );

    public function testPMSOnFeatures() {
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform('PMS');
        $this->runFeaturesTest($result['id'], $editModal, 1);
    }

    public function testPMSOffFeatures() {
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform('PMS');
        $this->runFeaturesTest($result['id'], $editModal, -1);
    }

    public function testPMSAUTOFeatures() {
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform('PMS');
        $this->runFeaturesTest($result['id'], $editModal, 0);
    }

    public function testOTAOnFeatures() {
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform('OTA');
        $this->runFeaturesTest($result['id'], $editModal, 1);
    }

    public function testOTAOffFeatures() {
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform('OTA');
        $this->runFeaturesTest($result['id'], $editModal, -1);
    }

    public function testOTAAUTOFeatures() {
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform('OTA');
        $this->runFeaturesTest($result['id'], $editModal, 0);
    }

    public function testPMSAUTOFeaturesColors(){
        $platform = 'PMS';
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform($platform);
        $this->runFeaturesAutoColorTest($result['id'], $platform, $editModal);
    }

    public function testOTAAUTOFeaturesColors(){
        $platform = 'OTA';
        $result = $this->prepareTest();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform($platform);
        $this->runFeaturesAutoColorTest($result['id'], $platform, $editModal);
    }

    public function runFeaturesAutoColorTest($account_id, $platform, $editModal){
        if($account_id) {
            $featuresChanged = $this->toggleFeatures(0);
            $editModal->byCssSelector('.nav.nav-tabs li:nth-child(2) a')->click();
            $bp_options = $this->elements($this->using('css selector')->value('#enabled_packages option'));
            $billing_packages = array();
            foreach($bp_options as $opt){
                $billing_packages[] = $opt->attribute('value');
            }

            foreach($billing_packages as $bp_id){
                $this->execJS('$(\'#enabled_packages\').val(\''.$bp_id.'\');$(\'#enabled_packages\').multipleSelect(\'refresh\');$(\'.modal:visible\').click();$(\'#enabled_packages\').change();');
                $val = (array)$this->execJS('var $a = $(\'.modal .row-fluid:not(.hide) .radio-switcher\');var $b = {};$a.each(function(i, v){$b[$(v).attr(\'id\')]=$(v).find(\'button[value="0"]\').hasClass(\'danger\') ? 0 : 1;});return $b;');

                $newVal = array();
                foreach($val as $k => $v){
                    $k = str_replace(array('account-', '-'), array('', '_'), $k);
                    $newVal[$k] = $v;
                }

                echo PHP_EOL . print_r($newVal, true) . PHP_EOL;

                if(!empty($val)) {
                    $merged_features = $this->getBillingPackageFeaturesAutoValues($bp_id, $platform);
                    echo PHP_EOL . print_r($merged_features, true) . PHP_EOL;

                    foreach ($newVal as $k => $v) {
                        if($k == 'myallocator_enabled') $v *= 4;
                        $this->assertEquals($merged_features[$k], $v);
                    }
                } else {
                    $this->fail('Auto values not loaded.');
                }
            }
        }
    }

    public function prepareTest() {
        $this->go_to_crm();
        $account_row = $this->filter_accounts($this->account_filter);
        return array('row' => $account_row, 'id' => $this->getAccountId($account_row));
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

    public function runFeaturesTest($account_id, $editModal, $featureVal) {
        if($account_id){
            $featuresChanged = $this->toggleFeatures($featureVal);//ON all features

            $editModal->byCssSelector('#mask-save-btn-tab1')->click();
            sleep(10);

            $openedModals = $this->findModals(true);
            $this->assertEquals(0, count($openedModals), 'Modal still opened, check save server error');

            $this->checkServerFeatures($account_id, $featuresChanged, $featureVal);
        } else {
            $this->fail('Account was not found');
        }
    }

    public function checkServerFeatures($account_id, $featuresChanged, $value) {
        $fChanged = array();
        foreach($featuresChanged as $input){
            if($input instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
                $input_id = str_replace(array('acc-m-', '-'), array('', '_'), $input->attribute('id'));
                $fChanged[$input_id] = $input->value();
            }
        }

        echo PHP_EOL . 'fetures changed' . print_r($fChanged, true) . PHP_EOL;

        $features = $this->getAccountFeatures($account_id);

        if($value != 0) {//!AUTO
            $a_default = 0;
            $af_default = -1;

            if ($value == 1) {
                $a_default = 1;
                $af_default = 1;
            }

            foreach ($features as $name => $val) {
                if ($name == 'id' || !isset($fChanged[$name])) continue;
                $koef = $name == 'myallocator_enabled' ? 4 : 1;
                if (strpos($name, 'account_') !== FALSE) {
                    $this->assertEquals($af_default * $koef, $val, '`af`.`' . $name . '` wrong! [acc_id='.$account_id.']');
                } else {
                    $this->assertEquals($a_default * $koef, $val, '`a`.`' . $name . '` wrong! [acc_id='.$account_id.']');
                }
            }

        } else {//AUTO
            //not implemented yet
            $af_default = 0;//always - AUTO value in accounts_features table
            $a_default = 0;//changing value related to next request
            $featuresAutoValues = $this->getAccountFeaturesAutoValues($account_id);

            foreach ($features as $name => $val) {
                if ($name == 'id' || !isset($fChanged[$name])) continue;

                $a_default = isset($featuresAutoValues[$name]) ? $featuresAutoValues[$name] : 0;

                $koef = 1;
                if (strpos($name, 'account_') !== FALSE) {
                    $this->assertEquals($af_default * $koef, $val, '`af`.`' . $name . '` wrong! [acc_id='.$account_id.']');
                } else {
                    $this->assertEquals($a_default * $koef, $val, '`a`.`' . $name . '` wrong! [acc_id='.$account_id.']');
                }
            }
        }
    }

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

    }

    public function toggleFeature($account_id, $feature, $value) {

    }

    public function toggleFeatures($val = 0) {
        $this->execJS('$(\'.modal:visible .radio-switcher button[value=\"'.$val.'\"]\').click();');
        return $this->elements($this->using('css selector')->value('.modal .row-fluid:not(.hide) .radio-switcher input'));
    }

    public function go_to_crm() {
        $site_login = $this->login;
        $site_pass = $this->password;
        $site_server_url = $this->server_url;

        /*
            $this->login = 'engineering@cloudbeds.com';
            $this->password = 'cl0udb3ds';
        */

        $this->login = 'admin@test.test';
        $this->password = '123qwe';

        $this->loginToSite();

        $this->server_url = 'acessa.loc';
        $crm_accounts = $this->_prepareUrl($this->crmAccountsUrl);
        $this->url($crm_accounts);
        $this->waitForLocation($crm_accounts);

        $this->login = $site_login;
        $this->password = $site_pass;
        $this->server_url = $site_server_url;
    }
}