<?php

?>
 
<style>
    table td{margin: 5px;}
</style>
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
            window.location='http://mascallnetnorth.in/ispark/Jclrs/deletefile?path='+val+'&EmpCode='+emp+'&filename='+file;

        }
        
        
        function viewimage(val)
   {
      
       
newwindow= window.open('http://mascallnetnorth.in/ispark/'+val,'Image','height=500,width=600');
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
 window.location.href='http://mascallnetnorth.in/ispark/Jclrs/viewdoc';
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
                    
                    <span>Save Status</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4 class="page-header">
                    <?php echo $this->Session->flash(); ?>
		</h4>
<div class="form-group has-success has-feedback">
  
 <label class="col-sm-2 control-label">Doc Type</label> 
<div class="col-sm-2"> 
    <select name="type" class="form-control" onchange="return checkread(this.value);">
        
        <option value="">Type</option>
       
       <? foreach ($Data1 as $d)
        { ?>
             <option value="<?php echo $d['doc_option']['Doctype']; ?>"><?php echo $d['doc_option']['Doctype']; ?></option>
       <?php }  ?>
    </select>
</div> 
 
 <label class="col-sm-2 control-label">Doc Name</label> 
<div class="col-sm-2">
     <div id="mm">
    <select name="styp" class="form-control" >
        
        <option value="">DocName</option>
       
       
    </select></div>
</div> 
 
 </div>
 
 
 
 <div class="form-group has-success has-feedback">
  
 
 <label class="col-sm-2 control-label">Box No</label>
<div class="col-sm-2">
     <input type="text" name="BoxNo"  class="form-control" value="">
 </div>



<label class="col-sm-2 control-label">File</label>
<div class="col-sm-2">
    <?php	
                    echo $this->Form->input('file', array('label'=>false,'type' => 'file','required'=>true));
                    ?>
 </div>
 </div>
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label">Page No</label> 
                    <div id="typequery">
<div class="col-sm-2">
    
    <select name="pageno" class="form-control" id="pageno" >
        
        <option value="">Select</option>
       
       
    </select>
    </div> </div>
                </div>
                    
                   
                <div class="form-group has-success has-feedback">
                    <label class="col-sm-2 control-label"></label> 
                    <div class="col-sm-2">
                        <button type="Save" class="btn btn-info">save</button>
                       <input type="button" name="next" value="Finish" <?php echo $finish; ?> class="btn btn-info"  onclick="nextpage()" />
                    </div>  
                    
                    <label class="col-sm-2 control-label"></label> 
                    <div class="col-sm-2">
                        
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
                    	<th>Emp Code</th>
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
                                             $imagepath=$show.$post['Docfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
						echo "<td align=\"center\">".$post['Docfile']['EmpCode']."</td>";
						echo "<td>".$post['Docfile']['DocType']."</td>";
						echo "<td>".$post['Docfile']['DocName'].'_'.$post['Docfile']['fileno']."</td>";
						echo "<td>".$post['Docfile']['BoxNo']."</td>";
						echo "<td onclick=\"viewimage('$imagepath')\"><a href ='#'>view</a></td>";
                                               if($Jclr['Jclr']['AccountApprove']!= 0 && $post['Docfile']['DocType']=='PassBook' ) { if($Jclr['Jclr']['AccountApprove']== 1){echo "<td >"."Approved"."</td>";}else{echo "<td >"."DisAproved"."</td>";} }
                                              else{  echo "<td onclick=\"deleteimage('$imagepath','{$post['Docfile']['EmpCode']}','{$post['Docfile']['filename']}')\"><a href = '#' style = 'color:red;'>Delete</a></td>"; }
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
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
   
        <label class="col-sm-2 control-label">Bank</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Bank', array('label'=>false,'class'=>'form-control','placeholder'=>'Bank.','disabled'=>'true','value'=>$Jclr['Jclr']['Bank'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div> </div>
    <div class="form-group has-info has-feedback">
       <label class="col-sm-2 control-label">IFSC</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('IFSC', array('label'=>false,'class'=>'form-control','placeholder'=>'IFSC Code','disabled'=>'true','value'=>$Jclr['Jclr']['IFSC'])); ?>

                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
       <label class="col-sm-2 control-label">A/c Type</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('ACType', array('label'=>false,'options'=>array('Saving'=>'Saving','Current'=>'Current'),'class'=>'form-control','empty'=>'Type','disabled'=>'true','value'=>$Jclr['Jclr']['ACType'])); ?>
 
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
        </div></div>
    </div>
 
 
 
 <div class="form-group has-info has-feedback">
  
 <label class="col-sm-2 control-label">Branch Name</label>
        <div class="col-sm-3">
           <div class="input-group">
               <?php	echo $this->Form->input('BankBranch', array('label'=>false,'class'=>'form-control','placeholder'=>'Type','disabled'=>'true','value'=>$Jclr['Jclr']['BankBranch'])); ?>
 
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
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