<?php
/**
 * Created by PhpStorm.
 * User: philipp
 * Date: 30.10.15
 * Time: 17:04
 */

if (getenv('SELENIUM_LIVE')){
    $config = array(
        'PMS' => array(
            'server' => 'hotels.cloudbeds.com',
            'login' => '',
            'password' => '',
            'property_id' => '',
            'browser_info' => '',
        ),
        'OTA' => array(
          'server' => 'hotels.cloudbeds.com',
          'login' => '',
          'password' => '',
          'property_id' => '',
          'browser_info' => '',
      ),
        'PMS_super' => array(
            'server' => 'hotels.cloudbeds.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '',
            'browser_info' => '',
        ),
        'OTA_super' => array(
            'server' => 'hotels.cloudbeds.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '',
            'browser_info' => '',
        )

    );
} else {
    $config = array(
        'PMS_master' => array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'selenium@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '366',
            'browser_info' => '',
        ),
        'OTA_master' => array(
              'server' => 'wwwdev.ondeficar.com',
              'login' => 'selenium2@cloudbeds.com',
              'password' => 'testTime!',
              'property_id' => '479',
              'browser_info' => '',
        ),
        'PMS_master_super' => array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '366',
            'browser_info' => '',
        ),
        'OTA_master_super' => array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '479',
            'browser_info' => '',
        ),
        'PMS_dev3' => array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'selenium@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '366',
            'browser_info' => '',
        ),
        'OTA_dev3' => array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'selenium2@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '479',
            'browser_info' => '',
        ),
        'PMS_dev3_super' => array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '366',
            'browser_info' => '',
        ),
        'OTA_dev3_super' => array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '479',
            'browser_info' => '',
        )
    );

}