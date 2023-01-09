<?php build('content') ?>
    <?php if(!isset($catalogs)) :?>
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <?php
                        Form::open([
                            'method' => 'get'
                        ]);
                    ?> 
                        <h4>Your Search Starts Here..</h4>
                        <div class="form-group"><?php Form::text('keyword', '', ['class' => 'form-control','autocomplete'=>'off'])?></div>
                        <div class="form-group text-center">
                            <?php Form::submit('btn_search','Search By Keyword', ['class' => 'btn btn-primary btn-lg',])?>
                            <div class="mt-5"><a href="#">Advanced Search</a></div>
                        </div>
                    <?php Form::close()?>
                </div>
            </div>
        </div>
    <?php else:?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Catalogs</h4>
                <?php
                        Form::open([
                            'method' => 'get'
                        ]);
                    ?>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group"><?php Form::text('keyword', '', ['class' => 'form-control','autocomplete'=>'off'])?></div>
                        </div>
                        <div class="col-md-3">
                            <?php Form::submit('btn_search','Search By Keyword', ['class' => 'btn btn-primary btn-sm',])?>
                        </div>
                    </div>
                <?php Form::close()?>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <label for="#">Search Result for <h5 style="display: inline;"> '<?php echo $_GET['keyword']?>'</h5> </label>
                        <div><small>Total Results : <?php echo count($catalogs)?></small></div>
                        <div class="row mt-4">
                            <?php foreach($catalogs as $catalog) :?>
                                <div class="col-md-3" style="border: 1px solid #000; padding:2px;border-radius:5px">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5>
                                                <a href="<?php echo _route('item:show', $catalog->id)?>" style="text-decoration:underline"><?php echo $catalog->title?></a>
                                            </h5>
                                            <small><?php echo $catalog->year?></small>

                                            <p><?php echo $catalog->brief?></p>

                                            <div><small>(<?php echo $catalog->genre?>)</small></div>
                                            <div><small><?php echo $catalog->tags?></small></div>
                                            <div><a href="#"><?php echo $catalog->authors?></a></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="#">Other Results Related to <h5 style="display: inline;"> '<?php echo $_GET['keyword']?>'</h5></label>
                    </div>
                </div>
            </div>
        </div>
    <?php endif?>
<?php endbuild()?>
<?php loadTo()?>