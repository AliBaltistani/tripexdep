<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> <?= $clabel ?> Management
        <small>Add New <?= $clabel ?></small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter <?= $clabel ?> Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <?php echo form_open_multipart('packages/add_new'.$parms); ?>
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="categoryTitle"><?= $clabel ?> Title</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('categoryTitle'); ?>" id="categoryTitle" name="categoryTitle" maxlength="256" placeholder="Enter <?= $clabel.' title'; ?>" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control required" id="description" name="description" placeholder="write description here... " ><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="maincatid">Select Main Category</label>
                                        <select class="form-control required" id="maincatid" name="maincatid" required>
                                            <option value="">Select Main Category</option>
                                            <?php foreach($categories as $categoty){

                                                $cText = $categoty->categoryName;
                                                $cClass = false;
                                                if ($categoty->isPublished == INACTIVE) {
                                                    $cText = $categoty->categoryName . ' (Inactive)';
                                                    $cClass = true;
                                                }
                                                ?>
                                                <option data-label="<?= $categoty->categoryLabel; ?>" value="<?= $categoty->categoryId ?>" <?php if ($cClass) { echo "class=text-warning"; } if($categoty->categoryId == set_value('categoryId') || $categoty->categoryId == $cid) {echo "selected=selected";} ?>><?= $cText ?></option>
                                                <?php
                                            }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="role">Status</label>
                                        <select class="form-control required" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="<?= ACTIVE ?>" >Publish</option>
                                            <option value="<?= INACTIVE ?>" >Unpublish</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 toggle">                                
                                    <div class="form-group">
                                        <label for="taskTitle">Passengers</label>
                                        <input type="text" class="form-control required" value="<?= (set_value('passengers'))?set_value('passengers'): '0'; ?>" id="passengers" name="passengers" maxlength="256" placeholder="i.e 3 " />
                                    </div>
                                </div>

                                <div class="col-md-6 toggle">                                
                                    <div class="form-group">
                                        <label for="taskTitle">Luggage</label>
                                        <input type="number" class="form-control required" value="<?= (set_value('luggage'))?set_value('luggage'): '0'; ?>" id="luggage" name="luggage" maxlength="256" placeholder="i.e 20 " />
                                    </div>
                                </div>

                                <div class="col-md-6 toggle">                                
                                    <div class="form-group">
                                        <label for="taskTitle">Baby seats</label>
                                        <br>
                                       <input type="radio" name="baby_seats" id="baby_seats_yes" value="1" checked> <span for="baby_seats_yes">Yes</span>
                                       &nbsp;&nbsp;&nbsp;
                                       <input type="radio" name="baby_seats" id="baby_seats_no" value="0" > <span for="baby_seats_no">No</span>
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="taskTitle"><?= $clabel ?> Tubmnail</label>
                                        <input type="file" name="subcategoryImage" size="1" required />
                                    </div>
                                </div>

                                
                                
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                        <?php echo form_close(); ?>
                </div>
            </div>
            <div class="col-md-4">
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
    </section>
    
</div>

<script src="<?= base_url()?>assets/admin/js/common.js"></script>
<script>

$(document).ready(function() {
    var cLabel = "<?= strtolower($clabel) ?>";
if(cLabel == "transportation"){
    $('.toggle').show('slow');
}else{
    $('.toggle').hide('slow');
}

  $('#maincatid').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var dataName = selectedOption.data('label'); // Preferred method

    if(dataName == "transportation"){
        $('.toggle').show('slow');
    }else{
        $('.toggle').hide('slow');
    }
    
  });
});
</script>