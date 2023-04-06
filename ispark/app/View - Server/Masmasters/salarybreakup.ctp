<?php

?>
<script>
function salarydata(val){
      
         $.post("get_salary",{offerNo:val},function(data)
            {$("#bb").html(data);});
             document.getElementById("References").style.display = "block";
       
    }
</script>

<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<script>
    function Design(val)
  {
      $.post("Masmasters/get_design",{val},function(data){
        $('#tower').html(data);});
  }
    
    
    function Test(val,Id) {
    var str ='';
    if(val=='AMT'){
        str ='';
    }
    else if(val=='per')
    {
    str +='<input style="width:57px;" type="text" name='+"IN"+Id+' id ='+"IN"+Id+' value="" oninput='+"convenceValue"+Id+'(this.value); class="" placeholder="" onKeyPress="return checkNumber(this.value,event)">';
           
        }
         document.getElementById(Id).innerHTML=str;
}
function convenceValueNameShow(val)
{
    //alert(val);
  var Basic=  document.getElementById('Basic').value;
   //alert(Basic);
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('Conveyance').value =parseInt( amt);
        grossAmount(amt);
        }
}

function convenceValueNameShow1(val)
{
    
  var Basic=  document.getElementById('Basic').value;
 
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('Portfolio').value =parseInt( amt);
  grossAmount(amt);
}}
function convenceValueNameShow2(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('Medical').value =parseInt( amt);
  grossAmount(amt);
    }
    }
    function convenceValueNameShow3(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('Special').value =parseInt( amt);
  grossAmount(amt);
    }
    }
     function convenceValueNameShow4(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('OtherAllow').value =parseInt( amt);
  grossAmount(amt);
    }}
    function convenceValueNameShow5(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('HRA').value =parseInt( amt);
  grossAmount(amt);
    }
    }
     function convenceValueNameShow6(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('HRA').value =parseInt( amt);
   var  gross = parseInt(amt)+parseInt(Basic);
  document.getElementById('Gross').value = parseInt(gross);
  grossAmount(amt);
    }}
     function convenceValueNameShow7(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('PLI').value =parseInt( amt);
   var  gross = parseInt(amt)+parseInt(Basic);
  document.getElementById('Gross').value = parseInt(gross);
  grossAmount(amt);
    }}
    
   function convenceValueNameShow8(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('EPF').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntepf(amt);
    }}
    function convenceValueNameShow9(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('ESIC').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntesic(amt);
    }}
    
    function convenceValueNameShow10(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('Professional').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntProfessional(amt);
    }}
    function convenceValueNameShow11(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('EPFCO').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntEPFCO(amt);
    }}
    function convenceValueNameShow12(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('ESICCO').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntESICCO(amt);
    }}
    function convenceValueNameShow13(val)
{
     var Basic=  document.getElementById('Basic').value;
   if(Basic==''){
   alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;

        }
        else{
  var amt  = Basic*val/100;
 //alert(amt);
  document.getElementById('Admin').value =parseInt( amt);
//   var  gross = parseInt(amt)+parseInt(Basic);
//  document.getElementById('Gross').value = parseInt(gross);
  cntAdmin(amt);
    }}
    
