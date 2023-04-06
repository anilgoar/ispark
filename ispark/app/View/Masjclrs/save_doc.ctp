<?php

?>
 
<style>
    table td{margin: 5px;}
</style>
<script language="javascript">
    $(function () {
    $("#datepick").datepicker1({
       
        changeMonth: true,
        changeYear: true
    });
});



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
<script>
   
        function statustype(val)
        {    
          
             var fileno = 0;
             if(val == 'Code Of Conduct'){
                   fileno= 7;
          
      }
      else if(val == 'Epf Declaration Form'){
                   fileno= 3;
          
      }
       else if(val == 'Contrat Form'){
                   fileno= 2;
          
      }
      else if(val == 'Resume'){
           fileno= 7;
                  
          
      }
      else
      {
         fileno = 0; 
      }
            //alert(val);
            $.post("get_status_data",{types:val,fileno:fileno},function(data)
            {
                  
                $("#mm").html(data);});

        }
        
        function checkread(val)
        {
            var fileno = 0;
            statustype(val);
            if(val == 'Code Of Conduct'){
                 fileno= 7;
                  document.getElementById("pageno").disabled = false;
          
      }
      else if(val == 'Epf Declaration Form'){
            fileno= 3;
                   document.getElementById("pageno").disabled = false;
          
      }
       else if(val == 'Contrat Form'){
           fileno= 2;
                  document.getElementById("pageno").disabled = false;
          
          
      }
       else if(val == 'Resume'){
           fileno= 7;
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
            order += "<div class='col-sm-2'><select name='pageno' class = 'form-control' required='' id = 'pageno'><option value=''>Select</option>";
            for(i=1;i<=fileno;i++)
            {
           
           
            
               order +="<option value='"+i+"'>"+i+"</option>";
                
           
            
        }
     order += "</select>";
            order +="</div>";
	document.getElementById("typequery").innerHTML=order;
        }
            
        }
        
        
        
        function gettypeofquery(val)
{
    
	
}
        
        
        
        
         function deleteimage(val,emp,file)
        {
            //alert(val);
            window.location='http://192.168.137.230/ispark/Masjclrs/deletefile?path='+val+'&EmpCode='+emp+'&filename='+file;

        }
        
        
        function viewimage(val)
   {
      
       
newwindow= window.open('http://192.168.137.230/ispark/'+val,'Image','height=500,width=600');
 if (window.focus) {newwindow.focus()}
return false;
   } 
   
   
   
      //alert(val);
      $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).is(":checked")){
          document.getElementById("JclrAcNo").disabled = false;
          document.getElementById("JclrBank").disabled = false;
          document.getElementById("JclrIFSC").disabled = false;
          document.getElementById("JclrACType").disabled = false;
          document.getElementById("JclrBankfile").disabled = false; 
          document.getElementById("JclrBankBranch").disabled = false;
          document.getElementById("submit").disabled = false;

       }
        else if($(this).is(":not(:checked)")){
         document.getElementById("JclrAcNo").disabled = true;
           
          document.getElementById("JclrBank").disabled = true;
           
          document.getElementById("JclrIFSC").disabled = true;
            
          document.getElementById("JclrACType").disabled = true;
           
          document.getElementById("JclrBankfile").disabled = true; 
            document.getElementById("JclrBankBranch").disabled = true;  
          document.getElementById("submit").disabled = true;
           
  
         }
        });
    });
    
    
    function nextpage()
   {
 window.location.href='http://192.168.137.230/ispark/Masjclrs/newemp';
   }
        </script>
        
