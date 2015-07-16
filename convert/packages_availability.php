<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class packages_availability extends test_restrict{
    private $packages_list_url = 'http://{server}/connect/{property_id}#/packages';
    /*protected $booking_url = 'http://{server}/reservas/{property_id}#checkin={date_from}&checkout={date_to}';*/
    protected $booking_rooms_url = 'http://{server}/booking/rooms';

    private $packages = array(
        array(
            '[name=\'package_name\']' => 'Selenium Pack 1',
            '[name=\'package_name_internal\']' => 'Selenium Pack 001',
            'is_derived' => false,//[name=\'derived\']
            'have_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 1 (test min/max los, ranges)',
                    'end_date' => '+20 days',
                    'start_date' => '+2 days',
                    'prices' => array(
                        '0' => '12',
                        '1' => '12',
                        '2' => '18',
                        '3' => '10',
                        '4' => '20',
                        '5' => '22',
                        '6' => '22'
                    ),
                    'min_los' => 2,
                    'max_los' => 4,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                )
            )
        ),
        array(
            '[name=\'package_name\']' => 'Selenium Pack 2',
            '[name=\'package_name_internal\']' => 'Selenium Pack 002',
            'is_derived' => false,//[name=\'derived\']
            'have_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 2 (test cut off)',
                    'end_date' => '+40 days',
                    'start_date' => '+21 days',
                    'prices' => array(
                        '0' => '12',
                        '1' => '12',
                        '2' => '18',
                        '3' => '10',
                        '4' => '20',
                        '5' => '22',
                        '6' => '22'
                    ),
                    'min_los' => 2,
                    'max_los' => 4,
                    'cut_off' => 2,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                ),
            )
        ),
        array(
            '[name=\'package_name\']' => 'Selenium Pack 3',
            '[name=\'package_name_internal\']' => 'Selenium Pack 003',
            'is_derived' => false,//[name=\'derived\']
            'have_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 3 (test LMB)',
                    'end_date' => '+60 days',
                    'start_date' => '+41 days',
                    'prices' => array(
                        '0' => '12',
                        '1' => '12',
                        '2' => '18',
                        '3' => '10',
                        '4' => '20',
                        '5' => '22',
                        '6' => '22'
                    ),
                    'min_los' => 2,
                    'max_los' => 4,
                    'cut_off' => 0,
                    'last_minute_booking' => 3,
                    'closed_to_arrival'  => false
                )
            )
        ),
        array(
            '[name=\'package_name\']' => 'Selenium Pack 4',
            '[name=\'package_name_internal\']' => 'Selenium Pack 004',
            'is_derived' => false,//[name=\'derived\']
            'have_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 4 (test CTA)',
                    'end_date' => '+80 days',
                    'start_date' => '+61 days',
                    'prices' => array(
                        '0' => '12',
                        '1' => '12',
                        '2' => '18',
                        '3' => '10',
                        '4' => '20',
                        '5' => '22',
                        '6' => '22'
                    ),
                    'min_los' => 0,
                    'max_los' => 0,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => true
                )
            )
        ),
        array(
            '[name=\'package_name\']' => 'Selenium Pack 5',
            '[name=\'package_name_internal\']' => 'Selenium Pack 005',
            'is_derived' => false,//[name=\'derived\']
            'have_promo' => true,//[name=\'have_promo\']
            'promo_code' => 'Xxkj3dDSd!',
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 5 (test CTA)',
                    'end_date' => '+100 days',
                    'start_date' => '+81 days',
                    'prices' => array(
                        '0' => '12',
                        '1' => '12',
                        '2' => '18',
                        '3' => '10',
                        '4' => '20',
                        '5' => '22',
                        '6' => '22'
                    ),
                    'min_los' => 0,
                    'max_los' => 0,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                )
            )
        ),
        array(
            '[name=\'package_name\']' => 'Selenium Pack 6',
            '[name=\'package_name_internal\']' => 'Selenium Pack 006',
            'is_derived' => true,//[name=\'derived\']
            '.action_rate' => '+',
            '.currency_rate' => 'fixed',
            '[name=\'derived_rate\']' => 5,
            'have_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 6 (test Derived)',
                    'end_date' => '+120 days',
                    'start_date' => '+101 days',
                    'min_los' => 0,
                    'max_los' => 0,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                )
            )
        ),
        array(
            '[name=\'package_name\']' => 'Selenium Pack 7',
            '[name=\'package_name_internal\']' => 'Selenium Pack 007',
            'is_derived' => true,//[name=\'derived\']
            '.action_rate' => '-',
            '.currency_rate' => 'percentage',
            '[name=\'derived_rate\']' => 5,
            'have_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 7 (test percentage Derived)',
                    'end_date' => '+140 days',
                    'start_date' => '+121 days',
                    'min_los' => 0,
                    'max_los' => 0,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                )
            )
        )
    );

    public function _update_and_verifyPackage($index){
        if(!empty($this->packages[$index])) {
            $package = $this->packages[$index];
            $package_id = $this->addPackage($package);
            echo 'package id = ' . $package_id . PHP_EOL;
            if(!$package_id) $this->fail('added package was not found');

            $this->_checkAvailability($package);
            $this->removePackage($package_id);
        } else {
            $this->fail('Package with such index for test doesn\'t exists, myabe data was corrupted.');
        }
    }

    public function _verifyPackage($index){
        if(!empty($this->packages[$index])){
            $package = $this->packages[$index];
            $package_id = $this->addPackage($package);
            echo 'package id = ' . $package_id . PHP_EOL;
            if(!$package_id) $this->fail('added package was not found');

            $this->_checkAvailability($package);
            $this->removePackage($package_id);
        }else{
            $this->fail('Package with such index for test doesn\'t exists, myabe data was corrupted.');
        }
    }

    public function go_to_package_page(){
        //$this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);//for 31 hotel
        $this->setupInfo('', 'aleksandr.brus+20150715@cloudbeds.com', 'KNS16111988', 412);//for 412 - my demo hotel on dev3

        $this->loginToSite();
        $this->url($this->_prepareUrl($this->packages_list_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_list_url));

        /*foreach($this->packages as $package) {
            $package_id = $this->addPackage($package);
            echo 'package id = ' . $package_id . PHP_EOL;
            if(!$package_id) $this->fail('added package was not found');

            $this->_checkAvailability($package);
            $this->removePackage($package_id);
        }*/
    }