</script>
<script>
 function grossAmount(){
     var Basic = parseInt(document.getElementById('Basic').value);
     if(Basic == '')
     {
       alert("Please Enter Basic The Amount."); 
   document.getElementById('Basic').focus();
		return false;
     }
     else
     {
      
  
  if(document.getElementById('Conveyance').value=='')
     {
         var Conveyance =0;
     }
     else
     {
      var Conveyance= parseInt( document.getElementById('Conveyance').value);
  }
 
  if(document.getElementById('Medical').value=='')
     {
         var Medical =0;
     }
     else
     {
       var Medical= parseInt( document.getElementById('Medical').value);
   }
   if(document.getElementById('Portfolio').value=='')
     {
         var Portfolio =0;
     }
     else
     {
        var Portfolio= parseInt( document.getElementById('Portfolio').value);
    }
     if(document.getElementById('Special').value=='')
     {
         var Special =0;
     }
     else
     {
       var Special= parseInt( document.getElementById('Special').value);
   }
   if(document.getElementById('OtherAllow').value=='')
     {
         var OtherAllow =0;
     }
     else
     {
        var OtherAllow= parseInt( document.getElementById('OtherAllow').value);
    }
     if(document.getElementById('Bonus').value=='')
     {
         var Bonus =0;
     }
     else
     {
         var Bonus= parseInt( document.getElementById('Bonus').value);
     }
    
     if(document.getElementById('HRA').value=='')
     {
         var HRA =0;
     }
     else
     {
          var HRA= parseInt( document.getElementById('HRA').value);
      }
      if(document.getElementById('PLI').value=='')
     {
         var PLI =0;
     }
     else
     {
           var PLI= parseInt( document.getElementById('PLI').value);
       }
      // alert(PLI);
      //var amt1 = parseInt(document.getElementById('Conveyance').value );
    var  gross = Basic+PLI+HRA+Bonus+OtherAllow+Special+Portfolio+Medical+Conveyance;
     //alert(gross);
    //document.getElementById('CntGross').value = gross;
    var epf =gross*12/100;
    var epfco = Basic * 12/100;
    var admin =  Basic * 1.16/100;
    if(gross <= 21000)
    {
        var esico = gross*4.75/100;
    var ecsic = gross*1.75/100;
    }
    else
    {
        var esico =0;
      var ecsic =0;  
    }//alert(ecsic);
    if(document.getElementById('Professional').value=='')
    {
      var prof=0;   
    }
    else
    {
    var prof = parseInt(document.getElementById('Professional').value);
    }
    var ctc = gross+epfco+esico+admin;
    var netinhand = gross-epf-ecsic-prof;
    document.getElementById('Gross').value = parseInt(gross);
    document.getElementById('EPF').value = parseInt(epf);
    document.getElementById('ESIC').value = parseInt(ecsic);
    document.getElementById('NetInHand').value = parseInt(netinhand);
     document.getElementById('EPFCO').value = parseInt(epfco);
      document.getElementById('ESICCO').value = parseInt(esico);
       document.getElementById('Admin').value = parseInt(admin);
        document.getElementById('CTC').value = parseInt(ctc);
         
    }
    }
