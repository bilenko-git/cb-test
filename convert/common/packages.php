<?php

trait Packages {
    private $packages_url = 'http://{server}/connect/{property_id}#/packages';

    public function create_package($package) {
        $this->url($this->_prepareUrl($this->packages_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_url));
        $this->waitForElement('#layout .add-new-package', 15000, 'css')->click();
        $this->waitForElement('#layout .package-edit-block', 15000, 'css')->click();
        return false;
    }

    public function delete_package($package) {
        return false;
    }
}
