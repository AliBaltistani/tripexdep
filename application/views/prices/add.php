<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle-o" aria-hidden="true"></i> Prices Management
            <small>Add / Edit Role</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Prices Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addRole" action="" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Role</label>
                                        <select class="form-control required" id="status" name="role_id">
                                            <option value="">Select Role</option>
                                            <?php if (!empty($roles)) {
                                                foreach ($roles as $role) {
                                            ?>
                                                    <option value="<?= $role->roleId ?>"><?= $role->role ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_amount">Chrages (% or AED)</label>
                                        <input type="number" class="form-control required" placeholder="Enter Discount Amount (% or AED)" value="" id="discount_amount" name="discount_amount" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control required" id="status" name="status">
                                            <option value="">Select Status</option>
                                            <option value="<?= ACTIVE ?>">Active</option>
                                            <option value="<?= INACTIVE ?>">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status">Charges Type</label>
                                    <div class="form-group">
                                        <input type="radio" class=" required" name="discount_type" id="c_type" value="percentage" checked>
                                        <label for="tax_type1" > Count as Percentage</label> 
                                        &nbsp; &nbsp; &nbsp;
                                        <input type="radio" class="required" name="discount_type" id="c_type" value="fixed">
                                        <label for="tax_type2" >Count as AED</label>
                                   </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <a class="btn btn-default" href="<?= base_url('roles/roleListing') ?>">Back</a>

                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) {
                ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<script src="<?php echo base_url(); ?>assets/admin/js/addRole.js" type="text/javascript"></script>