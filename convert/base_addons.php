<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class base_addons extends test_restrict{
    private $products_url = 'http://{server}/connect/{property_id}#/products';
    private $booking_url = 'http://{server}/reservas/{property_id}';
    private $fees_and_taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    private $room_types_url = 'http://{server}/connect/{property_id}#/roomTypes';

    /**
     * Add new add-on without intervals
     * @param array $addon_info
     */
    public function addAddon($addon_info)
    {
        $this->url($this->_prepareUrl($this->products_url));
        $this->waitForLocation($this->_prepareUrl($this->products_url));
        $this->waitForElement('#tab_addons', 15000, 'css')->click();
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

        if (isset($interval['min'])){
            $this->byName('min_los')->value($interval['min']);
        }

        if (isset($interval['max'])){
            $this->byName('max_los')->value($interval['max']);
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
 }
?>
