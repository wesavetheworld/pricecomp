<?php echo View::make('header', array('title' => '- ' .$product->product_name, 'products' => $products, 'merchants' => $merchants, 'siteData' => $siteData)) ?>

<div class="row">
    <div class="col-md-2">
        <p><a href="/product/<?php echo($product->label); ?>"><img src="<?php echo($product->img_url); ?>" width="158" class="img-thumbnail"></a></p>
    </div>
    <div class="col-md-10">
        <h2>
            <?php echo($product->product_name); ?>
            <button type="button" class="btn btn-sm btn-primary">£<?php echo($product->rrp); ?> RRP</button>
            <?php if (isset($prices[0]->price)) { echo('<button type="button" class="btn btn-sm btn-warning">£'.$prices[0]->price.' best price</button>'); } ?>
        </h2>
        <p><?php echo($product->description); ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tbody>
            <?php foreach($prices as $price) { ?>
                <tr>
                    <td><img src="<?php echo($price->merchant_img_url); ?>" class="img-thumbnail"></td>
                    <td><h2><?php echo($price->merchant_name); ?></h2></td>
                    <td><h2>£<?php echo($price->price); ?></h2></td>
                    <td><h5 style="padding-top: 10px;"><?php echo($price->offer_type); ?><br>Added <?php echo(date('d/m/y H:i', strtotime($price->date))); ?></h5></td>
                    <td>
                        <?php if ($price->price_url && $price->price_url != '') { ?>
                            <h2><a href="<?php echo($price->price_url); ?>" target="_blank" class="btn btn-success">Buy</a></h2>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo View::make('footer') ?>
