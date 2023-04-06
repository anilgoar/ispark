<?php ?>
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $(".datepickers").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function showdiv(id){
    $("#"+id).toggle();
}

function validate(tab){
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    $("#msgerr").remove();
    $(".bordered").removeClass('bordered'); 
    
    var EmpType="<?php echo $data['Masjclrentry']['EmpType'];?>";
    var Desgination="<?php echo $data['Masjclrentry']['Desgination'];?>";
    var FnfDoc="<?php echo $data['Masjclrentry']['FnfDoc'];?>";
    var type=$("#type").val();
    var styp=$("#styp").val();
    var pageno=$("#pageno").val();
    var file=$("#file").val();
    var OfferNo=$("#OfferNo").val();
    var mendatorydoc=$("#mendatorydoc").val();
    
    var ReleasingChequeDate=$("#ReleasingChequeDate").val();
    var ChequeAmount=$("#ChequeAmount").val();
    var ChequeDate=$("#ChequeDate").val();
    var ChequeNo=$("#ChequeNo").val();
    var CancelledChequeImage=$("#CancelledChequeImage").val();
    var ReasonofLeaving=$("#ReasonofLeaving").val();
    
    var av=checkEmpDoc1(OfferNo,EmpType,Desgination,type,pageno);
    
    //alert(av);return false;
   
    if(mendatorydoc > 0){
        if(type ===""){
            $("#type").addClass('bordered'); 
            $("#type").after("<span id='msgerr' class='msger'>Please upload all mendatory document.</span>");
            return false;
        }
        else if(styp ===""){
            $("#styp").addClass('bordered'); 
            $("#styp").after("<span id='msgerr' class='msger'>Please select doc name.</span>");
            return false;
        }
        else if(file ===""){
            $("#file").addClass('bordered'); 
            $("#file").after("<span id='msgerr' class='msger'>Please upload file.</span>");
            return false;
        }
        else if(checkEmpDoc1(OfferNo,EmpType,Desgination,type,pageno) !=""){
            $("#type").after("<span id='msgerr' class='msger'>This page document already uploaded.</span>");
            return false;
        }
        $("#JCLRFORM").submit();
    }
    else if(ReleasingChequeDate ===""){
        $("#ReleasingChequeDate").addClass('bordered'); 
        $("#ReleasingChequeDate").after("<span id='msgerr' class='msger'>Please enter releasing cheque date.</span>");
        return false;
    }
    else if(ChequeAmount ===""){
        $("#ChequeAmount").addClass('bordered'); 
        $("#ChequeAmount").after("<span id='msgerr' class='msger'>Please enter cheque amount.</span>");
        return false;
    }
    else if(ChequeDate ===""){
        $("#ChequeDate").addClass('bordered'); 
        $("#ChequeDate").after("<span id='msgerr' class='msger'>Please enter cheque date.</span>");
        return false;
    }
    else if(ChequeNo ===""){
        $("#ChequeNo").addClass('bordered'); 
        $("#ChequeNo").after("<span id='msgerr' class='msger'>Please enter cheque no.</span>");
        return false;
    }
    else if(CancelledChequeImage ==="" && FnfDoc ===""){
        $("#CancelledChequeImage").addClass('bordered'); 
        $("#CancelledChequeImage").after("<span id='msgerr' class='msger'>Please upload cheque.</span>");
        return false;
    }
    else if(ReasonofLeaving ===""){
        $("#ReasonofLeaving").addClass('bordered'); 
        $("#ReasonofLeaving").after("<span id='msgerr' class='msger'>Please enter leaving reason.</span>");
        return false;
    }
    else if(tab =="tab4"){
        $("#TabName").val('tab4');
        $("#JCLRFORM").submit();
    }
    else{
        $("#JCLRFORM").submit();
    }
}


function checkEmpDoc1(OfferNo,EmpType,Desgination,type,pageno){ 
    var posts = $.ajax({type: 'POST',url:"<?php echo $this->webroot;?>ProcessNocs/checkdoc1",async: false,dataType: 'json',data: {OfferNo:OfferNo,EmpType:EmpType,Desgination:Desgination,type:type,pageno:pageno},done: function(response) {return response;}}).responseText;	
    return posts;
}

