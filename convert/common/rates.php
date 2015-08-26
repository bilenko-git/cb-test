<?php

trait Rates {
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

    public function rates_add_rate($interval) {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#tab_0', 15000, 'css')->click();
        $add_new_rate_plan = $this->waitForElement('#tab_0 .add_interval', 15000, 'css');
        $add_new_rate_plan->click();
        $this->byName('interval_name')->value($interval['name']);
        $this->byName('start_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->byName('start_date')->value($value);
        $this->byName('end_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->byName('end_date')->clear();
        $this->byName('end_date')->value($value);
        $this->byCssSelector('.new_interval_form')->click();

        if (isset($interval['min'])){
            $this->byName('min_los')->value($interval['min']);
        }

        if (isset($interval['max'])){
            $this->byName('max_los')->value($interval['max']);
        }

        $l = $this->execute(array('script' => "return window.$('#tab_0 .define_week_days td:not(._hide) input').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ("#tab_0 .define_week_days td:not(._hide) input:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }


        $this->byCssSelector('.new_interval_form a.save_add_interval')->click();

        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
    }

    public function rates_remove_rate() {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('.nav-tabs .base.active a', 15000, 'css')->click();
        $this->byJQ('.tab-pane.base.active .intervals-table tr.r_rate:last .interval_delete')->click();
        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
    }

}
