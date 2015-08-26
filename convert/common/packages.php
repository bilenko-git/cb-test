<?php

trait Packages {
    private $packages_url = 'http://{server}/connect/{property_id}#/packages';

    public function packages_add_package(&$package){
        $this->waitForElement('#layout .add-new-package', 30000)->click();
        if(!$this->waitForElement('#layout .package-edit-block', 15000)){
            $this->fail('Form add package was not opened at time.');
        }

        $this->packages_fill_package($package);

        $package_id = $this->packages_get_last_package_id();
        $package['package_id'] = $package_id;
        return $package_id;
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

    public function packages_delete_package($package) {
        return false;
    }
}
