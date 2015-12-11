<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class base_addons extends test_restrict{
    private $products_url = 'http://{server}/connect/{property_id}#/products';
    private $booking_url = 'http://{server}/reservas/{property_id}';
    private $fees_and_taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    private $room_types_url = 'http://{server}/connect/{property_id}#/roomTypes';
    private $packages_list_url = 'http://{server}/connect/{property_id}#/packages';

    /**
     * Go to Packages List page
     */
    public function go_to_package_page()
    {
        $this->url($this->_prepareUrl($this->packages_list_url));
        $this->waitForLocation($this->_prepareUrl($this->packages_list_url));
    }

    /**
     * Go to Products Page
     */
    public function go_to_products_page()
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
    }

    /**
     * Confirm Delete dialog
     */
    public function confirmDeleteDialog()
    {
        $this->waitForElement('#confirm_delete', 20000);//delete confirmation almost all over site we can you this method to confim deleting something
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
        $this->go_to_products_page();
        $this->waitForElement('#open_product', 25000, 'css')->click();

        $num = count($this->getAllProducts()); // check number before save and after

        // Remove one by one
        while ($num > 0) {
            $this->waitForElement('#layout .delete_product', 12000, 'css')->click();
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
        $this->go_to_products_page();
        $this->waitForElement('#open_addon', 15000, 'css')->click();

        $num = count($this->getAllAddons()); // check number before save and after
        // Remove one by one
        while ($num > 0) {
            $this->waitForElement('#addons_list .delete_addon', 10000, 'css')->click();
            $this->confirmDeleteDialog();

            $this->timeouts()->implicitWait(5000);
            $num = count($this->getAllAddons());
        }

        $this->assertEquals($num, 0);
        echo '~~~~~~~~~ All Add-ons deleted successfully ~~~~~~~~~' . PHP_EOL;
    }

    public function checkAddonsForEmptyProducts()
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~ Check Addons for empty Products ~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->go_to_products_page();
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
        $this->go_to_products_page();
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
        $products = $this->getJSObject("BET.products.products({is_active: '1'})");
        echo 'Number of products = ' . count($products) . PHP_EOL;
        return $products;
    }

    /**
     * Get All add-ons
     * @return array
     */
    public function getAllAddons()
    {
        $addons = $this->getJSObject("BET.products.addons({is_deleted: '0'})");
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
        echo 'Checked Add-on name: ' . $addon_name . PHP_EOL;
        $saved_addon = $this->execute(array('script' => "return window.BET.products.addons({is_deleted: '0', addon_name: '" . $addon_name . "'})", 'args' => array()));
        $with_assert && $this->assertEquals(1, count($saved_addon), 'Check saved add-on with the following name: '. $addon_name);

        return $saved_addon && count($saved_addon) ? $saved_addon[0] : false;
    }

    /**
     * Add new add-on with or without intervals
     * @param array $addon_info
     * @param bool $with_intervals
     * @return int|bool
     */
    public function addAddon($addon_info, $with_intervals = false)
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~ Started Add Add-on function~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo 'New Add-on name = ' . $addon_info['addon_name'] . PHP_EOL;
        $this->go_to_products_page();
        $this->waitForElement('#open_addon', 15000, 'css')->click();
        $add_new_addon = $this->waitForElement('#tab_addons .add-new-addon', 15000, 'css');
        $add_new_addon->click();
        $this->byName('addon_name')->value($addon_info['addon_name']);
        $product_id = $this->byName('product_id');
        $this->select($product_id)->selectOptionByValue($addon_info['product_id']);
        $this->byName('transaction_code')->value($addon_info['transaction_code']);

        $charge_type = $this->byName('charge_type');
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
        $max_qty = $this->waitForElement('#layout #max_qty_per_res', 15000, 'jQ', false);
        if ($addon_info['charge_type'] == 'quantity') {
            // we can input max qty
            $this->assertEquals($max_qty && $max_qty->displayed(), true, 'addAddon::1.1 Check visibility of max qty');
            $this->waitForElement('[name=max_qty_per_res]', 5000, 'jQ', false)->value($addon_info['max_qty_per_res']);
        } else {
            // not visible max qty field
            $this->assertEquals($max_qty && $max_qty->displayed(), false, 'addAddon::1.2 Check visibility of max qty');
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
        if ($with_intervals && isset($addon_info['intervals'])) {
            echo PHP_EOL;
            echo 'Number of new intervals: ' . count($addon_info['intervals']) . PHP_EOL;
            echo PHP_EOL;
            foreach($addon_info['intervals'] as $i => $interval) {
                echo '>>>>>>> New Interval #' . $i . PHP_EOL;
                $this->addAddonInterval($interval);
            }
        }
        $this->getAllAddons();
        $this->saveAddon();
        $this->checkSavedMessage();
        $result = $this->checkSavedAddon($addon_info['addon_name']);
        $this->getAllAddons();
        echo $result ? 'Saved add-on id = ' . $result['id'] . PHP_EOL : '';
        echo ($result ? '~~~~~~~~~ Add-on saved successfully ~~~~~~~~~' : '~~~~~~~~~ Add-on NOT saved ~~~~~~~~~') . PHP_EOL;
        return $result ? $result['id'] : false;

    }

    /**
     * Check all fields & errors
     */
    public function checkAddonErrors()
    {
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~ Started Add-on Validation ~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $this->go_to_products_page();
        $this->waitForElement('#open_addon', 15000, 'css')->click();

        $add_new_addon = $this->waitForElement('#tab_addons .add-new-addon', 15000, 'css');
        $add_new_addon->click();
        $this->byName('transaction_code')->value('TEST'); // need to change something for showing panel
        $this->saveAddon();

        $this->waitForElement('#error_modal', 7000);
        $this->waitForElement('#error_modal button.close', 30000)->click();//click Done
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=addon_name]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(true, $has_error, 'Check error class for addon name');
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=product_id]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(true, $has_error, 'Check error class for product');
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=charge_type]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(true, $has_error, 'Check error class for charge type');

        $charge_type = $this->byName('charge_type');
        // Check QTY charge type
        $this->select($charge_type)->selectOptionByValue('quantity');
        echo 'Checking of QTY Charge Type' . PHP_EOL;
        $this->execute(array('script' => "window.$('#tab_addons [name=charge_type]').trigger('change');", 'args' => array()));
        $this->moveto(array(
            'element' => $this->byId('open_addon'), // If this is missing then the move will be from top left.
            'xoffset' => 10,
            'yoffset' => 10,
        ));

        echo 'Max QTY visibility checking ... '.PHP_EOL;
        $max_qty = $this->waitForElement('#max_qty_per_res', 15000, 'jQ', false);
        $this->assertEquals($max_qty->displayed(), true, 'Check visibility of max qty');

        echo 'Max QTY default value checking ... '.PHP_EOL;
        $default_value = $this->byName('max_qty_per_res')->value();
        $this->assertEquals($default_value, 0, 'Check default value of max qty');
        $this->saveAddon();
        $this->waitForElement('#error_modal', 7000);
        $this->waitForElement('#error_modal button.close', 30000)->click();//click Done
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=max_qty_per_res]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        // Max qty not required. We can set 0 or live empty
        $this->assertEquals(false, $has_error, 'Check error class for max qty');

        echo 'Max QTY with empty string checking ... '.PHP_EOL;
        $this->byName('max_qty_per_res')->value('');
        $this->saveAddon();
        $this->waitForElement('#error_modal', 7000);
        $this->waitForElement('#error_modal button.close', 30000)->click();//click Done
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=max_qty_per_res]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(false, $has_error, 'Check error class for empty max qty');

        echo 'Max QTY with correct number checking ... '.PHP_EOL;
        $this->byName('max_qty_per_res')->value('82');
        $this->byName('addon_name')->value('bla-bla');
        $this->saveAddon();
        $this->waitForElement('#error_modal', 7000);
        $this->waitForElement('#error_modal button.close', 30000)->click();//click Done
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=max_qty_per_res]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(false, $has_error, 'Check error class for correct max qty');
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=addon_name]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(false, $has_error, 'Check error class for not empty add-on name');

        $this->cancelAddon();
        $add_new_addon->click();

        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=addon_name]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(false, $has_error, 'Check error class for addon name');
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=product_id]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(false, $has_error, 'Check error class for product');
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=charge_type]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(false, $has_error, 'Check error class for charge type');

        echo '~~~~~~~~~~~~~ Add-on Validation checked successfully ~~~~~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
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
        echo '~~~~~~~~ Start of Add Add-on Interval function ~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
        $add_new_interval_btn = $this->waitForElement('#tab_addons .add_date_range', 15000, 'css');
        $add_new_interval_btn->click();

        $form = $this->waitForElement('.portlet.interval_form', 10000);
        echo 'Interval Name: ' . $interval['interval_name']. PHP_EOL;
        echo PHP_EOL;
        if($form instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $value = $this->convertDateToSiteFormat($interval['start_date']);
            $this->byName('start_date')->click();
            echo 'Start Date:' . $value . PHP_EOL;
            $this->byName('start_date')->value($value);
            $this->byName('end_date')->click();
            $form->click();

            $value = $this->convertDateToSiteFormat($interval['end_date']);
            $this->byName('end_date')->clear();
            echo 'End date: ' . $value . PHP_EOL;
            $this->byName('end_date')->value($value);
            $form->click();

            $interval_name = $this->byName('interval_name');
            $interval_name->click();
            $interval_name->clear();
            $interval_name->value($interval['interval_name']);
            $form->click();
        }
        if (isset($interval['min_overlap'])){
            $min_overlap = $this->byName('min_overlap');
            $min_overlap->clear();
            $min_overlap->value($interval['min_overlap']);
            $min_overlap->click();
        }

        if (isset($interval['max_overlap'])){
            $max_overlap = $this->byName('max_overlap');
            $max_overlap->clear();
            $max_overlap->value($interval['max_overlap']);
            $max_overlap->click();
        }

        if (isset($interval['room_types']) && count($interval['room_types'])) {
            echo "Number of selected room types = " . count($interval['room_types']) . PHP_EOL;
            foreach($interval['room_types'] as $room_type) {
                if (isset($room_type['room_type_id'])) {
                    echo PHP_EOL;
                    echo "Room type id = " . $room_type['room_type_id'] . PHP_EOL;
                    $avail_button = $this->waitForElement('[name=\'available_room_types\'] + div > button', 15000, 'jQ');
                    $avail_button->click();//open
                    $room_type_checkbox = $this->waitForElement('[data-name=\'selectItemavailable_room_types\'][value=\'' . $room_type['room_type_id'] . '\'] + label', 16000, 'jQ', false);
                    echo "Room type is visible? " . ($room_type_checkbox->displayed() ? 'Yes' : 'No') . PHP_EOL;
                    $room_type_checkbox->click();
                    $avail_button->click();//close
                    $form->click();

                    //for better video view
                    $this->execute(array(
                        'script' => "window.$('html, body').animate({scrollTop: '+=200px'}, 0);",
                        'args' => array()
                    ));

                    // For each day of week: from sunday(0) to saturday(6)
                    // 'day_0' => 1, // checked checkbox
                    // 'day_0_adult_price' => 11, // price for adult
                    // 'day_0_child_price' => 10, // price for child
                    for($i = 0; $i <= 6; $i++) {

                        echo 'Day ' . $i . ' checking...' . PHP_EOL;
                        $checkbox_selector = '[name=\'day_' . $i . '_' . $room_type['room_type_id'] . '\']';
                        $room_type_checkbox_label = $this->waitForElement($checkbox_selector . ' + label', 10000, 'jQ', false);
                        $room_type_checkbox = $this->waitForElement($checkbox_selector, 10000, 'jQ', false);
                        echo 'Visible checkbox? ' . ($room_type_checkbox_label->displayed() ? 'Yes' : 'No') . PHP_EOL;
                        if ($room_type_checkbox_label->displayed()) {
                            $is_enabled = $room_type_checkbox->enabled();
                            echo 'Enabled checkbox? ' . ($is_enabled ? 'Yes' : 'No') . PHP_EOL;
                            echo 'Checkbox selected? ' . ($room_type_checkbox->selected() ? 'Yes' : 'No') . PHP_EOL;

                            if ($is_enabled) {
                                $adult_input_selector = '[name=\'day_' . $i . '_adult_price_' . $room_type['room_type_id'] . '\']';
                                $child_input_selector = '[name=\'day_' . $i . '_child_price_' . $room_type['room_type_id'] . '\']';

                                if (!empty($room_type['day_' . $i])) {
                                    // If need to turn on checkbox for this day
                                    if (!$room_type_checkbox->selected()) {
                                        $room_type_checkbox_label->click();
                                    }

                                    $adult_price_input = $this->waitForElement($adult_input_selector, 10000, 'jQ');
                                    $child_price_input = $this->waitForElement($child_input_selector, 10000, 'jQ', false);

                                    $charge_type = $this->select($this->byName('charge_type'))->value();
                                    echo 'Charge Type = ' . $charge_type . PHP_EOL;
                                    $this->assertEquals(true, $adult_price_input->enabled(), 'Check enabled adult price field');
                                    // Check visibility
                                    if ($charge_type != 'per_guest' && $charge_type != 'per_guest_per_night') {
                                        $this->assertEquals(false, $child_price_input->displayed(), 'Check visibility of child price field. Need to be hidden');
                                    }

                                    if ($adult_price_input->enabled()) {
                                        $adult_price_input->clear();
                                        $adult_price_input->value($room_type['day_' . $i . '_adult_price']);


                                        echo 'Price for child is visible? ' . ($child_price_input->displayed() ? 'Yes' : 'No') . PHP_EOL;
                                        if ($child_price_input->displayed()) {
                                            $child_price_input->clear();
                                            $child_price_input->value($room_type['day_' . $i . '_child_price']);
                                        }
                                    }
                                } else {
                                    // IF we turn off checkbox for day
                                    // Need to check fields with prices. Need to be readonly (disabled)
                                    if ($room_type_checkbox->selected()) {
                                        $room_type_checkbox_label->click();
                                    }
                                    $adult_price_input = $this->waitForElement($adult_input_selector, 10000, 'jQ');
                                    $child_price_input = $this->waitForElement($child_input_selector, 10000, 'jQ', false);
                                    $this->assertEquals(false, $adult_price_input->enabled(), 'Check disabled adult price field');
                                    $this->assertEquals(false, $child_price_input->enabled(), 'Check disabled child price field');
                                }
                            }
                        }
                    }

                } else {
                    $this->fail('room type id can not be selected');
                }
            }
        }

        $this->waitForElement('.save_interval', 5000, 'jQ')->click();
        echo '~~~~~~~~~~~~~~~~ Interval added successfully ~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
    }

    /**
     * Delete Interval and save Add-on. Only for opened add-on
     */
    public function delAddonInterval()
    {
        $this->go_to_products_page();
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
        $this->checkSavedMessage();
    }

    /**
     * Check Save panel & Save Add-on
     */
    public function saveAddon()
    {
        $save = $this->waitForElement('#panel-save .btn-html .btn.save_add_ons', 15000, 'css');
        $save->click();
    }

    public function checkSavedMessage()
    {
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
    }

    public function checkUniqueAddonName()
    {
        $this->waitForElement('#error_modal', 7000);
        $this->waitForElement('#error_modal button.close', 30000)->click();//click Done
        $has_error = $this->execute(array('script' => "return window.$('#tab_addons [name=addon_name]').closest('.form-group').hasClass('has-error');", 'args' => array()));
        $this->assertEquals(true, $has_error, 'Check error class for unique add-on name');
    }

    public function cancelAddon()
    {
        $cancel = $this->waitForElement('#panel-save .btn-html .btn.cancel_add_ons', 15000, 'css');
        $cancel->click();
    }

    /**
     * Delete Add-on from table
     */
    public function delAddon($addon_id)
    {
        $this->go_to_products_page();
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

    public function editAddonAction($addonId)
    {
        $script_show = 'jQuery(".addons-list-block .table-scrollable", "#layout").addClass("table-scrollable-tmp").removeClass("table-scrollable");';
        $script_hide = 'jQuery(".addons-list-block .table-scrollable-tmp", "#layout").addClass("table-scrollable").removeClass("table-scrollable-tmp");';

        //prior to accessing the non-visible element
        $this->execJS($script_show);
        //for better video view
        $this->execJS("window.$('html, body').animate({scrollLeft: '+=200px'}, 0);");

        $this->waitForElement('#layout #addons_list #addon_'. $addonId . ' .action-btn.edit_addon', 15000, 'jQ')->click();
        // undo style changes
        $this->execJS($script_hide);
    }

    public function createReservation($start, $end)
    {
        //will check next week
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end, strtotime($startDate)));

        //get first available room on booking
        $url = $this->_prepareUrl($this->booking_url).'#checkin='.$startDate.'&checkout='.$endDate;
        $this->url($url);
        $this->waitForLocation($url);

        //looking for first room block in list
        try {
            $el = $this->waitForElement('.room_types .room:first', 20000, 'jQ');
        }
        catch (\Exception $e)
        {
            $this->fail('No rooms to booking');
        }

        $roomTypeId = $this->getAttribute($el, 'data-room_type_id');
        $selectName = $this->execute(array('script' => 'return window.$(".room_types .room:first select.rooms_select").attr("name")', 'args' => array()));
        $rateId = preg_replace('/qty_rooms\[(\d+)\]/', '$1', $selectName);
        echo 'Room Type Id = ' . $roomTypeId . PHP_EOL;
        echo 'rate Id = ' . $rateId . PHP_EOL;
        echo 'Select Name = ' . $selectName . PHP_EOL;

        echo 'Search start date = ' . $startDate . PHP_EOL;
        echo 'Search end date = ' . $endDate . PHP_EOL;

        //get cache before booking
        $beforeAvailability = $this->getAvailability($startDate, $endDate, $roomTypeId, false, true);

        //select 1 room
        $el->byCssSelector('div.rooms_select button')->click();
        $this->byjQ('div.rooms_select ul.dropdown-menu li:eq(1) a')->click();

        //waiting for folio js execution
        $this->waitForElement('.selected_rooms_price');
        $this->byCssSelector('.book_now')->click();

        //waiting for rendering
        $el = $this->waitForElement('select[name="country"]');

        //fill out all inputs/textarea
        $this->execute(array('script' => 'window.$("input:text[class~=\'required\']").filter(function(){return !this.value;}).val(\'test\')', 'args' => array()));
        $this->execute(array('script' => 'window.$("textarea[class~=\'required\']").filter(function(){return !this.value;}).val(\'test\')', 'args' => array()));

        //set country
        $el->value('US');

        //set email
        $this->byId('email')->value('test@test.com');

        //select Bank Transfer
        $this->byCssSelector('.payment_method label[for="ebanking"]')->click();

        $this->execute(array('script' => 'window.$("#agree_terms").click()', 'args' => array()));

        //Go booking
        $this->byCssSelector('.finalize')->click();

        //waiting for success status
        try {
            $this->waitForElement('.reserve_success', 40000);
        }
        catch (\Exception $e) {
            $this->fail('Reserva was not added');
        }

        $cookie = $this->cookie();
        $reservationIdentifier = $cookie->get('last_reservation_id');
        echo "Coockie [RESERVATION ID]: " . $reservationIdentifier . PHP_EOL;
        echo '~~~~~~~~~~~~~~~~ Booking Creation successfully ~~~~~~~~~'.PHP_EOL;

        $btn = $this->waitForElement('.select_addons a', 20000);
        $addons_block = $this->waitForElement('.addons', 15000,'css', false);
        $this->assertEquals(false, $addons_block->displayed(), 'Check visibility of block');
        $btn->click();

        $addons_block = $this->waitForElement('.addons', 15000,'css', false);
        $this->assertEquals(true, $addons_block->displayed(), 'Check visibility of block after click');

        $addons = $this->elements($this->using('css selector')->value('.room_services'));
        echo 'Add-ons count: ' . count($addons) . PHP_EOL;

        if (count($addons)) {
            echo PHP_EOL . 'LIST OF FOUND ADD-ONS:' . PHP_EOL;
            foreach($addons as $i => $addon) {
                //TODO-natali select more than one type
                $addonId = $this->getAttribute($addon, 'data-id');
                $roomTypeId = $this->getAttribute($addon, 'data-room_type_id');
                $addonPrice = $this->getAttribute($addon, 'data-price');
                $chargeType = $this->getAttribute($addon, 'data-charge-type');
                $roomIdentifier = $this->getAttribute($addon, 'data-room_identifier');
                echo '>>' . $i . PHP_EOL;
                echo 'Add-on #' . $addonId . ' for room type "' . $roomTypeId . '". Price: "' . $addonPrice . '". Charge Type: ' . $chargeType . PHP_EOL;
                echo 'Room Identifier: '. $roomIdentifier . PHP_EOL;
                echo 'Checking...' . PHP_EOL;
                if ($chargeType == 'quantity') {
                    // Select list needed
                    //$addonBlock = $this->waitForElement('.room_services[data-id="' . $addonId . '"][data-room_identifier="' . $roomIdentifier . '"]', 10000, 'jQ', false);

                    //select 1 item
                    //$selectList = $addonBlock->byCssSelector('div.addon_count button');
                    $selectList = $addon->byCssSelector('div.addon_count button');
                    $this->assertEquals(false, $selectList->disaplayed(), 'Select list needed');

                    $selectList->click();
                    $this->byjQ('div.addon_count ul.dropdown-menu li:eq(1) a')->click();

                    echo 'Success' . PHP_EOL;
                } else {
                    // Checkbox needed
                    //$addonCheckbox = $this->waitForElement('#addon_checkbox_' . $addonId  . '_' . $i .' + label', 15000, 'jQ', false);
                    $addonCheckbox = $addon->byCssSelector('#addon_checkbox_' . $addonId  . '_' . $roomIdentifier .' + label');
                    $this->assertEquals(true, $addonCheckbox->displayed(), 'Checkbox needed');

                    if (! $addonCheckbox->selected()) {
                        // Select add-on if it is not selected
                        $addonCheckbox->click();
                    }
                    echo 'Success' . PHP_EOL;
                }
            }
            // Click Update Total Price Button
            $this->waitForElement('button.update-btn', 15000)->click();


            //waiting for success status
            try {
                // Need to be the block with sold add-ons
                $this->waitForElement('.additional_saved_items', 40000);
            }
            catch (\Exception $e) {
                $this->fail('Add-ons not added to reservation #' . $reservationIdentifier);
            }
        } else {
            echo 'There are no add-ons' . PHP_EOL;
        }

        echo '~~~~~~~~~~~~~~~~ Add-ons for booking page checked successfully ~~~~~~~~~'.PHP_EOL;
        echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
    }

    /* -------------------------------------------------------------------------------- */
    /* ------------------------------- PART FOR PACKAGES ------------------------------ */
    public function addPackage(&$package){
        $this->waitForElement('#layout .add-new-package', 30000)->click();
        if(!$this->waitForElement('#layout .package-edit-block', 15000)){
            $this->fail('Form add package was not opened at time.');
        }
        echo PHP_EOL . "------->Sleep 15". PHP_EOL;
        sleep(2);

        $this->fillPackage($package);

        $package_id = $this->getLastPackagesID();
        $package['package_id'] = $package_id;
        return $package_id;
    }

    public function getLastPackagesID() {
        $last_tr = $this->waitForElement('#layout .packages-table tbody > tr[data-id]:last', 5000, 'jQ');

        if($last_tr){
            return $this->getAttribute($last_tr, 'data-id');
        }

        return false;
    }

    public function removePackage($package_id) {
        $delete_package_btn = $this->waitForElement('#layout .packages-table tbody > tr[data-id=\''.$package_id.'\'] .action-btn.delete', 5000, 'jQ');
        sleep(1);
        $delete_package_btn->click();

        $this->confirmDeleteDialog();
    }

    public function fillPackage(&$package) {
        $is_derived = false;
        echo '~~~~~~~~~~~~~~~~ Fill Package ~~~~~~~~~'.PHP_EOL;

        if(isset($package['is_derived'])) {
            $this->waitForElement('[name=\'derived\'][value=\''.($package['is_derived']?1:0).'\'] + label', 5000, 'jQ')->click();
            $is_derived = $package['is_derived'];
        }

        if(isset($package['addons'])) {
            if (count($package['addons'])) {
                foreach($package['addons'] as $id) {
                    echo "Add-on ID selected for package = ". $id .PHP_EOL;
                    $addons_button = $this->waitForElement('[name=\'addons\'] + div > button', 15000, 'jQ');
                    $addons_button->click();//open
                    $this->waitForElement('[name=\'selectItemaddons\'][value=\''.$id.'\'] + label', 16000, 'jQ')->click();
                    $addons_button->click();//close
                }
                echo "Selected Add-ons:" . PHP_EOL;
                $selectedAddons = $this->getJSObject("$('select[name=addons]', '#layout').val();");
                var_dump($selectedAddons);
            }
        }

        if(isset($package['have_promo'])) {
            $this->waitForElement('[name=\'have_promo\'][value=\''.($package['have_promo']?1:0).'\'] + label', 5000, 'jQ')->click();
        }

        if(isset($package['promo_code'])) {
            $promo_code_input = $this->waitForElement('[name=\'promo_code\']', 5000, 'jQ', true);
            $promo_code_input->value($package['promo_code']);
        }

        sleep(1);

        foreach($package as $selector => $value){
            if(in_array($selector, array('is_derived', 'addons', 'have_promo', 'ranges', 'promo_code'))) continue;
            echo 'Field '. $selector. ' = ' . $value .PHP_EOL;
            $this->execJS('window.$("'.$selector.'", "#layout").each(function() {
                $(this).val("'.$value.'");
            });');
        }

        foreach($package['ranges'] as &$range) {
            $rm_type_id = $this->addPackageRange($range, $is_derived);
            $range['rm_type_id'] = $rm_type_id;
        }

        $this->uploadPackagePhoto();

        $btns = $this->waitForElement('.edit-package-save.btn-save-panel', 5000);
        $this->waitToVisible($btns, 30000);
        if($btns) $btns->click();//click Save on save panel

        $result = $this->waitForElement('#layout .package-list-block', 5000);
        if(!$result) {
            $this->fail('Saving failed');
        }
    }

    public function addPackageRange($range, $is_derived)
    {
        $this->waitForElement('.btn.date_range', 15000)->click();

        $form = $this->waitForElement('.portlet.add_interval', 10000);

        if($form instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $skip = array('prices', 'available_room_types', 'closed_to_arrival');
            foreach($range as $selector => $value) {
                if(!in_array($selector, $skip)) {
                    echo "Search " . $selector . PHP_EOL;
                    if(strpos($selector, 'date') !== FALSE){
                        $value = $this->convertDateToSiteFormat($value);
                    }

                    $input = $form->byName($selector);
                    $input->click();
                    $form->click();
                    $input->value($value);
                }
            }
            $form->click();
            sleep(1);
            if(isset($range['closed_to_arrival'])) {
                $this->waitForElement('[name=\'closed_to_arrival\'][value=\''.($range['closed_to_arrival']?1:0).'\'] + label', 5000, 'jQ')->click();
            }
        }

        $rm_type_id = 0;
        $rm_types = $this->getJSObject('BET.roomTypes.items()');

        if($rm_types && is_array($rm_types)) {
            foreach ($rm_types as $index => $rm_type) {
                if (empty($rm_type['room_type_capacity'])) unset($rm_types[$index]);
            }

            $rm_type_id = $rm_type['room_type_id'];
        }

        if($rm_type_id) {
            echo "rm_type_id = ".$rm_type_id.PHP_EOL;
            $avail_button = $this->waitForElement('[name=\'available_room_types\'] + div > button', 15000, 'jQ');
            $avail_button->click();//open
            $room_type_checkbox = $this->waitForElement('[name=\'selectItemavailable_room_types\'][value=\''.$rm_type_id.'\'] + label', 16000, 'jQ')->click();
            $avail_button->click();//close
            $form->click();

            //for better video view
            $this->execute(array(
                'script' => "window.$('html, body').animate({scrollTop: '+=200px'}, 0);",
                'args' => array()
            ));

            if(!$is_derived && !empty($range['prices'])) {
                foreach ($range['prices'] as $index => $price) {
                    $price_input_selector = '[name=\'day_' . $rm_type_id . '_' . $index . '\']';
                    echo "Price selector: " . $price_input_selector . PHP_EOL;
                    $price_input = $this->waitForElement($price_input_selector, 12000, 'jQ', false);
                    echo "Is enabled? " . $price_input->enabled() ? 'Yes' : 'No';
                    if ($price_input->enabled()) {
                        $price_input->clear();
                        $price_input->value($price);
                    }
                }
            }

            $this->waitForElement('.save_add_interval', 5000, 'jQ')->click();
        } else {
            $this->fail('room type id can not be selected');
        }

        return $rm_type_id;
    }

    public function uploadPackagePhoto() {
        $upload_button = $this->waitForElement('#layout .package-uploader > .myimg_upload');
        $upload_button->click();

        $modal = $this->waitForElement('#photo_upload_modal', 7000);

        $this->uploadFileToElement('body > input[type=\'file\']', __DIR__ .'/files/cloudbeds-logo-250x39.png');

        $btns = $this->waitForElement('#photo_upload_modal .btn.done', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
        $btns->click();//click Done

        $btns = $this->waitForElement('#photo_upload_modal .save-uploader', 30000);//$modal->elements($this->using('css selector')->value('.btn.done'));
        $btns->click();//click Save & Continue;
    }

    public function editPackageAction($package_id){
        echo 'Edit Package ' . $package_id . PHP_EOL;
        $this->waitForElement('#layout .packages-table tbody > tr[data-id=\''.$package_id.'\'] .action-btn.edit', 10000, 'jQ')->click();
    }
}
