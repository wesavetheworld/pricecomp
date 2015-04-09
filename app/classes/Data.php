<?php
/**
 * Created by PhpStorm.
 * User: mhague
 * Date: 01/09/2014
 * Time: 12:09
 */

namespace classes;

use DB;

class Data
{
    public static function getProducts()
    {
        return DB::table('products')->select('*')->get();
    }

    public static function getProduct($value, $field = 'label')
    {
        return DB::table('products')->select('*')->where($field, '=', $value)->first();
    }

    public static function getMerchants()
    {
        return DB::table('merchants')->select('*')->get();
    }

    public static function getMerchant($id)
    {
        return DB::table('merchants')->select('*')->where('merchant_id', '=', $id)->first();
    }

    public static function getProductPrices($productID)
    {
        return DB::select("
            select *, max(pp.date)
            from product_prices pp
            inner join merchants m on pp.merchant_id = m.merchant_id
            where pp.product_id = ".$productID."
            group by pp.merchant_id
            order by pp.price
        ");
    }
}