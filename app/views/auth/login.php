<?php build('content') ?>
    <div class="container">
        <div class="card">
          <?php Form::open(['method' => 'post'])?>
            <div class="card-body">
              <?php Flash::show()?>
                <div class="text-center">
                    <div class="mb-5"><?php echo wLogo()?></div>
                    <h4>Register to TUP Archiving System</h4>
                </div>
                <?php echo wDivider(80)?>
                <div class="col-md-5 mx-auto">
                    <div class="form-group"><?php echo $form->getCol('email');?></div>
                    <div class="form-group"><?php echo $form->getCol('password');?></div>
                    <div class="form-group mt-3">
                        <?php echo Form::submit('', 'Login')?>
                    </div>

                    <?php echo wDivider('20')?>
                    <a href="#">Forgot password?</a> <?php echo wDivider('20')?>
                    <label for="#">Don't have Account? <a href="<?php echo _route('auth:register')?>">Register Here.</a></label>
                </div>
                <?php echo wDivider(80)?>
                <?php echo wLogo('wide')?>
            </div>

            <?php __( $form->end() )?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo('tmp/base')?>