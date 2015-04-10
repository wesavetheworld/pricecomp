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

    public static function getSiteData($asList = false)
    {
        $siteData = DB::table('site_data')->select('*')->get();

        if ($asList) {
            $siteDataList = null;
            foreach ($siteData as $data) {
                $siteDataList[$data->label] = $data->text;
            }

            return $siteDataList;
        }

        return $siteData;
    }

    public static function updateSiteData($key, $value)
    {
        return DB::table('site_data')->where('label', '=', $key)->update(array('text' => $value));
    }

    public static function getProductPriceData($productID, $date)
    {
        $priceData = array(
            'product_id' => $productID,
            'date' => $date,
            'average_price' => 0,
            'lowest_price' => 0,
            'lowest_price_id' => 0
        );

        $sql = "select round(avg(price), 2) as average_price
                from product_prices
                where product_id = ".$priceData['product_id']."
                and date_format(date,'%Y-%m-%d') = '".$priceData['date']."'";

        if ($data = DB::select($sql)) {
            $priceData['average_price'] = $data[0]->average_price;
        }


        $sql = "select price as lowest_price, id as lowest_price_id from product_prices
                where product_id = ".$priceData['product_id']."
                and date_format(date,'%Y-%m-%d') = '".$priceData['date']."'
                order by price";

        if ($data = DB::select($sql)) {
            $priceData['lowest_price'] = $data[0]->lowest_price;
            $priceData['lowest_price_id'] = $data[0]->lowest_price_id;
        }

        return $priceData;
    }

    public static function getProductDayChartData($productID, $date)
    {
        return DB::table('chart_data')->where('product_id', '=', $productID)->where('date', '=', $date)->first();
    }

    public static function setChartData($productID)
    {
        $date = date('Y-m-d');
        $priceData = self::getProductPriceData($productID, $date);

        if ($chartData = self::getProductDayChartData($productID, $date)) {
            $priceData['id'] = $chartData->id;
            Utils::updateEntry($priceData, 'chart_data');
        } else {
            Utils::insertEntry($priceData, 'chart_data');
        }
    }
}