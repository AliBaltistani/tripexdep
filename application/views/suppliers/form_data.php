<?php $this->load->helper("form"); ?>
<form role="form" id="addUser" action="<?php echo base_url() ?>Supplier/addNewSupplier" method="post" role="form">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fname">Full Name</label>
                    <input type="hidden" class="form-control required" value="<?= $bookingId ?? 0; ?>" id="bookingId" name="bookingId" >
                    <input type="hidden" class="form-control required" value="<?= $params ?>" id="bookingId" name="param" >
                    <input type="text" class="form-control required" value="<?php echo set_value('fname'); ?>" id="fname" name="fname" maxlength="128">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="text" class="form-control required email" id="email" value="<?php echo set_value('email'); ?>" name="email" maxlength="128">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" class="form-control required digits" id="mobile" value="<?php echo set_value('mobile'); ?>" name="mobile" maxlength="16">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control required" id="role" name="role">
                        <option value="0">Select Role</option>
                        <?php
                        if (!empty($roles)) {
                            foreach ($roles as $rl) {
                                $roleText = $rl->role;
                                $roleClass = false;
                                if ($rl->roleStatus == INACTIVE) {
                                    $roleText = $rl->role . ' (Inactive)';
                                    $roleClass = true;
                                }
                        ?>
                                <option value="<?php echo $rl->roleId ?>" 
                                <?php if ($roleClass) {
                                        echo "class=text-warning";
                                    }
                                    if ($rl->roleId == set_value('role')) {
                                        echo "selected=selected";
                                    } ?>><?= $roleText ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Create Password</label>
                    <input type="password" class="form-control required" id="password" name="password" maxlength="20">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" class="form-control required equalTo" id="cpassword" name="cpassword" maxlength="20">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="isAdmin" value="<?= SUPPLIER_USER ?>">
                </div>
            </div>
            <div class="col-md-6">
            </div>
        </div>
    </div><!-- /.box-body -->

    <div class="box-footer">
        <input type="submit" class="btn btn-primary" value="Submit" />
        <!-- <a href="" class="btn btn-default">Back</a> -->
    </div>
</form>
<script src="<?php echo base_url(); ?>assets/admin/js/addUser.js" type="text/javascript"></script>