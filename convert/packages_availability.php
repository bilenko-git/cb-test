<?php
namespace MyProject\Tests;
require_once 'availability_base_test.php';

class packages_availability extends availability_base_test{
    private $packages_list_url = 'http://{server}/connect/{property_id}#/packages';
    private $packages = array(
        array(
            '[name=\'package_name\']' => 'Pack 1',
            '[name=\'package_name_internal\']' => 'Pack 001',
            /*'[name=\'derived\']' => 0,
            '[name=\'have_promo\']' => 0,*/
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.'
            /*'ranges' => array(
                array(
                    'from' => 'now',
                    'to' => '+20 days',
                    array(
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
                    'closed_to_arrival'  => false
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
            )*/
        )
    );

    public function setUpPage() {
        $this->fileDetector(function($filename) {
            if(file_exists($filename)) {
                return $filename;
            } else {
                return NULL;
            }
        });
    }

    public function testSteps(){
        $step = $this;

        $this->setupInfo('', '', '', 31);

        $this->loginToSite();
        $this->url($this->_prepareUrl($this->packages_list_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_list_url));

        $add_new_package_btn = $this->waitForElement('#layout .add-new-package', 15000, 'css');
        $add_new_package_btn->click();

        $package_edit_wrapper = $this->waitForElement('#layout .package-edit-block', 15000);

        foreach($this->packages as $package){
            $this->addPackage($package);
        }
    }
    public function addPackage($package){
        foreach($package as $selector => $value){
            $this->execute(array(
                'script' => 'return window.$("'.$selector.'").val("'.$value.'");',
                'args' => array()
            ));
        }

        $this->uploadPackagePhoto();
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

        /*            $btns = $this->waitForElement('.btn.save-uploader', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
                    $btns->click();//click Save & Continue*/

        $btns = $this->waitForElement('.edit-package-save.btn-save-panel', 5000);
        $this->waitUntilVisible($btns, 30000);
        if($btns) $btns->click();//click Save

        $result = $this->waitForElement('#layout .package-list-block', 5000);
        if(!($result && $this->waitUntilVisible($result, 30000))) {
            $this->fail('Saving failed');
        }
    }
}
?>
