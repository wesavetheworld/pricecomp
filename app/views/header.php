<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Price Comparison <?php echo($title); ?></title>
    <link rel="icon" href="/img/icons/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
</head>

<body>

<div class="container">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Price Comparison</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Prices<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach ($products as $product) { ?>
                                <li><a href="#" class="load-modal" modal-url="/products/pricemodal/?label=<?php echo($product->label); ?>">Add <?php echo($product->product_name); ?> price</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Products<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" class="load-modal" modal-url="/products/modal/">Add new product</a></li>
                            <li class="divider"></li>
                            <?php foreach ($products as $product) { ?>
                                <li><a href="#" class="load-modal" modal-url="/products/modal/?label=<?php echo($product->label); ?>">Edit <?php echo($product->product_name); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Merchants<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" class="load-modal" modal-url="/merchants/modal/?redirect=/<?php echo(Request::path()); ?>">Add new merchant</a></li>
                            <li class="divider"></li>
                            <?php foreach ($merchants as $merchant) { ?>
                                <li><a href="#" class="load-modal" modal-url="/merchants/modal/?id=<?php echo($merchant->merchant_id); ?>&redirect=/<?php echo(Request::path()); ?>">Edit <?php echo($merchant->merchant_name); ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>