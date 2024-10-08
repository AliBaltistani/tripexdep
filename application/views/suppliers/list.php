<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Supplier Management
        <small>Add, Edit, Delete</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                <?php if((check_permission('Suppliers','create_records') == 1)){ ?>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>supplier-add"><i class="fa fa-plus"></i> New Supplier</a>
                    <?php } ?>
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
                    <h3 class="box-title">Supplier List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>supplier-listing" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover table-striped table-responsive ">
                    <thead >
                        <tr >
                            <td>Sr.#</td>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>Type</th>
                            <th>Created On</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <?php
                    if(!empty($userRecords))
                    {
                        $sr = 1;
                        foreach($userRecords as $record)
                        {
                    ?>
                    <tr>
                        <td width="5px"><?php echo $sr++ ?></td>
                        <td><?php echo $record->name ?></td>
                        <td><?php echo $record->email ?></td>
                        <td><?php echo $record->mobile ?></td>
                        <td><?php echo $record->role; if($record->roleStatus == INACTIVE) { echo ' <br><span class="label label-warning">Inactive</span>'; } ?></td>
						<td><?php if($record->isAdmin == SYSTEM_ADMIN) { echo 'System Administrator'; } 
                                  else if($record->isAdmin == REGULAR_USER) { echo 'Regular User'; } 
                                  else if($record->isAdmin == AGENT_USER) { echo 'Agent | Agencies'; }
                                  else if($record->isAdmin == SUPPLIER_USER) { echo 'Suppliers'; } ?></td>
                        <td><?php echo date("d-M-Y", strtotime($record->createdDtm)) ?></td>
                        <td class="text-center">
                            <?php if((check_permission('Suppliers','list') == 1)){ ?>
                            <a class="btn btn-sm btn-primary" href="<?= base_url().'supplier-bookings?id='.$record->userId; ?>" title="View Bookings"><i class="fa fa-eye"></i></a> | 
                            <?php } if(check_permission('Suppliers','edit_records') == 1){ ?>
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'supplier-edit/'.$record->userId; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                            <?php } if(check_permission('Suppliers','delete_records') == 1){ ?>
                            <a class="btn btn-sm btn-danger deletecommon" href="#" data-taskname="users" data-col="userId" data-taskid="<?php echo $record->userId; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                            <?php } ?>
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "supplier-listing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
