<?php ?>
<script>
function validatdFieldMark(){  
    var MarkDate = $('#MarkDate').val();
    var all_location_id = document.querySelectorAll('input[name="check[]"]:checked');
    var all_location_id1 = document.querySelectorAll('input[name="checkHd[]"]:checked');
    
    if(MarkDate==''){
        alert("Please select date.");
        return false;
    }
    /*
    else if(checkEmp(all_location_id) =="" || checkEmp(all_location_id1) ==""){
        alert("Please check any mark field.");
        return false;
    }*/
    else{
      return true; 
    }   
}

function checkCharacter(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {  
            
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}

function checklength(data){
    var num=data.value;
   
    var lastday="<?php echo date('d', strtotime('last day of previous month')); ?>";
   
    if(parseInt(num) > parseInt(lastday)){
        $(data).val('');
    }
   
}



function goBack(){
    window.location="<?php echo $this->webroot;?>OnSiteAttendances";  
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
<div class="box">
<div class="box-header"  >
                <div class="box-name">
                    <span>OnSite Attendance</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
<div class="box-content box-con" >
    
        
    
    <?php echo $this->Form->create('OnSiteAttendances',array('class'=>'form-horizontal','action'=>'markfield')); ?>
    
    <input type="hidden" name="CostCenter" value="<?php echo $empArr['CostCenter'];?>" >
    <input type="hidden" name="BranchName" value="<?php echo $empArr['BranchName'];?>" >
      
    <div class="form-group">
        <div class="col-sm-2">Branch</div>
        <div class="col-sm-1">-</div>
        <div class="col-sm-2"><?php echo $empArr['BranchName'];?></div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-2">Cost Center</div>
        <div class="col-sm-1">-</div>
       <div class="col-sm-2"><?php echo $empArr['CostCenter'];?></div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-2">Employee Location</div>
        <div class="col-sm-1">-</div>
        <div class="col-sm-2"><?php echo $empArr['EmpLoc'];?></div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-2">No of Employees</div>
        <div class="col-sm-1">-</div>
        <div class="col-sm-2"><?php echo $empArr['TotalEmp'];?></div>
    </div>

    <div class="form-group">
        <div class="col-sm-10">
            <span><?php echo $this->Session->flash(); ?></span>
        </div>
    </div>
    
    <div class="row" style="margin-top:-20px;" >
        <div class="col-sm-7">
            <table class = "table table-striped table-hover  responstable"  >         
                <thead>
                    <tr>
                        <th style="text-align: center;width:30px;" >SNo</th>
                        <th style="text-align: center;width:80px;">Emp Code</th>
                        <th>Employees Name</th>
                         <th style="text-align: center;width:80px;">Max Sal Days</th>
                          <th style="text-align: center;width:80px;">Sal Month</th>
                         <th style="text-align: center;width:80px;">Sal Days</th>
                        
                    </tr>
                </thead>
                <tbody>         
                    <?php $i=1; foreach ($fieldArr as $val){?>
                    <tr>
                        <td style="text-align: center;" ><?php echo $i++;?></td>
                        <td style="text-align: center;"><?php echo $val['EmpCode'];?></td>
                        <td><?php echo $val['EmpName'];?></td>
                        <td style="text-align: center;"><?php echo date('d', strtotime('last day of previous month')); ?></td>
                        <td style="text-align: center;"><?php echo date('M - Y', strtotime('last month'));?></td>
                        <td style="text-align: center;"><center><input type="text" name="<?php echo $val['EmpCode'];?>" maxlength="4" onkeypress="return checkCharacter(event,this)" onkeyup="checklength(this);" autocomplete="off"  style="width:50px;" value="<?php echo isset($val['SalDays'])?$val['SalDays']:'';?>"  required="" class="form-control" ></center></td>
                    </tr>
                    
                    <input type="hidden" name="emcodeid[]" value="<?php echo $val['EmpCode'];?>" >
                    <input type="hidden" name="<?php echo $val['EmpCode'];?>_name" value="<?php echo $val['EmpName'];?>" >
                   
                        <?php }?>
                </tbody>           
            </table>
        </div>
    </div>
   
    <div class="row" style="margin-top:15px;" >
        <div class="col-sm-7">
           
           <input style="margin-left:10px;" type='Button' onclick="goBack();" class="btn btn-info pull-right btn-new" value="Back">
          <input type='submit' class="btn btn-info pull-right btn-new" value="Submit">
        </div>
        
    </div>
        
    <?php echo $this->Form->end(); ?>
</div>
</div>







