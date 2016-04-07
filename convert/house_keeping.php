<?php
namespace MyProject\Tests;

require_once 'test_restrict.php';
require_once 'common/features_control.php';

class house_keeping extends test_restrict {
    use \FeaturesControl;

    /* TESTS SETTINGS*/
    private $house_keeping_url = 'http://{server}/connect/{property_id}/#/house_keeping';
    protected $server_url = 'hotel.acessa.loc';
    protected $property_id = 2;
    protected $account_filter = array(
        '#status' => '',
        '#hotelName' => 'Test Hotel 2');


    protected $house_keeping_crm_js_name = 'account-house-keeping-enabled';
    protected $room_names_crm_js_name = 'account-room-names-enabled';
    protected $house_keepers = array(
        'insert' => array(
            'Alex Brus',
            'Alex Kalinin',
            'Andre Sovgir'
        ),
        'update' => array(
            'Alex Brus' => 'Brus A',
            'Alex Kalinin' => 'Kalinin A'
        ),
        'delete' => array(
            'Kalinin A',
            'Andre Sovgir'
        )
    );
    protected $checkInspectionChange = array(
        array(
            'condition' => 'Dirty',
            'assign_to' => 0,
            'do_not_disturb' => 1,
            'comment' => 'test comment'
        ),
        array(
            'condition' => 'Clean',
            'assign_to' => 1,
            'do_not_disturb' => 0,
            'comment' => 'test comment 2'
        ),
    );
    protected $isMiniBase = true;


    /*TESTS FUNCTIONS*/
    public function testAddHouseKeeper() {
        $this->_prepare_house_keeping_test(true);
        usleep(1000000);
        $this->openHKTabs('house_keepers');
        $this->removeAllHouseKeepers();
        foreach($this->house_keepers['insert'] as $hk_name) {
            $this->openHKTabs('house_keepers');
            $this->addHouseKeeper($hk_name);
        }
    }
    public function testRenameHouseKeeper() {
        $this->_prepare_house_keeping_test(true);
        foreach($this->house_keepers['update'] as $hk_name => $hk_name_replace_to) {
            $this->openHKTabs('house_keepers');
            $this->editHouseKeeper($hk_name, $hk_name_replace_to);
        }
    }
    public function testRemoveHouseKeeper() {
        $this->_prepare_house_keeping_test(true);
        $this->openHKTabs('house_keepers');
        foreach ($this->house_keepers['delete'] as $hk_name) {
            $this->removeHouseKeeper($hk_name);
        }
    }

    public function testHKFeatureOn() {
        $this->HKFeatureToggle('PMS', 1);
    }
    public function testHKFeatureOff() {
        $this->HKFeatureToggle('PMS', 0);
    }

    public function testChangeRoomNamesFeature() {
        throw new \Exception('Not implemented yet');
    }
    public function testDisabledForOTA() { //SPRINT: OperationGoCommando
        //TODO: enabled this after OperationGoCommando in master
        //$this->HKFeatureToggle('OTA', 0);
    }
    public function testAssignModalsConditionShow() {
        //TODO: enable this after Hess in master
    }

    public function testChangeInspections(){
        $this->_prepare_house_keeping_test(true);
        usleep(1000000);
        $this->openHKTabs('house_keeping_inspections');
        $rowIndex = 0;
        $housekeepers = $this->getHouseKeepersList();

        print_r($housekeepers);

        foreach($this->checkInspectionChange as $change) {
            $row = $this->findInspections($rowIndex);

            foreach($change as $name => &$value) {
                if($name == 'assign_to') $value = isset($housekeepers[$value]) ? $housekeepers[$value] : reset($housekeepers);
                $this->inspectionChange($row, $name, $value);
                sleep(1);
            }

            $this->assertInspection($rowIndex, $change);
        }
    }
    public function testChangeRoomsInfo(){}

    public function testCron(){}
    public function testCalendarMoveInHouse(){}
    public function testBlocksOnCalendar(){}
    public function testOutOfOrderOnCalendar(){}

    public function testPrintInspections(){}
    public function testPDFInspections(){
        throw new \Exception('Not implemented yet.');
    }
    public function testXLSInspections(){
        throw new \Exception('Not implemented yet.');
    }

