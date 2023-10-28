<script>

function getBranch(BranchName){ 
    $.post("<?php echo $this->webroot;?>BusinessRules/getcostcenter",{BranchName:BranchName}, function(data){
        $("#CostCenter").html(data);
    });  
}

function SubmitForm()
{ 

    $("#msgerr").remove();

    var target_date       = $("#target_date").val();
    var growth_date       = $("#growth_date").val();
    var basic_date       = $("#basic_date").val();
    var deduction       = $("#deduction").val();
    

    if(target_date ===""){
        $("#target_date").focus();
        $("#target_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Target Date.</span>");
        return false;
    }
    else if(growth_date ===""){
        $("#growth_date").focus();
        $("#growth_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Growth Date.</span>");
        return false;
    }
    else if(basic_date ===""){
        $("#basic_date").focus();
        $("#basic_date").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Basic Date.</span>");
        return false;
    }
    else if(deduction ===""){
        $("#deduction").focus();
        $("#deduction").after("<span id='msgerr' style='color:red;font-size:11px;'>Please Select Deduction.</span>");
        return false;
    }
    
    else{

        $("#form1").submit();
    }
    
}

function Ruleaction(Id,Action)
{

    if(Action == "Delete")
    {
        if(confirm("Are you sure you want to delete this record?")){
            window.location="<?php echo $this->webroot;?>PliSystems/delete_rule?Id="+Id;
        }
    }else{
        if(confirm("Are you sure you want to Apply this Rule ?")){
            window.location="<?php echo $this->webroot;?>PliSystems/apply_rule?Id="+Id;
        }
    }
    return false;
   
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

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Performance Linked Incentive Rules</span>
		        </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
		    <div class="no-move"></div>
        </div>
           
            
            <div class="box-content box-con">
                
                <span><?php echo $this->Session->flash(); ?></span>
                <?php echo $this->Form->create('PliSystem',array('action'=>'rule','class'=>'form-horizontal','id'=>'form1')); ?>

                <div class="form-group">
                    <label class="col-sm-1 control-label">Target Date</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('target_date',array('label' => false,'empty'=>'Select','options'=>$options,'class'=>'form-control','id'=>'target_date','required'=>true)); ?>
                    </div>
                   

                    <label class="col-sm-1 control-label">Growth</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('growth_date',array('label' => false,'empty'=>'Select','options'=>$options,'class'=>'form-control','id'=>'growth_date','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">Basic</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('basic_date',array('label' => false,'empty'=>'Select','options'=>$options,'class'=>'form-control','id'=>'basic_date','required'=>true)); ?>
                    </div>

                    <label class="col-sm-1 control-label">Deduction(%)</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('deduction',array('label' => false,'class'=>'form-control','empty'=>'Select Deduction','options'=>$per_options,'id'=>'deduction','required'=>true)); ?>
                        <p><strong>Note:</strong> If achievement are not close by the reporting head.</p>
                    </div>
                    <h5 style="margin-left:28px;"><strong>Description:</strong> For instance, if you set the target date as November 23, it means you aim to accomplish the goal by October 23rd.</h5>

                </div> 
                <div class="form-group">
                    <div class="col-sm-1">
                        <input type="button" onclick="SubmitForm();" value="Submit" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                    </div>
                </div>              
                <?php echo $this->Form->end(); ?>
                 
                <?php if(!empty($plirule_arr)) {?>
                <div class="form-group" style="overflow-y:scroll;height:500px;">
                <h5><strong>Description:</strong> Apply one rule at a time; if another rule is applied, the previous one will be automatically disabled.</h5>
                    <table class = "table table-striped table-hover  responstable">     
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Target Date</th>
                                <th>Growth</th>
                                <th>Basic</th>
                                <th>Deduction</th>
                                <!-- <th>Status</th> -->
                                <th>Create Date</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody> 
                        
                            <?php $n=1; foreach($plirule_arr as $data){ ?>
                            <tr>
                                <td><?php echo $n++;?></td>
                                <td><?php echo $data['PliRule']['target_date'];?></td>
                                <td><?php echo $data['PliRule']['growth_date'];?></td>
                                <td><?php echo $data['PliRule']['basic_date'];?></td>
                                <td><?php echo $data['PliRule']['deduction'];?></td>
                                <!-- <td><?php// if($data['PliRule']['status'] == '1'){ echo "Applied" ; }else { echo "Not Apply" ;} ?></td> -->
                                <td><?php echo date_format(date_create($data['deduction']['created_at']),"d-M-Y");?></td>
                                <td style="text-align: center;">
                                <?php if($data['PliRule']['status'] == '1'){ ?>
                                    <!-- <i title="Delete" onclick="Ruleaction('<?php //echo $data['PliRule']['id'];?>');" style="font-size:20px;cursor: pointer;" class="material-icons">delete_forever</i> -->
                                    <p style='color:green;font-size:16px;'>Rule Applied</p>
                                <?php }else{?>
                                    <a href="#" style="font-size:16px;cursor: pointer;" onclick="Ruleaction('<?php echo $data['PliRule']['id'];?>','Apply');">Apply Rule</a> ||
                                    <i title="Delete" onclick="Ruleaction('<?php echo $data['PliRule']['id'];?>','Delete');" style="font-size:20px;cursor: pointer;" class="material-icons">delete_forever</i>
                                <?php } ?>
                                </td>
                            </tr>

                        <?php }?>
                        
                        </tbody>   
                    </table>
                </div>
                <?php }?>
            </div>

            
            
        </div>
    </div>	
</div>
