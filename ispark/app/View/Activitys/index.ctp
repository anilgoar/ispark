
<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script>
        function getData(val)
        {
            //alert(val);
            $.post("Activitys/save_doc",{branch_name:val},function(data)
            {$("#nn").html(data);});
            getData1(val);
        }
         function getData1(val)
        {
            //alert(val);
            $.post("Activitys/view",{branch_name:val},function(data)
            {$("#mm").html(data);});
        }
        
         function deleteimage(val,emp,file)
        {
          var x = confirm("Are you sure you want to delete?");
  if (x) { 
            window.location='http://192.168.137.230/ispark/Activitys/deletefile?path='+val;
        }
        else{
            return false;
        }

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
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
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
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Activity Entry</span>
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
<div class="box-content box-con">
    <h4 class="textClass"><?php echo $this->Session->flash(); ?></h4>

    <?php echo $this->Form->create('Activitys',array('class'=>'form-horizontal','action'=>'index')); ?>
     <div class="form-group">
                    <label class="col-sm-2 control-label">Branch</label>
                    <div class="col-sm-3">
                        <select name="Branch" id="Title" class="form-control" required="">
                            <option value=''>Select Branch</option>
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['BranchName']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['BranchName'] ?>" ><?php echo $bc['Actdata']['BranchName'] ?></option>
                            <?php   }} ?>
                           
                           
                        </select>
                    </div>
                    <div id="mm">
                        <label class="col-sm-2 control-label">Group</label>
                    <div class="col-sm-3">
                        <select name="Group" id="EmpType" class="form-control"  >
                           
                            <option value="">Select</option>
                           
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['Group']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Group'] ?>" ><?php echo $bc['Actdata']['Group'] ?></option>
                            <?php   }} ?> </select>
                  
                       
                    </div> </div>
                    </div>
     <div id="nn"><div class="form-group">
                    <label class="col-sm-2 control-label">Client</label>
                    <div class="col-sm-3">
 <select name="Client" id="Client" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['Client']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Client'] ?>" ><?php echo $bc['Actdata']['Client'] ?></option>
                            <?php  } } ?> </select>
                    </div>

                    <label class="col-sm-2 control-label">Project</label>
                    <div class="col-sm-3">
                       <select name="Project" id="Project" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['Project']!='') {  ?>
                               <option value="<?php echo $bc['Actdata']['Project'] ?>" ><?php echo $bc['Actdata']['Project'] ?></option>
                            <?php  } } ?> </select>
                    </div>
 </div>

               <div class="form-group">
                    <label class="col-sm-2 control-label">Module</label>
                    <div class="col-sm-3">
                       <select name="Module" id="Module" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['Module']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Module'] ?>" ><?php echo $bc['Actdata']['Module'] ?></option>
                            <?php  } } ?> </select>
                    </div>

                    <label class="col-sm-2 control-label">Activity</label>
                    <div class="col-sm-3">
                        <select name="Activity" id="Activity" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ if($bc['Actdata']['Activity']!='') { ?>
                               <option value="<?php echo $bc['Actdata']['Activity'] ?>" ><?php echo $bc['Actdata']['Activity'] ?></option>
                            <?php   }} ?> </select>
                    </div>
                 </div>
                    </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">Remarks</label>
                    <div class="col-sm-9">
                        <textarea name="Remarks" id ='Remarks'  required="" rows="5" cols="80"></textarea>
                    </div>
                     </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">Time</label>
                    <div class="col-sm-3">
                        <select name="Time" id="Time" class="form-control"  required="">
                            <option value="">Select</option>
                            <option value="00:15">00:15</option>
                            <option value="00:30">00:30</option>
                            <option value="00:45">00:45</option>
                            <option value="01:00">01:00</option>
                            <option value="01:15">01:15</option>
                            <option value="01:30">01:30</option>
                            <option value="01:45">01:45</option>
                            <option value="02:00">02:00</option>
                            <option value="02:15">02:15</option>
                            
                            <option value="02:30">02:30</option>
                            <option value="02:45">02:45</option>
                            <option value="03:00">03:00</option>
                            <option value="03:15">03:15</option>
                            <option value="03:30">03:30</option>
                            <option value="03:45">03:45</option>
                            <option value="04:00">04:00</option>
                            <option value="04:15">04:15</option>
                            <option value="04:30">04:30</option>
                            
                             
                            <option value="04:45">04:45</option>
                            <option value="05:00">05:00</option>
                            <option value="05:15">05:15</option>
                            <option value="05:30">05:30</option>
                            <option value="05:45">05:45</option>
                            <option value="06:00">06:00</option>
                            <option value="06:15">06:15</option>
                            <option value="06:30">06:30</option>
                             <option value="06:45">06:45</option>
                             
                             <option value="07:00">07:00</option>
                            <option value="07:15">07:15</option>
                            <option value="07:30">07:30</option>
                            <option value="07:45">07:45</option>
                            <option value="08:00">08:00</option>
                            <option value="08:15">08:15</option>
                            <option value="08:30">08:30</option>
                             <option value="08:45">08:45</option>
                             
                             <option value="09:00">09:00</option>
                            <option value="09:15">09:15</option>
                            <option value="09:30">09:30</option>
                            <option value="09:45">09:45</option>
                            <option value="10:00">10:00</option>
                            <option value="10:15">10:15</option>
                            <option value="10:30">10:30</option>
                             <option value="10:45">10:45</option>
                        </select>
                    </div>
                </div>
                
                
    
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-3">
            
                <input type='submit' class="btn btn-info" value="Save" style="margin-left:109px;">
            
        </div>
    </div>
   
    <div class="clearfix"></div>
   
    <?php echo $this->Form->end(); ?>
    