function checkPhoto(target) { 
    if(target.files[0].type.indexOf("image") == -1) {
        document.getElementById("file").value = "";
        alert('File not supported');
        return false;
    }
    if(target.files[0].size > 230400) {
        alert('Maximum size of document to upload is 225 kb.');
        document.getElementById("file").value = "";
        return false;
    }
    return true;
}
</script>
<style>
.req{
    color:red;
    font-weight: bold;
    font-size: 16px;
}
.msger{
    color:red;
    font-size:11px;
}
.bordered{
    border-color: red;
}
.col-sm-2{margin-top:-12px !important;}
.col-sm-3{margin-top:-12px !important;}

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


<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>EMPLOYEE DETAILS</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            
            <div class="box-content box-con" >
                
                <?php echo $this->Form->create('ProcessNocs',array('action'=>'viewdetails','class'=>'form-horizontal','id'=>'JCLRFORM','enctype'=>'multipart/form-data')); ?>
                <input type="hidden" name="MasJclrsId" id="MasJclrsId" value="<?php echo $data['Masjclrentry']['id'];?>" >
                <input type="hidden" name="OfferNo" id="OfferNo" value="<?php echo $data['Masjclrentry']['OfferNo'];?>" >
                <input type="hidden" name="TabName" id="TabName">
                
                
                <div class="form-group" >
                  
                    <div class="col-sm-12">
                        <table class = "table table-striped table-hover  responstable" style="margin-top:-5px;"  >     
                            <thead>
                                <tr>
                                    <th colspan="8" style="text-align: left;" >Employee Details 
                                        <?php 
                                        if($data['Masjclrentry']['Status'] ==1){
                                            echo "(Status : Active)"; 
                                        }
                                        else{
                                            echo "(Status : Left [".date('d M Y',strtotime($data['Masjclrentry']['ResignationDate']))."])"; 
                                        }
                                        ?> 
                                    </th>
                                </tr>
                            </thead>
                            <tbody>         
                                <tr>
                                    <td style="text-align: left;"><strong>Employee Code</strong></td> <td><?php echo $data['Masjclrentry']['EmpCode'];?></td>
                                    <td><strong>Employee Name</strong></td> <td><?php echo $data['Masjclrentry']['EmpName'];?></td>
                                    <td><strong>Type</strong></td> <td><?php echo $data['Masjclrentry']['EmpType'];?></td>
                                    <td><strong>Father's Name</strong></td> <td><?php echo $data['Masjclrentry']['Father'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Gender</strong></td> <td><?php echo $data['Masjclrentry']['Gendar'];?></td>
                                    <td><strong>Date of Birth </strong></td> <td><?php echo date('d/M/Y',strtotime($data['Masjclrentry']['DOB']));?> <br/>(<?php echo $data['Masjclrentry']['Age'];?>)</td>
                                    <td><strong>Date of Join</strong></td> <td><?php echo date('d/M/Y',strtotime($data['Masjclrentry']['DOJ']));?></td>
                                    <td><strong>Qualification</strong></td> <td><?php echo $data['Masjclrentry']['Qualification'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Marrital Status</strong></td> <td><?php echo $data['Masjclrentry']['MaritalStatus'];?></td>
                                    <td><strong>Blood Group</strong></td> <td><?php echo $data['Masjclrentry']['BloodGruop'];?></td>
                                    <td><strong>Email ID</strong></td> <td><?php echo $data['Masjclrentry']['EmailId'];?></td>
                                    <td><strong>Designation</strong></td> <td><?php echo $data['Masjclrentry']['Desgination'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Department</strong></td> <td><?php echo $data['Masjclrentry']['Dept'];?></td>
                                    <td><strong>Stream</strong></td> <td><?php echo $data['Masjclrentry']['Stream'];?></td>
                                    <td><strong>Process</strong></td> <td><?php echo $data['Masjclrentry']['Process'];?></td>
                                    <td><strong>Profile</strong></td> <td><?php echo $data['Masjclrentry']['Profile'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Location</strong></td> <td><?php echo $data['Masjclrentry']['BranchName'];?></td>
                                    <td><strong>Documentation</strong></td> <td><?php echo $data['Masjclrentry']['documentDone'];?></td>
                                    <td><strong>Created Date</strong></td> <td><?php if($data['Masjclrentry']['EntryDate'] !=""){ echo date('d/m/Y H:i:s',strtotime($data['Masjclrentry']['EntryDate']));}?></td>
                                    <td><strong>Code Created on</strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Biometric Code</strong></td> <td><?php echo $data['Masjclrentry']['BioCode'];?></td>
                                    <td><strong>Employee Location</strong></td> <td><?php echo $data['Masjclrentry']['EmpLocation'];?></td>
                                    <td><strong>Cost Center</strong></td> <td><?php echo $data['Masjclrentry']['CostCenter'];?></td>
                                    <td><strong></strong></td> <td></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;color:red;"><strong>Loan From</strong></td> <td></td>
                                    <td style="text-align: left;color:red;"><strong>Loan To</strong></td> <td></td>
                                    <td style="text-align: left;color:red;"><strong>Loan Amount</strong></td> <td></td>
                                    <td><strong></strong></td> <td></td>
                                </tr>                 
                            </tbody>   
                        </table>
                    </div>
                </div>
                
                 <div class="form-group" >
                    <div class="col-sm-12">
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th colspan="8" onclick="showdiv('EA');" style="cursor: pointer;text-align: left;" >Employee Address</th>
                                </tr>
                            </thead>
                            <tbody style="display: none;" id="EA" > 
                                <tr>
                                    <td colspan="2"><strong>Present</strong></td>
                                    <td colspan="2" style="text-align: center;" ><strong>Permanent</strong></td>
                                    <td colspan="2" style="text-align: center;" ><strong>Present</strong></td>
                                    <td colspan="2" style="text-align: center;" ><strong>Permanent</strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Address</strong></td> <td><?php echo $data['Masjclrentry']['Adrress2'];?></td>
                                    <td><strong>Address</strong></td> <td><?php echo $data['Masjclrentry']['Adrress1'];?></td>
                                    <td><strong>City</strong></td> <td><?php echo $data['Masjclrentry']['City1'];?></td>
                                    <td><strong>City</strong></td> <td><?php echo $data['Masjclrentry']['City'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>State</strong></td> <td><?php echo $data['Masjclrentry']['State1'];?></td>
                                    <td><strong>State</strong></td> <td><?php echo $data['Masjclrentry']['State'];?></td>
                                    <td><strong>Mobile No</strong></td> <td><?php echo $data['Masjclrentry']['Mobile1'];?></td>
                                    <td><strong>Mobile No</strong></td> <td><?php echo $data['Masjclrentry']['Mobile'];?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;"><strong>Landline No</strong></td> <td><?php echo $data['Masjclrentry']['LandLine1'];?></td>
                                    <td><strong>Landline No</strong></td> <td><?php echo $data['Masjclrentry']['LandLine'];?></td>
                                    <td><strong>Pin Code</strong></td> <td><?php echo $data['Masjclrentry']['PinCode1'];?></td>
                                    <td><strong>Pin Code</strong></td> <td><?php echo $data['Masjclrentry']['PinCode'];?></td>
                                </tr>                 
                            </tbody>   
                        </table>
                    </div>
                </div>
                
                
                
                <div class="box-header" >
                    <div class="box-name">
                        <span>Documentation Details</span>
                    </div>
                </div>
                
                <span><?php echo $this->Session->flash();?></span>
                <div class="form-group" style="margin-top:30px;" >
                    <label class="col-sm-2 control-label">Doc Type<span class="req">*</span></label> 
                    <div class="col-sm-3"> 
                        <input type="hidden" name="documentDone" id="documentDone" value="<?php echo $data['Masjclrentry']['documentDone'];?>" >
                        <select name="type" id="type" class="form-control" onchange="return checkread(this.value);">
                            <option value="">Select</option>
                            <?php foreach ($Data1 as $d){?>
                                <option value="<?php echo $d['masdoc_option']['Doctype']; ?>"><?php echo $d['masdoc_option']['Doctype']; ?></option>
                            <?php } ?>
                        </select>
                    </div> 

                    <label class="col-sm-2 control-label">Doc Name<span class="req">*</span></label> 
                    <div class="col-sm-3">
                        <div id="mm">
                            <select name="styp" id="styp" class="form-control" >
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                     <label class="col-sm-2 control-label">Page No</label> 
                    <div id="typequery">
                        <div class="col-sm-3">
                            <select name="pageno" id="pageno" class="form-control" id="pageno" >
                                <option value="">Select</option>
                            </select>
                        </div> 
                    </div> 
                   

                    <label class="col-sm-2 control-label">File<span class="req">*</span></label>
                    <div class="col-sm-3">
                        <?php   echo $this->Form->input('file', array('label'=>false,'type' => 'file','id'=>'file','accept'=>'image/jpg','onchange'=>'checkPhoto(this)'));?>
                    </div>
                </div>
     
                
                
                <div class="form-group ">
                    <label class="col-sm-2 control-label">Box No</label>
                    <div class="col-sm-3">
                        <input type="text" name="BoxNo" id="BoxNo"  class="form-control" value="">
                    </div>
                    
                    <div class="col-sm-4">
                        <input type='button' class="btn btn-info btn-new pull-right" value="Save Document" onclick="validate('tab3');" >
                    </div>
                </div>
                
                <?php if(!empty($mendatorydoc)){?>
                <div class="form-group ">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-4">
                        <input type="hidden" id="mendatorydoc" value="<?php echo count($mendatorydoc);?>"  >
                        <table  class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
                            <thead>
                                <tr>                	
                                    <th >DOCUMENT</th>
                                    <th style="text-align:center;width:30px;">MENDATORY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($mendatorydoc as $key=>$val){ ?>
                                <tr>
                                    <td style="background-color:red;color:white;text-align:left;" ><strong><?php echo $key;?></strong></td><td style="background-color:red;color:white;text-align:center;" ><strong><?php echo $val;?></strong></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }?>
                
                <?php if(!empty($find)){?>
                <div class="form-group ">
                    <label class="col-sm-1 control-label"></label>
                    <div class="col-sm-8">
                    <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
                        <thead>
                	<tr>                	
                        <th style="text-align:center;width:" >SNo.</th>
                    	<th style="text-align:center;width:">Offer latter No</th>
                    	<th style="text-align:center;width:">Doc Type</th>
                    	<th style="text-align:center;width:">Doc name</th>
                    	<th style="text-align:center;width:">Box No</th>
                        <th style="text-align:center;width:" >View</th>
                        <th style="text-align:center;width:">Delete</th>
                        
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
             
					 foreach($find as $post):
                    //print_r($Jclr['Jclr']['AccountApprove']);die;
                                             $imagepath=$show.$post['Masdocfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td style='text-align:center;' >".$i++."</td>";
						echo "<td style='text-align:center;' align=\"center\">".$post['Masdocfile']['OfferNo']."</td>";
						echo "<td style='text-align:center;'>".$post['Masdocfile']['DocType']."</td>";
						echo "<td style='text-align:center;'>".$post['Masdocfile']['DocName'].'_'.$post['Masdocfile']['fileno']."</td>";
						echo "<td style='text-align:center;'>".$post['Masdocfile']['BoxNo']."</td>";
						echo "<td style='text-align:center;' onclick=\"viewimage('$imagepath')\"><i style='font-size:20px;cursor: pointer;' title='view' class='material-icons'>pageview</i></td>";
                                               if($Jclr['Jclr']['AccountApprove']!= 0 && $post['Docfile']['DocType']=='PassBook' ) { if($Jclr['Jclr']['AccountApprove']== 1){echo "<td >"."Approved"."</td>";}else{echo "<td >"."DisAproved"."</td>";} }
                                              else{  echo "<td style='text-align:center;' onclick=\"deleteimage('$imagepath','{$post['Masdocfile']['OfferNo']}','{$post['Masdocfile']['filename']}','{$data['Masjclrentry']['id']}')\"><i  style='font-size:20px;cursor: pointer;' title='Delete' class='material-icons'>delete_forever</i></td>"; }
					 echo "</tr>";
					 endforeach;
				?>
                </tbody>
				</table>
                    </div>
                </div>
                
                <?php }?>
                
                
                
                
                
                
                
               
                
                <div class="box-header"  >
                    <div class="box-name">
                        <span>FNF DETAILS</span>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top:30px;" >
                    <label class="col-sm-2 control-label" >Employee Name</label>
                    <div class="col-sm-3">
                        <input type="text" name="EmpName" readonly="" id="EmpName" value="<?php echo $data['Masjclrentry']['EmpName'];?>" class="form-control" >
                    </div> 
                    
                    <label class="col-sm-2 control-label" >Releasing Cheque Date</label>
                    <div class="col-sm-3">
                        <input type="text" name="ReleasingChequeDate" id="ReleasingChequeDate" value="<?php if($data['Masjclrentry']['ReleasingChequeDate'] !=""){echo $data['Masjclrentry']['ReleasingChequeDate'];}?>" class="form-control datepickers" >
                    </div>  
                </div>
                
                <div class="form-group" >
                     <label class="col-sm-2 control-label" >Cheque Amount</label>
                    <div class="col-sm-3">
                        <input type="text" name="ChequeAmount" value="<?php echo $data['Masjclrentry']['ChequeAmount'];?>" onkeypress="return isNumberDecimalKey(event,this)" id="ChequeAmount" class="form-control" >
                    </div> 
                    
                    <label class="col-sm-2 control-label" >Cheque Date</label>
                    <div class="col-sm-3">
                        <input type="text" name="ChequeDate" id="ChequeDate" value="<?php if($data['Masjclrentry']['ChequeDate'] !=""){echo $data['Masjclrentry']['ChequeDate'];}?>" class="form-control datepickers" >
                    </div> 
                </div>
               
                <div class="form-group" >
                    <label class="col-sm-2 control-label" >Cheque No </label>
                    <div class="col-sm-3">
                        <input type="text" name="ChequeNo" value="<?php echo $data['Masjclrentry']['ChequeNo'];?>"  id="ChequeNo" class="form-control" >
                    </div> 
                    
                    <label class="col-sm-2 control-label" >Upload Filled NOC</label>
                    <div class="col-sm-2">
                        <?php   echo $this->Form->input('CancelledChequeImage', array('label'=>false,'type' => 'file','id'=>'CancelledChequeImage','accept'=>'image/jpg'));?>
                    </div> 
                    <div class="col-sm-2">
                        <?php if($data['Masjclrentry']['FnfDoc'] !=""){?>
                        <img style="width:50px;" src="<?php echo $this->webroot;?>Doc_File/<?php echo $data['Masjclrentry']['OfferNo'];?>/<?php echo $data['Masjclrentry']['FnfDoc'];?>" >
                        <?php }?>
                    </div>
                </div>
            
                <div class="form-group" >
                     <label class="col-sm-2 control-label" >Reason of Leaving</label>
                    <div class="col-sm-3">
                        
                        <textarea id="ReasonofLeaving" name="ReasonofLeaving" class="form-control"><?php echo $data['Masjclrentry']['ReasonofLeaving'];?></textarea>
                    </div>
                     <div class="col-sm-4">
                        <input onclick='return window.location="<?php echo $this->webroot;?>ProcessNocs"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <input type='button' class="btn btn-info btn-new pull-right" value="Submit" onclick="validate('tab4');" style="margin-left:5px;" >
                    </div>
                </div>
                
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="col-sm-3" id="mm" >
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
function statustype(val){    
          
             var fileno = 0;
             if(val == 'Code Of Conduct'){
                   fileno= 2;
          
      }
      else if(val == 'Epf Declaration Form'){
                   fileno= 3;
          
      }
       else if(val == 'Contrat Form'){
                   fileno= 7;
          
      }
      else if(val == 'Resume'){
           fileno= 4;
                  
          
      }
      else
      {
         fileno = 0; 
      }
           
            $.post("get_status_data",{types:val,fileno:fileno},function(data)
            {
                  
                $("#mm").html(data);});

        }    
    
    
function checkread(val){
        var fileno = 0;
        statustype(val);
        if(val == 'Code Of Conduct'){
             fileno= 2;
              document.getElementById("pageno").disabled = false;

  }
  else if(val == 'Epf Declaration Form'){
        fileno= 3;
               document.getElementById("pageno").disabled = false;

  }
   else if(val == 'Contrat Form'){
       fileno= 7;
              document.getElementById("pageno").disabled = false;


  }
   else if(val == 'Resume'){
       fileno= 4;
              document.getElementById("pageno").disabled = false;

  }
  else
  {
     document.getElementById("pageno").disabled = true;
      document.getElementById("pageno").value = "";
  }



       if(fileno!=0)
       {
        var i =0;
        var order ='';
        order += "<div class='col-sm-3'><select name='pageno' class = 'form-control' required='' id = 'pageno'><option value=''>Select</option>";
        for(i=1;i<=fileno;i++)
        {



           order +="<option value='"+i+"'>"+i+"</option>";



    }
 order += "</select>";
        order +="</div>";
    document.getElementById("typequery").innerHTML=order;
    }

    }
    
         

function deleteimage(val,emp,file,MasJclrId){
    if(confirm('Are you sure you want to delete this record?')){
        window.location='<?php echo $this->webroot;?>ProcessNocs/deletefile?path='+val+'&EmpCode='+emp+'&filename='+file+'&MasJclrId='+MasJclrId;
    }
}
        
function viewimage(val){   
    newwindow= window.open('<?php echo $this->webroot;?>'+val,'Image','height=500,width=600');
    if (window.focus) {
        newwindow.focus()
    }
    return false;
}

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}

function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}
</script>