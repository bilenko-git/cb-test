<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class base_addons extends test_restrict{
    private $products_url = 'http://{server}/connect/{property_id}#/products';
    private $booking_url = 'http://{server}/reservas/{property_id}';
    private $fees_and_taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    private $room_types_url = 'http://{server}/connect/{property_id}#/roomTypes';

    /**
     * Confirm Delete dialog
     */
    public function confirmDeleteDialog()
    {
        $this->waitForElement('#confirm_delete', 15000);//delete confirmation almost all over site we can you this method to confim deleting something
        $this->waitForElement('.btn_delete', 5000)->click();
        echo '~~~~~~~~~~Confirmed Delete operation~~~~~~~~~~~~~~~~~~'.PHP_EOL;
    }

    /**
     * Delete all products from tab
     */
    public function delAllProducts()
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~ Started Del All Products function~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_product', 15000, 'css')->click();

        $num = count($this->getAllProducts()); // check number before save and after

        // Remove one by one
        while ($num > 0) {
            $this->waitForElement('.delete_product', 10000, 'css')->click();
            $this->confirmDeleteDialog();
            $this->timeouts()->implicitWait(5000);
            $num = count($this->getAllProducts());
        }
        $this->assertEquals($num, 0);
        echo '~~~~~~~~~ All products deleted successfully ~~~~~~~~~' . PHP_EOL;
    }

    /**
     * Delete all Add-ons from tab
     */
    public function delAllAddons()
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~ Started Del All Add-ons function~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_addon', 15000, 'css')->click();

        $num = count($this->getAllAddons()); // check number before save and after
        $script_show = 'jQuery("#addons_list .btn-group", "#layout").css("cssText", "display: block !important;");';
        $script_hide = 'jQuery("#addons_list .btn-group", "#layout").css("cssText", "display: none !important;");';

        //prior to accessing the non-visible element
        $this->execute( array( 'script' => $script_show , 'args'=>array() ) );
        // Remove one by one
        while ($num > 0) {
            $this->waitForElement('.delete_addon', 10000, 'css')->click();
            $this->confirmDeleteDialog();

            $this->timeouts()->implicitWait(5000);
            $num = count($this->getAllAddons());
        }
        $this->execute( array( 'script' => $script_hide , 'args'=>array() ) );
        $this->assertEquals($num, 0);
        echo '~~~~~~~~~ All Add-ons deleted successfully ~~~~~~~~~' . PHP_EOL;
    }

    public function checkAddonsForEmptyProducts()
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~ Check Addons for empty Products ~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_addon', 15000, 'css')->click();
        $add_new_product = $this->waitForElement('#tab_addons .add-new-addon', 15000, 'css');
        $add_new_product->click();

        $this->waitForElement('#no_items', 7000);
        $this->waitForElement('#no_items .btn[type=submit]', 30000, 'jQ')->click();
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#tab_products', 15000, 'css');
        echo '~~~~~~~~~~~~~~~  Checked successfully  ~~~~~~~~~~~~~~~' . PHP_EOL;
    }

    /**
     * Add Product Item
     * @param $product
     * @return int|bool
     */
    public function addProduct($product)
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~ Started Add Product function~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo 'New Product name = ' . $product['product_name'] . PHP_EOL;
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_product', 15000, 'jQ')->click();
        $add_new_product = $this->waitForElement('#tab_products .add-new-product', 15000, 'css');
        $add_new_product->click();

        $this->waitForElement('#product_modal', 7000);
        $this->byName('product_name')->value($product['product_name']);

        $sku = $this->byName('sku');
        $sku->click();
        $sku_new = $sku->value();
        echo 'SKU = ' . $sku_new . PHP_EOL;

        $this->byName('product_code')->value($product['product_code']);
        $this->byName('product_description')->value($product['product_description']);
        $this->byName('product_price')->value($product['product_price']);
        $btn = $this->waitForElement('#product_modal .btn.save_product', 30000);

        $this->getAllProducts(); // check number before save and after
        $btn->click();//click Save & Continue;
        $this->getAllProducts();

        $saved_product = $this->checkSavedProduct($sku_new);

        echo $saved_product ? 'Saved product id = ' . $saved_product['id'] . PHP_EOL : '';
        echo ($saved_product ? '~~~~~~~~~ Product saved successfully ~~~~~~~~~' : '~~~~~~~~~ Product NOT saved ~~~~~~~~~') . PHP_EOL;
        return $saved_product ? $saved_product['id'] : false;
    }

    /**
     * Get all products
     * @return array
     */
    public function getAllProducts()
    {
        $products = $this->execute(array('script' => "return window.BET.products.products({is_active: '1'})", 'args' => array()));
        echo 'Number of products = ' . count($products) . PHP_EOL;
        return $products;
    }

    /**
     * Get All add-ons
     * @return array
     */
    public function getAllAddons()
    {
        $addons = $this->execute(array('script' => "return window.BET.products.addons({is_deleted: '0'})", 'args' => array()));
        echo 'Number of add-ons = ' . count($addons) . PHP_EOL;
        return $addons;
    }

    /**
     * Get product by sku & check it
     * @param string $product_sku
     * @param bool $with_assert
     * @return bool
     */
    public function checkSavedProduct($product_sku, $with_assert = false)
    {
        $saved_product = $this->execute(array('script' => "return window.BET.products.products({is_active: '1', sku: '" . $product_sku . "'})", 'args' => array()));
        $with_assert && $this->assertEquals(1, count($saved_product), 'Check saved product id by sku = '. $product_sku);

        return $saved_product && count($saved_product) ? $saved_product[0] : false;
    }

    /**
     * Get add-on by unique name & check it
     * @param string $addon_name
     * @param bool $with_assert
     * @return bool
     */
    public function checkSavedAddon($addon_name, $with_assert = false)
    {
        $saved_addon = $this->execute(array('script' => "return window.BET.products.products({is_deleted: '0', addon_name: '" . $addon_name . "'})", 'args' => array()));
        $with_assert && $this->assertEquals(1, count($saved_addon), 'Check saved add-on with the following name: '. $addon_name);

        return $saved_addon && count($saved_addon) ? $saved_addon[0] : false;
    }

    /**
     * Add new add-on without intervals
     * @param array $addon_info
     * @return int|bool
     */
    public function addAddon($addon_info)
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~ Started Add Add-on function~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo 'New Add-on name = ' . $addon_info['addon_name'] . PHP_EOL;
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#open_addon', 15000, 'css')->click();
        $add_new_addon = $this->waitForElement('#tab_addons .add-new-addon', 15000, 'css');
        $add_new_addon->click();
        $this->byName('addon_name')->value($addon_info['addon_name']);
        $product_id = $this->byName('product_id');
        $this->select($product_id)->selectOptionByValue($addon_info['product_id']);
        $this->byName('transaction_code')->value($addon_info['transaction_code']);


        $charge_type = $this->byName('charge_type');
        //$this->select($charge_type)->value($addon_info['charge_type']);
        $this->select($charge_type)->selectOptionByValue($addon_info['charge_type']);
        echo 'Charge Type = ' . $addon_info['charge_type'] . PHP_EOL;
        $this->execute(array('script' => "window.$('#tab_addons [name=charge_type]').trigger('change');", 'args' => array()));
        $this->moveto(array(
            'element' => $this->byId('open_addon'), // If this is missing then the move will be from top left.
            'xoffset' => 10,
            'yoffset' => 10,
        ));
        $available = $this->byName('available');
        $this->select($available)->selectOptionByValue($addon_info['available']);
        echo 'Availability = ' . $addon_info['available'] . PHP_EOL;

        echo '~~~~~~~~~ Max QTY visibility checking ... ~~~~~~~~~'.PHP_EOL;
        $max_qty = $this->waitForElement('#max_qty_per_res', 15000, 'jQ', false);
        if ($addon_info['charge_type'] == 'quantity') {
            // we can input max qty
            $this->assertEquals($max_qty->displayed(), true, 'addAddon::1.1 Check visibility of max qty');
            $this->waitForElement('[name=max_qty_per_res]', 5000, 'jQ', false)->value($addon_info['max_qty_per_res']);
        } else {
            // not visible max qty field
            $this->assertEquals($max_qty->displayed(), false, 'addAddon::1.2 Check visibility of max qty');
        }
        echo '~~~~~~~~~ Max QTY visibility checked successfully ~~~~~~~~~'.PHP_EOL;

        echo '~~~~~~~~~ Charge for children & different charge checking....~~~~~~~~~'.PHP_EOL;
        $charge_for_children = $this->waitForElement('#charge_for_children', 30000, 'css', false);
        $charge_different_price_for_children = $this->waitForElement('#charge_different_price_for_children', 30000, 'css', false);
        $script_show = 'jQuery(".md-radio input[type=radio]", "#layout").css("cssText", "visibility: visible !important;");';
        $script_hide = 'jQuery(".md-radio input[type=radio]", "#layout").css("cssText", "visibility: hidden;");';

        //prior to accessing the non-visible radio element
        $this->execute( array( 'script' => $script_show , 'args'=>array() ) );
        if ($addon_info['charge_type'] == 'per_guest' || $addon_info['charge_type'] == 'per_guest_per_night') {
            $this->assertEquals($charge_for_children->displayed(), true, "addAddon::1.3 Check charge for children visibility for add-on with charge type = " . $addon_info['charge_type']);
            $this->assertEquals($charge_different_price_for_children->displayed(), false, "addAddon::1.4 Check different charge visibility for add-on with charge type = " . $addon_info['charge_type']);

            if (empty($addon_info['charge_for_children'])) {
                $this->byJQ("[name=charge_for_children][value='0']")->click();
                $this->check("[name=charge_for_children][value='0']");
                $charge_different_price_for_children = $this->waitForElement('#layout [name=charge_different_price_for_children]', 15000, 'jQ', false);
                $this->assertEquals($charge_different_price_for_children->displayed(), false, "addAddon::1.5 Check different charge visibility when charge for children = 0 for add-on with charge type = " . $addon_info['charge_type']);
            } else {
                $this->byJQ("[name=charge_for_children][value='1']")->click();
                $charge_different_price_for_children = $this->waitForElement('#layout [name=charge_different_price_for_children]', 15000, 'jQ', false);
                $this->assertEquals($charge_different_price_for_children->displayed(), true, "addAddon::1.6 Check different charge visibility for add-on with charge type = " . $addon_info['charge_type']);

                if (empty($addon_info['charge_different_price_for_children'])) {
                    $this->byJQ("[name=charge_different_price_for_children][value='0']")->click();
                    // TODO-natali ckeck intervals
                } else {
                    $this->byJQ("[name=charge_different_price_for_children][value='1']")->click();
                    // TODO-natali ckeck intervals
                }
            }
        } else {
            $this->assertEquals($charge_for_children->displayed(), false, "addAddon::1.7 Check charge for children visibility for add-on with charge type = " . $addon_info['charge_type']);
            $this->assertEquals($charge_different_price_for_children->displayed(), false, "addAddon::1.8 Check different charge visibility for add-on with charge type = " . $addon_info['charge_type']);
        }
        $this->execute( array( 'script' => $script_hide , 'args'=>array() ) );
        echo '~~~~~~~~~ Charge for children & different charge checked successfully~~~~~~~~~'.PHP_EOL;

        if (isset($addon_info['with_image'])) {
            $this->uploadAddonPhoto();
        }
        if (isset($addon_info['intervals'])) {
            foreach($addon_info['intervals'] as $interval) {
                // $this->addAddonInterval($interval);
            }
        }
        $this->saveAddon();

        $this->getAllAddons();
        $result = $this->checkSavedAddon($addon_info['addon_name']);
        $this->getAllAddons();
        echo $result ? 'Saved add-on id = ' . $result['id'] . PHP_EOL : '';
        echo ($result ? '~~~~~~~~~ Add-on saved successfully ~~~~~~~~~' : '~~~~~~~~~ Add-on NOT saved ~~~~~~~~~') . PHP_EOL;
        return $result ? $result['id'] : false;

    }

    /**
     * Upload image to addon
     */
    public function uploadAddonPhoto() {
        echo '~~~~~~~~~Upload Add-On Photo checking...~~~~~~~~~'.PHP_EOL;
        $upload_button = $this->waitForElement('#layout .qq-uploader > .myimg_upload_featured');
        $upload_button->click();

        $this->waitForElement('#photo_upload_modal', 7000);

        $this->uploadFileToElement('body > input[type=\'file\']', __DIR__ .'/files/cloudbeds-logo-250x39.png');

        $btns = $this->waitForElement('#photo_upload_modal .btn.done', 30000);
        $btns->click();//click Done

        $btns = $this->waitForElement('#photo_upload_modal .save-uploader', 30000);
        $btns->click();//click Save & Continue;
        echo '~~~~~~~~~Photo uploading checked successfully~~~~~~~~~'.PHP_EOL;
    }

    /**
     * Add interval to opened add-on
     * @param $interval
     */
    public function addAddonInterval($interval)
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~ Start of Add Addon Interval function ~~~~~~~~'.PHP_EOL;
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
        echo '~~~~~~~~~ Add-on Interval saved successfully ~~~~~~~~~' . PHP_EOL;

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
