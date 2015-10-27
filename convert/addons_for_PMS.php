<?php
namespace MyProject\Tests;
require_once 'base_addons.php';

class addons_for_PMS extends base_addons {

    private $products = array(
        array(
            'sku' => '',
            'product_name' => 'Black Tea',
            'product_code' => 'BT0001',
            'product_description' => 'More of a Good Thing Tea - Our exceptional Earl Greyer blend combines the lush flavor and exquisite fragrance of the Bergamot Orange, native to Southern Italy, with a lovely Ceylon black tea leaf from Sri Lanka. Recognized as the best tasting Earl Grey available.',
            'product_price' => 11,
        ),
        array(
            'sku' => '',
            'product_name' => 'Wi-Fi',
            'product_code' => 'WF0001',
            'product_description' => '',
            'product_price' => 5.46,
        ),
        array(
            'sku' => '',
            'product_name' => 'Iron',
            'product_code' => 'IR0001',
            'product_description' => '',
            'product_price' => 5.46,
        ),
        array(
            'sku' => '',
            'product_name' => 'Cakes',
            'product_code' => 'CK0001',
            'product_description' => 'Cakes with kiwi',
            'product_price' => 28.1,
        ),
        array(
            'sku' => '',
            'product_name' => 'Taxi to Airport',
            'product_code' => 'TX0001',
            'product_description' => 'Advance reservations are dispatched with large lead times making our service especially timely. A portion of our fleet is stationed at MSP Airport and can immediately respond to pickup requests from either terminal.',
            'product_price' => 15,
        ),
        array(
            'sku' => '',
            'product_name' => 'Cleaning',
            'product_code' => 'CL0001',
            'product_description' => '',
            'product_price' => 15,
        ),
        array(
            'sku' => '',
            'product_name' => 'Dance Club',
            'product_code' => 'DCL001',
            'product_description' => '',
            'product_price' => 5,
        ),
    );

