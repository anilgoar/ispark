<script>
function get_user()
{
   var BranchId =  $('#branchId').val();
   $.post("get_user",
            {
             BranchId: BranchId
            },
            function(data,status){
                $("#UserId").empty();
                $("#UserId").html(data);
            }); 
}
</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>


<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Add New Imprest Manager</h4>
    <h4 style="color:green"><?php echo $this->Session->flash(); ?></h4>
    <?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal')); ?>
    <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Branch</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('BranchId',array('label' => false,'options'=>$branch_master,'class'=>'form-control','empty'=>'Select','id'=>'branchId','onChange'=>'get_user()','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
            </div>    
        </div>
        <label class="col-sm-2 control-label">User Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('UserId',array('label' => false,'class'=>'form-control','options'=>'','empty'=>'Select','id'=>'UserId','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>  
            </div>    
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Tally Name</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php echo $this->Form->input('TallyHead',array('label' => false,'class'=>'form-control','placeholder'=>'TallyHead','id'=>'UserId','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>  
            </div>    
        </div>
        <div class="col-sm-2">
            <button type='submit' class="btn btn-info" value="Save">Add</button>
        </div>
    </div>
    <div class="form-group has-info has-feedback">
        
        <label class="col-sm-2 control-label"></label>
        
    </div>
    <div class="form-group has-info has-feedback">
        
    </div> 
    <div class="clearfix"></div>
    
    <?php echo $this->Form->end(); ?>
</div>