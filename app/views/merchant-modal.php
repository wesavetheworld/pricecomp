<form action="/merchants/edit/" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo($merchant['title']); ?></h4>
    </div>
    <div class="modal-body">
        <input type="hidden" name="merchant_id" value="<?php echo($merchant['merchant_id']); ?>" />
        <input type="hidden" name="redirect" value="<?php echo($redirect); ?>" />
        <div class="form-group">
            <label for="merchant_name">Merchant name</label>
            <input type="text" id="merchant_name" name="merchant_name" class="form-control" value="<?php echo($merchant['merchant_name']); ?>" />
        </div>

        <div class="form-group">
            <label for="label">URL</label>
            <input type="text" id="merchant_url" name="merchant_url" class="form-control" value="<?php echo($merchant['merchant_url']); ?>" />
        </div>

        <div class="form-group">
            <label for="label">Thumbnail</label>
            <input type="text" id="merchant_img_url" name="merchant_img_url" class="form-control" value="<?php echo($merchant['merchant_img_url']); ?>" />
        </div>

        <?php if ($merchant['merchant_img_url'] <> '') { ?>
            <p><img src="<?php echo($merchant['merchant_img_url']); ?>" class="img-thumbnail"></p>
        <?php } ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>