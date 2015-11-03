<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class features_control extends test_restrict
{
    private $crmAccountsUrl = 'http://wwwcrm.{server}/crm/accounts';
    protected $server_url = 'hotel.acessa.loc';
    protected $account_features_url = 'http://{server}/api/tests/getFeatures';
    protected $account_filter = array(
        '#status' => '',
        '#hotelName' => 'Ukraine Hotel'
    );

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

    public function prepareTest(){
        $this->go_to_crm();
        $account_row = $this->filter_accounts($this->account_filter);
        return array('row' => $account_row, 'id' => $this->getAccountId($account_row));
    }

    public function runFeaturesTest($account_id, $account_row, $featureVal){
        if($account_id){
            $editModal = $this->editAccount($account_row);
            echo PHP_EOL . 'modals: ' . count($editModal) . PHP_EOL;
            if(!empty($editModal)) {
                $editModal = $editModal[0];
                if ($editModal instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
                    echo PHP_EOL . 'edit account modal found' . PHP_EOL;

                    $editModal->byCssSelector('.nav.nav-tabs li:nth-child(3) a')->click();//go to features tab

                    $featuresChanged = $this->toggleFeatures($featureVal);//ON all features
                    sleep(5);

                    $editModal->byCssSelector('#mask-save-btn-tab1')->click();
                    sleep(10);

                    $openedModals = $this->findModals(true);
                    $this->assertEquals(0, count($openedModals), 'Modal still opened, check save server error');

                    $this->checkServerFeatures($account_id, $featuresChanged, $featureVal);
                }
            }
        }
    }

    public function testOnFeatures(){
        $result = $this->prepareTest();
        $this->runFeaturesTest($result['id'], $result['row'], 1);
    }

    /*
        public function testOffFeatures(){
            $result = $this->prepareTest();
            $this->runFeaturesTest($result['id'], $result['row'], -1);
        }

        public function testAUTOFeatures(){
            $result = $this->prepareTest();
            $this->runFeaturesTest($result['id'], $result['row'], 0);
        }
    */

    public function getAccountFeatures($account_id, $asArray = true){
        $params = array(
            'account_id' => $account_id
        );

        $account_features_url = $this->_prepareUrl($this->account_features_url) . '?' . http_build_query($params);

        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode($this->cbApiLogin.':'.$this->cbApiPass)
            )
        ));

        $data = file_get_contents($account_features_url, false, $context);

        return json_decode($data, $asArray);
    }

    public function checkServerFeatures($account_id, $featuresChanged, $value){
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
        }
    }

    public function editAccount($account_row){
        if($account_row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            $account_row->byCssSelector('.account-edit-lnk')->click();
            sleep(10);
            return $this->findModals(true);
        }

        return false;
    }

    public function getAccountId($account_row){
        if($account_row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            return $account_row->byCssSelector('td.sorting_1 a')->attribute('account');
        }

        return false;
    }

    private function filter_accounts($filter = false){
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

    public function toggleFeatures($val = 0){
        $this->execJS('$(\'.modal:visible .radio-switcher button[value=\"'.$val.'\"]\').click();');
        return $this->elements($this->using('css selector')->value('.modal .row-fluid:not(.hide) .radio-switcher input'));
    }
}