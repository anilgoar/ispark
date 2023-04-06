
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


<div class="box-content">

<?php 

echo $this->Form->create('Connectivities',array('class'=>'form-horizontal','action'=>'view')); 

?>
<div class="form-group has-info has-feedback">

 <?php
                         if(!empty($branchName))
                            {
                                
                                echo ' 
        <label class="col-sm-2 control-label">Branch</label>';
                                echo '<div class="col-sm-3">
            <div class="input-group">';
                                echo $this->Form->input('Branch',array('label'=>false,'options'=>$branchName,'class'=>'form-control','empty'=>'Select Branch','required'=>true));
                                echo ' <span class="input-group-addon"><i class="fa fa-group"></i></span></div>    
       
    </div>';
                        }
                        
                           
                        
                        ?>

 <label class="col-sm-2 control-label">Report Type</label>
        <div class="col-sm-3">
            <div class="input-group">
                
                <?php echo $this->Form->input('rtype',array('label' => false,'options'=>array('HardWare'=>'HardWare','Connectivity'=>'Connectivity','Mobile Data'=>'Mobile Data'),'class'=>'form-control','empty'=>'Select','id'=>'rtype')); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>    
        </div>
    
 </div>

		<div class="form-group has-info has-feedback">								
			
			<label class="col-sm-2 control-label">start date</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Sdate', array('type'=>'text','label'=>false,'class'=>'form-control','value'=>'','onclick'=>"displayDatePicker('data[Connectivities][Sdate]');",'placeholder'=>'Start Date','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div>
			<label class="col-sm-2 control-label">End date</label>
        <div class="col-sm-3">
            <div class="input-group">
                <?php	echo $this->Form->input('Edate', array('type'=>'text','label'=>false,'class'=>'form-control','value'=>'','onclick'=>"displayDatePicker('data[Connectivities][Edate]');",'placeholder'=>'End Date','required'=>true)); ?>
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
            </div>
        </div></div>
		<div class="clearfix"></div>
		<div class="form-group">
			<div class="col-sm-2">
                                <input type="submit" class="btn btn-info"  name='export' value="View" >
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>







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
					<span>Employee Details</span>
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
				<h4 class="page-header">View </h4>
				
               <?php      
                            if(!empty($Data))
        {
                                
                                if(($type=='HardWare'))
                                {
                                ?><div style="overflow: scroll;width: 100%;">
       <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom table-fixed" id="table_id">
            <tr>
                        <th >Devices Name</th>     
                       
                             <th >BranchName</th>   
                        <th >Owner</th>
                        <th >Working</th>
                        <th >Not working</th>
                        <th >Damage</th>
                        <th >Stand by </th>
                         <th >Stand by but notworking </th>
                         <th >Save Date </th>
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['hardware'];
                         echo "<tr>";
                        echo "<td>".$d['DeviceName']."</td>";
                                   
                                    echo "<td>".$d['BranchName']."</td>";
                                    echo "<td>".$d['Owner']."</td>";
                                   echo "<td>".$d['working']."</td>";
                                    echo "<td>".$d['NotWorking']."</td>";
                                   echo "<td>".$d['Damage']."</td>";
                                   
                                   echo "<td>".$d['StandBy']."</td>";
                                    echo "<td>".$d['StandByNet']."</td>";
                                   echo "<td>".$da[0]['SaveDataDate']."</td>";
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>


                                <?php } else if(($type=='Connectivity'))
                                {
                                ?><div style="overflow: scroll;width: 100%;">
       <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom table-fixed" id="table_id">
            <tr>
                        <th >Connectivity Type</th>     
                       
                             <th >Cunsumercode</th>   
                        <th >RelationshipNo</th>
                        <th >TariffPlan</th>
                        <th >BillingAddress</th>
                        <th >BillingPeriod</th>
                        <th >BillingType </th>
                         <th >Bandwidth </th>
                         
                          <th >PlanName</th>   
                        <th >Billdate</th>
                        <th >BillDuedate</th>
                        <th >securitydeposit</th>
                        <th >ContactPerson</th>
                        <th >MobileNo </th>
                         <th >Username </th>
                         
                         
                          <th >Ownership</th>   
                        <th >Rembursment</th>
                        <th >Branch</th>
                        <th >ActivePlan</th>
                        <th >ApprovedAmount</th>
                        <th >SaveDataDate </th>
                         
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['tbl_connectivity'];
                         echo "<tr>";
                        echo "<td>".$d['ConnectivityType']."</td>";
                                   
                                    echo "<td>".$d['Cunsumercode']."</td>";
                                    echo "<td>".$d['RelationshipNo']."</td>";
                                   echo "<td>".$d['TariffPlan']."</td>";
                                    echo "<td>".$d['BillingAddress']."</td>";
                                   echo "<td>".$d['BillingPeriod']."</td>";
                                   
                                   echo "<td>".$d['BillingType']."</td>";
                                    echo "<td>".$d['Bandwidth']."</td>";
                                  
                                   
                                   
                                   
                                    echo "<td>".$d['PlanName']."</td>";
                                    echo "<td>".$d['Billdate']."</td>";
                                   echo "<td>".$d['BillDuedate']."</td>";
                                    echo "<td>".$d['securitydeposit']."</td>";
                                   echo "<td>".$d['ContactPerson']."</td>";
                                   
                                   echo "<td>".$d['MobileNo']."</td>";
                                    echo "<td>".$d['Username']."</td>";
                                   echo "<td>".$d['Ownership']."</td>";
                                   
                                   
                                    echo "<td>".$d['Rembursment']."</td>";
                                    echo "<td>".$d['Branch']."</td>";
                                   echo "<td>".$d['ActivePlan']."</td>";
                                    echo "<td>".$d['ApprovedAmount']."</td>";
                                  
                                   echo "<td>".$da[0]['SaveDataDate']."</td>";
                                   
                                   
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>


 <?php } else if(($type=='Mobile Data'))
                                {
                                ?><div style="overflow: scroll;width: 100%;">
        <table class = "table table-striped table-bordered table-hover table-heading no-border-bottom table-fixed" id="table_id">
            <tr>
                        <th >Connectivity Type</th>     
                       
                             <th >Cunsumercode</th>   
                        <th >RelationshipNo</th>
                        <th >TariffPlan</th>
                        <th >BillingAddress</th>
                        <th >BillingPeriod</th>
                        
                         
                          <th >PlanName</th>   
                        <th >Billdate</th>
                        <th >BillDuedate</th>
                        <th >securitydeposit</th>
                        <th >ContactPerson</th>
                        <th >MobileNo </th>
                         <th >Username </th>
                         
                         
                          <th >Ownership</th>   
                        <th >Rembursment</th>
                        <th >Rembursment</th>
                        <th >ActivePlan</th>
                        <th >ApprovedAmount</th>
                        <th >SaveDataDate </th>
                         
                       
                        
            </tr>
            <?php
            


                

                    foreach($Data as $da):
                        
                        $d = $da['tbl_mobile'];
                         echo "<tr>";
                        echo "<td>".$d['ConnectivityType']."</td>";
                                   
                                    echo "<td>".$d['Cunsumercode']."</td>";
                                    echo "<td>".$d['RelationshipNo']."</td>";
                                   echo "<td>".$d['TariffPlan']."</td>";
                                    echo "<td>".$d['BillingAddress']."</td>";
                                   echo "<td>".$d['BillingPeriod']."</td>";
                                   
                                  
                                  
                                   
                                   
                                   
                                    echo "<td>".$d['PlanName']."</td>";
                                    echo "<td>".$d['Billdate']."</td>";
                                   echo "<td>".$d['BillDuedate']."</td>";
                                    echo "<td>".$d['securitydeposit']."</td>";
                                   echo "<td>".$d['ContactPerson']."</td>";
                                   
                                   echo "<td>".$d['MobileNo']."</td>";
                                    echo "<td>".$d['Username']."</td>";
                                   echo "<td>".$d['Ownership']."</td>";
                                   
                                   
                                    echo "<td>".$d['Rembursment']."</td>";
                                    echo "<td>".$d['Branch']."</td>";
                                   echo "<td>".$d['ActivePlan']."</td>";
                                    echo "<td>".$d['ApprovedAmount']."</td>";
                                  
                                   echo "<td>".$da[0]['SaveDataDate']."</td>";
                                   
                                   
                                   //echo "<td>".$da[0]['Importdate']."</td>";
                        echo "</tr>";
                       
        endforeach;
                      
?>
        </table>
                                </div>




        <?php }} ?>
			</div>
		</div>
	</div>
</div>
