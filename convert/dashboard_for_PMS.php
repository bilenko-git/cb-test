<?php
//HOTELS-10984
namespace MyProject\Tests;
require_once 'test_restrict.php';

class dashboard_for_PMS extends test_restrict{
    private $dashboard = 'http://{server}/connect/{property_id}';

    public function testTodayArrivals(){
        $this->go_to_dashboard();
        $topArrivals = $this->_getText('#layout .round_arrivals');
        print "topArrivals : $topArrivals" . PHP_EOL;
        if($topArrivals !== FALSE) {
            if($this->_goToTab('#layout .reservations-nav a[data-tab=\'arrivals\']')) {
                $tableArrivals = $this->_getTableElementsCount('#tab_arrivals-today .arrivals-today tbody');
                print "tableArrivals : $tableArrivals" . PHP_EOL;
                $this->assertEquals($tableArrivals, $topArrivals, 'Arrivals are not equal!');

                $checkTableArrivals = $this->_checkArrivalsTableElements('#tab_arrivals-today .arrivals-today tbody');
                print "check tableArrivals : $checkTableArrivals" . PHP_EOL;
                $this->assertEquals($checkTableArrivals, $topArrivals, 'Arrivals has reservations with wrong statuses');
            } else {
                $this->fail('Fail go to tab arrivals');
            }
        } else {
            $this->fail('Arrivals Count on Top not found');
        }
    }
    public function testTodayDepartures(){
        $this->go_to_dashboard();
        $topDepartures = $this->_getText('#layout .round_departures');
        print "topDepartures : $topDepartures" . PHP_EOL;
        if($topDepartures !== FALSE){
            if($this->_goToTab('#layout .reservations-nav a[data-tab=\'departures\']')){
                $tableDepartures = $this->_getTableElementsCount('#tab_departures-today .departures-today tbody');
                print "tableDepartures : $tableDepartures" . PHP_EOL;
                $this->assertEquals($tableDepartures, $topDepartures, 'Departures are not equal!');

                $checkTableDepartures = $this->_checkDeparturesTableElements('#tab_departures-today .departures-today tbody');
                print "check tableDepartures : $checkTableDepartures" . PHP_EOL;
                $this->assertEquals($checkTableDepartures, $topDepartures, 'Departures has reservations with wrong statuses');
            } else {
                $this->fail('Fail go to tab departures');
            }
        } else {
            $this->fail('Departures Count on Top not found');
        }
    }

    private function _getText($selector){
        if(($element = $this->waitForElement($selector, 30000, 'jQ')) instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            return $element->text();
        }

        return false;
    }

    private function _getTableElementsCount($table_name){
        return $this->execute(array('script' => 'var cnt = 0; window.$("'.$table_name.'").find("tr").each(function(){ if(!$(this).find(\'td.dataTables_empty\').length) cnt++;}); return cnt;', 'args' => array()));
    }
    private function _checkArrivalsTableElements($table_name){
        return $this->execute(array('script' => 'var cnt = 0; window.$("'.$table_name.'").find("tr").each(function(){ if($(this).find(\'td .status_color_confirmed, td .status_color_not_confirmed\').length > 0) cnt++;}); return cnt;', 'args' => array()));
    }
    private function _checkDeparturesTableElements($table_name){
        return $this->execute(array('script' => 'var cnt = 0; window.$("'.$table_name.'").find("tr").each(function(){ if($(this).find(\'td .status_color_confirmed, td .status_color_not_confirmed, td .status_color_checked_in\').length > 0) cnt++;}); return cnt;', 'args' => array()));
    }

    public function go_to_dashboard(){
        //$this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);//for 31 hotel
        //  $this->setupInfo('', 'aleksandr.brus+20150715@cloudbeds.com', 'KNS16111988', 412);//for 412 - my demo hotel on dev3
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->dashboard));
        $this->waitForLocation($this->_prepareUrl($this->dashboard));
    }

    private function _goToTab($tab){
        if(($tab = $this->waitForElement($tab, 30000, 'jQ')) instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            $tab->click();
            return true;
        }

        return false;
    }
}