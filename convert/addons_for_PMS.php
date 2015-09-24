<?php
namespace MyProject\Tests;
require_once 'base_addons.php';

class addons_for_PMS extends base_addons {

    private $products = array(
        array(
            'sku' => '862621',
            'product_name' => 'Black Tea',
            'product_code' => 'BT0001',
            'product_description' => 'More of a Good Thing Tea - Our exceptional Earl Greyer blend combines the lush flavor and exquisite fragrance of the Bergamot Orange, native to Southern Italy, with a lovely Ceylon black tea leaf from Sri Lanka. Recognized as the best tasting Earl Grey available.',
            'product_price' => 11,
        ),
        array(
            'sku' => '862620',
            'product_name' => 'Wi-Fi',
            'product_code' => 'WF0001',
            'product_description' => '',
            'product_price' => 5.46,
        ),
        array(
            'sku' => '862619',
            'product_name' => 'Iron',
            'product_code' => 'IR0001',
            'product_description' => '',
            'product_price' => 5.46,
        ),
        array(
            'sku' => '862618',
            'product_name' => 'Cakes',
            'product_code' => 'CK0001',
            'product_description' => 'Cakes with kiwi',
            'product_price' => 28.1,
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
                    'interval_name' => '',
                    'min_overlap' => 0,
                    'max_overlap' => 0,
                    'start_date' => 'now',
                    'end_date' => '+2 days',
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

    private $interval = array(
        'name' => 'interval today',
        'value_today' => '20',
        'start' => 'now',
        'end' => '+1 days',
        'min' => '2',
        'edit_end_day' => '+12 days'
    );

    public function testSteps()
    {
        $this->setupInfo('wwwdev9.ondeficar.com', 'selenium_PMS@cloudbeds.com', 'Cloudbed$', 3);
        $this->loginToSite();
        $product_id = $this->addProduct($this->products[3]);
        var_dump($product_id);

        $this->addons[3]['product_id'] = $product_id;
        $this->addAddon($this->addons[3]);
    }
}
?>
