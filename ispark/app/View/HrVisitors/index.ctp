<?php ?>
<script>
function actionlist(path){
    if(confirm('Are you sure you want to delete this list?')){
            window.location.href=path;
    }
}

function Show_Branch(id){
    $(".Branch_Name").hide();
    $(".Branch_Label,.icon").show();
    $("#Branch_Label_"+id+",#icon_"+id).hide();
    $("#Branch_Name_"+id).show();   
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
                    <span>View Interview</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con form-horizontal">
                <span><?php echo $this->Session->flash(); ?></span>
                <div class="form-group">
                    <div class="col-sm-10 " id="trainerdata" >
                        
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Sr.No</th>
                                    <th>Branch</th>
                                    <th>Name</th>
                                    <th style="text-align: center;">Job Position</th>
                                    <th style="text-align: center;">Mobile No</th>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;foreach($data as $rowArr){$row=$rowArr['InterviewMaster'];?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $i++;?></td>
                                    <td style="text-align: center;">
                                        <?php echo $this->Form->create('HrVisitors',array('action'=>'index','class'=>'form-horizontal')); ?>
                                        <span class="Branch_Label" id="Branch_Label_<?php echo $row['Interview_Id']?>"><?php echo $row['BranchName']?></span>
                                        <span class="Branch_Name" id="Branch_Name_<?php echo $row['Interview_Id']?>" style="display: none;" >
                                            <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'value'=>$row['BranchName'],'empty'=>'Select','class'=>'form-control','id'=>'BranchName','required'=>true)); ?>
                                            <input type="hidden" name="Interview_Id" value="<?php echo $row['Interview_Id']?>" >
                                            <input type="submit" name="Submit" value="save" >
                                        </span>
                                        <span class="icon" id="icon_<?php echo $row['Interview_Id']?>" onclick="Show_Branch('<?php echo $row['Interview_Id']?>')" ><i class="material-icons" style="font-size:12px;cursor: pointer;margin-left: 10px;">mode_edit</i></span>
                                         <?php echo $this->Form->end(); ?>
                                    </td>
                                    <td ><a href="<?php echo $this->webroot.'HrVisitors/recruiter?url='.base64_encode($row['Interview_Id']);?>"><?php echo $row['Name']?></a></td>
                                    <td style="text-align: center;"><?php echo $row['Job_Position']?></td>
                                    <td style="text-align: center;"><?php echo $row['Mobile_No']?></td>
                                    <td style="text-align: center;"><?php echo $row['Create_Date']?></td>
                                    <td style="text-align: center;"> 
                                        <a href="<?php $this->webroot;?>HrVisitors/resendinterview?id=<?php echo $row['Interview_Id']?>&no=<?php echo $row['Mobile_No']?>" onclick="return confirm('Are you sure you want to sent again message?');"><span class='icon' ><i class="material-icons" style="font-size:20px;cursor: pointer;" >message</i></span></a>
                                        <a href="<?php $this->webroot;?>HrVisitors/deleteinterview?id=<?php echo base64_encode($row['Interview_Id'])?>" onclick="return confirm('Are you sure you want to delete this record?');"><span class='icon' ><i class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                       
                    </div>
                </div>
                <div class="form-group has-info has-feedback">
                    <div class="col-sm-10">
                        <input onclick='return window.location="<?php echo $this->webroot;?>Menus?AX=MTI2"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                    </div>
                </div>
                
            </div>
        </div>
    </div>	
</div>

<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Branch</h4>
            </div>
            <div class="modal-body" id="Update_Branch" >
        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-new pull-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
