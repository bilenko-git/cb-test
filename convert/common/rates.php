<?php

trait Rates {
    private $rates_url = 'http://{server}/connect/{property_id}#/roomRates';

    public function create_rate($rate) {
        return false;
    }
}
