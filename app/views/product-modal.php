<form action="/products/edit/" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo($product['title']); ?></h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="product_id" value="<?php echo($product['product_id']); ?>" />

        <div class="form-group">
            <label for="product_name">Product name</label>
            <input type="text" id="product_name" name="product_name" class="form-control" value="<?php echo($product['product_name']); ?>" />
        </div>

        <div class="form-group">
            <label for="label">Product label (URL)</label>
            <input type="text" id="label" name="label" class="form-control" value="<?php echo($product['label']); ?>" />
        </div>

        <div class="form-group">
            <label for="label">RRP (£)</label>
            <input type="text" id="rrp" name="rrp" class="form-control" value="<?php echo($product['rrp']); ?>" />
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?php echo($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="label">Thumbnail</label>
            <input type="text" id="img_url" name="img_url" class="form-control" value="<?php echo($product['img_url']); ?>" />
        </div>

        <?php if ($product['img_url'] <> '') { ?>
            <p><img src="<?php echo($product['img_url']); ?>" class="img-thumbnail"></p>
        <?php } ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>