<form action="/products/price/" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h2 class="modal-title" id="myModalLabel"><img src="<?php echo($product->img_url); ?>" class="img-thumbnail" width="40"> Add <?php echo($product->product_name); ?> price <button type="button" class="btn btn-sm btn-primary">£<?php echo($product->rrp); ?> RRP</button></h2>
    </div>
    <div class="modal-body">
        <input type="hidden" name="product_id" value="<?php echo($product->product_id); ?>" />

        <div class="form-group">
            <label for="merchant_id">Merchant</label>
            <select id="merchant_id" name="merchant_id" class="form-control input-lg">
            <?php foreach ($merchants as $merchant) { ?>
                <option value="<?php echo($merchant->merchant_id); ?>"><?php echo($merchant->merchant_name); ?></option>
            <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="offer_type">Offer type</label>
            <select id="offer_type" name="offer_type" class="form-control input-lg">
                <?php foreach ($offerTypes as $offerType) { ?>
                    <option value="<?php echo($offerType); ?>"><?php echo($offerType); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price (£)</label>
            <input type="text" id="price" name="price" class="form-control input-lg" value="0" />
        </div>

        <div class="form-group">
            <label for="price_url">Offer URL</label>
            <input type="text" id="price_url" name="price_url" class="form-control" value="" />
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>