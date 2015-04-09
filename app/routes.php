<?php

Route::get('/', 'ProductController@showProducts');
Route::get('product/{name}', 'ProductController@showProduct');
Route::controller('/products', 'ProductController');
Route::controller('/merchants', 'MerchantController');
Route::controller('/data', 'DataController');