/*    public function test_Range_Min_Max_los(){
        $this->go_to_package_page();
        $this->_verifyPackage(0);
    }
    public function test_Cut_off(){
        $this->go_to_package_page();
        $this->_verifyPackage(1);
    }
    public function test_Last_minute_booking(){
        $this->go_to_package_page();
        $this->_verifyPackage(2);
    }
    public function test_Closed_to_arrival(){
        $this->go_to_package_page();
        $this->_verifyPackage(3);
    }
    public function test_Promo_code(){
        $this->go_to_package_page();
        $this->_verifyPackage(4);
    }
    public function test_Derived_fixed_package(){
        $this->go_to_package_page();
        $this->_verifyPackage(5);
    }*/
    public function test_Derived_percentage_package(){
        $this->go_to_package_page();
        $this->_verifyPackage(6);
    }
/*    public function test_Package_update(){
        $this->go_to_package_page();
        $this->_update_and_verifyPackage(0);
    }*/

    public function getLastPackagesID() {
        $last_tr = $this->waitForElement('#layout .packages-table tbody > tr[data-id]:last', 5000, 'jQ');

        if($last_tr){
            return $this->getAttribute($last_tr, 'data-id');
        }

        return false;
    }

    public function removePackage($package_id) {
        $delete_package_btn = $this->waitForElement('#layout .packages-table tbody > tr[data-id=\''.$package_id.'\'] .action-btn.delete', 5000, 'jQ');
        sleep(1);
        $delete_package_btn->click();

        $delete_modal = $this->waitForElement('#confirm_delete', 15000);
        $this->waitForElement('.btn_delete', 5000)->click();
    }

    public function checkPackageNeedToBeExists($reservation_from, $reservation_to, $start_date, $end_date, $min_los = false, $max_los = false, $cut_off = false, $last_mb = false, $closed_to_arrival = false, $is_promo = false, $promo_code = ''){
        echo 'Check if package have to exists'.PHP_EOL;
        if($closed_to_arrival) {
            echo 'Closed to arrival check failed';
            return false;
        }
        if($is_promo && empty($promo_code)) {
            echo 'Promo check failed';
            return false;
        }

        $result = true;

        $today = date('Y-m-d');

        if($cut_off > 0){
            if($this->diffDates($start_date, $today, false) < $cut_off){
                echo 'Cut off check failed';
                $result = false;
            }
        }

        if($result && $last_mb > 0){
            if($this->diffDates($start_date, $today, false) > $last_mb){
                echo 'LMB check failed';
                $result = false;
            }
        }

        if($result) {
            $days = $this->diffDates($reservation_from, $reservation_to);
            if ($min_los > 0 && $days < $min_los) {
                echo 'Min LOS check failed';
                $result = false;
            }

            if ($max_los > 0 && $days > $max_los) {
                echo 'Max LOS check failed';
                $result = false;
            }
        }

        if($result) {
            $result = $this->date_in_range($reservation_from, $start_date, $end_date) && $this->date_in_range($reservation_to, $start_date, $end_date);
            if(!$result){
                echo 'Range check failed';
            }
        }

        return $result;
    }

    public function date_in_range($date, $start, $end){
        $start_ts = strtotime($start, mktime(0,0,0));
        $end_ts = strtotime($end, mktime(0,0,0));
        $user_ts = strtotime($date, mktime(0,0,0));

        // Check that user date is between start & end
        $result = (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
        if(!$result){
            echo 'date: ' . date('d.m.Y', strtotime($date)) . ' not between [' . date('d.m.Y', strtotime($start)) . ', ' . date('d.m.Y', strtotime($end)) . ']'.PHP_EOL;
            echo 'date: ' . $user_ts . PHP_EOL;
            echo 'start: ' . $start_ts . PHP_EOL;
            echo 'end: ' . $end_ts . PHP_EOL;
        }
        return $result;
    }

    public function _checkAvailability($package){
        $is_derived = !empty($package['is_derived']);

        if($is_derived)
            $clean_up_charge = 0.0;
        else
            $clean_up_charge = (float) $this->execute(array(
                'script' => 'return BET.roomRates.charge_clean_up_room();',
                'args' => array()
            ));

        echo '~~~~~~~~~Cache checking....~~~~~~~~~'.PHP_EOL;

        foreach($package['ranges'] as $range) {
            $date_from = $this->convertDateToSiteFormat($range['start_date'], 'Y-m-d');
            $date_to = $this->convertDateToSiteFormat($range['end_date'], 'Y-m-d');

            $base_rate_by_days = array();
            if($is_derived){
                $base_rate_availability = $this->getAvailability($date_from, $date_to, $range['rm_type_id'], 0, true);
                print_r($base_rate_availability);
                foreach($base_rate_availability['data'] as $assoc) {
                    if ($assoc['id'] != 0) continue;//now only check base rate; TODO: associations rates check

                    foreach($assoc['rates'] as $rate_id => $dates) {
                        foreach($dates as $currentDate => $value) {
                            if ($value['package_id'] == 0 && $range['rm_type_id'] == $value['room_type_id']) {
                                $base_rate_by_days[$currentDate] = $value;
                            }
                        }
                    }
                }
                print_r($base_rate_by_days);
            }

            $availability = $this->getAvailability($date_from, $date_to, $range['rm_type_id'], $package['package_id'], true);
            print_r($availability);

            $checked = false;
            foreach($availability['data'] as $assoc){
                if($assoc['id'] != 0) continue;//now only check base rate; TODO: associations rates check

                foreach($assoc['rates'] as $rate_id => $dates) {
                    foreach($dates as $currentDate => $value) {
                        $value = (array) $value;
                        if($package['package_id'] == $value['package_id'] && $range['rm_type_id'] == $value['room_type_id']) {
                            $dayOfWeek = date('w', strtotime($currentDate));

                            $expected_price = 0;

                            if(!$is_derived) {
                                $expected_price = floatVal($range['prices'][$dayOfWeek]) + $clean_up_charge;
                            } else {


                                /*
                                 *  '.action_rate' => '+',
                                 *  '.currency_rate' => 'fixed',
                                 *  '[name=\'derived_rate\']' => 5,
                                 * */

                                $expected_price = $base_rate_by_days[$currentDate]['rate'];
                                $expected_price += ($package['.action_rate'] == '+'?1:-1)
                                                    * ($package['.currency_rate'] == 'fixed'?1:$expected_price/100)
                                                    * $package['[name=\'derived_rate\']'];
                            }

                            $this->assertEquals($expected_price, floatVal($value['rate']), 'Added Package Range Rate and Cache Range Rate is not equal for date: ' . $currentDate, $this->delta);

                            $this->assertEquals(intval($range['min_los']), intval($value['min_los']), 'Added Package Range Min Los and Cache Range Min Los is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['max_los']), intval($value['max_los']), 'Added Package Range Max Los and Cache Range Max Los is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['cut_off']), intval($value['cut_off']), 'Added Package Range Cut Off and Cache Range Cut Off is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['last_minute_booking']), intval($value['last_minute_booking']), 'Added Package Range Last minute booking and Cache Range Last minute booking is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['closed_to_arrival']), intval($value['closed_to_arrival']), 'Added Package Range Closed to Arrival and Cache Range Closed to Arrival is not equal for date: ' . $currentDate);

                            $checked = true;
                        }
                    }
                }
            }

            if(!$checked) $this->fail('Cache was not checked - there is no suck package in results');
        }

        echo '~~~~~~~~~Cache checked successfully~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~Booking checking...~~~~~~~~~'.PHP_EOL;

        //TODO: hotel admin checking

        //  10.07 - 20.07 - Package Range
        //  08.07 - 09.07 - not avail
        //  09.07 - 11.07 - not avail
        //  11.07 - 18.07 - avail (without min/max los, cutoff, lmb, closed to arrival etc. restrictions)
        //  18.07 - 21.07 - not avail
        //  22.07 - 30.07 - not avail

        foreach($package['ranges'] as $range) {
            $base_date_from = $range['start_date'];
            $base_date_to = $range['end_date'];
            /*$los = $this->diffDates($base_date_from, $base_date_to);
            $los_in_range = $this->diffDates($this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from), $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to));*/

            echo '~~~~~~~~~Booking WITHOUT PROMO CODE checking~~~~~~~~~'.PHP_EOL;
            $variants = array(
                array(
                    'date_from' => $this->convertDateToSiteFormat($base_date_from, 'Y-m-d'),
                    'date_to' => $this->convertDateToSiteFormat($base_date_to, 'Y-m-d'),
                    'exists' => $this->checkPackageNeedToBeExists($this->convertDateToSiteFormat($base_date_from, 'Y-m-d'), $this->convertDateToSiteFormat($base_date_to, 'Y-m-d'), $range['start_date'], $range['end_date'], $range['min_los'], $range['max_los'], $range['cut_off'], $range['last_minute_booking'], $range['closed_to_arrival'], $package['have_promo']),
                    'package_id' => $package['package_id']
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('-4 days', 'Y-m-d', $base_date_from),
                    'date_to' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_from),
                    'exists' => false,
                    'package_id' => $package['package_id']
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_from),
                    'date_to' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_from),
                    'exists' => false,
                    'package_id' => $package['package_id']
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from),
                    'date_to' => $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to),
                    'exists' => $this->checkPackageNeedToBeExists($this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from), $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to), $range['start_date'], $range['end_date'], $range['min_los'], $range['max_los'], $range['cut_off'], $range['last_minute_booking'], $range['closed_to_arrival'], $package['have_promo']),
                    'package_id' => $package['package_id']
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_to),
                    'date_to' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_to),
                    'exists' => false,
                    'package_id' => $package['package_id']
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_to),
                    'date_to' => $this->convertDateToSiteFormat('+4 days', 'Y-m-d', $base_date_to),
                    'exists' => false,
                    'package_id' => $package['package_id']
                )
            );

            $this->_checkBookingVariants($variants, '');
            echo '~~~~~~~~~Booking WITHOUT PROMO CODE checked successfully~~~~~~~~~'.PHP_EOL;

            if($package['have_promo']) {
                echo '~~~~~~~~~Booking PROMO CODE checking~~~~~~~~~'.PHP_EOL;
                $variants_with_promo = array(
                    array(
                        'date_from' => $this->convertDateToSiteFormat($base_date_from, 'Y-m-d'),
                        'date_to' => $this->convertDateToSiteFormat($base_date_to, 'Y-m-d'),
                        'exists' => $this->checkPackageNeedToBeExists($this->convertDateToSiteFormat($base_date_from, 'Y-m-d'), $this->convertDateToSiteFormat($base_date_to, 'Y-m-d'), $range['start_date'], $range['end_date'], $range['min_los'], $range['max_los'], $range['cut_off'], $range['last_minute_booking'], $range['closed_to_arrival'], $package['have_promo'], $package['promo_code']),
                        'package_id' => $package['package_id']
                        //'exists' => ((!$range['min_los'] || $los >= $range['min_los']) && (!$range['max_los'] || $los <= $range['max_los']))
                    ),
                    array(
                        'date_from' => $this->convertDateToSiteFormat('-4 days', 'Y-m-d', $base_date_from),
                        'date_to' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_from),
                        'exists' => false,
                        'package_id' => $package['package_id']
                    ),
                    array(
                        'date_from' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_from),
                        'date_to' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_from),
                        'exists' => false,
                        'package_id' => $package['package_id']
                    ),
                    array(
                        'date_from' => $this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from),
                        'date_to' => $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to),
                        'exists' => $this->checkPackageNeedToBeExists($this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from), $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to), $range['start_date'], $range['end_date'], $range['min_los'], $range['max_los'], $range['cut_off'], $range['last_minute_booking'], $range['closed_to_arrival'], $package['have_promo'], $package['promo_code']),
                        'package_id' => $package['package_id']
                        //'exists' => ((!$range['min_los'] || $los_in_range >= $range['min_los']) && (!$range['max_los'] || $los_in_range <= $range['max_los']))
                    ),//avail without cut off, lmb, min/max los etc.
                    array(
                        'date_from' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_to),
                        'date_to' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_to),
                        'exists' => false,
                        'package_id' => $package['package_id']
                    ),
                    array(
                        'date_from' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_to),
                        'date_to' => $this->convertDateToSiteFormat('+4 days', 'Y-m-d', $base_date_to),
                        'exists' => false,
                        'package_id' => $package['package_id']
                    )
                );

                $this->_checkBookingVariants($variants_with_promo, $package['promo_code']);

                echo '~~~~~~~~~Booking PROMO CODE checked successfully~~~~~~~~~'.PHP_EOL;
            }
        }

        echo '~~~~~~~~~Booking checked successfully~~~~~~~~~'.PHP_EOL;
    }

    public function _checkBookingVariants($variants, $promo_code = ''){
        foreach($variants as $var_index => $var) {
            $result = $this->getBookingRooms($var['date_from'], $var['date_to'], $promo_code);

            $exists = -1;

            if($promo_code){
                //print_r($result);
                if(isset($result['promo_error']))
                    $exists = $result['promo_error'];
                else if(isset($result['success']))
                    $exists = $result['success'];
            }

            if($exists == -1) {
                $rooms = !empty($result['room_types']) ? $result['room_types'] : array();
                $exists = $rooms ? $this->_isExistsPackage($rooms, $var['package_id']) : false;
            }

            $this->assertEquals($var['exists'], $exists, $var_index . '. Package ' . $var['package_id'] . ' must ' . ($var['exists'] ? '' : 'not') . ' exists but ' . ($exists ? '' : 'not') . ' exists' . ' for interval [' . $var['date_from'] . ',' . $var['date_to'] . ']' . print_r($result, true));
        }
    }

    public function diffDates($date1, $date2, $abs = true){
        $diff = intval((strtotime($date1, mktime(0,0,0)) - strtotime($date2, mktime(0,0,0)))/86400);
        return $abs ? abs($diff) : $diff;
    }

    public function _isExistsPackage($rooms, $package_id){
        foreach($rooms as $room) {
            if(!empty($room['is_package']) && !empty($room['package_id']) && $room['package_id'] == $package_id) return true;
        }

        return false;
    }

    public function getBookingRooms($checkin, $checkout, $promo_code = ''){
        $postdata = http_build_query(
            array(
                'widget_property' => $this->property_id,
                'checkin' => $checkin,
                'checkout' => $checkout,
                'date_format' => $this->property_settings['formats']['date_format'],
                'get_fees' => '0',
                'promo_code' => $promo_code,
                'lang' => $this->property_settings['formats']['default_language']
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => array('Content-type: application/x-www-form-urlencoded', 'X-Requested-With: XMLHttpRequest'),
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $result = file_get_contents($this->_prepareUrl($this->booking_rooms_url), false, $context);
        return json_decode($result, true);
    }

    public function addPackage(&$package) {
        $is_derived = false;

        $this->waitForElement('#layout .add-new-package', 30000)->click();
        if(!$this->waitForElement('#layout .package-edit-block', 15000)){
            $this->fail('Form add package was not opened at time.');
        }

        if(isset($package['is_derived'])) {
            /*$derived_input = $this->waitForElement('[name=\'derived\'][value=\''.($package['is_derived']?1:0).'\']', 5000, 'jQ', false);//->click();
            $this->setAttribute($derived_input, 'checked', 'checked');*/
            $this->waitForElement('[name=\'derived\'][value=\''.($package['is_derived']?1:0).'\'] + label', 5000, 'jQ')->click();
            $is_derived = $package['is_derived'];
        }

        if(isset($package['have_promo'])) {
            /*$have_promo_input = $this->waitForElement('[name=\'have_promo\'][value=\''.($package['have_promo']?1:0).'\']', 5000, 'jQ', false);
            $this->setAttribute($have_promo_input, 'checked', 'checked');*/
            $this->waitForElement('[name=\'have_promo\'][value=\''.($package['have_promo']?1:0).'\'] + label', 5000, 'jQ')->click();
        }

        if(isset($package['promo_code'])) {
            $promo_code_input = $this->waitForElement('[name=\'promo_code\']', 5000, 'jQ', true);
            $promo_code_input->value($package['promo_code']);
        }

        foreach($package as $selector => $value){
            if(in_array($selector, array('is_derived', 'have_promo', 'ranges', 'promo_code'))) continue;

            $this->execute(array(
                'script' => 'return window.$("'.$selector.'").val("'.$value.'");',
                'args' => array()
            ));
        }

        foreach($package['ranges'] as &$range) {
            $rm_type_id = $this->addPackageRange($range, $is_derived);
            $range['rm_type_id'] = $rm_type_id;
        }

        $this->uploadPackagePhoto();

        $btns = $this->waitForElement('.edit-package-save.btn-save-panel', 5000);
        $this->waitUntilVisible($btns, 30000);
        if($btns) $btns->click();//click Save on save panel

        $result = $this->waitForElement('#layout .package-list-block', 5000);
        if(!$result) {
            $this->fail('Saving failed');
        }

        $package_id = $this->getLastPackagesID();
        $package['package_id'] = $package_id;
        return $package_id;
    }

    public function addPackageRange($range, $is_derived) {
        $this->waitForElement('.btn.date_range', 15000)->click();

        $form = $this->waitForElement('.portlet.add_interval', 10000);

        if($form instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $skip = array('prices', 'available_room_types', 'closed_to_arrival');
            foreach($range as $selector => $value) {
                if(!in_array($selector, $skip)) {
                    if(strpos($selector, 'date') !== FALSE){
                        $value = $this->convertDateToSiteFormat($value);
                    }

                    $input = $form->byName($selector);
                    $input->click();
                    $form->click();
                    $input->value($value);
                }
            }

            if(isset($range['closed_to_arrival'])) {
                $this->waitForElement('[name=\'closed_to_arrival\'][value=\''.($range['closed_to_arrival']?1:0).'\'] + label', 5000, 'jQ')->click();
            }
        }

        $rm_type_id = 0;
        $rm_types = $this->getJSObject('BET.roomTypes.items()');

        if($rm_types && is_array($rm_types)) {
            foreach ($rm_types as $index => $rm_type) {
                if (empty($rm_type['room_type_capacity'])) unset($rm_types[$index]);
            }

            $rm_type_index = rand(0, 1000) % count($rm_types);
            $rm_type = $rm_types[$rm_type_index];
            $rm_type_id = $rm_type['room_type_id'];
        }

        if($rm_type_id) {
            echo "rm_type_id = ".$rm_type_id.PHP_EOL;
            $avail_button = $this->waitForElement('[name=\'available_room_types\'] + div > button', 15000, 'jQ');
            $avail_button->click();//open
            $room_type_checkbox = $this->waitForElement('[name=\'selectItemavailable_room_types\'][value=\''.$rm_type_id.'\'] + label', 16000, 'jQ')->click();
            $avail_button->click();//close
            $form->click();

            //for better video view
            $this->execute(array(
                'script' => "window.$('html, body').animate({scrollTop: '+=200px'}, 0);",
                'args' => array()
            ));

            if(!$is_derived && !empty($range['prices'])) {
                foreach ($range['prices'] as $index => $price) {
                    $price_input_selector = '[name=\'day_' . $rm_type_id . '_' . $index . '\']';
                    $price_input = $this->waitForElement($price_input_selector, 10000, 'jQ');
                    $price_input->clear();
                    $price_input->value($price);
                }
            }

            $this->waitForElement('.save_add_interval', 5000, 'jQ')->click();
        } else {
            $this->fail('room type id can not be selected');
        }

        return $rm_type_id;
    }

    public function uploadPackagePhoto() {
        $upload_button = $this->waitForElement('#layout .package-uploader > .myimg_upload');
        $upload_button->click();

        $modal = $this->waitForElement('#photo_upload_modal', 7000);

        $this->uploadFileToElement('body > input[type=\'file\']', './files/cloudbeds-logo-250x39.png');

        $btns = $this->waitForElement('#photo_upload_modal .btn.done', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
        $btns->click();//click Done

        $btns = $this->waitForElement('#photo_upload_modal .save-uploader', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
        $btns->click();//click Save & Continue;
    }
}
?>
