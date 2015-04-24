<form action="/admin/edit/" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="redirect" value="<?php echo($redirect); ?>" />
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Update site data</h4>
    </div>
    <div class="modal-body">
        <?php foreach ($siteData as $data) { ?>
            <div class="form-group">
                <label for="<?php echo($data->label); ?>"><?php echo($data->label); ?></label>
                <textarea id="<?php echo($data->label); ?>" name="<?php echo($data->label); ?>" class="form-control" rows="3"><?php echo($data->text); ?></textarea>
            </div>
        <?php } ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>