</div></div></div></div>
  

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Entry Details</span>
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
			
				<h4 class="page-header">Entry Details</h4>
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <thead>
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Branch</th>
                    	<th>Group</th>
                    	<th>Client</th>
                    	<th>Project</th>
                    	<th>Module</th>
                        <th>Activity</th>
                        <th>Time</th>
                       
                        <th>Edit</th>
                         <th>Delete</th>
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
              // print_r($find);die;
                $it=0;
					 foreach($find as $post):
//print_r($post);die;                   
                                             //$imagepath=$show.$post['Docfile']['filename'];
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
						echo "<td align=\"center\">".$post['Act']['Branch']."</td>";
						echo "<td>".$post['Act']['Group']."</td>";
						echo "<td>".$post['Act']['Client']."</td>";
						echo "<td>".$post['Act']['Project']."</td>";
                                                echo "<td>".$post['Act']['Module']."</td>";
						echo "<td>".$post['Act']['Activity']."</td>";
                                                 echo "<td>".$post['Act']['SpentTime']."</td>";
                                                 $dt=explode(':',$post['Act']['SpentTime']);
						$dtm=$dt[0]*60+$dt[1];
                                               $it=$it+$dtm
//						?>
                <td><a href="javascript:void(0)" onclick="window.open('Activitys/edit?Id=<?php echo $post['Act']['id'];?>','mmm','width=600,left=600,height=600,top=100')">Edit</a></td>
<?php echo "<td onclick=\"deleteimage('{$post['Act']['id']}')\"><a href = '#' style = 'color:red;'>Delete</a></td>";
                                               
                                               
					 echo "</tr>";
                                         
					 endforeach;
                                         $minutes1 = $it % 60;
$hours1 = ($it - $minutes1) / 60;
$minutes1 = ($minutes1<10?"0".$minutes1:"".$minutes1);

$hours1 = ($hours1<10?"0".$hours1:"".$hours1);

 $Totalh = ($hours1>0?$hours1.":":"00:").($minutes1>0?$minutes1.":":"00");
                                         echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>Grand Total</td>";
						echo "<td align=\"center\"></td>";
						echo "<td></td>";
						echo "<td></td>";
						echo "<td></td>";
                                                echo "<td></td>";
						echo "<td></td>";
                                                 echo "<td>".$Totalh."</td>";
                                                 echo "<td></td>";
                                                echo "<td></td>";
                                                echo "</tr>";
				?>
                </tbody>
				</table>
			</div>
		</div>
	</div>
</div>