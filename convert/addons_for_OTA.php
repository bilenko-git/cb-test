<?php
namespace MyProject\Tests;
require_once 'base_addons.php';

class addons_for_OTA extends base_addons {
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
    );

    private $addons = array(
        array(
            'addon_name' => 'Black Tea',
            'product_id' => '0',
            'transaction_code' => 'TRD1544545',
            'available' => 'n/a',
            'charge_type' => 'per_guest_per_night',
            'charge_for_children' => '1',
            'charge_different_price_for_children' => '1',
            'with_image' => true,
            'intervals' => array(
                array(
                    'interval_name' => '2 day interval only with one room type',
                    'start_date' => 'now',
                    'end_date'  => '+2 days',
                    'min_overlap' => '0',
                    'max_overlap' => '0',
                    'room_types' => array(
                        array(
                            'room_type_id' => 18,
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
                            'room_type_id' => 18,
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
                            'room_type_id' => 19,
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
            ),
        ),
        array(
            'addon_name' => 'Wi-Fi',
            'product_id' => '99',
            'transaction_code' => 'TRD1544501',
            'available' => 'arrival_date',
            'charge_type' => 'per_accommodation',
            'intervals' => array(

            )
        ),
        array(
            'addon_name' => 'Iron',
            'product_id' => '0',
            'transaction_code' => 'TRD1544512',
            'available' => 'departure_date',
            'charge_type' => 'per_reservation',
            'intervals' => array(

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

            )
        )
    );

    public function testDeleteAllAddons()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_OTA@cloudbeds.com', 'Cloudbed$', 4);
        $this->loginToSite();
        $this->delAllAddons();
    }

    public function testDeleteAllProducts()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_OTA@cloudbeds.com', 'Cloudbed$', 4);
        $this->loginToSite();
        $this->delAllProducts();
    }

    public function testCheckAllErros()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_OTA@cloudbeds.com', 'Cloudbed$', 4);
        $this->loginToSite();
        $product =  array(
            'sku' => '',
            'product_name' => 'Tour',
            'product_code' => 'TR0001',
            'product_description' => 'Trip to Croatia',
            'product_price' => 800,
        );

        $product_id = $this->addProduct($product);
        if ($product_id) {
            $this->checkAddonErrors();
            $this->delAllProducts();
        }
    }

    public function testAddonsCreation()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_OTA@cloudbeds.com', 'Cloudbed$', 4);
        $this->loginToSite();
        $this->checkAddonsForEmptyProducts();
        foreach($this->products as $i => $product) {
            $product_id = $this->addProduct($product);

            $this->addons[$i]['product_id'] = $product_id;
            $this->addAddon($this->addons[$i]);
        }
    }


    public function testAddonsCreationWithIntervals()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_OTA@cloudbeds.com', 'Cloudbed$', 4);
        $this->loginToSite();
        $this->delAllProducts();
        $product_id = $this->addProduct($this->products[0]);
        $this->addons[0]['product_id'] = $product_id;
        $this->addAddon($this->addons[0], true);
    }

    public function testAddonBooking()
    {
        echo PHP_EOL. PHP_EOL. '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_OTA@cloudbeds.com', 'Cloudbed$', 4);
        $this->loginToSite();
        $this->createReservation('now', '+2 days');
    }
}
?>
