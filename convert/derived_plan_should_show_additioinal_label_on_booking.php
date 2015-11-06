<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/rates.php';
require_once 'common/packages.php';

class derived_plan_should_show_additioinal_label_on_booking extends test_restrict {
    use \Rates, \Packages;

    private $booking_url = 'http://{server}/reservas/{property_reserva_code}';
    private $start_date_interval = '+121 days';
    private $end_date_interval = '+140 days';
    private $start_date_booking = '+121 days';
    private $end_date_booking = '+125 days';
    private $package;
    private $interval;

    public function testSteps() {
        $this->init_vars();
        //$this->setupInfo('wwwdev.ondeficar.com', '', '', 366);
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $interval_id = $this->rates_add_rate($this->interval);
        $package_id = $this->packages_add_package($this->package);
        $this->check_derived_text_on_booking($package_id);
        $this->packages_remove_package($package_id);
        $this->rates_remove_rate();
    }

    private function init_vars() {
        $this->package = array(
           '[name^=\'package_name\']' => 'Derived Pack HOTELS-9974',
           '[name^=\'package_name_internal\']' => 'Derived Pack HOTELS-9974',
           'is_derived' => true,//[name=\'derived\']
           '.action_rate' => '-',
           '.currency_rate' => 'percentage',
           '[name=\'derived_rate\']' => 5,
           'have_promo' => false,//[name=\'have_promo\']
           '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
           '[id^=\'packages_wysiwyg_terms_\']' => 'Has no any policy.',
           'ranges' => array(
               array(
                   'interval_name' => '(test percentage Derived)',
                   'end_date' => $this->end_date_interval,
                   'start_date' => $this->start_date_interval,
                   'min_los' => 0,
                   'max_los' => 0,
                   'cut_off' => 0,
                   'last_minute_booking' => 0,
                   'closed_to_arrival'  => false
               )
           )
       );
       $this->interval = array(
           'name' => 'interval today',
           'value_today' => '99',
           'end' => $this->end_date_interval,
           'start' => $this->start_date_interval,
           'min' => '2',
           'edit_end_day' => '+12 days'
       );
    }

    private function go_to_booking_page($start_date, $end_date) {
        $url = $this->_prepareUrl($this->booking_url).'#checkin='.$this->convertDateToSiteFormat($start_date, 'Y-m-d').'&checkout='.$this->convertDateToSiteFormat($end_date, 'Y-m-d');
        $this->url($url);
        $this->waitForLocation($url);
    }

    private function check_derived_text_on_booking($package_id) {
        $this->go_to_booking_page($this->start_date_booking, $this->end_date_booking);
        $this->waitForElement('.room[data-package_id='.$package_id.'] .rate_basic_not_derived', 15000, 'jQ');
    }
}