    /* SYSTEM FUNCTIONS TO REALIZE TESTS */
    protected function getHouseKeepersList(){
        return $this->execute(array('script' => 'return _.map(BET.DB().select("house_keepers")[0], function(val, i){return val.name});', 'args' => array()));
    }
    protected function assertInspection($rowIndex, $expected) {
        $this->element($this->using('css selector')->value('.hk_apply_filter'))->click();
        sleep(3);
        $row = $this->element($this->using('css selector')->value('#layout .housekeeping-inspections-table tbody tr:nth-child('.($rowIndex+1).')'));
        if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $row_id = $row->attribute('data-id');
            foreach ($expected as $name => $value) {
                switch ($name) {
                    case 'condition':
                    case 'assign_to':
                    case 'do_not_disturb':
                        $input = $row->byCssSelector('[name="'.$name.'['.$row_id.']"]');

                        $currentValue = false;
                        if($name == 'assign_to'){
                            $currentValue = $this->select($input)->selectedLabel();
                        } else {
                            $currentValue = $name == 'do_not_disturb' ? $input->selected() : $input->value();
                        }

                        $this->assertEquals($value, $currentValue);
                        break;
                    case 'comment':
                        $this->assertEquals($value, trim($row->byCssSelector('.notes-truncate')->text()));
                        break;
                }
            }
        } else {
            $this->fail('Failed to find inspection row: index = ' . $rowIndex);
        }
    }

    protected function inspectionChange($row, $name, $value) {
        echo PHP_EOL . "Inspection change: [$name : $value]" . PHP_EOL;
        if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $row_id = $row->attribute('data-id');

            switch ($name) {
                case 'condition':
                    $input = $row->byCssSelector('select[name="'.$name.'['.$row_id.']"]');
                    $input->value($value);
                    break;
                case 'assign_to':
                    $input = $row->byCssSelector('select[name="'.$name.'['.$row_id.']"]');
                    $input->value($value);
                    break;
                case 'do_not_disturb':
                    $input = $row->byCssSelector('input[name="do_not_disturb['.$row_id.']"]');
                    if((int)$input->selected() != $value) {
                        $label = $row->byCssSelector('label[for="hk_do_not_disturb_'.$row_id.'"]');
                        $label->click();
                    }
                    break;
                case 'comment':
                    $edit = $row->byCssSelector('.edit_notes');
                    $edit->click();
                    sleep(1);

                    $modal = $this->waitForElement('#house_keeper_room_notes_modal');
                    if($modal instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
                        $input = $modal->byCssSelector('#house_keeper_room_notes');
                        $save = $modal->byCssSelector('.save_house_keeping_note');
                        $input->clear();
                        $input->value($value);
                        $save->click();
                    }
                    break;
            }
        }
    }

    protected function findInspections($index = false){
        $rows = $this->elements($this->using('css selector')->value('#layout .housekeeping-inspections-table tbody tr'));
        if($index === false) {
            return $rows;
        } else return isset($rows[$index]) ? $rows[$index] : false;
    }

    protected function assertHouseKeeperExists($name, $expected) {
        echo PHP_EOL . 'Running HK Assertion' . PHP_EOL;
        //check house keepers exists/doesn't exists in all places
        $results = array(
            'House keepers table' => false,
            'Quick Assign' => false,
            'Assign Column' => false,
            'Assign Filter' => false
        );

        sleep(5);//wait for insert new name

        $row = $this->findHouseKeepers($name);
        $hk_id = false;
        if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            echo PHP_EOL . 'Instance OF' . PHP_EOL;
            $results['House keepers table'] = true;
            $hk_id = $this->execute(array('script' => 'return $(arguments[0]).find("a.edit").data("id");', 'args' => array($row->toWebDriverObject())));
            echo PHP_EOL . 'HK_ID :' . $hk_id . PHP_EOL;
        }

        echo PHP_EOL . ' returned HK ID: ' . $hk_id . PHP_EOL;

        echo PHP_EOL . 'Checked Table'.PHP_EOL;
        $this->openHKTabs('hk_tab_1');
        sleep(1);

        echo PHP_EOL . 'Checking Quick Assign' . PHP_EOL;
        $quick_assign = $this->elements($this->using('css selector')->value('select[name="quick_assign_to"] option'));
        foreach($quick_assign as $opt){
            echo PHP_EOL . 'Comparing: ' . $opt->value() . ' vs ' . $hk_id . '|' . $opt->text() . ' vs ' . $name . PHP_EOL;
            if($opt->text() == $name || ($hk_id && $opt->value() == $hk_id)) {
                $results['Quick Assign'] = true;
                break;
            }
        }
        echo PHP_EOL . 'Checked Quick Assign' . PHP_EOL;

        echo PHP_EOL . 'Checking Assign Columns'. PHP_EOL;
        $assign_columns = $this->elements($this->using('css selector')->value('select.assign_select option'));
        foreach($assign_columns as $opt) {
            echo PHP_EOL . 'Comparing: ' . $opt->value() . ' vs ' . $hk_id . '|' . $opt->text() . ' vs ' . $name . PHP_EOL;
            if($opt->text() == $name || ($hk_id && $opt->value() == $hk_id)) {
                $results['Assign Column'] = true;
                break;
            }
        }
        echo PHP_EOL . 'Checked Assign Columns'. PHP_EOL;

        echo PHP_EOL . 'Checking Assign Filter' . PHP_EOL;
        $assign_filter = $this->elements($this->using('css selector')->value('#filter_hk_assigned_to option'));
        echo PHP_EOL . 'CNT: ' . count($assign_filter) . PHP_EOL;
        foreach($assign_filter as $opt) {
            echo PHP_EOL . 'Comparing: ' . $opt->value() . ' vs ' . $hk_id . '|' . $opt->text() . ' vs ' . $name . PHP_EOL;
            if($opt->text() == $name || ($hk_id && $opt->value() == $hk_id)) {
                $results['Assign Filter'] = true;
                break;
            }
        }
        echo PHP_EOL . 'Checked Assign Filter' . PHP_EOL;

        foreach($results as $k => $r) {
            $this->assertEquals($expected, $r, 'House keeper ' . $name . ' has ' . (!$expected ? 'not' : '') . ' to be exist in ' . $k . '.');
        }
    }

    protected function addHouseKeeper($name){
        $this->waitForElement('#layout .add-new-housekeeper')->click();

        $this->waitIfLocal(500000);
        $this->fillHouseKeeperForm($name);

        $this->waitIfLocal(3000000);
        $this->assertHouseKeeperExists($name, true);
    }

    protected function editHouseKeeper($name, $new_name){
        $row = $this->findHouseKeepers($name);
        if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $row->byCssSelector('[data-toggle="dropdown"]')->click();
            $row->byCssSelector('.dropdown-menu.export_report li:nth-child(1)')->click();

            $this->waitIfLocal(500000);
            $this->fillHouseKeeperForm($new_name);
        }

        $this->waitIfLocal(3000000);
        $this->assertHouseKeeperExists($name, false);
        $this->assertHouseKeeperExists($new_name, true);
    }

    protected function removeHouseKeeper($name){
        $row = $this->findHouseKeepers($name);
        if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $row->byCssSelector('[data-toggle="dropdown"]')->click();
            $row->byCssSelector('.dropdown-menu.export_report li:nth-child(2)')->click();
            $this->confirm_delete_modal();
        }

        $this->waitIfLocal(3000000);
        $this->assertHouseKeeperExists($name, false);
    }

    protected function removeAllHouseKeepers() {
        //need to find house keeper, remove and re-new find because table reloading
        while($rows = $this->findHouseKeepers()) {
            foreach($rows as $row) {
                if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
                    $row->byCssSelector('[data-toggle="dropdown"]')->click();
                    $row->byCssSelector('.dropdown-menu.export_report li:nth-child(2)')->click();
                    $this->confirm_delete_modal();
                }

                break;
            }

            $this->waitIfLocal();
        }
    }

    protected function findHouseKeepers($name = ''){
        $rows = $this->elements($this->using('css selector')->value('#layout .housekeeper-list-table tr.data-row'));//$this->execute(array( 'script' => 'return $("#layout .housekeeper-list-table tbody tr.data-row");', 'args' => array()));
        $result = $rows;

        if(!empty($name)) {
            echo PHP_EOL . ' filtering house keepers by name: ' . $name . PHP_EOL;
            foreach($rows as $row) {
               if($row instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
                   $td = $row->byCssSelector('td:nth-child(1)');
                   if($td && $td->text() == $name) {
                       $result = $row;
                       break;
                   }
               }
            }
        }

        return $result;
    }

    protected function fillHouseKeeperForm($name) {
        $modal = $this->waitForElement('#add_house_keeper_modal');
        $input = $this->waitForElement('#house_keeper_name');
        if($input instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $input->clear();
            $input->value($name);
        }

        $this->saveHouseKeeperForm($modal);
    }

    protected function saveHouseKeeperForm($modal){
        $this->waitForElement('.save_house_keeper')->click();
        $this->waitUntilVisible($modal);
    }

    protected function openHKTabs($tabName = 'house_keepers') {
        if($tabName == 'house_keepers') $tabName = 'tab_hk_2'; else $tabName = 'tab_hk_1';
        $tab = $this->waitForElement('#layout [href=\'#'.$tabName.'\']', 30000, 'jQ');

        if($tab instanceof \PHPUnit_Extensions_Selenium2TestCase_Element)
            $tab->click();
        else $this->fail('Tab ' . $tabName . ' wasn\'t found.');
    }

    protected function HKFeatureToggle($platform, $val) {
        $isPMS = $platform == 'PMS';
        $result = $this->_prepare_crm_test();
        $editModal = $this->openFeaturesTab($result['row']);
        $this->switchPlatform($platform);
        //check visibility, have to be visible only for PMS (SPRINT OperationGoCommando)
        $this->assertEquals($isPMS, $this->getFeatureIsVisible($this->house_keeping_crm_js_name), 'Wrong Feature visibility "House Keeping"');

        if($isPMS) {
            $this->toggleFeature($this->house_keeping_crm_js_name, $val);
        }

        $this->saveAccount($editModal);

        $this->assertPageExists($this->house_keeping_url, $val && $isPMS);
    }

    protected function assertPageExists($url, $expected) {
        $this->_customLoginToSite();

        $url = $this->_prepareUrl($url);
        $this->url($url);
        $this->waitForBETLoaded();

        $href = $this->execJS('return window.location.href');
        $isExists = $href == $url;

        $this->assertEquals($expected, $isExists, "Page: $url have " . ($expected ? ' to be ' : 'not to be') . " exists, but currently it does" . ($isExists ? '' : ' not'));
    }

    protected function _prepare_crm_test() {
        $this->go_to_crm_page();
        $account_row = $this->filter_accounts($this->account_filter);
        return array('row' => $account_row, 'id' => $this->getAccountId($account_row));
    }

    protected function _prepare_house_keeping_test($skip_crm = false) {
        if($skip_crm) $this->go_to_house_keeping_page();
        else $this->HKFeatureToggle('PMS', 1);
    }

    public function _customLoginToSite() {
        if($this->isMiniBase) {
            $site_login = $this->login;
            $site_pass = $this->password;

            $this->login = 'admin@test.test';
            $this->password = '123qwe';
        }

        $this->loginToSite();

        if($this->isMiniBase) {
            $this->login = $site_login;
            $this->password = $site_pass;
        }
    }

    public function go_to_house_keeping_page() {
        $this->_customLoginToSite();

        $hk_page = $this->_prepareUrl($this->house_keeping_url);
        $this->url($hk_page);
        $this->waitForBETLoaded();
    }

    public function go_to_crm_page() {
        $this->_customLoginToSite();

        $server_url = $this->server_url;
        $this->server_url = 'acessa.loc';
        $crm_accounts = $this->_prepareUrl($this->crmAccountsUrl);
        $this->url($crm_accounts);
        $this->waitForLocation($crm_accounts);
        $this->server_url = $server_url;
    }

    public function waitIfLocal($time = 1000000) {
        if(getenv('SELENIUM_LOCAL') == 1)
            usleep($time);
    }
}