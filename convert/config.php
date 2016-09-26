<?php
/**
 * Created by PhpStorm.
 * User: philipp
 * Date: 30.10.15
 * Time: 17:04
 */

switch (getenv('SELENIUM_ENV')) {
    default:
    case 'DEV':
        $config['PMS_user'] = array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'selenium@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '366',
            'property_reserva_code' => 'ZeRY9J',
            'browser_info' => '',
            'server_ma' => 'inbox.myallocator.com',
            'login_ma' => 'ofc_server_dev_366',
            'password_ma' => 'zKNVwxsLcnY5'
        );
        $config['OTA_user'] = array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'selenium2@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '479',
            'property_reserva_code' => 'cUf8la',
            'browser_info' => '',
            'server_ma' => 'inbox.myallocator.com',
            'login_ma' => 'ofc_server_dev_366',
            'password_ma' => 'zKNVwxsLcnY5'
        );
        $config['PMS_super_user'] = array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'E$4QBY3m&e6e@t*6',
            'property_id' => '366',
            'property_reserva_code' => 'ZeRY9J',
            'browser_info' => '',
            'server_ma' => 'inbox.myallocator.com',
            'login_ma' => 'ofc_server_dev_366',
            'password_ma' => 'zKNVwxsLcnY5'
        );
        $config['OTA_super_user'] = array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'E$4QBY3m&e6e@t*6',
            'property_id' => '479',
            'browser_info' => '',
            'server_ma' => 'inbox.myallocator.com',
            'login_ma' => 'ofc_server_dev_366',
            'password_ma' => 'zKNVwxsLcnY5'
        );
        break;
    case 'LIVE':
        $config['PMS_user'] = array(
            'server' => 'hotels.cloudbeds.com',
            'login' => '',
            'password' => '',
            'property_id' => '',
            'browser_info' => '',
        );
        $config['OTA_user'] = array(
            'server' => 'hotels.cloudbeds.com',
            'login' => '',
            'password' => '',
            'property_id' => '',
            'browser_info' => '',
        );
        $config['PMS_super_user'] = array(
            'server' => 'hotels.cloudbeds.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'E$4QBY3m&e6e@t*6',
            'property_id' => '',
            'browser_info' => '',
        );
        $config['OTA_super_user'] = array(
            'server' => 'hotels.cloudbeds.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'E$4QBY3m&e6e@t*6',
            'property_id' => '',
            'browser_info' => '',
        );
        break;
    case 'DEV3':
        $config['PMS_user'] = array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'selenium@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '366',
            'property_reserva_code' => 'QcOmgE',
            'browser_info' => '',
        );
        $config['OTA_user'] = array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'selenium2@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '479', //412
            'browser_info' => '',
        );
        $config['PMS_super_user'] = array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '366',
            'property_reserva_code' => 'QcOmgE',
            'browser_info' => '',
        );
        $config['OTA_super_user'] = array(
            'server' => 'wwwdev3.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'cl0udb3ds',
            'property_id' => '479',
            'browser_info' => '',
        );
        break;
    case 'DEV9':
        $config['PMS_user'] = array(
            'server' => 'wwwdev9.ondeficar.com',
            'login' => 'selenium_PMS@cloudbeds.com',
            'password' => 'Cloudbed$',
            'property_id' => '3',
            'property_reserva_code' => 'Zt420y',
            'browser_info' => '',
        );
        $config['OTA_user'] = array(
            'server' => 'wwwdev9.ondeficar.com',
            'login' => 'selenium_OTA@cloudbeds.com',
            'password' => 'Cloudbed$',
            'property_id' => '4',
            'property_reserva_code' => 'dROicj',
            'browser_info' => '',
        );
        $config['PMS_super_user'] = array(
            'server' => 'wwwdev9.ondeficar.com',
            'login' => 'admin@test.test',
            'password' => '123qwe',
            'property_id' => '3',
            'property_reserva_code' => 'Zt420y',
            'browser_info' => '',
        );
        $config['OTA_super_user'] = array(
            'server' => 'wwwdev9.ondeficar.com',
            'login' => 'admin@test.test',
            'password' => '123qwe',
            'property_id' => '4',
            'property_reserva_code' => 'dROicj',
            'browser_info' => '',
        );
        break;
    case 'DEV2':
        $config['PMS_user'] = array(
            'server' => 'wwwdev2.ondeficar.com',
            'login' => 'selenium_PMS@cloudbeds.com',
            'password' => 'Cloudbed$',
            'property_id' => '54',
            'property_reserva_code' => 'eQJrfw',
            'browser_info' => '',
        );
        $config['OTA_user'] = array(
            'server' => 'wwwdev2.ondeficar.com',
            'login' => 'selenium_OTA@cloudbeds.com',
            'password' => 'Cloudbed$',
            'property_id' => '58',
            'property_reserva_code' => 'livfVK',
            'browser_info' => '',
        );
        $config['PMS_super_user'] = array(
            'server' => 'wwwdev2.ondeficar.com',
            'login' => 'admin@test.test',
            'password' => '123qwe',
            'property_id' => '54',
            'property_reserva_code' => 'eQJrfw',
            'browser_info' => '',
        );
        $config['OTA_super_user'] = array(
            'server' => 'wwwdev2.ondeficar.com',
            'login' => 'admin@test.test',
            'password' => '123qwe',
            'property_id' => '58',
            'property_reserva_code' => 'livfVK',
            'browser_info' => '',
        );
        break;
    case 'DIMA':
        $config['PMS_user'] = array(
            'server' => 'hotels.accessa.loc',
            'login' => 'admin@test.test',
            'password' => '123qwe',
            'property_id' => '1',
            'property_reserva_code' => 'Eki5mf',
            'browser_info' => '',
//            'server_ma' => 'inbox.myallocator.com',
//            'login_ma' => 'ofc_server_dev_366',
//            'password_ma' => 'zKNVwxsLcnY5'
        );
        $config['OTA_user'] = array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'selenium2@cloudbeds.com',
            'password' => 'testTime!',
            'property_id' => '479',
            'property_reserva_code' => 'cUf8la',
            'browser_info' => '',
            'server_ma' => 'inbox.myallocator.com',
            'login_ma' => 'ofc_server_dev_366',
            'password_ma' => 'zKNVwxsLcnY5'
        );
        $config['PMS_super_user'] = array(
            'server' => 'hotels.accessa.loc',
            'login' => 'admin@test.test',
            'password' => '123qwe',
            'property_id' => '1',
            'property_reserva_code' => 'Eki5mf',
            'browser_info' => '',
//            'server_ma' => 'inbox.myallocator.com',
//            'login_ma' => 'ofc_server_dev_366',
//            'password_ma' => 'zKNVwxsLcnY5'
        );
        $config['OTA_super_user'] = array(
            'server' => 'wwwdev.ondeficar.com',
            'login' => 'engineering@cloudbeds.com',
            'password' => 'E$4QBY3m&e6e@t*6',
            'property_id' => '479',
            'browser_info' => '',
            'server_ma' => 'inbox.myallocator.com',
            'login_ma' => 'ofc_server_dev_366',
            'password_ma' => 'zKNVwxsLcnY5'
        );
        break;


}
