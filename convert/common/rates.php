<?php

trait Rates {
    private $rates_url = 'http://{server}/connect/{property_id}#/roomRates';

    public function rates_create_rate($rate) {
        return false;
    }
}
