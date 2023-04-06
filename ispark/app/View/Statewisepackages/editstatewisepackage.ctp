<?php ?>
<script>


$( document ).ready(function() {
   $.post("<?php echo $this->webroot;?>Statewisepackages/get_state_wise_package_type_list",{StateName:"<?php echo $statename;?>",StateValue:"<?php echo $statetype;?>"},function(data){
		$('#CostCenter').html(data);
		$('#CostCenter option:first').after($('<option />', { "value": 'ALL', text: 'ALL'}));
    });
});


function getBranch(StateName){
    $("#CostCenter").val('');
	
	$.post("<?php echo $this->webroot;?>Statewisepackages/get_state_wise_package_type_list",{StateName:StateName,StateValue:''},function(data){
		$('#CostCenter').html(data);
		$('#CostCenter option:first').after($('<option />', { "value": 'ALL', text: 'ALL'}));
    });

}

</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left"></ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>VIEW STATE WISE PACKAGE MASTER</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('Statewisepackages',array('action'=>'editstatewisepackage','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">State</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'value'=>$statename,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">Package&nbsp;Type </label>
                    <div class="col-sm-3">
                        <?php 
						echo $this->Form->input('PackageType',array('label' => false,'options'=>'','empty'=>'Select','class'=>'form-control','id'=>'CostCenter','required'=>true)); 
						?>
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=OA=="' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
				
               
                <div class="form-group">
                    <div class="col-sm-8">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>SNo</th>
                                    <th style='text-align:center;'>State</th>
                                    <th style='text-align:center;'>Package Type</th>
                                    <th style='text-align:center;'>Package Amount</th>  
									<th style='text-align:center;'>Create Date</th> 
									<th style='text-align:center;'>Action</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; foreach ($data as $val){
                                ?>
                                <tr>
                                    <td style='text-align:center;'><?php echo $n++;?></td>
                                    <td style='text-align:center;'><?php echo $val['maspackagestatewise']['StateName'];?></td>
                                    <td style='text-align:center;'><?php echo $val['maspackagestatewise']['PackageType'];?></td>
									<td style='text-align:center;'><?php echo $val['maspackagestatewise']['PackageAmount'];?></td>
                                    <td style='text-align:center;'><?php echo date('d M Y',strtotime($val['maspackagestatewise']['CreateDate']));?></td>
									<td>
                                        <a href="viewpackage?id=<?php echo $val['maspackagestatewise']['id'];?>"><span class="icon"><i class="material-icons" style="font-size:20px;">mode_edit</i></span></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php }?>
                    </div>
                </div>
                
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>




