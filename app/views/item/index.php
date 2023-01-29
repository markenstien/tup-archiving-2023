<?php build('content') ?>
<?php Flash::show()?>
    <?php if(!isset($catalogs)) :?>
        <div class="col-md-5 mx-auto">
            <div class="card">
                <div class="card-body text-center">
                    <?php
                        Form::open([
                            'method' => 'get'
                        ]);
                    ?> 
                        <h4>Your Search Starts Here..</h4>
                        <div class="form-group"><?php Form::text('keyword', '', ['class' => 'form-control','autocomplete'=>'off' , 'required' => true, 'placeholder' => 'search by tags : #tagname'])?></div>
                        <div class="form-group text-center">
                            <?php Form::submit('btn_search','Search By Keyword', ['class' => 'btn btn-primary btn-lg',])?>
                        </div>
                    <?php Form::close()?>
                </div>

                <div class="card-footer">
                    <div class="mt-5">
                        <div class="text-center">
                            <a href="#">How to use Advance Search</a>
                        </div>

                        <ul>
                            <li>Search by tags : '<strong>#</strong>keyword'</li>
                            <li>
                                Advance Search  use '&;' 
                                <div>Example : Search by keyword and year</div>
                                <div>Thesis&;<strong>year</strong>=2020&;genre=tech</div>
                            </li>
                        </ul>
                    </div>
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
                            <div class="form-group"><?php Form::text('keyword', $_GET['keyword'] ?? '', ['class' => 'form-control','autocomplete'=>'off' , 'required' => true, 'placeholder' => 'search by tags : #tagname'])?></div>
                        </div>
                        <div class="col-md-3">
                            <?php Form::submit('btn_search','Search By Keyword', ['class' => 'btn btn-primary btn-sm',])?>
                        </div>
                    </div>
                <?php Form::close()?>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <label for="#">Search Result for <h5 style="display: inline;"> '<?php echo $_GET['keyword']?>'</h5> </label>
                        <div><small>Total Results : <?php echo count($catalogs)?></small></div>
                        <?php echo wDivider()?>

                        <div class="row">
                            <div class="col-md-7">
                                <?php if(empty($catalogs)) :?>
                                    <p class="text-danger">No Result found for '<?php echo $_GET['keyword']?>'</p>
                                    <?php echo wDivider('20')?>

                                    <?php if($possibleCatalogs) :?>
                                        
                                    <small>Other topics tha you might be intrested in.</small>
                                    <?php foreach($possibleCatalogs as $catalog) :?>
                                        <?php echo wCatalogToStringToLink($catalog->tags, _route('item:index'), 'tags')?>
                                    <?php endforeach?>
                                    <?php echo wDivider('20')?>

                                    <h5>You might be looking for..</h5>
                                        <?php foreach($possibleCatalogs as $catalog) :?>
                                            <div class="mt-3">
                                                <h5>
                                                    <a href="<?php echo _route('item:show', $catalog->id)?>" style="text-decoration:underline"><?php echo $catalog->title?></a>
                                                </h5>
                                                <p><?php echo crop_string($catalog->brief, 100)?></p>
                                                <div><small>(<?php echo $catalog->genre?>) <?php echo $catalog->year?></small></div>
                                                <div><a href="#"><?php echo $catalog->authors?></a></div>
                                                <div style="font-size: 8pt;"><?php echo !empty($catalog->view_total) ? 'Views : '.$catalog->view_total : ''?> <?php echo !empty($catalog->like_total) ? 'Likes : '.$catalog->like_total : ''?> </div>
                                            </div>
                                        <?php endforeach?>
                                    <?php endif?>
                                <?php else:?>
                                    <?php foreach($catalogs as $catalog) :?>
                                        <div class="mt-3">
                                            <h5>
                                                <a href="<?php echo _route('item:show', $catalog->id)?>" style="text-decoration:underline"><?php echo $catalog->title?></a>
                                            </h5>
                                            <p><?php echo crop_string($catalog->brief, 100)?></p>
                                            <div><small>(<?php echo $catalog->genre?>) <?php echo $catalog->year?></small></div>
                                            <div><a href="#"><?php echo $catalog->authors?></a></div>
                                            <div style="font-size: 8pt;"><?php echo !empty($catalog->view_total) ? 'Views : '.$catalog->view_total : ''?> <?php echo !empty($catalog->like_total) ? 'Likes : '.$catalog->like_total : ''?> </div>
                                        </div>
                                    <?php endforeach?>
                                <?php endif?>
                            </div>

                            <?php if(isset($otherResults) && !empty($otherResults)) :?>
                                <div class="col-md-5">
                                    <label for="#" class="mb-5">Other Results Related to <h5 style="display: inline;"> '<?php echo $_GET['keyword']?>'</h5></label>
                                    
                                    <?php foreach($otherResults as $key => $catalog) :?>
                                        <div style="font-size: 76%;">
                                            <h5>
                                                <a href="<?php echo _route('item:show', $catalog->id)?>" style="text-decoration:underline"><?php echo $catalog->title?></a>
                                            </h5>
                                            <p><?php echo crop_string($catalog->brief, 100)?></p>
                                            <div><small>(<?php echo $catalog->genre?>) <?php echo $catalog->year?></small></div>
                                            <div><a href="#"><?php echo $catalog->authors?></a></div>
                                            <div style="font-size: 8pt;"><?php echo !empty($catalog->view_total) ? 'Views : '.$catalog->view_total : ''?> <?php echo !empty($catalog->like_total) ? 'Likes : '.$catalog->like_total : ''?> </div>
                                        </div>
                                    <?php endforeach?>
                                </div>
                            <?php endif?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif?>
<?php endbuild()?>
<?php loadTo()?>