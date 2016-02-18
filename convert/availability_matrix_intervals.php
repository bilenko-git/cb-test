<?php
require_once('availability_matrix.php');
require_once('common/rates.php');

class availability_matrix_intervals_editor extends \MyProject\Tests\availability_matrix {
    use \Rates;

    private $intervals = array(
        'one_date_range' => array(
            'room_types' => '*:0:1',
            'intervals' => array(
                array(
                    'start_date' => '+1 days',
                    'end_date' => '+10 days',
                    'days_of_week' => array(
                        1 => false,
                        3 => false
                    )
                )
            ),
            'set_parameters' => array(
                //'available' => 2,
                'rate' => '*',
                /*'MinLOS' => 1,
                'MaxLOS' => 2,
                'ClosedArrival' => true,
                'ClosedDeparture' => true,
                'closed' => true*/
            )
        ),
        'two_date_range_not_intersect' => array(
            'room_types' => '*:0:1',
            'intervals' => array(
                array(
                    'start_date' => '+11 days',
                    'end_date' => '+20 days',
                    'days_of_week' => array(
                        1 => false,
                        3 => false
                    )
                ),
                array(
                    'start_date' => '+11 days',
                    'end_date' => '+20 days',
                    'days_of_week' => array(
                        0 => false,
                        2 => false,
                        4 => false,
                        5 => false,
                        6 => false
                    )
                )
            ),
            'set_parameters' => array(
                //'available' => 2,
                'rate' => '*',
                /*'MinLOS' => 1,
                'MaxLOS' => 2,
                'ClosedArrival' => true,
                'ClosedDeparture' => true,
                'closed' => true*/
            )
        ),
        'two_date_range_intersect' => array(
            'room_types' => '*:0:1',
            'intervals' => array(
                array(
                    'start_date' => '+11 days',
                    'end_date' => '+20 days',
                    'days_of_week' => array(
                        1 => false,
                        3 => false
                    )
                ),
                array(
                    'start_date' => '+11 days',
                    'end_date' => '+20 days',
                    'days_of_week' => array()
                )
            ),
            'set_parameters' => array(
                //'available' => 2,
                'rate' => '*',
                /*'MinLOS' => 1,
                'MaxLOS' => 2,
                'ClosedArrival' => true,
                'ClosedDeparture' => true,
                'closed' => true*/
            )
        )
    );
    private $insert_intervals =
        array(
            'n1' => array(
                'n1-1' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+2 days',
                            'end_date' => '+4 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 2
                    ),
                    'need_save' => false,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+10 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n1-1'
                    )
                ),
                'n1-2' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+2 days',
                            'end_date' => '+4 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 3
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+1 day'
                            ),
                            array(
                                'start_date' => '+2 day',
                                'end_date' => '+4 day'
                            ),
                            array(
                                'start_date' => '+5 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+10 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n1-2'
                    )
                ),
                'n1-3' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+1 days',
                            'end_date' => '+4 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 2,
                        'MinLOS' => 2
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+4 day'
                            ),
                            array(
                                'start_date' => '+5 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+10 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n1-3'
                    )
                )
            ),
            'n2' => array(
                'n2-1' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+9 days',
                            'end_date' => '+11 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 2
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+9 day',
                                'end_date' => '+11 day'
                            ),
                            array(
                                'start_date' => '+12 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n2-1'
                    )
                ),
                'n2-2' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+2 days',
                            'end_date' => '+8 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 4,
                        'MinLOS' => 2
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+1 day'
                            ),
                            array(
                                'start_date' => '+2 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+8 day',
                                'end_date' => '+8 day'
                            ),
                            array(
                                'start_date' => '+10 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n2-2'
                    )
                )
            ),
            'n4' => array(
                'n4-1' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+5 days',
                            'end_date' => '+12 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 4
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+4 day'
                            ),
                            array(
                                'start_date' => '+5 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+8 day',
                                'end_date' => '+9 day'
                            ),
                            array(
                                'start_date' => '+10 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n4-1'
                    )
                )
            ),
            'n5' => array(
                'n5-1' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+1 days',
                            'end_date' => '+8 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 2
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+8 day',
                                'end_date' => '+8 day'
                            ),
                            array(
                                'start_date' => '+10 day',
                                'end_date' => '+14 day'
                            ),
                            array(
                                'start_date' => '+18 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n5-1'
                    )
                )
            ),
            'n6' => array(
                'n6-1' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+7 days',
                            'end_date' => '+22 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 2
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+8 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n6-1'
                    )
                )
            ),
            'n7' => array(
                'n7-1' => array(
                    'intervals' => array(
                        array(
                            'start_date' => '+5 days',
                            'end_date' => '+20 days',
                            'days_of_week' => array()
                        )
                    ),
                    'set_parameters' => array(
                        'rate' => 2
                    ),
                    'need_save' => true,
                    'expected' => array(
                        'intervals' => array(
                            array(
                                'start_date' => '+1 day',
                                'end_date' => '+7 day'
                            ),
                            array(
                                'start_date' => '+8 day',
                                'end_date' => '+20 day'
                            ),
                            array(
                                'start_date' => '+21 day',
                                'end_date' => '+22 day'
                            )
                        ),
                        'name' => 'n7-1'
                    )
                )
            )
        );

    private $std_intervals = array(
        'i1' => array(
            'name' => 'rate 1',
            'start' => '+1 day',
            'end' => '+7 day',
            'min' => 1,
            'max' => 5,
            'value_today' => 2
        ),
        'i2' => array(
            'name' => 'rate 2',
            'start' => '+10 day',
            'end' => '+14 day',
            'value_today' => 4
        ),
        'i3' => array(
            'name' => 'rate 3',
            'start' => '+18 day',
            'end' => '+22 day',
            'value_today' => 6
        )
    );

    /**
     * @param $expr *[1] - room type_id, *[2] - package_id, *[3] - count of room types/packages
     * @return array
     */
    public function prepareRoomTypes($expr = '*:*:*') {
        $items = $this->getRoomTypes(false);
        list($rmt_id, $pck_id, $cnt) = explode(':', $expr);

        $room_types = array();
        foreach($items as $item) {
            if($rmt_id != '*' && $item['room_type_id'] != $rmt_id) continue;
            if($pck_id != '*' && $item['package_id'] != $pck_id) continue;
            $room_types[] = $item;
        }

        if($cnt == '*' || $cnt <= count($room_types)) return $room_types;

        $rand = array_rand($room_types, $cnt);
        $result = array();

        foreach($rand as $rnd){
            $result[] = $room_types[$rnd];
        }

        return $result;
    }

    public function prepareDateRanges($expr = array()) {
        $result = array();
        foreach($expr as $i => $rng) {
            $one = array();
            foreach($rng as $key => $value) {
                if(is_array($value)) {
                    foreach($value as $j => $v) {
                        $one[$key . '[' . $i . '][' . $j . ']'] = $v;
                    }
                } else {
                    $one[$key . '[' . $i . ']'] = $this->convertDateToSiteFormat($value);
                }
            }

            $result[] = $one;
        }

        return $result;
    }

    public function createInterval($data) {
        $long_interval_btn = $this->waitForElement('#layout .add_interval', 15000, 'jQ');
        $long_interval_btn->click();

        $form = $this->waitForElement('.availability-interval-editor');

        $room_types = $this->prepareRoomTypes($data['room_types']);
        $intervals = $this->prepareDateRanges($data['intervals']);
        $set_params = $data['set_parameters'];

        $this->selectPickerValue('room_types', array_map(function($a){return $a['package_id'] . '-' . $a['room_type_id'];}, $room_types));
        $this->selectPickerValue('set_parameters', array_keys($set_params));

        $add_range_btn = $this->waitForElement('.availability-interval-editor .add-range-btn', 15000, 'jQ');
        foreach($intervals as $ind => $dt_range) {
            foreach($dt_range as $k => $v) {
                $rmt_input = $this->waitForElement('.availability-interval-editor [name=\'' . $k .'\']', 15000, 'jQ', false);
                if($rmt_input->attribute('type') != 'checkbox') {
                    $rmt_input->click();
                    $this->fillDate($k, $v);
                    $form->click();//to lose focus from datepickers
                } else {
                    $is_checked = $this->execute(array('script' => 'return $(\'input[name="'.$k.'"]\').is(\':checked\');', 'args' => array()));
                    $label_for_checkbox = $this->byCssSelector('label[data-for="'.$k.'"]');

                    if($is_checked != (bool)$v) {
                        $label_for_checkbox->click();
                    }
                }
            }

            if($ind < count($intervals) -1) {
                $add_range_btn->click();
            }
        }

        foreach($set_params as $k => $v) {
            $param = $this->waitForElement('[data-param-name="' . $k . '"]', 15000, 'css', false);
            if($param->attribute('type') != 'checkbox') {
                $param->value($v);
            } else {
                $this->execute(array(
                    'script' => '$(arguments[0]).bootstrapSwitch(\'toggleState\', arguments[1]);',
                    'args' => array(
                        $param->toWebDriverObject(),
                        !$v ? 1 : 0 //here is reverse order because params is opened, openedArrival, openedDeparture, but we use closed, ClosedArrival ClosedDeprature
                    )
                ));
            }
        }

        $form->click();
        $add_interval_btn = $this->waitForElement('a[type=\'submit\']', 15000, 'jQ');
        $add_interval_btn->click();

        return array('room_types' => $room_types);
    }

    public function getBaseRates() {
        return $this->execute(array(
            'script' => 'return BET.roomRates.items()',
            'args' => array()
        ));
    }

    public function _simple_interval_test($name, callable $custom_func = NULL) {
        $this->prepareTest();

        if(isset($this->intervals[$name]['set_parameters']['rate']) && $this->intervals[$name]['set_parameters']['rate'] == '*')
            $this->intervals[$name]['set_parameters']['rate'] = rand(1, 50);

        $result = $this->createInterval($this->intervals[$name]);

        if($custom_func) $custom_func();
        else {
            $this->saveAvailability();
            foreach($result['room_types'] as $rmt) {
                foreach($this->intervals[$name]['intervals'] as $interval) {
                    $this->checkIntervalCache($rmt, $this->intervals[$name], date('Y-m-d', strtotime($interval['start_date'])), date('Y-m-d', strtotime($interval['end_date'])));
                }
            }
        }
    }

    public function checkIntervalCache($rm_type, $range, $startDate, $endDate)
    {
        $cache = $this->apiCall($this->apiGetCache, array(
            'property_id' => $this->property_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'group_by' => 'room_date'
        ));

        $expectedAvailability = $this->getExpectedValues($range);
        $currentAvailability = $cache[$rm_type['room_type_id'] . '-' . $rm_type['package_id']];

        foreach($expectedAvailability as $date => $cell) {
            if(isset($currentAvailability[$date])) {
                foreach($cell as $k => $v) {
                    if($k == 'rate') $v = floatval($v);
                    $this->assertEquals($v, floatval($currentAvailability[$date][$k]), 'Check cache for ' . $date . ' failed. Param: ' . $k . ' is wrong.');
                }
            }
        }
    }

    public function getExpectedValues($range) {
        $result = array();
        foreach($range['intervals'] as $interval) {
            foreach($this->datesRange($interval['start_date'], $interval['end_date']) as $dt) {
                $dw = $dt->format('w');
                if(!isset($interval['days_of_week'][$dw]) || $interval['days_of_week'][$dw]) {
                    $result[$dt->format('Y-m-d')] = array();
                    foreach($range['set_parameters'] as $param => $value) {
                        if($param == 'rate') $value = floatval($value);
                        $result[$dt->format('Y-m-d')][$this->cache_fields_map[$param]] = $value;
                    }
                }
            }
        }

        return $result;
    }

    public function datesRange($start_date, $end_date){
        $result = array();
        $startTime = strtotime( $start_date );
        $endTime = strtotime( $end_date );

        for ( $i = $startTime; $i <= $endTime; $i += 86400 ) {
            $date = new DateTime();
            $date->setTimestamp($i);
            $result[] = $date;
        }

        return $result;
    }

    public function createRoomType(){
        $rmt = array(
            'name' => 'room type selenium 1',
            'rooms' => 1,
            'room_type_descr_langs' => 'room types used for selenium testing availability and base rates'
        );

        $this->roomtype_delRoomType($rmt);

        $room_type_id = $this->roomtype_addRoomType($rmt);
        return array_merge($rmt, array('room_type_id' => $room_type_id));
    }

    public function setDefaultRates($rmt) {
        $this->rate_delAllRates($rmt);

        foreach($this->std_intervals as $std_int) {
            $this->rate_addRate($std_int, $rmt);
        }
    }

    public function checkIntervalBaseRates($apply) {
        $rates = $this->getBaseRates();
        $rate = $this->getRate($rates, $apply['room_type_id']);
        if(!$rate) $this->fail('rate not found');

        foreach($rate['intervals'] as $int) {
            $found = false;
            foreach($apply['expected']['intervals'] as $int2) {
                $int2['start_date'] = date('Y-m-d', strtotime($int2['start_date']));
                $int2['end_date'] = date('Y-m-d', strtotime($int2['end_date']));

                if($int['start_date'] == $int2['start_date'] && $int['end_date'] == $int2['end_date']) {
                    $found = true;
                }
            }

            $this->assertEquals(true, $found, 'Interval not matching with expected ' . $apply['expected']['name']);
        }
    }

    public function getRate($rates, $room_type_id) {
        $rate = false;
        foreach($rates as $r) {
            if($r['room_type_id'] == $room_type_id) {
                $rate = $r;
                break;
            }
        }

        return $rate;
    }

    public function _base_rates_test($name) {
        $this->prepareTest();

        $rmt = $this->createRoomType();

        foreach($this->insert_intervals[$name] as $new_int) {
            $this->setDefaultRates($rmt);//remove old rates - set 3 default rates for testing
            $this->_go_availability_matrix();

            $new_int['room_types'] = $rmt['room_type_id'] . ':0:1';
            $new_int['room_type_id'] = $rmt['room_type_id'];

            $this->createInterval($new_int);
            if(!empty($new_int['need_save']))
                $this->saveAvailability();
            else {
                sleep(5);
                $save_btn = $this->waitForElement('.btn-save', 15000, 'jQ', false);
                if($save_btn instanceof \PHPUnit_Extensions_Selenium2TestCase_Element)
                    if($save_btn->displayed())
                        $this->fail('Save panel should not be displayed.');
            }

            $this->checkIntervalCache(array_merge($rmt, array('package_id' => 0)), $new_int, date('Y-m-d', strtotime($new_int['intervals'][0]['start_date'])), date('Y-m-d', strtotime($new_int['intervals'][0]['end_date'])));
            $this->checkIntervalBaseRates($new_int);
        }

        $this->roomtype_delRoomType($rmt);//finish remove test data
    }

    public function test_one_interval() {
        $this->_simple_interval_test('one_date_range');
    }

    public function test_two_intervals_ni() {
        $this->_simple_interval_test('two_date_range_not_intersect');
    }

    public function test_two_intervals_i() {
        $this->_simple_interval_test('two_date_range_intersect', function() {
            $this->waitForElement('#error_modal', 20000, 'jQ', true);
        });
    }

    public function test_availability_to_rates_n1() {
        $this->_base_rates_test('n1');
    }

    public function test_availability_to_rates_n2() {
        $this->_base_rates_test('n2');
    }

    public function test_availability_to_rates_n4() {
        $this->_base_rates_test('n4');
    }

    public function test_availability_to_rates_n5() {
        $this->_base_rates_test('n5');
    }

    public function test_availability_to_rates_n6() {
        $this->_base_rates_test('n6');
    }

    public function test_availability_to_rates_n7() {
        $this->_base_rates_test('n7');
    }

    /*
        public function test_add_interval_with_calendar_custom_price(){}
    */
}