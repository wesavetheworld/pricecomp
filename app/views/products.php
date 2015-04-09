<?php echo View::make('header', array('title' => '', 'products' => $products, 'merchants' => $merchants)) ?>

<div class="jumbotron">
    <h1>Price Comparison!</h1>
    <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet.</p>
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
