<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/packages.php';

class derived_plan_should_show_additioinal_label_on_booking extends test_restrict {
    use \Packages;
    public function testSteps() {
        $this->setupInfo('wwwdev3.ondeficar.com', '', '', 366);
        $this->loginToSite();
        $this->create_package('name');
    }
}
