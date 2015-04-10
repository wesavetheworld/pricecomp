<?php

use classes\Data;
use classes\Utils;

class ProductController extends BaseController
{
    public function showProduct($label)
    {
        if ($product = Data::getProduct($label)) {
            return View::make('product', array(
                'product' => $product,
                'products' => Data::getProducts(),
                'merchants' => Data::getMerchants(),
                'prices' => Data::getProductPrices($product->product_id),
                'siteData' => Data::getSiteData(true)
            ));
        }
    }

    public function showProducts()
    {
        return View::make('products', array(
            'products' => Data::getProducts(),
            'merchants' => Data::getMerchants(),
            'siteData' => Data::getSiteData(true)
        ));
    }

    public function getModal()
    {
        if (!$product = Data::getProduct(Input::get('label'))) {
            $product = array(
                'title' => 'Add new product',
                'product_id' => 0,
                'product_name' => '',
                'label' => '',
                'rrp' => 0,
                'img_url' => '',
                'description' => ''
            );
        } else {
            $product = get_object_vars($product);
            $product['title'] = $product['product_name'];
        }
        return View::make('product-modal', array(
            'product' => $product
        ));
    }

    public function getPricemodal()
    {
        if ($product = Data::getProduct(Input::get('label'))) {
            return View::make('price-modal', array(
                'product' => $product,
                'merchants' => Data::getMerchants(),
                'offerTypes' => array('Code','Sale','Deal')
            ));
        }
    }

    public function postEdit()
    {
        $product = array(
            'product_id' => Input::get('product_id'),
            'product_name' => Input::get('product_name'),
            'label' => Input::get('label'),
            'rrp' => Input::get('rrp'),
            'img_url' => Input::get('img_url'),
            'description' => Input::get('description')
        );

        if ($product['product_id'] == 0) {
            $product['product_id'] = Utils::insertEntry($product, 'products');
        } else {
            Utils::updateEntry($product, 'products', 'product_id');
        }

        if ($product = Data::getProduct($product['label'])) {
            return Redirect::to('/product/'.$product->label);
        } else {
            Redirect::to('/');
        }
    }

    public function postPrice()
    {
        $price = array(
            'product_id' => Input::get('product_id'),
            'merchant_id' => Input::get('merchant_id'),
            'price' => Input::get('price'),
            'offer_type' => Input::get('offer_type'),
            'price_url' => Input::get('price_url')
        );

        Utils::insertEntry($price, 'product_prices');
        Data::setChartData($price['product_id']);

        if ($product = Data::getProduct($price['product_id'], 'product_id')) {
            return Redirect::to('/product/'.$product->label);
        } else {
            Redirect::to('/');
        }
    }

    public function getTest()
    {
        $date = '2015-04-09';

        print_r(Data::getProductDayChartData(1, $date));

    }
}
