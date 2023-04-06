<?php ?>
<script>
function salaryDetails(id){
    $.post("<?php echo $this->webroot;?>SalaryBreakups/salaryedit",{id:id},function(data){
        $('#salarydetails').html(data);
    });
}

function submitForm(form,path){
    var Band=$("#Band").val()
    var CTC=$("#CTC").val()
    var pos= $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>SalaryBreakups/matchbandctc",async: false,dataType: 'json',data: {Band:Band,CTC:CTC},done: function(response) {return response;}}).responseText;	
   
    var formData = $(form).serialize(); 
    if(confirm("Are you sure you want to update?")){
        if(pos !=""){
            $.post(path,formData).done(function(data){
                if(data !=""){
                    $("#msg").html('<span style="color:green;font-weight:bold;" >Employee salary update sucessfully.</span>');
                }
                else{
                    $("#msg").html('<span style="color:red;font-weight:bold;" >Employee salary not update please try again later.</span>');  
                } 
            });
            return true;
        
        }
        else{
            alert('Offered CTC can not be more then maximum slab of selected band.');
            return false;
        }
        
    }
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
                    <span>SALARY STRUCTURE </span>
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
                <?php echo $this->Form->create('SalaryBreakups',array('action'=>'index','class'=>'form-horizontal')); ?>
                <div class="form-group">  
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('branch_name',array('label' => false,'options'=>$branchName,'empty'=>'Select','class'=>'form-control','id'=>'BranchName','onchange'=>'getBranch(this.value)','required'=>true)); ?>
                    </div>
                    
                    <label class="col-sm-1 control-label">SearchType</label>
                    <div class="col-sm-2">
                        <select id="SearchType" name="SearchType" autocomplete="off" class="form-control" required="" >
                            <option value="">Select</option>
                            <option value="EmpName">Name</option>
                            <option value="EmpCode">Employee Code</option>
                            <option value="BioCode">Biometric Code</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-2">
                        <input type="text" id="SearchValue" name="SearchValue" autocomplete="off" placeholder="Search" class="form-control"  required="" >
                    </div>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type="submit"  value="Search" class="btn pull-right btn-primary btn-new">
                    </div>
                </div>
               
                <div class="form-group" style="position: relative;top:-25px;" >
                    <div class="col-sm-12">
                        <?php if(!empty($data)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th style="width:30px;text-align: center;">SNo</th>
                                    <th style="width:60px;text-align: center;">EmpCode</th>
                                    <th>EmpName</th>
                                    <th>FatherName</th>
                                    <th style="width:80px;text-align: center;">DOJ</th>
                                    <th style="width:80px;text-align: center;">DOB</th>
                                    <th style="text-align: center;" >Department</th>
                                    <th style="text-align: center;" >Process</th>
                                    <th style="width:30px;text-align: center;" >Status</th>
                                    <th style="width:30px;text-align: center;" >Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php
                                $n=1; foreach ($data as $val){
                                    $EJEID = base64_encode($val['Masjclrentry']['id']);
                                ?>
                                <tr>
                                    <td ><?php echo $n++;?></td>
                                    <td style="text-align: center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                                    <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                                    <td><?php echo $val['Masjclrentry']['Father'];?></td>
                                    <td style="text-align: center;"><?php echo date('d M Y',strtotime($val['Masjclrentry']['DOJ']));?></td>
                                    <td style="text-align: center;"><?php echo date('d M Y',strtotime($val['Masjclrentry']['DOB']));?></td>
                                    <td style="text-align: center;"><?php echo $val['Masjclrentry']['Dept'];?></td>
                                    <td style="text-align: center;"><?php echo $val['Masjclrentry']['CostCenter'];?></td>
                                    <?php 
                                    if($val['Masjclrentry']['Status'] =="1"){echo "<td style='color:green;text-align: center;'>Active</td>";}else{echo "<td style='color:red;text-align: center;'>Left</td>";}
                                    ?>
                                    <td style="text-align: center;" >
                                        <?php if($val['Masjclrentry']['Status'] =="1"){ ?>
                                        <i style="cursor: pointer;" title="edit" onclick="salaryDetails('<?php echo $val['Masjclrentry']['id'];?>');" class="material-icons">border_color</i>
                                        <?php }else{?>
                                            <i style="cursor: pointer;" title="edit" class="material-icons">border_color</i>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php }?>
                    </div>
                </div>
                
                
                <?php echo $this->Form->end(); ?>
                
                
                <div id="salarydetails" style="position: relative;top:-40px;" ></div>
                
                
            </div>
            
        </div>
    </div>	
</div>



