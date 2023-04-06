<?php ?>
<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});

function validatdFieldMark(){  
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var MarkDate = $('#MarkDate').val();
    
    var posts =checkDate1(MarkDate);
   
    if(MarkDate==''){
        $("#MarkDate").addClass('bordered');
        $("#MarkDate").after("<span id='msgerr' style='color:red;font-size:11px;'>Please select correct date</span>");
        return false;
    }/*
    else if(posts !=''){
        $("#MarkDate").addClass('bordered');
        $("#MarkDate").after("<span id='msgerr' style='color:red;font-size:11px;'>"+ posts +"</span>");
        return false;
    }*/
    else{
        return true; 
    }   
}

function reload(url){
    window.location.href = url;
}

function goBack(){
    window.location="<?php echo $this->webroot;?>MarkFieldAttendances";  
}

/*
function checkHd(chkall,chkhd){
    var ChkAl = $('#'+chkall).is(':checked');
    var ChkHd = $('#'+chkhd).is(':checked');
     
    if(ChkAl ==ChkHd){
        alert('Please select any one option.');
        $('input[type="checkbox"]').removeAttr('checked');
        return false;
    }
}
*/


function checkDate1(FromDate){
    var CostCenter=$("#CostCenter").val();
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>MarkFieldAttendances/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,CostCenter:CostCenter},done: function(response) {return response;}}).responseText;	
    return posts;  
}

function checkDate(FromDate){
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered');
    var CostCenter=$("#CostCenter").val();
    
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>MarkFieldAttendances/check_date",async: false,dataType: 'json',data: {FromDate:FromDate,CostCenter:CostCenter},done: function(response) {return response;}}).responseText;	
    
    if(posts !=""){
        $("#MarkDate").addClass('bordered');
        $("#MarkDate").after("<span id='msgerr' style='color:red;font-size:11px;'>"+ posts +"</span>");
        return false;
    }
    else{
        return true;
    }
}

</script>
<style>
.bordered{
    border-color: red;
}
</style>
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
                    <span>Mark Field Attendance</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con"> 
                <?php echo $this->Form->create('MarkFieldAttendances',array('class'=>'form-horizontal','action'=>'markfield')); ?>
                    <input type="hidden" name="CostCenter" value="<?php echo isset($empArr['cost_center'])?$empArr['cost_center']:'' ?>" >
                    <div class="form-group">
                        <div class="col-sm-2">Search</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="SearchData" autocomplete="off" placeholder="Emp Code / Name" required="" >
                        </div>
                        <div class="col-sm-1">
                            <input type='submit' value="Search" class="btn btn-info pull-right btn-new" style="position: relative;top:-8px;"  >
                        </div>
                        <div class="col-sm-1">
                            <input type='button' style="position: relative;top:-8px;" onclick="reload('<?php $this->webroot;?>markfield?CSN=<?php echo base64_encode(isset($empArr['cost_center'])?$empArr['cost_center']:'');?>')" value="Reload" class="btn btn-info  btn-new" >
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
                
                <div style="position: relative;top:-28px;">
                <?php echo $this->Form->create('MarkFieldAttendances',array('class'=>'form-horizontal','action'=>'savefieldmark','onsubmit'=>'return validatdFieldMark()')); ?>
                    <input type="hidden" id="CostCenter" name="CostCenter" value="<?php echo isset($empArr['cost_center'])?$empArr['cost_center']:'' ?>" >
                    <input type="hidden" name="EmpCode" value="<?php echo isset($empArr['EmpCode'])?$empArr['EmpCode']:'' ?>" >
                    <input type="hidden" name="EmpName" value="<?php echo isset($empArr['EmpName'])?$empArr['EmpName']:'' ?>" >
                    <input type="hidden" name="BranchName" value="<?php echo isset($empArr['BranchName'])?$empArr['BranchName']:'' ?>" >
          
                    <div class="form-group" >
                        <div class="col-sm-2">Branch</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><?php echo $this->Session->read('branch_name');?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Cost Center</div>
                        <div class="col-sm-1">-</div>
                       <div class="col-sm-2"><?php echo $empArr['cost_center'];?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Count</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"><?php echo $empArr['TotalEmp'];?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">Date</div>
                        <div class="col-sm-1">-</div>
                        <div class="col-sm-2"> 
                            <!-- onchange="checkDate(this.value);" -->
                            <select  class="form-control"  name="MarkDate" id="MarkDate" required="">
                               <?php foreach($dateArr as $dt){?>
                               <option value="<?php echo $dt;?>"><?php echo date('d-M-Y',strtotime($dt));?></option>
                               <?php }?>
                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <span><?php echo $this->Session->flash(); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-8">
                    <table class = "table table-striped table-hover  responstable" >         
                        <thead>
                            <tr>
                                <th style="text-align:center;width:60px;">SNo</th>
                                <th style="text-align:center;width:60px;"><input type="checkbox" checked id="select_all"/></th>
                                <th style="text-align:center;width:100px;">Mark HD</th>
                                <th style="text-align:center;width:100px;">Emp Code</th>
                                <th>Employees Name</th>
                            </tr>
                        </thead>
                        <tbody>         
                            <?php $i=1; foreach ($fieldArr as $val){?>
                            <tr>
                                <td><?php echo $i++;?></td>

                                <td><center><input class="checkbox" id="chkall_<?php echo $val['Masjclrentry']['Id'];?>" checked type="checkbox" value="<?php echo $val['Masjclrentry']['EmpCode'];?>" name="check[]"></center></td>
                    <td><center><input class="checkHd" id="chkhd_<?php echo $val['Masjclrentry']['Id'];?>"  onclick="checkHd('chkall_<?php echo $val['Masjclrentry']['Id'];?>','chkhd_<?php echo $val['Masjclrentry']['Id'];?>');" onch type="checkbox" value="<?php echo $val['Masjclrentry']['EmpCode'];?>" name="checkHd[]"></center></td>
                        <td style="text-align:center;"><?php echo $val['Masjclrentry']['EmpCode'];?></td>
                                <td><?php echo $val['Masjclrentry']['EmpName'];?></td>
                            </tr>
                            <?php }?>
                        </tbody>           
                    </table>
                   
                            </div>
                        </div>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <input style="margin-left:10px;" type='button' onclick="goBack()" class="btn btn-info pull-right  btn-new" value="Back">
                            <?php if($_REQUEST['STP'] !='search'){?>
                                <input  type='submit' class="btn btn-primary pull-right btn-new" value="Submit">
                            <?php }?>
                        </div>
                    </div>
        
                <?php echo $this->Form->end(); ?> 
                  
                </div>
            </div>
        </div>
    </div>	
</div>




























