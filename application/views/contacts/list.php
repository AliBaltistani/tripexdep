<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Contact Management
        <small>Add, Edit, Delete</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <!-- <a class="btn btn-primary" href="admin/contact-us/add"><i class="fa fa-plus"></i> Add New Price</a> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
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
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Contact List</h3>
                    <div class="box-tools">
                        
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                        <th>Sr.no</th>
                        <th>Subject</th>
                        <th>Email</th>
                        <th>message</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($records))
                    {
                        $sr = 1;
                        foreach($records as $record)
                        { 
                    ?>
                    <tr>
                        			status	isDeleted	createdBy	createdDtm	updatedBy	updatedDtm
                        <td width="5px"><?php echo $sr++ ?></td>
                        <td><?= $record->subject ?? 'unknown' ?></td>
                        <td><?php echo $record->email ?? 'unknown' ?> </td>
                        <td><?php echo $record->message ?? 'unknown' ?> </td>
                        <td><?php echo $record->email ?? 'unknown' ?> </td>
                        <td>
                            <?php 
                            if($record->status == ACTIVE) {
                                ?> <span class="label label-success">Active</span> <?php
                            } else {
                                ?> <span class="label label-warning">Inactive</span> <?php
                            }
                            ?>
                        </td>
                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                        <td class="text-center">
                            <!-- <a class="btn btn-sm btn-info" href="'admin/contact-us/edit/'.$record->id; " ><i class="fa fa-pencil"></i></a> -->
                            <a class="btn btn-sm btn-danger deletecommon" href="#" data-taskname="contacts" data-col="id" data-taskid="<?= $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                   
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/deleteRole.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "admin/contact-us/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
