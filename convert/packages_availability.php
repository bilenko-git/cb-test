<?php
    namespace MyProject\Tests;
    require_once 'availability_base_test.php';

    class packages_availability extends availability_base_test{
        private $packages_list_url = 'http://{server}/connect/{property_id}#/packages';
       /* private $packages = array(
            array(
                'private_title' => 'Pack 1',
                'rate_plan_name' => 'Pack 001',
                'is_derived' => false,
                'promotion_code' => false,
                'package_include' => 'Nothing include. Just test package',
                'policies' => 'No any policy.',
                'file' => __DIR__ . '/files/main_clouds_blue.png',
                'ranges' => array(
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
                )
            )
        );*/
        public function testSteps(){
            $step = $this;

            $this->setupInfo('', '', '', 31);

            $this->loginToSite();
            $this->url($this->_prepareUrl($this->packages_list_url));
            $this->waitForLocation($this->_prepareUrl($this->packages_list_url));

            $this->waitForElement('#main_menu', 50000);
            $this->waitForElement('#layout', 10000);

            $this->waitForElement('#main_menu', 50000);
            $add_new_package_btn = $this->waitForElement('#layout .package-list-block a.add-new-package', 15000);
            $add_new_package_btn->click();

            $package_edit_wrapper = $this->waitForElement('#layout .package-edit-block', 15000);
            //$this->uploadPackagePhoto();
        }
        public function addPackage($package){

        }
        public function uploadPackagePhoto() {
            $upload_button = $this->waitForElement('.package-uploader > .myimg_upload');
            $upload_button->click();

            $modals = $this->findModals(true);
            if(!empty($modals)) {
                $modal = reset($modals);
                $this->uploadFileToElement('body>input[type=file]', $this->packages[0]['file']);

                $btns = $modal->elements($this->using('css selector')->value('.btn.done'));
                foreach($btns as $btn)
                    $btn->click();//click Done

                $btns = $modal->elements($this->using('css selector')->value('.btn.save-uploader'));
                foreach($btns as $btn)
                    $btn->click();//click Save & Continue

                $btn = $this->waitForElement('.btn.saveButton', 5000);
                if($btn) $btn->click();//click Save

                /*assert [data-qe-iq] to saved*/
                $save_result = '';
                $this->waitUntil(function() use (&$save_result){
                    if($save_element = $this->byCssSelector('[data-qe-id]')) {
                        $save_result = $save_element->attribute('data-qe-id');
                        echo "current result is: " . $save_result . "\n";
                        return $save_result == 'saved'?true:null;
                    }

                    return null;
                }, 5000);//5000ms until saving process finish

                $this->assertEquals('saved', $save_result, 'Saving result is: ' . $save_result); //assert saving result
                /*./assert [data-qe-id] to saved*/
            }
        }
    }
?>