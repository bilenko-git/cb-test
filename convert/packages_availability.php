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
                    'interval_name' => 'Selenium Interval 1',
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
                    'closed_to_arrival'  => false,
                    //'available_room_types' => 737
                ),
                /*array(
                    'from' => '+21 days',
                    'to' => '+40 days',
                    array(
                        'Mon' => 0.01,
                        'Tue' => 0.01,
                        'Wed' => 99.99,
                        'Thu' => 1011.01,
                        'Fri' => 1212.12,
                        'Sat' => 29.22,
                        'Sun' => 17.5
                    ),
                    'min_los' => 1,
                    'max_los' => 2,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                )*/
            )
        )
    );

    public function getSeleniumPackagesID(){
        $last_tr = $this->waitForElement('#layout .packages-table tbody > tr[data-id]:last', 5000, 'jQ');

        if($last_tr){
            return $this->getAttribute($last_tr, 'data-id');
        }

        return false;
    }

    public function removeOldSeleniumPackages(){}

    public function testSteps() {
        $step = $this;

        $this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->packages_list_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_list_url));

        $this->waitForElement('#layout .add-new-package', 30000, 'jQ')->click();
        if(!$this->waitForElement('#layout .package-edit-block', 15000, 'jQ')){
            $this->fail('Form add package was not opened at time.');
        }

        foreach($this->packages as $package) {
            $package_id = $this->addPackage($package);
            echo 'package id = ' . $package_id . PHP_EOL;
            if(!$package_id) $this->fail('added package was not found');
        }
    }

    public function addPackage($package) {
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

        foreach($package['ranges'] as $range)
            $this->addPackageRange($range);

        $this->uploadPackagePhoto();

        $btns = $this->waitForElement('.edit-package-save.btn-save-panel', 5000);
        $this->waitUntilVisible($btns, 30000);
        if($btns) $btns->click();//click Save on save panel

        $result = $this->waitForElement('#layout .package-list-block', 5000);
        if(!$result) {
            $this->fail('Saving failed');
        }

        return $this->getSeleniumPackagesID();
    }

    public function addPackageRange($range) {
        $this->waitForElement('.btn.date_range', 15000)->click();

        $form = $this->waitForElement('.portlet.add_interval', 10000);

        if($form instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $skip = array('prices', 'available_room_types', 'closed_to_arrival');
            foreach($range as $selector => $value) {
                if(!in_array($selector, $skip)) {
                    if(strpos($selector, 'date') !== FALSE){
                        echo 'date before value = ' . $value . PHP_EOL;
                        $value = $this->convertDateToSiteFormat($value);
                        echo 'converted value = ' . $value . PHP_EOL;
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
            echo "rm_type+id = ".$rm_type_id.PHP_EOL;
            $avail_button = $this->waitForElement('[name=\'available_room_types\'] + div > button', 15000, 'jQ');
            $avail_button->click();//open
            $room_type_checkbox = $this->waitForElement('[name=\'selectItemavailable_room_types\'][value=\''.$rm_type_id.'\'] + label', 16000, 'jQ')->click();
            $avail_button->click();//close
            $form->click();
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
