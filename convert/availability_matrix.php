<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class availability_matrix extends test_restrict {
    protected $availability_matrix_url = 'http://{server}/connect/{property_id}#/availability';
    //protected $server_url = 'hotel.acessa.loc';
    protected $property_id = 1;
    protected $apiGetAvailability = 'http://{server}/api/tests/getAvailability';
    protected $apiGetCache = 'http://{server}/api/tests/getInventoryCache';
    protected $allowedOverBooking = false;

    private $availaility_fields_map = array(
        'saleable' => 'sell',
        'closed' => 'closed',
        'rate' => 'rate',
        'MinLOS' => 'min_los',
        'MaxLOS' => 'max_los',
        'ClosedArrival' => 'closed_to_arrival',
        'ClosedDeparture' => 'closed_to_departure'
    );

    protected $cache_fields_map = array(
        'saleable' => 'avail',
        'available' => 'avail',
        'closed' => 'closed',
        'rate' => 'rate',
        'MinLOS' => 'min_los',
        'MaxLOS' => 'max_los',
        'ClosedArrival' => 'closed_to_arrival',
        'ClosedDeparture' => 'closed_to_departure'
    );

    private $realAvailability = array();
    private $roomTypes = array();

    private $availabilityValues = array(
        array(
            '+1 days' => array(
                'saleable' => '+2', //available +2
                'rate' => 1.99,
                'MinLOS' => 2,
                'MaxLOS' => 5,
                'closed' => 0,
                'ClosedArrival' => 0,
                'ClosedDeparture' => 1
            )
        ),
        array(
            '+1 days' => array(
                'saleable' => 0, //available +2
                'rate' => 1.99,
                'MinLOS' => 2,
                'MaxLOS' => 5,
                'closed' => 0,
                'ClosedArrival' => 0,
                'ClosedDeparture' => 1
            ),
        ),
        array(
            '+4 days' => array(
                'saleable' => '+2', //available +2
                'rate' => 2.99,
                'MinLOS' => 1,
                'MaxLOS' => 5,
                'closed' => 1,
                'ClosedArrival' => 1,
                'ClosedDeparture' => 1
            )
        ),
        array(
            '+2 days' => array(
                'saleable' => 7,
                'rate' => 2.59,
                'MinLOS' => 0,
                'MaxLOS' => 5,
                'closed' => 1,
                'ClosedArrival' => 0,
                'ClosedDeparture' => 0
            ),
            '+3 days' => array(
                'saleable' => 7,
                'rate' => 5.00,
                'MinLOS' => 4,
                'MaxLOS' => 10,
                'closed' => 0,
                'ClosedArrival' => 1,
                'ClosedDeparture' => 0
            )
        )
    );

    private $availabilityValuesIndex = 0;

    public function testOverBooking(){
        $this->availabilityValuesIndex = 0;

        $this->prepareTest();
        $this->allowOverBooking();
        sleep(3);
        $this->roomTypes = $this->getRoomTypes();
        $index = 0;

        if(!empty($this->roomTypes)) {
            $minTimestamp = false;
            $maxTimestamp = false;

            $avValues = $this->availabilityValues[$this->availabilityValuesIndex];
            foreach ($avValues as $date => $values) {
                $cellTimestamp = strtotime($date);

                if($minTimestamp === false || $cellTimestamp < $minTimestamp)
                    $minTimestamp = $cellTimestamp;
                if($maxTimestamp === false || $cellTimestamp > $maxTimestamp)
                    $maxTimestamp = $cellTimestamp;

                $cellDate = date('Y-m-d', $cellTimestamp);
                $this->setAvailabilityCell($index, $cellDate, $values);
            }

            $this->saveAvailability();

            if($minTimestamp != false && $maxTimestamp != false)
                $this->checkAvailability($this->roomTypes[$index], date('Y-m-d', $minTimestamp), date('Y-m-d', $maxTimestamp));
            else $this->fail('Probably changes of availability is empty');
        } else {
            $this->fail('Room types and packages doesn\'t exist on availability matrix');
        }
    }

    public function testAvailabilityValues(){
        $this->availabilityValuesIndex = 1;

        $this->prepareTest();
        sleep(3);
        $this->roomTypes = $this->getRoomTypes();
        $index = 0;

        if(!empty($this->roomTypes)) {
            $minTimestamp = false;
            $maxTimestamp = false;

            $avValues = $this->availabilityValues[$this->availabilityValuesIndex];
            foreach ($avValues as $date => $values) {
                $cellTimestamp = strtotime($date);

                if($minTimestamp === false || $cellTimestamp < $minTimestamp)
                    $minTimestamp = $cellTimestamp;
                if($maxTimestamp === false || $cellTimestamp > $maxTimestamp)
                    $maxTimestamp = $cellTimestamp;

                $cellDate = date('Y-m-d', $cellTimestamp);
                $this->setAvailabilityCell($index, $cellDate, $values);
            }

            $this->saveAvailability();

            if($minTimestamp != false && $maxTimestamp != false)
                $this->checkAvailability($this->roomTypes[$index], date('Y-m-d', $minTimestamp), date('Y-m-d', $maxTimestamp));
            else $this->fail('Probably changes of availability is empty');
        } else {
            $this->fail('Room types and packages doesn\'t exist on availability matrix');
        }
    }

    public function testNotAllowedOverBooking(){
        $this->availabilityValuesIndex = 2;

        $this->prepareTest();
        $this->allowOverBooking(false);
        sleep(3);
        $this->roomTypes = $this->getRoomTypes();
        $index = 0;

        if(!empty($this->roomTypes)) {
            $minTimestamp = false;
            $maxTimestamp = false;

            $avValues = $this->availabilityValues[$this->availabilityValuesIndex];
            foreach ($avValues as $date => $values) {
                $cellTimestamp = strtotime($date);

                if($minTimestamp === false || $cellTimestamp < $minTimestamp)
                    $minTimestamp = $cellTimestamp;
                if($maxTimestamp === false || $cellTimestamp > $maxTimestamp)
                    $maxTimestamp = $cellTimestamp;

                $cellDate = date('Y-m-d', $cellTimestamp);
                $this->setAvailabilityCell($index, $cellDate, $values);
            }

            $this->saveAvailability();

            if($minTimestamp != false && $maxTimestamp != false)
                $this->checkAvailability($this->roomTypes[$index], date('Y-m-d', $minTimestamp), date('Y-m-d', $maxTimestamp));
            else $this->fail('Probably changes of availability is empty');
        } else {
            $this->fail('Room types and packages doesn\'t exist on availability matrix');
        }
    }

    public function testEditValues(){
        $this->availabilityValuesIndex = 3;

        $this->prepareTest();
        $this->allowOverBooking();

        sleep(3);
        $this->roomTypes = $this->getRoomTypes();
        $index = 0;
        if(!empty($this->roomTypes)) {
            $minTimestamp = false;
            $maxTimestamp = false;
            $avValue = $this->availabilityValues[$this->availabilityValuesIndex];
            foreach ($avValue as $date => $values) {
                $cellTimestamp = strtotime($date);

                if($minTimestamp === false || $cellTimestamp < $minTimestamp)
                    $minTimestamp = $cellTimestamp;
                if($maxTimestamp === false || $cellTimestamp > $maxTimestamp)
                    $maxTimestamp = $cellTimestamp;

                $cellDate = date('Y-m-d', $cellTimestamp);
                $this->setAvailabilityCell($index, $cellDate, $values);
            }

            $this->saveAvailability();

            if($minTimestamp != false && $maxTimestamp != false)
                $this->checkAvailability($this->roomTypes[$index], date('Y-m-d', $minTimestamp), date('Y-m-d', $maxTimestamp));
            else $this->fail('Probably changes of availability is empty');

        } else {
            $this->fail('Room types and packages doesn\'t exist on availability matrix');
        }
    }

    public function allowOverBooking($value = true){
        if($value) {
            $this->waitForElement('[for="allow_ovb_1"]')->click();
            sleep(1);
            $this->waitForElement('#caution #continue')->click();
        } else {
            $this->waitForElement('[for="allow_ovb_2"]')->click();
        }

        $this->allowedOverBooking = $value;
    }

    public function getRoomTypes($from_matrix = true) {
        $result = array();

        if($from_matrix) {
            $rows = $this->elements($this->using('css selector')->value('.av_rooms .room_block'));
            foreach($rows as $row) {
                $one = array(
                    'room_type_id' => $row->attribute('data-room-type-id'),
                    'package_id' => $row->attribute('data-package-id')
                );

                if(is_numeric($one['room_type_id']))
                    $result[] = $one;
            }
        } else {
            $exec = $this->execute(array(
                'script' => 'return BET.roomTypes.items();',
                'args' => array()
            ));

            foreach($exec as $one) {
                $result[] = array(
                    'room_type_id' => $one['room_type_id'],
                    'package_id' => 0
                );
            }

            $exec = $this->execute(array(
                'script' => 'return BET.packages.room_types();',
                'args' => array()
            ));

            foreach($exec as $one) {
                $result[] = array(
                    'room_type_id' => $one['room_type_id'],
                    'package_id' => $one['package_id']
                );
            }
        }

        return $result;
    }
    public function saveAvailability(){
        $this->waitForElement('.btn-save')->click();
        //until loader showing
        $this->waitUntil(function($test) {
            $res = $test->execute(array('script' => "return window.$('.loading.layout-loading:visible').length", 'args' => array()));
            return $res==0 ? true : null;
        }, 50000);
    }
    public function checkAvailability($rm_type, $startDate, $endDate){
        $avail = $this->apiCall($this->apiGetAvailability, array(
            'property_id' => $this->property_id,
            'start_date' => $startDate,
            'end_date' => $endDate
        ));

        $cache = $this->apiCall($this->apiGetCache, array(
            'property_id' => $this->property_id,
            'start_date' => $startDate,
            'end_date' => $endDate
        ));

        $avail_availaiblity = !empty($avail[$rm_type['package_id'] . '-' . $rm_type['room_type_id']]) ? $avail[$rm_type['package_id'] . '-' . $rm_type['room_type_id']]['available'] : array();

        $prepared_cache = array();
        foreach($cache as $k => $v){
            $prepared_cache[$v['package_id'] . '-' . $v['room_type_id'] . '|' . $v['date']] = $v;
        }

        foreach($this->availabilityValues[$this->availabilityValuesIndex] as $date => $values){
            $date = date('Y-m-d', strtotime($date));
            $dateCheck = !empty($avail_availaiblity[$date]) ? $avail_availaiblity[$date] : array();
            $cacheDateCheck = !empty($prepared_cache[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date]) ? $prepared_cache[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date] : array();

            foreach($values as $k => $v){
                //Availability Checks
                $av_actual = isset($dateCheck[$this->availaility_fields_map[$k]]) ? $dateCheck[$this->availaility_fields_map[$k]] : 0;
                if($av_actual === 'false' || $av_actual === '') $av_actual = 0;
                if($av_actual === 'true') $av_actual = 1;
                if($k == 'saleable' && in_array($v[0], array('+', '-'))) {
                    if(!empty($this->allowedOverBooking)){
                        $v = $v + $this->realAvailability[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date];
                    } else {
                        $v = $this->realAvailability[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date];
                    }
                }

                $this->assertEquals($v, $av_actual, 'Av. Assert failed for: ' . $k . ' - ' . $av_actual . ' ?? ' . $v);

                //Cache Checks
                $cc_actual = isset($cacheDateCheck[$this->cache_fields_map[$k]]) ? $cacheDateCheck[$this->cache_fields_map[$k]] : 0;
                if($cc_actual === 'false' || $cc_actual === '') $cc_actual = 0;
                if($cc_actual === 'true') $cc_actual = 1;
                if($k == 'saleable' && in_array($v[0], array('+', '-'))) {
                    if(!empty($allowedOverBooking)){
                        $v = $v + $this->realAvailability[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date];
                    } else {
                        $v = $this->realAvailability[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date];
                    }
                }

                $this->assertEquals($v, $cc_actual, 'Cache Assert failed for: ' . $k . ' - ' . $cc_actual . ' ?? ' . $v);
            }
        }
    }

    public function prepareTest(){
        $this->go_to_availability_page();
    }

    public function setAvailabilityCell($rowIndex, $date, $values){
        $cell = $this->waitForElement('.table.av_interval tbody tr:eq('.$rowIndex.') td[data-date=\''.$date.'\']', 15000, 'jQ');
        if($cell instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            foreach($values as $k => $v){
                $isOverBooked = false;
                if(stripos($k, 'closed') !== FALSE){
                    $cell_row = $cell->byCssSelector('[data-row=\''.$k.'\']');
                    $cell_checkbox = $cell_row->byCssSelector('[type=\'checkbox\']');
                    if($cell_checkbox->selected() != $v){
                        $cell_row->byCssSelector('label')->click();
                    } else continue;
                } else {
                    $cell_row = $cell->byCssSelector('[data-row=\''.$k.'\'] a');
                    $cell_row->click();
                    if ($edit = $this->waitForElement('.custom_rooms', 15000, 'jQ')) {
                        if ($edit instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
                            if($k == 'rate') $v = $this->execJS('return BET.langs.formatCurrency(\''.$v.'\');');
                            if($k == 'saleable' && in_array($v[0], array('+', '-'))) {
                                $isOverBooked = ($v[0] == '+');

                                $rm_type = $this->roomTypes[$rowIndex];
                                $real_quantity = intval($cell->byCssSelector('.cell-row .total')->text());
                                $this->realAvailability[$rm_type['package_id'] . '-' . $rm_type['room_type_id'] . '|' . $date] = $real_quantity;
                                $v = $real_quantity + $v;
                            }

                            $edit->clear();
                            $edit->value($v);
                            $this->waitForElement('.popover.change_price .pop-confirm')->click();
                            echo PHP_EOL . 'is Over Booked: ' . $isOverBooked . PHP_EOL;
                            if($isOverBooked){
                                //click Continue in overbooking modal
                                $this->waitForElement('.availability-overbooking-confirm .continue-button', 15000, 'jQ')->click();
                            }
                        } else {
                            $this->fail('Edit value input not found');
                        }
                    }
                }
                sleep(1);
            }
        } else {
            $this->fail('Cell not found');
        }
    }

    public function go_to_availability_page(){
        /*$site_login = $this->login;
        $site_pass = $this->password;
        $site_server_url = $this->server_url;

        $this->login = 'admin@test.test';
        $this->password = '123qwe';*/

        $this->loginToSite();

        $this->_go_availability_matrix();
        $this->waitForBETLoaded();

        /*$this->login = $site_login;
        $this->password = $site_pass;
        $this->server_url = $site_server_url;*/
    }

    public function _go_availability_matrix() {
        $url = $this->_prepareUrl($this->availability_matrix_url);
        $this->url($url);
        $this->waitForLocation($url);
    }
}