<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Catalog</h4>
            <?php echo wLinkDefault(_route('item:index'), 'Catalogs')?>
        </div>

        <div class="card-body">
            <?php Flash::show()?>
            <?php echo $_form->start()?>
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

                    <div class="form-group mt-5">
                        <div class="row">
                            <div class="col-md-6"><?php echo $_form->getCol('pdf_file')?></div>
                            <div class="col-md-6"><?php echo $_form->getCol('wallpaper')?></div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <?php Form::submit('', 'Create Catalog')?>
                    </div>
                </div>
            </div>
                
            <?php echo $_form->end()?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>