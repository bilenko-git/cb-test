<?php

trait Rates {
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

    public function rates_add_rate($interval) {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#layout #tab_0', 15000, 'css')->click();
        $add_new_rate_plan = $this->waitForElement('#layout #tab_0 .add_interval', 15000, 'css');
        $r = $add_new_rate_plan->click();
        $this->waitForElement('#layout #tab_0 [name=interval_name]', 15000, 'css')->value($interval['name']);
        $this->waitForElement('[name=start_date]')->click();
        $this->byCssSelector('#layout #tab_0 .new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->waitForElement('[name=start_date]')->value($value);
        $this->waitForElement('[name=end_date]')->click();
        $this->byCssSelector('#layout #tab_0 .new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->waitForElement('[name=end_date]')->clear();
        $this->waitForElement('[name=end_date]')->value($value);
        $this->byCssSelector('#layout #tab_0 .new_interval_form')->click();

        if (isset($interval['min'])){
            $this->waitForElement('[name=min_los]')->value($interval['min']);
        }

        if (isset($interval['max'])){
            $this->waitForElement('[name=max_los]')->value($interval['max']);
        }

        $l = $this->execute(array('script' => "return window.$('#tab_0 .define_week_days td:not(._hide) input').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ("#layout #tab_0 .define_week_days td:not(._hide) input:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }


        $this->byCssSelector('#layout #tab_0 .new_interval_form a.save_add_interval')->click();
        $this->save();
    }

    public function rates_remove_rate() {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#layout .nav-tabs .base.active a')->click();
        $this->waitForElement('#layout .tab-pane.base.active .intervals-table tr.r_rate:last .interval_delete', 15000, 'jQ')->click();
        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $this->save();
    }
}