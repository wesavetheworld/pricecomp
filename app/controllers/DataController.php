<?php

use classes\Data;
use classes\Utils;

class DataController extends BaseController {

    public function getProducts()
    {
        foreach (Data::getProducts() as $product) {
            echo('<img src="'.$product->thumb_url.'"><br>');
        }
    }

    public function getWhatsapp()
    {
        return View::make('whatsapp');
    }
}
