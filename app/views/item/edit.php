<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Catalog</h4>
            <?php echo wLinkDefault(_route('item:index'), 'Catalogs')?>
        </div>

        <div class="card-body">
            <?php Flash::show()?>
            <?php echo $_form->start()?>
            <?php echo $_form->getCol('id')?>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <?php echo $_form->getCol('reference')?>
                    </div>
                    <div class="form-group">
                        <?php echo $_form->getCol('title')?>
                    </div>
                    <div class="form-group">
                        <?php echo $_form->getCol('brief')?>
                    </div>

                    <div class="form-group">
                        <?php echo $_form->getCol('description')?>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col"><?php echo $_form->getCol('tags')?></div>
                            <div class="col"><?php echo $_form->getCol('genre')?></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <?php echo $_form->getCol('year', ['value' => date('Y')])?>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <?php echo $_form->getCol('authors')?>
                        <small>Use user Id, Seperate by comma (',')</small>
                    </div>
                    <div class="form-group">
                        <?php echo $_form->getCol('publishers')?>
                        <small>Use user Id, Seperate by comma (',')</small>
                    </div>

                    <div class="form-group mt-3">
                        <?php Form::submit('', 'Save Changes')?>
                        <a href="#" class="btn btn-danger btn-sm form-verify">Delete</a>
                        <a href="<?php echo _route('item:show', $catalog->id)?>" class="btn btn-info btn-sm" style="margin-left: 20px;">Show Catalog</a>
                    </div>
                </div>
            </div>

            <?php echo wDivider(40)?>
            <section class="mt-2">
                <h4>Image And Document</h4>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td>Wallpaper</td>
                            <td>
                                <a href="<?php echo _route('viewer:show', null, ['file' => $catalog->wallpaper->full_url])?>"><img src="<?php echo $catalog->wallpaper->full_url?>"></a>
                                <?php echo $catalog->wallpaper->display_name?>
                            </td>
                            <td><?php echo wLinkDefault('#','Remove')?> | <?php echo wLinkDefault('#', 'Change')?></td>
                        </tr>

                        <tr>
                            <td>Document</td>
                            <td>
                                <?php echo $catalog->document->display_name?>
                                <a href="<?php echo _route('viewer:show', null, ['file' => $catalog->document->full_url])?>"><span class="badge bg-info">Show</span></a>
                            </td>
                            <td><?php echo wLinkDefault('#','Remove')?> | <?php echo wLinkDefault('#', 'Change')?></td>
                        </tr>
                    </table>
                </div>
            </section>
            <?php echo $_form->end()?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>