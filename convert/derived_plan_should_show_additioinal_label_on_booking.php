<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/packages.php';

class derived_plan_should_show_additioinal_label_on_booking extends test_restrict {
    use \Packages;
    public function testSteps() {
        $this->setupInfo('wwwdev3.ondeficar.com', '', '', 366);
        $this->loginToSite();
        $package = array(
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
        );

        $this->packages_add_package($package);
    }
}
