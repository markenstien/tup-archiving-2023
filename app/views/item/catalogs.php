<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Catalogs</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Catagory</th>
                        <th>Genre</th>
                        <th>Authors</th>
                        <th>Publishers</th>
                        <th>year</th>
                        <th>Uploader</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($catalogs as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->title?></td>
                                <td><?php echo $row->category_name?></td>
                                <td><?php echo $row->genre?></td>
                                <td><?php echo $row->authors?></td>
                                <td><?php echo $row->publishers?></td>
                                <td><?php echo $row->year?></td>
                                <td><?php echo $row->uploader_name?></td>
                                <td>
                                    <a href="<?php echo _route('item:show', $row->id)?>">Show</a>
                                </td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>