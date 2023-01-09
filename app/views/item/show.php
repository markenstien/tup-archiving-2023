<?php build('content') ?>
<?php Flash::show()?>
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo $catalog->title?></h4>
                    <span class="badge bg-danger">Popular</span>
                    <?php echo wLinkDefault(_route('item:edit', $catalog->id),'Edit Catalog')?>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <?php if($catalog->wallpaper) :?>
                            <img src="<?php echo $catalog->wallpaper->full_url?>" 
                                class="wd-100 wd-sm-200 me-3" alt="..." style="width: 200px;">
                        <?php else:?>
                            <img src="https://www.thesecret.tv/wp-content/uploads/2015/05/Daily-Teaching-Book.png" 
                                class="wd-100 wd-sm-200 me-3" alt="..." style="width: 200px;">
                        <?php endif?>
                        <div>
                            <h5 class="mb-2">Sypnosis</h5>
                            <p><?php echo $catalog->brief?></p>

                            <div>
                                <ul class="list-unstyled">
                                    <li>Year : <?php echo $catalog->year?></li>
                                    <li>Genre : <?php echo $catalog->genre?></li>
                                    <li>Tags : <?php echo $catalog->tags?></li>
                                    <li class="mt-3"></li>
                                    <li>Authors : 
                                        <?php
                                            foreach($catalogItems['authors'] as $key => $author) {
                                                if($key > 0) {
                                                    echo " , ";
                                                }
                                                echo "<a href='#'>{$author}</a>";
                                            }
                                        ?>
                                    </li>
                                    <li>Publishers :
                                        <?php
                                            foreach($catalogItems['publishers'] as $key => $publisher) {
                                                if($key > 0) {
                                                    echo " , ";
                                                }
                                                echo "<a href='#'>{$publisher}</a>";
                                            }
                                        ?>
                                    </li>
                                    <li>Uploader : <a href="#"><?php echo $catalog->uploader_name?></a></li>
                                    <li class="mt-3">
                                        <?php echo wLinkDefault(_route('item:read', $catalog->id), 'Read ('.$catalog->read_total.')')?> | 
                                        <?php echo wLinkDefault(_route('item:like', $catalog->id), 'Like ('.$catalog->like_total.')')?> | 
                                        Views : <?php echo $catalog->view_total?>
                                    </li>
                                </ul>
                                <?php Flash::show('item-like')?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5>Description</h5>
                        <?php echo $catalog->description?>
                    </div>

                    <div class="mt-5">
                        <h5>Catalogs Uploaded By same user</h5>
                        <ul class="list-group">
                            <?php foreach($userUploads as $key => $row) :?>
                                <li class="list-group-item">
                                    <a href="<?php echo _route('item:show', $row->id)?>"><?php echo $row->title?></a>
                                    <div><strong><small><?php echo $row->genre?></small></strong> <small><?php echo $row->tags?></small></div>
                                    <small><?php echo $row->authors?></small>
                                </li>
                            <?php endforeach?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Comments</h4>
                </div>
                <div class="card-body">
                    <?php echo $_formComment->getForm('col');?>
                    <?php echo wDivider('30')?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($comments as $key => $comment) :?>
                            <li class="list-group-item">
                                <div><strong><small><?php echo $comment->commentor_name?></small></strong></div>
                                <?php echo $comment->comment?>
                                <div><a href="<?php echo _route('item-comment:like', $comment->comment_id)?>"><small>Like <?php echo $comment->total_like?> </small></a> | <a href="#"><small>Comment</small></a> Posted On : <?php echo time_since($comment->posted_date) ?></div>
                            </li>
                        <?php endforeach?>
                    </ul>
                    <?php Flash::show('comment-like')?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Related Catalogs</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach($relatedCatalogs as $key => $row) :?>
                            <li class="list-group-item">
                                <a href="#"><?php echo $row->title?></a>
                                <div><strong><small><?php echo $row->genre?></small></strong> <small><?php echo $row->tags?></small></div>
                                <small><?php echo $row->authors?></small>
                            </li>
                        <?php endforeach?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>