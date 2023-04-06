<?php

?>
<style>
    table td{margin: 5px;}
</style>
<script>
function getSubHeading()
{
    var HeadingId=$("#head").val();
  $.post("<?php echo $this->webroot;?>/ExpenseEntries/get_sub_heading",
            {
             HeadingId: HeadingId
            },
            function(data,status){
                var text='<option value="">Select</option>';
                var json = jQuery.parseJSON(data);
                for(var i in json)
                {
                    text += '<option value="'+i+'">'+json[i]+'</option>';
                }
                //alert(text);
                $("#subhead").empty();
                $("#subhead").html(text);
                
            });  
}
</script>

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
                    
                    <span>Imprest Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h3><?php echo $this->Session->flash(); ?></h3>
		<div class="form-group has-success has-feedback">
                 <?php echo $this->Form->create('Imprests',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Branch</label>
                        <div class="col-sm-4">

                        <?php	
                            echo $this->Form->input('branch_name', array('label'=>false,'class'=>'form-control','options' => $branch_master,'empty' => 'Select Branch','required'=>true));
                        ?>
                        </div>
                        
                    
                        <label class="col-sm-2 control-label">Head</label>
                        <div class="col-sm-4">

                        <?php	
                            echo $this->Form->input('head', array('label'=>false,'class'=>'form-control','options' => $head,'empty' => 'Select Head','id'=>'head','onChange'=>"getSubHeading()",'required'=>true));
                        ?>
                        </div>
                        
                        
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Sub Head</label>
                        <div class="col-sm-4">
                                <?php	
                                    echo $this->Form->input('subhead', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Select Sub Head','id'=>'subhead'));
                                ?>
                        </div>
                        <label class="col-sm-2 control-label">Unit Name</label>
                        <div class="col-sm-4">
                            <?php	
                                    echo $this->Form->input('ExpenceUnit', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'Expence Unit'));
                                ?>
                        </div>
                        
                        
                        
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-1">
                           <button class="btn btn-info btn-label-left">Save</button>
                        </div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                    </div>
		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" id="data">
                <table border="2" class="table">
                    <thead>
                        <tr>
                            
                            <th>Branch</th>
                           
                            <th>Exp. Head</th>
                            <th>Exp. SubHead</th>
                            <th>ExpenseUnit</th>
                            <th>Craete By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $Total=0;
                                foreach($UnitReport as $exp)
                                {
                                    echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$exp['tu']['Branch']."</td>";
                                        echo "<td>".$exp['hm']['HeadingDesc']."</td>";
                                        echo "<td>".$exp['shm']['SubHeadingDesc']."</td>";
                                        echo "<td>".$exp['tu']['ExpenseUnit']."</td>";
                                    echo "</tr>";
                                }
                        ?>
                    </tbody>
                </table>    
            
		

		
					
		
            <div class="clearfix"></div>
            <div class="form-group">
                    
            </div>
            </div>
        </div>
    </div>
</div>

