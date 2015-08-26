<?php

trait Packages {
    private $packages_url = 'http://{server}/connect/{property_id}#/packages';

    public function packages_add_package(&$package) {
        $this->packages_go_to_package_page();
        $this->waitForElement('#layout .add-new-package', 30000)->click();
        if (!$this->waitForElement('#layout .package-edit-block', 15000))
            $this->fail('Form add package was not opened at time.');
        $this->packages_fill_package($package);
        $package_id = $this->packages_get_last_package_id();
        $package['package_id'] = $package_id;
        return $package_id;
    }

    public function packages_go_to_package_page() {
        $packages_url = 'http://{server}/connect/{property_id}#/packages';
        $this->url($this->_prepareUrl($packages_url));
        $this->waitForLocation($this->_prepareUrl($packages_url));
    }

    public function packages_fill_package(&$package) {
        $is_derived = false;

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
            $rm_type_id = $this->packages_add_package_range($range, $is_derived);
            $range['rm_type_id'] = $rm_type_id;
        }

        $this->packages_upload_package_photo();

        $btns = $this->waitForElement('.edit-package-save.btn-save-panel', 5000);
        $this->waitUntilVisible($btns, 30000);
        if($btns) $btns->click();//click Save on save panel

        $result = $this->waitForElement('#layout .package-list-block', 5000);
        if(!$result) {
            $this->fail('Saving failed');
        }
    }

    public function packages_add_package_range($range, $is_derived) {
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


            // TODO:   $rm_type_index = rand(0, 1000) % count($rm_types);????????
          //  $rm_type_index = rand(0, 1000) % count($rm_types);
            $rm_type = $rm_types[1];
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

    public function packages_upload_package_photo() {
        $upload_button = $this->waitForElement('#layout .package-uploader > .myimg_upload');
        $upload_button->click();
        $modal = $this->waitForElement('#photo_upload_modal', 7000);
        $this->uploadFileToElement('body > input[type=\'file\']', __DIR__ .'/../files/cloudbeds-logo-250x39.png');
        $btns = $this->waitForElement('#photo_upload_modal .btn.done', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
        $btns->click();//click Done
        $btns = $this->waitForElement('#photo_upload_modal .save-uploader', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
        $btns->click();//click Save & Continue;
    }

    public function packages_get_last_package_id() {
        $last_tr = $this->waitForElement('#layout .packages-table tbody > tr[data-id]:last', 5000, 'jQ');
        if ($last_tr)
            return $this->getAttribute($last_tr, 'data-id');
        return false;
    }

    public function packages_remove_package($package_id) {
        $delete_package_btn = $this->waitForElement('#layout .packages-table tbody > tr[data-id=\''.$package_id.'\'] .action-btn.delete', 5000, 'jQ');
        sleep(1);
        $delete_package_btn->click();
        $this->packages_confirm_delete_dialog();
    }

    public function packages_confirm_delete_dialog() {
        $this->waitForElement('#confirm_delete', 15000);//delete confirmation almost all over site we can you this method to confim deleting something
        $this->waitForElement('.btn_delete', 5000)->click();
    }

}