function cntepf(val){
    if(val ==''){
        val=0;
    }
 if(document.getElementById('Professional').value==''){
         var Professional =0;
    }
    else{
    var Professional = parseInt( document.getElementById('Professional').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('ESIC').value==''){
        var ESIC  =0;
     }
     else{
   var ESIC  =parseInt( document.getElementById('ESIC').value);
     }
   var netinhand = Gross -(parseInt(val)+ESIC+Professional);
    document.getElementById('NetInHand').value = parseInt(netinhand);
}
function cntesic(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('Professional').value==''){
         var Professional =0;
    }
    else{
    var Professional = parseInt( document.getElementById('Professional').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('EPF').value ==''){
        var ESIC  =0;
     }
     else{
   var ESIC  =parseInt( document.getElementById('EPF').value);
     }
     
   var netinhand = Gross -(parseInt(val)+ESIC+Professional);
    document.getElementById('NetInHand').value = parseInt(netinhand);
}

function cntProfessional(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('ESIC').value==''){
         var Professional =0;
    }
    else{
    var Professional = parseInt( document.getElementById('ESIC').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('EPF').value ==''){
        var ESIC  =0;
     }
     else{
   var ESIC  =parseInt( document.getElementById('EPF').value);
     }
     
   var netinhand = Gross -(parseInt(val)+ESIC+Professional);
    document.getElementById('NetInHand').value = parseInt(netinhand);
}
function cntEPFCO(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('ESICCO').value==''){
         var ESICCO =0;
    }
    else{
    var ESICCO = parseInt( document.getElementById('ESICCO').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('Admin').value ==''){
        var Admin  =0;
     }
     else{
   var Admin  =parseInt( document.getElementById('Admin').value);
     }
     
   var netinhand = Gross +(parseInt(val)+Admin+ESICCO);
    document.getElementById('CTC').value = parseInt(netinhand);
}

function cntESICCO(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('EPFCO').value==''){
         var ESICCO =0;
    }
    else{
    var ESICCO = parseInt( document.getElementById('EPFCO').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('Admin').value ==''){
        var Admin  =0;
     }
     else{
   var Admin  =parseInt( document.getElementById('Admin').value);
     }
     
   var netinhand = Gross +(parseInt(val)+Admin+ESICCO);
    document.getElementById('CTC').value = parseInt(netinhand);
}

function cntAdmin(val){
    if(val ==''){
        val=0;
    }
    if(document.getElementById('EPFCO').value==''){
         var ESICCO =0;
    }
    else{
    var ESICCO = parseInt( document.getElementById('EPFCO').value);
    }
     if(document.getElementById('Gross').value==''){
   var Gross =0;
     }
     else{
         var Gross = parseInt( document.getElementById('Gross').value);
     }
     if(document.getElementById('ESICCO').value ==''){
        var Admin  =0;
     }
     else{
   var Admin  =parseInt( document.getElementById('ESICCO').value);
     }
     
   var netinhand = Gross +(parseInt(val)+Admin+ESICCO);
    document.getElementById('CTC').value = parseInt(netinhand);
}

function checkNumber(val,evt)
       {

    
    var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (charCode> 31 && (charCode < 48 || charCode > 57) && charCode != 46)
        {            
		return false;
        }
        
            else{
              
                 return true; 
           
           
        }
        }
	
</script>

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
<div class="box-header"  >
                <div class="box-name">
                    <span>Salary Update</span>
		</div>
		
		<div class="no-move"></div>
                
            </div>

<div class="box-content">
   <?php echo $this->Session->flash(); ?>
<?php //print_r($branch_master); ?>

  <?php echo $this->Form->create('Masmasters',array('class'=>'form-horizontal','action'=>'salarybreakup','id'=>'salarybreakup')); ?>
	
		
		<div class="form-group has-info has-feedback">								
			
			<label class="col-sm-2 control-label"><b style="font-size:14px">Search By</b></label>
				<div class="col-sm-3">
					<?php	echo $this->Form->input('Serach', array('label'=>false,'class'=>'form-control','empty'=>'Select',
							'options'=>array('EmpName'=>'Name','EmpCode'=>'Employee Code','BioCode'=>'Biometric COde'),'required'=>true)); ?>
				</div>
			<label class="col-sm-2 control-label"><b style="font-size:14px"> </b></label>
				<div class="col-sm-3">
					<?php	echo $this->Form->input('searchvalue', array('label'=>false,'class'=>'form-control','placeholder'=>'',
							'required'=>true)); ?>
				</div>
			
				
		</div>
		<div class="clearfix"></div>
		<div class="form-group">
			
			<div class="col-sm-2">
				 <input type='submit' class="btn btn-info btn-new pull-right" value="Search">
                        </div>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
<?php if(!empty($masJclr)){ ?>


			<div class="box-content">
			
				<h4 class="page-header">Employee Data </h4>
                                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <thead>
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Offered Latter No.</th>
                    	<th>Employee Name</th>
                        <th>Employee Type</th>
                       
                    	<th>Father/Husband name</th>
                         <th>DOB</th>
                         <th>Designation</th>
                    	<th>Department</th>
                    	
                        <th>Offered CTC	</th>
                        <th>Action</th>
                                            
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
              // print_r($Jclr);die;
					 foreach($masJclr as $post):
//print_r($post);die;
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
                                                echo "<td>".$post['Masjclrentry']['OfferNo']."</td>";
						echo "<td>".$post['Masjclrentry']['EmpName']."</td>";
						echo "<td>".$post['Masjclrentry']['EmpType']."</td>";
						echo "<td>".$post['Masjclrentry']['Father']."</td>";
                                                echo "<td>".$post['Masjclrentry']['DOB']."</td>";
                                                echo "<td>".$post['Masjclrentry']['Desgination']."</td>";
						echo "<td>".$post['Masjclrentry']['Dept']."</td>";
						
                                                echo "<td>".$post['Masjclrentry']['CTCOffered']."</td>";
                                                ?>
                <td><a href ='#' onclick="salarydata('<?php echo $post['Masjclrentry']['OfferNo']; ?>');">Edit</a></td>
                                               
                                               <?php
					 echo "</tr>";
					 endforeach;
				?>
                </tbody>
				</table>
<?php } ?>
                                <div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-con">
            
<div class="box-content" id="References" style="display:none">
    
    <?php echo $this->Form->create('Masmasters',array('class'=>'form-horizontal','action'=>'updatesalary')); ?>
                                <div id="bb"></div>
                                
                                <div class="form-group has-info has-feedback">
        <div class="col-sm-11">
            <input type='submit' class="btn btn-info btn-new pull-right" value="Save">
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-12" style="overflow:scroll;height:300px;" id="tower"></div>
    </div>
   
    <div class="clearfix"></div>
   
    <?php echo $this->Form->end(); ?>
    
    
    
</div>
</div>
    </div>	
</div>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
//    $(document).ready(function () {
//        $('#table_id').dataTable();
//        
//    });
//    $(document).on('click', '.actionCost', function(){var postdata = $(this).attr('class'); 
//            var postdataArray=postdata.split(" ");var cost_id = postdataArray[2]; var cost_status = postdataArray[1];$.ajax({type:"Post",cache:false,url: "disable_cost",
//                data:{cost_id:cost_id,cost_status:cost_status}, success: function(data){alert(data); }});});
        
        
        
        function myFunction() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  
  table = document.getElementById("table_id");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) >=0) {
         // alert(td.innerHTML.toUpperCase().indexOf(filter));
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>
</div>

<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