    private $addons = array(
        array(
            'addon_name' => 'Earl Greyer Black Tea',
            'product_id' => '0',
            'transaction_code' => 'TRD1544545',
            'available' => 'n/a',
            'charge_type' => 'per_guest_per_night',
            'charge_for_children' => '1',
            'charge_different_price_for_children' => '1',
            'with_image' => true,
            'intervals' => array(
                array(
                    'interval_name' => '3 day interval only with one room type',
                    'start_date' => 'now',
                    'end_date'  => '+2 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                ),
                array(
                    'interval_name' => '28 days with two room types',
                    'start_date' => '+2 days',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        ),
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                )
            )
        ),
        array(
            'addon_name' => 'Wi-Fi',
            'product_id' => '99',
            'transaction_code' => 'TRD1544501',
            'available' => 'arrival_date',
            'charge_type' => 'per_accommodation',
            'intervals' => array(
                array(
                    'interval_name' => '7 day interval only with two room types',
                    'start_date' => 'now',
                    'end_date'  => '+6 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 11,
                            'day_1' => 0,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 11,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 11,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 11,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        ),
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 17,
                            'day_0_child_price' => 11,
                            'day_1' => 1,
                            'day_1_adult_price' => 17,
                            'day_1_child_price' => 11,
                            'day_2' => 0,
                            'day_2_adult_price' => 16,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 15,
                            'day_3_child_price' => 11,
                            'day_4' => 1,
                            'day_4_adult_price' => 15,
                            'day_4_child_price' => 11,
                            'day_5' => 0,
                            'day_5_adult_price' => 15,
                            'day_5_child_price' => 11,
                            'day_6' => 1,
                            'day_6_adult_price' => 15,
                            'day_6_child_price' => 11,
                        )
                    )
                ),
                array(
                    'interval_name' => '28 days with two room types',
                    'start_date' => '+7 days',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        ),
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                )
            )
        ),
        array(
            'addon_name' => 'Iron',
            'product_id' => '0',
            'transaction_code' => 'TRD1544512',
            'available' => 'departure_date',
            'charge_type' => 'per_reservation',
            'intervals' => array(
                array(
                    'interval_name' => '3 day interval only with one room type',
                    'start_date' => 'now',
                    'end_date'  => '+2 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                ),
                array(
                    'interval_name' => '28 days with two room types',
                    'start_date' => '+3 days',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        ),
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                )
            )
        ),
        array(
            'addon_name' => 'Cakes',
            'product_id' => '0',
            'transaction_code' => 'TRD1544512',
            'available' => 'n/a',
            'charge_type' => 'quantity',
            'max_qty_per_res' => '250',
            'with_image' => true,
            'intervals' => array(
                array(
                    'interval_name' => '3 day interval only with one room type',
                    'start_date' => 'now',
                    'end_date'  => '+2 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                ),
                array(
                    'interval_name' => '28 days with two room types',
                    'start_date' => '+2 days',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        ),
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 11,
                            'day_0_child_price' => 10,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 9,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 11,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 12,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 11,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 12,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 11,
                        )
                    )
                )
            )
        ),
        array(
            'addon_name' => 'Taxi to Airport',
            'product_id' => '0',
            'transaction_code' => 'TRD1544545',
            'available' => 'n/a',
            'charge_type' => 'per_guest',
            'charge_for_children' => '0',
            'charge_different_price_for_children' => '0',
            'with_image' => true,
            'intervals' => array(
                array(
                    'interval_name' => '30 days with two room types',
                    'start_date' => 'now',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 15,
                            'day_0_child_price' => 15,
                            'day_1' => 1,
                            'day_1_adult_price' => 15,
                            'day_1_child_price' => 15,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 15,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 15,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 15,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 15,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 15,
                        ),
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 19,
                            'day_0_child_price' => 15,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 15,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 15,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 15,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 15,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 15,
                            'day_6' => 1,
                            'day_6_adult_price' => 18,
                            'day_6_child_price' => 15,
                        )
                    )
                )
            )
        ),
        array(
            'addon_name' => 'Cleaning',
            'product_id' => '0',
            'transaction_code' => 'TRD1544545',
            'available' => 'arrival_date',
            'charge_type' => 'per_accommodation_per_night',
            'charge_for_children' => '0',
            'charge_different_price_for_children' => '0',
            'with_image' => true,
            'intervals' => array(
                array(
                    'interval_name' => '30 days with two room types',
                    'start_date' => 'now',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 11,
                            'day_0' => 1,
                            'day_0_adult_price' => 15,
                            'day_0_child_price' => 15,
                            'day_1' => 1,
                            'day_1_adult_price' => 10,
                            'day_1_child_price' => 15,
                            'day_2' => 1,
                            'day_2_adult_price' => 10,
                            'day_2_child_price' => 15,
                            'day_3' => 1,
                            'day_3_adult_price' => 10,
                            'day_3_child_price' => 15,
                            'day_4' => 1,
                            'day_4_adult_price' => 10,
                            'day_4_child_price' => 15,
                            'day_5' => 1,
                            'day_5_adult_price' => 10,
                            'day_5_child_price' => 15,
                            'day_6' => 1,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 15,
                        ),
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 19,
                            'day_0_child_price' => 15,
                            'day_1' => 1,
                            'day_1_adult_price' => 11,
                            'day_1_child_price' => 15,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 15,
                            'day_3' => 1,
                            'day_3_adult_price' => 11,
                            'day_3_child_price' => 15,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 15,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 15,
                            'day_6' => 1,
                            'day_6_adult_price' => 18,
                            'day_6_child_price' => 15,
                        )
                    )
                )
            )
        ),
        array(
            'addon_name' => 'Dance Club',
            'product_id' => '0',
            'transaction_code' => 'TRD1544545',
            'available' => 'n/a',
            'charge_type' => 'per_night',
            'charge_for_children' => '0',
            'charge_different_price_for_children' => '0',
            'with_image' => true,
            'intervals' => array(
                array(
                    'interval_name' => '30 days with two room types',
                    'start_date' => 'now',
                    'end_date'  => '+30 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 11,
                            'day_0' => 0,
                            'day_0_adult_price' => 15,
                            'day_0_child_price' => 15,
                            'day_1' => 1,
                            'day_1_adult_price' => 15,
                            'day_1_child_price' => 15,
                            'day_2' => 1,
                            'day_2_adult_price' => 11,
                            'day_2_child_price' => 5,
                            'day_3' => 1,
                            'day_3_adult_price' => 5,
                            'day_3_child_price' => 5,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 5,
                            'day_5' => 1,
                            'day_5_adult_price' => 5,
                            'day_5_child_price' => 5,
                            'day_6' => 0,
                            'day_6_adult_price' => 12,
                            'day_6_child_price' => 5,
                        ),
                        array(
                            'room_type_id' => 10,
                            'day_0' => 1,
                            'day_0_adult_price' => 19,
                            'day_0_child_price' => 15,
                            'day_1' => 1,
                            'day_1_adult_price' => 5,
                            'day_1_child_price' => 5,
                            'day_2' => 0,
                            'day_2_adult_price' => 5,
                            'day_2_child_price' => 5,
                            'day_3' => 0,
                            'day_3_adult_price' => 5,
                            'day_3_child_price' => 5,
                            'day_4' => 1,
                            'day_4_adult_price' => 11,
                            'day_4_child_price' => 5,
                            'day_5' => 1,
                            'day_5_adult_price' => 12,
                            'day_5_child_price' => 5,
                            'day_6' => 1,
                            'day_6_adult_price' => 18,
                            'day_6_child_price' => 5,
                        )
                    )
                )
            )
        ),
    );

    private $packages = array(
        array(
            '[id^=\'package_name\']' => 'Selenium Pack 1',
            '[id^=\'package_name_internal\']' => 'Selenium Pack 001',
            'addons' => array(),
            'is_derived' => false,
            'have_promo' => false,
            '[id^=\'packages_descr_\']' => 'Nothing include. Just test package',
            '[id^=\'packages_wysiwyg_terms_\']' => 'No any policy.',
            'ranges' => array(
                array(
                    'interval_name' => 'Selenium Interval 1 (test min/max los, ranges)',
                    'start_date' => '+2 days',
                    'end_date' => '+20 days',
                    'prices' => array(
                        '0' => '12',
                        '1' => '12',
                        '2' => '18',
                        '3' => '10',
                        '4' => '20',
                        '5' => '22',
                        '6' => '22'
                    ),
                    'min_los' => 1,
                    'max_los' => 10,
                    'cut_off' => 0,
                    'last_minute_booking' => 0,
                    'closed_to_arrival'  => false
                )
            )
        )
    );

    public function testDeleteAllAddons()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllAddons();
    }

    public function testDeleteAllProducts()
    {
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();
        $this->checkAddonsForEmptyProducts();
    }

    public function testCheckAllErros()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $product =  array(
            'sku' => '',
            'product_name' => 'Tour',
            'product_code' => 'TR0001',
            'product_description' => 'Trip to Croatia',
            'product_price' => 800,
        );

        $product_id = $this->addProduct($product);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        if ($product_id) {
            $this->checkAddonErrors();
            $this->delAllProducts();
        }
    }

    public function testPerGuestPerNightAddonCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Per Guest Per Night~~~~~~~~~'.PHP_EOL;

        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[0]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[0]['product_id'] = $product_id;
        $this->addAddon($this->addons[0], true);
    }

    public function testPerAccommodationAddonCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Per Room/Bed"~~~~~~~~~'.PHP_EOL;

        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[1]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[1]['product_id'] = $product_id;
        $this->addAddon($this->addons[1], true);
    }

    public function testPerReservationAddonCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Per Reservation"~~~~~~~~~'.PHP_EOL;

        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[2]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[2]['product_id'] = $product_id;
        $this->addAddon($this->addons[2], true);
    }

    public function testQuantityAddonCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Quantity"~~~~~~~~~'.PHP_EOL;

        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[3]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[3]['product_id'] = $product_id;
        $this->addAddon($this->addons[3], true);
    }

    public function testPerGuestAddonCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Per Guest" ~~~~~~~~~'.PHP_EOL;

        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();
        $this->checkAddonsForEmptyProducts();

        $product_id = $this->addProduct($this->products[4]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[4]['product_id'] = $product_id;
        $this->addAddon($this->addons[4]);

    }

    public function testPerAccommodationPerNightAddonCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Per Room Per Night"~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~A charge will be added for each room night.~~~~~~~~~~~~~'.PHP_EOL;

        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[5]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[5]['product_id'] = $product_id;
        $this->addAddon($this->addons[5], true);
    }

    public function testPerNightAddonCreationWithCheckingUniqueName()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~Add-on with charge type "Per Night"~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[6]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');
        $this->addons[6]['product_id'] = $product_id;
        $this->addAddon($this->addons[6], true);
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~ Check Unique Add-on Name ~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->waitForElement('#open_addon', 15000, 'css')->click();
        $add_new_addon = $this->waitForElement('#tab_addons .add-new-addon', 15000, 'css');
        $add_new_addon->click();

        $this->byName('addon_name')->value($this->addons[6]['addon_name']);
        $product_id = $this->byName('product_id');
        $this->select($product_id)->selectOptionByValue($this->addons[6]['product_id']);
        $charge_type = $this->byName('charge_type');
        $this->select($charge_type)->selectOptionByValue($this->addons[6]['charge_type']);

        $this->saveAddon();
        $this->checkUniqueAddonName();
        $this->cancelAddon();
    }


    public function testAddonUpdate()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~~~~TEST ADD-ON UPDATE~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[0]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[0]['product_id'] = $product_id;
        $addon_id = $this->addAddon($this->addons[0]);
        echo 'addon id = ' . $addon_id . PHP_EOL;
        if (!$addon_id) $this->fail('Added add-on was not found');

        $this->editAddonAction($addon_id);

        $this->byName('addon_name')->value('Changed Add-on');
        $charge_type = $this->byName('charge_type');
        $this->select($charge_type)->selectOptionByValue('quantity');

        $this->saveAddon();
    }

    public function testAddonActiveState()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~~~~~~ Check Active/Inactive switcher ~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $this->delAllProducts();

        $product_id = $this->addProduct($this->products[0]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[0]['product_id'] = $product_id;
        $addon_id = $this->addAddon($this->addons[0]);
        if ($addon_id) {
            sleep(1);
            $this->execJS("$('#addons_list #addon_" . $addon_id . " [name=is_active]', '#layout').click()");
            sleep(1);
            $saved_addon = $this->getJSObject("window.BET.products.addons({is_deleted: '0', addon_id: '" . $addon_id . "'})");
            $this->assertEquals(1, count($saved_addon), 'Check addon');
            $this->assertEquals(0, (int)$saved_addon[0]['is_active'], 'Check addon active state');
            echo 'Refresh Page and check add-on state' . PHP_EOL;
            $this->refresh();
            $this->go_to_products_page();
            $this->waitForElement('#open_addon', 15000, 'css')->click();
            $saved_addon = $this->getJSObject("window.BET.products.addons({is_deleted: '0', addon_id: '" . $addon_id . "'})");
            $this->assertEquals(1, count($saved_addon), 'Check addon');
            $this->assertEquals(0, (int)$saved_addon[0]['is_active'], 'Check addon active state');

        } else {
            $this->fail('Add-on cannot be found');
        }
    }


    public function testAddonBooking()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~~~ Check Add-on for booking page ~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->createReservation('now', '+2 days');
    }

    public function testAddonsForPackages()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo PHP_EOL. '~~~~~~~~~~~~~~TEST ADD-ONS FOR PACKAGES ~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();

        $this->delAllProducts();
        $product_id = $this->addProduct($this->products[0]);
        echo 'Inventory Item (product_id) = ' . $product_id . PHP_EOL;
        if (!$product_id) $this->fail('Added product was not found');

        $this->addons[0]['product_id'] = $product_id;
        $addon_id = $this->addAddon($this->addons[0], true);
        echo 'Add-on id = ' . $addon_id . PHP_EOL;
        if (!$addon_id) $this->fail('Added add-on was not found');

        if ($addon_id) {
            $this->go_to_package_page();
            if (!empty($this->packages[0])) {
                $package = $this->packages[0];
                $package['addons'][]= $addon_id;
                $package_id = $this->addPackage($package);
                echo 'package id = ' . $package_id . PHP_EOL;
                if (!$package_id) $this->fail('added package was not found');

                $this->editPackageAction($package_id);
                echo "Selected Add-ons:" . PHP_EOL;
                $selectedAddons = $this->getJSObject("$('select[name=addons]', '#layout').val();");
                print_r($selectedAddons);
                $btns = $this->waitForElement('.edit-package-cancel', 5000);
                $this->waitUntilVisible($btns, 30000);
                if($btns) $btns->click();//click Cancel on save panel
                $this->assertEquals(count($selectedAddons), 1, 'Check number of add-ons');

                // $this->_checkAvailability($package);
                $this->removePackage($package_id);
            } else {
                $this->fail('Package with such index for test doesn\'t exists, myabe data was corrupted.');
            }
        }
    }
}
?>
