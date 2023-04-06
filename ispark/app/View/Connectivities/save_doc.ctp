<?php



?>
 



<script>
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
<div class="row">
    <?php echo $this->Form->create('Connectivitie',array('class'=>'form-horizontal','action'=>'save_doc')); ?>
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Device Details</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
			<?php echo $this->Session->flash(); ?>
				<h4 class="page-header">Hardware details </h4>
                                <?php
                               
                                
                    
                    
                    
                     
                    ?>
                                
                                
                                <div class="form-group has-info has-feedback">

 <?php
                         if(!empty($branchName))
                            {
                                
                                echo ' 
        <label class="col-sm-2 control-label">Branch</label>';
                                echo '<div class="col-sm-3">
            <div class="input-group">';
                                echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','onchange'=>'showdeatilsReports();','required'=>true));
                                echo '</div></div>';
                        }
                        
                           
                        
                        ?>
                               <div id="oo"> 
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom table-fixed" >
                <thead>
                    
                    
                    
                    
                
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Devices Name</th>
<th>Owner</th>
<th>working</th>
<th>Not working</th>
<th>Damage</th>
<th>Stand by</th>
<th>Stand by but notworking</th>
<th>Remarks</th>


                        
                	</tr>
				</thead>
                <tbody>
                <?php 
     //  print_r($data);die;
                 
					 for($i =0;$i<=61;$i++){ //echo $DataVal[$i]['working']; exit;
                                             if($i%2==0)
                                                $owner = "Mas";
                                             else
                                                 $owner = "vendor";
					 echo "<tr >";
					 	echo "<td>".$i."</td>";
						echo "<td >".$data[$i]."<input type='hidden' name ='devicename$i' value='$data[$i]' style ='width:80px'></td>";
                                                echo "<td><input type='text' name='Owner$i' value='$owner' readonly=''></td>";
						echo "<td><input type='text' name ='Working$i'  value='{$DataVal[$i]['working']}' style ='width:80px' onKeyPress='return checkNumber(this.value,event)' ></td>";
						echo "<td><input type='text' name ='Notworking$i'  value='{$DataVal[$i]['Notworking']}' style ='width:80px' onKeyPress='return checkNumber(this.value,event)' ></td>";
						echo "<td><input type='text' name ='Damage$i'  value='{$DataVal[$i]['Damage']}'  style ='width:80px' ></td>";
                                                echo "<td><input type='text' name ='Standby$i'  style ='width:80px' value='{$DataVal[$i]['Standby']}' ></td>";
                                               echo "<td ><input type='text' name ='StandByNet$i' style ='width:80px' value='{$DataVal[$i]['StandByNet']}' ></td>";
						echo "<td ><input type='text' name ='remarks$i'  style ='width:80px' value='{$DataVal[$i]['remarks']}' ></td>"; 
					 echo "</tr>"; 
                                         }
				?>
               
                </tbody>
				</table>
                                    </div> 
                                <div class="form-group has-info has-feedback">
        <div class="col-sm-3">
            <div class="input-group">
                
                <input type='submit' class="btn btn-info" value="Save">
            </div>
        </div>
    </div>
			</div>
		</div>
	</div>
     <?php echo $this->Form->end(); ?>
</div>
<script>
function showdeatilsReports()
{
    var branch;

    branch = document.getElementById("ConnectivitieBranch").value;
    
    var url = 'branch_name='+branch;
    var xmlHttpReq = false;
    if (window.XMLHttpRequest)
    {
        xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttpReq.onreadystatechange = function()
    {
        if (xmlHttpReq.readyState == 4)
        {     //alert(xmlHttpReq.responseText);
            document.getElementById("oo").readOnly= true;
            document.getElementById('oo').innerHTML = xmlHttpReq.responseText;
	}
    }
    xmlHttpReq.open('POST','http://mascallnetnorth.in/ispark/Connectivities/save_doc1/?'+url,true);
    xmlHttpReq.send(null);
}
</script>