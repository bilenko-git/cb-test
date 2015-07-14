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
                    'start_date' => 'now',
                    'end_date' => '+20 days',
                    'prices' => array(
                        'Mon' => 12.5,
                        'Tue' => 12.82,
                        'Wed' => 18.97,
                        'Thu' => 10.99,
                        'Fri' => 20.55,
                        'Sat' => 22.00,
                        'Sun' => 22.00
                    ),
                    'min_los' => 2,
                    'max_los' => 4,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false,
                    'available_room_types' => 737
                ),
                array(
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
                )
            )
        )
    );

    public function getSeleniumPackagesIDs(){}

    public function removeOldSeleniumPackages(){}

    public function testSteps() {
        $step = $this;

        $this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->packages_list_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_list_url));

        $this->waitForElement('#layout .add-new-package')->click();
        $this->waitForElement('#layout .package-edit-block', 15000);

        foreach($this->packages as $package){
            $this->addPackage($package);
        }
    }

    public function addPackage($package){
        foreach($package as $selector => $value){
            if(!in_array($selector, array('is_derived', 'have_promo', 'ranges')))
            $this->execute(array(
                'script' => 'return window.$("'.$selector.'").val("'.$value.'");',
                'args' => array()
            ));
        }

        if(isset($package['is_derived'])) {
            $this->waitForElement('[name=\'derived\'][value=\''.($package['is_derived']?1:0).'\']')->click();
        }

        if(isset($package['have_promo'])) {
            $this->waitForElement('[name=\'derived\'][value=\''.($package['have_promo']?1:0).'\']')->click();
        }

        foreach($package['ranges'] as $range)
            $this->addPackageRange($range);

        $this->uploadPackagePhoto();
    }

    public function addPackageRange($range){
        $this->waitForElement('.btn.date_range')->click();

        $form = $this->waitForElement('.portlet box.add_interval');
        if($form instanceof \PHPUnit_Extensions_Selenium2TestCase_Element){
            $skip = array('prices', 'available_room_types', 'closed_to_arrival');
            foreach($range as $selector => $value){
                if(!in_array($selector, $skip)){
                    $form->byName($selector)->value($value);
                }
            }
        }
    }

    public function uploadPackagePhoto() {
        $upload_button = $this->waitForElement('#layout .package-uploader > .myimg_upload');
        $upload_button->click();

        $modal = $this->waitForElement('#photo_upload_modal');

        $this->uploadFileToElement('body > input[type=\'file\']', './files/cloudbeds-logo-250x39.png');

        $btns = $this->byCssSelector('#photo_upload_modal .btn.done');//$modal->elements($this->using('css selector')->value('.btn.done'));
        $this->waitUntilVisible($btns, 30000);
        $btns->click();//click Done

        $btns = $this->waitForElement('#photo_upload_modal .save-uploader');//$modal->elements($this->using('css selector')->value('.btn.done'));
        $this->waitUntilVisible($btns, 30000);
        $btns->click();//click Save & Continue;

        $btns = $this->waitForElement('.edit-package-save.btn-save-panel', 5000);
        $this->waitUntilVisible($btns, 30000);
        if($btns) $btns->click();//click Save on save panel

        $result = $this->waitForElement('#layout .package-list-block', 5000);
        if(!($result && $this->waitUntilVisible($result, 30000))) {
            $this->fail('Saving failed');
        }
    }
}
?>
