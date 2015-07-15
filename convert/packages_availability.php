<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class packages_availability extends test_restrict{
    private $packages_list_url = 'http://{server}/connect/{property_id}#/packages';
    private $packages = array(
        array(
            '[name=\'package_name\']' => 'Selenium Pack 1',
            '[name=\'package_name_internal\']' => 'Selenium Pack 001',
            'is_derived' => false,//[name=\'derived\']
            'has_promo' => false,//[name=\'have_promo\']
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 1 (test min/max los, ranges)',
                    'end_date' => '+20 days',
                    'start_date' => '+2 days',
                    'prices' => array(
                        '0'/*'Mon'*/ => '12',
                        '1'/*'Tue'*/ => '12',
                        '2'/*'Wed'*/ => '18',
                        '3'/*'Thu'*/ => '10',
                        '4'/*'Fri'*/ => '20',
                        '5'/*'Sat'*/ => '22',
                        '6'/*'Sun'*/ => '22'
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
            'has_promo' => false,//[name=\'have_promo\']
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
            'has_promo' => false,//[name=\'have_promo\']
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
        )
    );
    protected $booking_url = 'http://{server}/reservas/{property_id}#checkin={date_from}&checkout={date_to}';
    protected $booking_rooms_url = 'http://{server}/booking/rooms';

    public function getLastPackagesID() {
        $last_tr = $this->waitForElement('#layout .packages-table tbody > tr[data-id]:last', 5000, 'jQ');

        if($last_tr){
            return $this->getAttribute($last_tr, 'data-id');
        }

        return false;
    }

    public function removeOldSeleniumPackage($package_id) {
        $delete_package_btn = $this->waitForElement('#layout .packages-table tbody > tr[data-id=\''.$package_id.'\'] .action-btn.delete', 5000, 'jQ');
        sleep(1);
        $delete_package_btn->click();

        $delete_modal = $this->waitForElement('#confirm_delete', 15000);
        $this->waitForElement('.btn_delete', 5000)->click();
    }

    public function checkPackageNeedToBeExists($reservation_from, $reservation_to, $start_date, $end_date, $min_los = false, $max_los = false, $cut_off = false, $last_mb = false){
        $result = true;
        $today = date('Y-m-d');
        if($cut_off){
            if($this->diffDates($start_date, $today, false) < $cut_off){
                $result = false;
            }
        }

        if($last_mb){
            if($this->diffDates($start_date, $today, false) > $last_mb){
                $result = false;
            }
        }

        $days = $this->diffDates($reservation_from, $reservation_to);
        if($min_los && $days < $min_los){
            $result = false;
        }

        if($max_los && $days > $max_los){
            $result = false;
        }

        if($result)
            $result = $this->date_in_range($reservation_from, $start_date, $end_date) && $this->date_in_range($reservation_to, $start_date, $end_date);

        return $result;
    }

    public function date_in_range($date, $start, $end){
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $user_ts = strtotime($date);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public function testSteps() {
        $step = $this;

        //$this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);
        $this->setupInfo('', 'aleksandr.brus+20150715@cloudbeds.com', 'KNS16111988', 412);

        $this->loginToSite();
        $this->url($this->_prepareUrl($this->packages_list_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_list_url));

        foreach($this->packages as $package) {
            $this->waitForElement('#layout .add-new-package', 30000)->click();
            if(!$this->waitForElement('#layout .package-edit-block', 15000)){
                $this->fail('Form add package was not opened at time.');
            }

            $package_id = $this->addPackage($package);
            echo 'package id = ' . $package_id . PHP_EOL;
            if(!$package_id) $this->fail('added package was not found');

            $this->_checkAvailability($package);
            $this->removeOldSeleniumPackage($package_id);
        }
    }

    public function _checkAvailability($package){
        $clean_up_charge = (float) $this->execute(array(
            'script' => 'return BET.roomRates.charge_clean_up_room();',
            'args' => array()
        ));

        echo '~~~~~~~~~Cache checking....~~~~~~~~~'.PHP_EOL;

        foreach($package['ranges'] as $range) {
            $date_from = $this->convertDateToSiteFormat($range['start_date'], 'Y-m-d');
            $date_to = $this->convertDateToSiteFormat($range['end_date'], 'Y-m-d');

            $availability = $this->getAvailability($date_from, $date_to, $range['rm_type_id'], $package['package_id']);
            foreach($availability->data as $assoc){
                if($assoc->id != 0) continue;//now only check base rate; TODO: associations rates check

                foreach($assoc->rates as $rate_id => $dates) {
                    foreach($dates as $currentDate => $value) {
                        $value = (array) $value;
                        if($package['package_id'] == $value['package_id'] && $range['rm_type_id'] == $value['room_type_id']) {
                            $dayOfWeek = date('w', strtotime($currentDate));

                            $this->assertEquals(floatVal($range['prices'][$dayOfWeek]) + $clean_up_charge, floatVal($value['rate']), 'Added Package Range Rate and Cache Range Rate is not equal for date: ' . $currentDate, $this->delta);
                            $this->assertEquals(intval($range['min_los']), intval($value['min_los']), 'Added Package Range Min Los and Cache Range Min Los is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['max_los']), intval($value['max_los']), 'Added Package Range Max Los and Cache Range Max Los is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['cut_off']), intval($value['cut_off']), 'Added Package Range Cut Off and Cache Range Cut Off is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['last_minute_booking']), intval($value['last_minute_booking']), 'Added Package Range Last minute booking and Cache Range Last minute booking is not equal for date: ' . $currentDate);
                            $this->assertEquals(intval($range['closed_to_arrival']), intval($value['closed_to_arrival']), 'Added Package Range Closed to Arrival and Cache Range Closed to Arrival is not equal for date: ' . $currentDate);
                        }
                    }
                }
            }
        }

        echo '~~~~~~~~~Cache checked successfully~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~Booking checking...~~~~~~~~~'.PHP_EOL;

        //TODO: booking and hotel admin checking

        //  10.07 - 20.07 - Package Range
        //  08.07 - 09.07 - not avail
        //  09.07 - 11.07 - not avail
        //  11.07 - 18.07 - avail
        //  18.07 - 21.07 - not avail
        //  22.07 - 30.07 - not avail

        foreach($package['ranges'] as $range) {
            $base_date_from = $range['start_date'];
            $base_date_to = $range['end_date'];
            $los = $this->diffDates($base_date_from, $base_date_to);
            $los_in_range = $this->diffDates($this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from), $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to));

            $variants = array(
                array(
                    'date_from' => $this->convertDateToSiteFormat($base_date_from, 'Y-m-d'),
                    'date_to' => $this->convertDateToSiteFormat($base_date_to, 'Y-m-d'),
                    'exists' => $this->checkPackageNeedToBeExists($this->convertDateToSiteFormat($base_date_from, 'Y-m-d'), $this->convertDateToSiteFormat($base_date_to, 'Y-m-d'), $range['start_date'], $range['end_date'], $range['min_los'], $range['max_los'], $range['cut_off'], $range['last_minute_booking'])
                    //'exists' => ((!$range['min_los'] || $los >= $range['min_los']) && (!$range['max_los'] || $los <= $range['max_los']))
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('-4 days', 'Y-m-d', $base_date_from),
                    'date_to' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_from),
                    'exists' => false
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_from),
                    'date_to' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_from),
                    'exists' => false
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from),
                    'date_to' => $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to),
                    'exists' => $this->checkPackageNeedToBeExists($this->convertDateToSiteFormat('+1 days', 'Y-m-d', $base_date_from), $this->convertDateToSiteFormat('-1 days', 'Y-m-d', $base_date_to), $range['start_date'], $range['end_date'], $range['min_los'], $range['max_los'], $range['cut_off'], $range['last_minute_booking'])
                    //'exists' => ((!$range['min_los'] || $los_in_range >= $range['min_los']) && (!$range['max_los'] || $los_in_range <= $range['max_los']))
                ),//avail without cut off, lmb, min/max los etc.
                array(
                    'date_from' => $this->convertDateToSiteFormat('-2 days', 'Y-m-d', $base_date_to),
                    'date_to' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_to),
                    'exists' => false
                ),
                array(
                    'date_from' => $this->convertDateToSiteFormat('+2 days', 'Y-m-d', $base_date_to),
                    'date_to' => $this->convertDateToSiteFormat('+4 days', 'Y-m-d', $base_date_to),
                    'exists' => false
                )
            );

            foreach($variants as $var) {
                $result = $this->getBookingRooms($var['date_from'], $var['date_to']);
                $rooms = !empty($result['room_types']) ? $result['room_types'] : array();
                $exists = $rooms ? $this->_isExistsPackage($rooms, $package['package_id']) : false;
                $this->assertEquals($var['exists'], $exists, 'Package '.$package['package_id'].' must '.($var['exists']?'':'not').' exists but '.($exists?'':'not').' exists' . ' for interval ['.$var['date_from'].','.$var['date_to'].']');
            }
        }

        echo '~~~~~~~~~Booking checked successfully~~~~~~~~~'.PHP_EOL;
    }

    public function diffDates($date1, $date2, $abs = true){
        $diff = intval((strtotime($date1) - strtotime($date2))/86400);
        return $abs ? abs($diff) : $diff;
    }

    public function _isExistsPackage($rooms, $package_id){
        foreach($rooms as $room) {
            if(!empty($room['is_package']) && !empty($room['package_id']) && $room['package_id'] == $package_id && $rooms['room_type_id']) return true;
        }

        return false;
    }

    public function getBookingRooms($checkin, $checkout){
        $postdata = http_build_query(
            array(
                'widget_property' => $this->property_id,
                'checkin' => $checkin,
                'checkout' => $checkout,
                'date_format' => $this->property_settings['formats']['date_format'],
                'get_fees' => '0',
                'promo_code' => '',
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

    /*
     * not need for now - decided to get data directly from server
     * */
    /*public function getBookingUrl($from = false, $to = false) {
        $url = $this->_prepareUrl($this->booking_url);
        $from  = $from ?: $this->convertDateToSiteFormat('now', 'Y-m-d');
        $to = $to ?: $this->convertDateToSiteFormat('+1 day', 'Y-m-d');

        return str_replace(array('{date_from}', '{date_to}'), array($from, $to), $url);
    }*/

    public function addPackage(&$package) {
        foreach($package as $selector => $value){
            if(in_array($selector, array('is_derived', 'have_promo', 'ranges'))) continue;

            $this->execute(array(
                'script' => 'return window.$("'.$selector.'").val("'.$value.'");',
                'args' => array()
            ));
        }

        if(isset($package['is_derived'])) {
            $derived_input = $this->waitForElement('[name=\'derived\'][value=\''.($package['is_derived']?1:0).'\']', 5000, 'jQ', false);//->click();
            $this->setAttribute($derived_input, 'checked', 'checked');
        }

        if(isset($package['have_promo'])) {
            $have_promo_input = $this->waitForElement('[name=\'have_promo\'][value=\''.($package['have_promo']?1:0).'\']', 5000, 'jQ', false);
            $this->setAttribute($have_promo_input, 'checked', 'checked');
        }

        foreach($package['ranges'] as &$range) {
            $rm_type_id = $this->addPackageRange($range);
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

    public function addPackageRange($range) {
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

            foreach($range['prices'] as $index => $price) {
                $price_input_selector = '[name=\'day_' . $rm_type_id . '_' . $index.'\']';
                $price_input = $this->waitForElement($price_input_selector, 10000, 'jQ');
                $price_input->clear();
                $price_input->value($price);
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
