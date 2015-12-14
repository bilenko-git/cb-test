<?php
namespace MyProject\Tests;

require_once 'test_restrict.php';
require_once 'common/features_control.php';

class house_keeping extends test_restrict {
    use \FeaturesControl;

    /* TESTS SETTINGS*/
    private $house_keeping_url = 'http://{server}/connect/{property_id}/#/house_keeping';
    protected $server_url = 'hotel.acessa.loc';
    protected $property_id = 1;

    /*TESTS FUNCTIONS*/
    public function testAddHouseKeeper(){}
    public function testRenameHouseKeeper(){}
    public function testRemoveHouseKeeper(){}

    public function testChangeFeature(){}
    public function testChangeRoomNamesFeature(){}
    public function testDisabledForOTA(){ //SPRINT: OperationGoCommando

    }

    public function testChangeInspections(){}
    public function testChangeRoomsInfo(){}

    public function testCron(){}
    public function testCalendarMoveInHouse(){}
    public function testBlocksOnCalendar(){}
    public function testOutOfOrderOnCalendar(){}

    public function testPrintInspections(){}
    public function testPDFInspections(){}
    public function testXLSInspections(){}


    /* SYSTEM FUNCTIONS TO REALIZE TESTS */
    public function go_to_house_keeping_page() {
        $site_login = $this->login;
        $site_pass = $this->password;
        $site_server_url = $this->server_url;

        $this->login = 'admin@test.test';
        $this->password = '123qwe';

        $this->loginToSite();

        $crm_accounts = $this->_prepareUrl($this->house_keeping_url);
        $this->url($crm_accounts);
        $this->waitForLocation($crm_accounts);

        $this->login = $site_login;
        $this->password = $site_pass;
        $this->server_url = $site_server_url;
    }
}