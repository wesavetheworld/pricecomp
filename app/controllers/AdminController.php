<?php

use classes\Data;
use classes\Utils;

class AdminController extends BaseController
{
    public function getModal()
    {
        return View::make('admin-modal', array(
            'siteData' => Data::getSiteData(),
            'redirect' => Input::get('redirect')
        ));
    }

    public function postEdit()
    {
        foreach (Request::all() as $key => $value) {
            if ($key !== 'redirect') {
                Data::updateSiteData($key, $value);
            }
        }

        return Redirect::to(Input::get('redirect'));
    }
}
