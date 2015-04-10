<?php echo View::make('header', array('title' => '', 'products' => $products, 'merchants' => $merchants, 'siteData' => $siteData)) ?>

<div class="jumbotron">
    <h1><?php echo($siteData['homepage_title']); ?></h1>
    <p class="lead"><?php echo($siteData['homepage_desc']); ?></p>
</div>

<div class="row">
<?php foreach ($products as $product) { ?>
    <div class="col-md-2">
        <h4><?php echo($product->product_name); ?></h4>
        <p><a href="/product/<?php echo($product->label); ?>"><img src="<?php echo($product->img_url); ?>" class="img-thumbnail"></a></p>
    </div>
<? } ?>
</div>

<?php echo View::make('footer') ?>
