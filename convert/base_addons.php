<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class base_addons extends test_restrict{
    private $products_url = 'http://{server}/connect/{property_id}#/products';
    private $booking_url = 'http://{server}/reservas/{property_id}';
    private $fees_and_taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    private $room_types_url = 'http://{server}/connect/{property_id}#/roomTypes';

    public function delAllProducts()
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_product', 15000, 'css')->click();
        $products = $this->execute(array('script' => "return window.BET.products.products({is_active: '1'})", 'args' => array()));
        print_r($products);
        $products_rows = $this->execute(array('script' => "return window.BET.products.map.products_table.dataTable().fnGetNodes()", 'args' => array()));
        print_r($products_rows);
    }

    /**
     * Add Product Item
     * @param $product
     * @return int|bool
     */
    public function addProduct($product)
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_product', 15000, 'jQ')->click();
        $add_new_product = $this->waitForElement('#tab_products .add-new-product', 15000, 'css');
        $add_new_product->click();

        $this->waitForElement('#product_modal', 7000);

        $this->byName('product_name')->value($product['product_name']);
        $this->byName('sku')->value($product['sku']);
        $this->byName('product_code')->value($product['product_code']);
        $this->byName('product_description')->value($product['product_description']);
        $this->byName('product_price')->value($product['product_price']);
        $this->byName('charge_type')->value($product['charge_type']);

        $btn = $this->waitForElement('#product_modal .btn.save_product', 30000);
        $btn->click();//click Save & Continue;

        $saved_product = $this->checkSavedProduct($product['sku']);
        print_r($saved_product);

        return $saved_product ? $saved_product['id'] : false;
    }

    /**
     * Get product by sku & check it
     * @param $product_sku
     * @return bool
     */
    public function checkSavedProduct($product_sku)
    {
        $saved_product = $this->execute(array('script' => "return window.BET.products.products({is_active: '1', sku: '" . $product_sku . "'})", 'args' => array()));
        $this->assertEquals(1, count($saved_product), 'Product has been saved');

        return $saved_product && count($saved_product) ? $saved_product[0] : false;
    }

    /**
     * Add new add-on without intervals
     * @param array $addon_info
     */
    public function addAddon($addon_info)
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_addon', 15000, 'css')->click();
        $add_new_addon = $this->waitForElement('#tab_addons .add-new-addon', 15000, 'css');
        $add_new_addon->click();
        $this->byName('addon_name')->value($addon_info['addon_name']);
        $this->byName('product_id')->value($addon_info['product_id']);
        $this->byName('transaction_code')->value($addon_info['transaction_code']);
        $this->byName('available')->value($addon_info['available']);
        $this->byName('charge_type')->value($addon_info['charge_type']);

        if ($addon_info['charge_type'] == 'quantity') {
            // we can input max qty
            $this->waitForElement('[name=max_qty_per_res]:visible', 15000, 'jQ');
            $this->byName('max_qty_per_res')->value($addon_info['max_qty_per_res']);
        } else {
            // not visible max qty field
            $this->waitForElement('[name=max_qty_per_res]:not(:visible)', 15000, 'jQ');
        }

        if ($addon_info['charge_type'] == 'per_guest' || $addon_info['charge_type'] == 'per_guest_per_night') {
            $this->waitForElement('[name=charge_for_children]:visible', 15000, 'jQ');
            $this->waitForElement('[name=charge_different_price_for_children]:not(:visible)', 15000, 'jQ');
            if (empty($addon_info['charge_for_children'])) {
                $this->byJQ("[name=charge_for_children][value='0']")->click();
                $this->waitForElement('[name=charge_different_price_for_children]:not(:visible)', 15000, 'jQ');
            } else {
                $this->byJQ("[name=charge_for_children][value='1']")->click();
                $this->waitForElement('[name=charge_different_price_for_children]:visible', 15000, 'jQ');
                if (empty($addon_info['charge_different_price_for_children'])) {
                    $this->byJQ("[name=charge_different_price_for_children][value='0']")->click();
                } else {
                    $this->byJQ("[name=charge_different_price_for_children][value='1']")->click();
                }
            }
        } else {
            $this->waitForElement('[name=charge_for_children]:not(:visible)', 15000, 'jQ');
            $this->waitForElement('[name=charge_different_price_for_children]:not(:visible)', 15000, 'jQ');
        }
        if (isset($addon_info['with_image'])) {
            $this->uploadAddonPhoto();
        }
        if (isset($addon_info['intervals'])) {
            foreach($addon_info['intervals'] as $interval) {
                // $this->addAddonInterval($interval);
            }
        }
        $this->saveAddon();
    }

    /**
     * Upload image to addon
     */
    public function uploadAddonPhoto() {
        $upload_button = $this->waitForElement('#layout .qq-uploader > .myimg_upload_featured');
        $upload_button->click();

        $this->waitForElement('#photo_upload_modal', 7000);

        $this->uploadFileToElement('body > input[type=\'file\']', __DIR__ .'/files/cloudbeds-logo-250x39.png');

        $btns = $this->waitForElement('#photo_upload_modal .btn.done', 30000);
        $btns->click();//click Done

        $btns = $this->waitForElement('#photo_upload_modal .save-uploader', 30000);
        $btns->click();//click Save & Continue;
    }

    /**
     * Add interval to opened add-on
     * @param $interval
     */
    public function addAddonInterval($interval)
    {
        $add_new_interval = $this->waitForElement('#tab_addons .add_interval', 15000, 'css');
        $add_new_interval->click();
        $this->byName('interval_name')->value($interval['name']);
        $this->byName('start_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->byName('start_date')->value($value);
        $this->byName('end_date')->click();
        $this->byCssSelector('.interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->byName('end_date')->clear();
        $this->byName('end_date')->value($value);
        $this->byCssSelector('.interval_form')->click();

        if (isset($interval['min_overlap'])){
            $this->byName('min_overlap')->value($interval['min_overlap']);
        }

        if (isset($interval['max_overlap'])){
            $this->byName('max_overlap')->value($interval['max_overlap']);
        }

        $l = $this->execute(array('script' => "return window.$('#tab_addons .define_week_days td:not(._hide) input').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ("#tab_addons .define_week_days td:not(._hide) input:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }


        $this->byCssSelector('.interval_form a.save_interval')->click();

        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
    }

    /**
     * Delete Interval and save Add-on. Only for opened add-on
     */
    public function delAddonInterval()
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('a[href="#tab_addon]', 15000, 'jQ')->click();

        $script_show = 'jQuery(".addon_form .intervals-table .btn-group", "#layout").css("cssText", "display: block !important;");';
        $script_hide = 'jQuery(".addon_form .intervals-table .btn-group", "#layout").css("cssText", "display: none !important;");';

        //prior to accessing the non-visible element
        $this->execute( array( 'script' => $script_show , 'args'=>array() ) );
        $this->waitForElement('#tab_addon .intervals-table tr[data-temp_id]:last .btn-group .dropdown-toggle', 15000, 'jQ');
        $this->byJQ('#tab_addon .intervals-table tr[data-temp_id]:last .btn-group .dropdown-toggle')->click();

        // Click Delete button
        $this->byJQ('#tab_addon .intervals-table tr[data-temp_id]:last .btn-group .dropdown-toggle .interval_delete')->click();

        $this->waitForElement('#confirm_delete', 50000, 'jQ');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();

        //after it no longer needs to be visible
        $this->execute( array( 'script' => $script_hide , 'args'=>array() ) );

        $this->saveAddon();
    }

    /**
     * Check Save panel & Save Add-on
     */
    public function saveAddon()
    {
        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
    }

    /**
     * Delete Add-on from table
     */
    public function delAddon($addon_id)
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('a[href="#tab_addon]', 15000, 'jQ')->click();

        $script_show = 'jQuery("#addons_list .btn-group", "#layout").css("cssText", "display: block !important;");';
        $script_hide = 'jQuery("#addons_list .btn-group", "#layout").css("cssText", "display: none !important;");';

        //prior to accessing the non-visible element
        $this->execute( array( 'script' => $script_show , 'args'=>array() ) );
        $this->waitForElement('#addon_' . $addon_id . ' .btn-group .dropdown-toggle', 15000, 'jQ');
        $this->byJQ('#addon_' . $addon_id . ' .btn-group .dropdown-toggle')->click();

        // Click Delete button
        $this->byJQ('#addon_' . $addon_id . ' .btn-group .dropdown-toggle .interval_delete')->click();

        $this->waitForElement('#confirm_delete', 50000, 'jQ');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();

        //after it no longer needs to be visible
        $this->execute( array( 'script' => $script_hide , 'args'=>array() ) );
    }

 }
