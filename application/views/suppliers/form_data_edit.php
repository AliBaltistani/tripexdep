<?php
$userId = $userInfo->userId ?? '';
$name = $userInfo->name ?? '';
$email = $userInfo->email ?? '';
$mobile = $userInfo->mobile ?? '';
$roleId = $userInfo->roleId ?? '';
$isAdmin = $userInfo->isAdmin ?? '';
?>

<form role="form" action="<?php echo base_url() ?>supplier-update" method="post" id="editUser" role="form">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fname">Full Name</label>
                    <input type="text" class="form-control" id="fname" placeholder="Full Name" name="fname" value="<?php echo $name; ?>" maxlength="128">
                    <input type="hidden" value="<?php echo $userId; ?>" name="userId" id="userId" />
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo $email; ?>" maxlength="128">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile" placeholder="Mobile Number" name="mobile" value="<?php echo $mobile; ?>" maxlength="16">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role">
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
                                <option value="<?php echo $rl->roleId; ?>" <?php if ($roleClass) {
                                                                                echo "class=text-warning";
                                                                            } ?> <?php if ($rl->roleId == $roleId) {
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
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="20">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" class="form-control" id="cpassword" placeholder="Confirm Password" name="cpassword" maxlength="20">
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

    <div class="box-footer d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">UPDATE</button> &nbsp;&nbsp;&nbsp;&nbsp;
        <!-- <a class="btn btn-default" href="<?= base_url('suppliers') ?>">Back</a> -->
        <a class="btn btn-sm btn-danger deletecommon" href="#" data-taskname="users" data-col="userId" data-taskid="<?php echo $userId; ?>" title="Delete"><i class="fa fa-trash"></i></a>
    </div>
</form>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/common.js" charset="utf-8"></script>