<?php echo $this->Form->create('Save',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
	<a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
	</a>
	<ol class="breadcrumb pull-left">
	</ol>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>DOCUMENT DETAILS</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Doc Type</label> 
                    <div class="col-sm-3"> 
                        <select name="type" class="form-control" onchange="return checkread(this.value);">
                            <option value="">Select</option>
                            <?php foreach ($Data1 as $d){?>
                                <option value="<?php echo $d['masdoc_option']['Doctype']; ?>"><?php echo $d['masdoc_option']['Doctype']; ?></option>
                            <?php } ?>
                        </select>
                    </div> 

                    <label class="col-sm-2 control-label">Doc Name</label> 
                    <div class="col-sm-3">
                        <div id="mm">
                            <select name="styp" class="form-control" >
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Box No</label>
                    <div class="col-sm-3">
                        <input type="text" name="BoxNo"  class="form-control" value="">
                    </div>

                    <label class="col-sm-2 control-label">File</label>
                    <div class="col-sm-3">
                        <?php   echo $this->Form->input('file', array('label'=>false,'type' => 'file','required'=>true));?>
                    </div>
                </div>
                
                <div class="form-group ">
                    <label class="col-sm-2 control-label">Page No</label> 
                    <div id="typequery">
                        <div class="col-sm-3">
                            <select name="pageno" class="form-control" id="pageno" >
                                <option value="">Select</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-sm-3">
                        <button type="Save" class="btn btn-info btn-new pull-right">save</button>
                    </div>  
                </div>
                    
               
       <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>



        
  

    <div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			
			<div class="box-content">
			
				<h4 class="page-header">File Details</h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <thead>
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Offer latter No</th>
                    	<th>Doc Type</th>
                    	<th>Doc name</th>
                    	<th>Box No</th>
                    	<th>View</th>
                        <th>Delete</th>
                        
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
             
					 foreach($find as $post):
                    //print_r($Jclr['Jclr']['AccountApprove']);die;
                                             $imagepath=$show.$post['Masdocfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
						echo "<td align=\"center\">".$post['Masdocfile']['OfferNo']."</td>";
						echo "<td>".$post['Masdocfile']['DocType']."</td>";
						echo "<td>".$post['Masdocfile']['DocName'].'_'.$post['Masdocfile']['fileno']."</td>";
						echo "<td>".$post['Masdocfile']['BoxNo']."</td>";
						echo "<td onclick=\"viewimage('$imagepath')\"><a href ='#'>view</a></td>";
                                               if($Jclr['Jclr']['AccountApprove']!= 0 && $post['Docfile']['DocType']=='PassBook' ) { if($Jclr['Jclr']['AccountApprove']== 1){echo "<td >"."Approved"."</td>";}else{echo "<td >"."DisAproved"."</td>";} }
                                              else{  echo "<td onclick=\"deleteimage('$imagepath','{$post['Masdocfile']['OfferNo']}','{$post['Masdocfile']['filename']}')\"><a href = '#' style = 'color:red;'>Delete</a></td>"; }
					 echo "</tr>";
					 endforeach;
				?>
                </tbody>
				</table>
			</div>
		</div>
	</div>
</div> 
     <?php echo $this->Form->end(); ?>   
        
        <?php echo $this->Form->create('Jclr',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
                    <span><?php if($Jclr['Jclr']['AccountApprove']==0) { ?><input type="checkbox" name="bankdetails" value="bankdet" id="check" onclick="bankdet(this.value);"><?php }?>
                                 Bank Details</span>
		</div>
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
 <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">AcNo</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('AcNo', array('label'=>false,'class'=>'form-control','placeholder'=>'AcNo','disabled'=>'true','value'=>$Jclr['Jclr']['AcNo'])); ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Bank</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Bank', array('label'=>false,'class'=>'form-control','placeholder'=>'Bank.','disabled'=>'true','value'=>$Jclr['Jclr']['Bank'])); ?>

                
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
       <label class="col-sm-2 control-label">IFSC</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('IFSC', array('label'=>false,'class'=>'form-control','placeholder'=>'IFSC Code','disabled'=>'true','value'=>$Jclr['Jclr']['IFSC'])); ?>

                
        </div></div>
       <label class="col-sm-2 control-label">A/c Type</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('ACType', array('label'=>false,'options'=>array('Saving'=>'Saving','Current'=>'Current'),'class'=>'form-control','empty'=>'Type','disabled'=>'true','value'=>$Jclr['Jclr']['ACType'])); ?>
 
                
        </div></div>
    </div>
 
 
 
 <div class="form-group has-info has-feedback">
  
 <label class="col-sm-2 control-label">Branch Name</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('BankBranch', array('label'=>false,'class'=>'form-control','placeholder'=>'Type','disabled'=>'true','value'=>$Jclr['Jclr']['BankBranch'])); ?>
 
                
        </div></div>
    
 

<label class="col-sm-2 control-label">File</label>
<div class="col-sm-2">
    <?php	
                    echo $this->Form->input('bankfile', array('label'=>false,'type' => 'file','disabled'=>'true','required'=>true));
                    ?>
 </div>
 </div>
                <div class="form-group has-success has-feedback">
               
                    
                   
                
		<div class="clearfix"></div>
		<div class="form-group">
                   <div class="col-sm-2">
                       <button type="Jclr" class="btn btn-primary btn-label-left" disabled id="submit">
                            save
			</button>
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('Masjclrs',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','action'=>'saverelation')); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    
<!--                    <span><?php if($Jclr['Jclr']['AccountApprove']==0) { ?><input type="checkbox" name="Family" value="Deteails" id="check" onclick="familydetails(this.value);"><?php }?>
                                Family Details</span>-->
                    <span>Family Details
                    </span>
		</div>
		
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
 <div class="form-group has-info has-feedback">
        <label class="col-sm-2 control-label">Nominee Name*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('RelativeName', array('label'=>false,'class'=>'form-control','placeholder'=>'Name','value'=>'')); ?>
                
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Relationship*</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Relationship', array('label'=>false,'class'=>'form-control','placeholder'=>'Relationship.','value'=>'')); ?>

                
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
       <label class="col-sm-2 control-label">Address Of Nominee</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('AddressOfRel', array('label'=>false,'class'=>'form-control','placeholder'=>'Address','value'=>'')); ?>

                
        </div></div>
       <label class="col-sm-2 control-label">State</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('State', array('label'=>false,'options'=>array('Up'=>'UP','MP'=>'MP'),'empty'=>'Select State','class'=>'form-control')); ?>
 
                
        </div></div>
    </div>
 
 
 
 <div class="form-group has-info has-feedback">
  
 <label class="col-sm-2 control-label">Mobile No</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('RelMoblie', array('label'=>false,'class'=>'form-control','placeholder'=>'Mobile','value'=>'')); ?>
 
                
        </div></div>
    
 

<label class="col-sm-2 control-label">PinCode</label>
<div class="col-sm-2">
    <?php	
                    echo $this->Form->input('RelPinCode', array('label'=>false,'class'=>'form-control','placeholder'=>'PinCode','required'=>true));
                    ?>
 </div>
 </div>
                <div class="form-group has-info has-feedback">
  
 <label class="col-sm-2 control-label">Adhar Id</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('RelAdharId', array('label'=>false,'class'=>'form-control','placeholder'=>'Adhar Id','value'=>'')); ?>
 
                
        </div></div>
    
 

<label class="col-sm-2 control-label">Nominee A Family Member</label>
<div class="col-sm-2">
    <?php	
                    echo $this->Form->input('FamilyMember', array('label'=>false,'options'=>array('Yes'=>'Yes','No'=>'No'),'class'=>'form-control','required'=>true));
                    ?>
 </div>
 </div>
                <div class="form-group has-info has-feedback">
  
 <label class="col-sm-2 control-label">DOB</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('RelDOB', array('label'=>false,'style'=>'width:200px;','id'=>'datepick','placeholder'=>'DOB','value'=>'')); ?>
 
                
        </div></div>
    
 

<label class="col-sm-2 control-label">Residing With</label>
<div class="col-sm-2">
    <?php	
                    echo $this->Form->input('ResidingWith', array('label'=>false,'options'=>array('Yes'=>'Yes','No'=>'No'),'class'=>'form-control','required'=>true));
                     echo $this->Form->input('OfferNo', array('type'=>'hidden','label'=>false,'value'=>$ID,'class'=>'form-control','required'=>true));
                    ?>
 </div>
 </div>
                
                <div class="form-group has-success has-feedback">
               
                    
                   
                
		<div class="clearfix"></div>
		<div class="form-group">
                   
                   <div class="col-sm-2">
                       
                       <input type="submit" name="Submit" value="Finish" <?php echo $finish; ?> class="btn btn-info"   />
                    </div>
		</div>
            </div>
        </div>
    </div>
</div>
    <?php echo $this->Form->end(); ?>
<?php echo $this->Html->css('jquery-ui'); 
 
 echo $this->Html->script('jquery-ui');
 ?>

   