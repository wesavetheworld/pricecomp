<?php

use classes\Data;
use classes\Utils;
use Request;

class MerchantController extends BaseController
{
    public function getModal()
    {
        if (!$merchant = Data::getMerchant(Input::get('id'))) {
            $merchant = array(
                'title' => 'Add new merchant',
                'merchant_id' => 0,
                'merchant_name' => '',
                'merchant_url' => '',
                'merchant_img_url' => ''
            );
        } else {
            $merchant = get_object_vars($merchant);
            $merchant['title'] = $merchant['merchant_name'];
        }
        return View::make('merchant-modal', array(
            'merchant' => $merchant,
            'redirect' => Input::get('redirect')
        ));
    }

    public function postEdit()
    {
        $merchant = array(
            'merchant_id' => Input::get('merchant_id'),
            'merchant_name' => Input::get('merchant_name'),
            'merchant_url' => Input::get('merchant_url'),
            'merchant_img_url' => Input::get('merchant_img_url')
        );

        if ($merchant['merchant_id'] == 0) {
            $merchant['merchant_id'] = Utils::insertEntry($merchant, 'merchants');
        } else {
            Utils::updateEntry($merchant, 'merchants', 'merchant_id');
        }

        return Redirect::to(Input::get('redirect'));
    }
}
