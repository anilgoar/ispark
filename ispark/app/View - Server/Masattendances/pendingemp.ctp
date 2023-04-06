<?php

?>
<style>
    table td{margin: 5px;}
</style>

<script>
     $(document).ready(function(){
    $("#select_all1").change(function(){  //"select all" change
        $(".checkbox1").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.checkbox1').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all1").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox1:checked').length == $('.checkbox1').length ){
            $("#select_all1").prop('checked', true);
        }
    });
});
    
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

function Design(val){
    $.post("get_design",{val},function(data){
    $('#tower').html(data);});
}


function getdept(Department,id){
    $.post("<?php echo $this->webroot;?>Masattendances/getdept",{'Department':$.trim(Department)},function(data){
        $("#"+id).html(data);
    });
}

function delEmptyEmp(Id,BioCode){
    if(confirm('Are you sure you want to delete this record?')){
        window.location="<?php echo $this->webroot;?>Masattendances/delete_empty_emply?Id="+Id+"&BioCode="+BioCode;
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
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>EMPLOYEE PENDING</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
            </div>
            <div class="box-content">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('Show',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                <div class="form-group has-info has-feedback">
                    <label class="col-sm-1 control-label">Branch</label>
                    <div class="col-sm-3">
                        <?php echo $this->Form->input('branch',array('label'=>false,'options'=>$branchName,'empty'=>'Select','required'=>true,'class'=>'form-control')); ?>  
                    </div>
              
                    <label class="col-sm-2 control-label">Biometric Code</label>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('BiometricCode',array('label' => false,'class'=>'form-control','id'=>'month')); ?>
                    </div>  
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <button type="Show" class="btn btn-primary pull-right btn-new"> Show </button>
                    </div>
                </div>
                
                <?php if(!empty($data)){?>
                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="box-header"><div class="box-name"><span>EMPLOYEE DETAILS</span></div></div>
                
                        <table class = "table table-striped table-hover  responstable">     
                            <thead>
                                <tr>
                                    <th style="text-align: center;width:30px;" >&#10004;</th>
                                    <th style="text-align: left;width:50px;">Sr.No</th>
                                    <th style="text-align: left;width:70px;">BioCode</th>
                                    <th>Emp Name</th>
                                    <th style="text-align: left;width:50px;" >Trainig</th>
                                </tr>
                            </thead>
                            <tbody>         
                            <?php $i=1; foreach ($data as $post): 
                                //print_r($BioArr);
                                
                            echo $this->Form->input('EmpName', array('type'=>'hidden','label'=>false,'class'=>'form-control','value'=>$post['Attandence']['EmpName'],'placeholder'=>'EmplrPF.'));  ?>
                            <?php if(!in_array($post['Attandence']['BioCode'], $BioCodeArr)){ ?>
                            <tr>
                                <td style="text-align: center;"><input type="checkbox" class="checkbox"  name="check[]" value="<?php echo $post['Attandence']['BioCode']; ?>"></td>
                                <td style="text-align: center;"><?php echo $i++; ?></td>
                                <td><?php echo $post['Attandence']['BioCode']; ?></td>
                                <td><?php echo $post['Attandence']['EmpName']; ?></td>
                                <td style="text-align: center;"><input type="checkbox" class="checkbox1"  name="check1[]" value="<?php echo $post['Attandence']['BioCode']; ?>"></td>
                            </tr>
                            <?php }?>
                            <?php $array[] = $id; endforeach;  ?>                 
                            </tbody>   
                        </table>
                    </div>

                    <div class="col-xs-6 col-sm-6">
                        <div class="box-header"><div class="box-name"><span>PROCESS DETAILS</span></div></div>
                        <div class="form-group has-info has-feedback">
                            <label class="col-sm-2 control-label"> CostCenter </label>
                            <div class="col-sm-6">
                                <?php echo $this->Form->input('CostCenter',array('label' => false,'options'=>$tower1,'empty'=>'Select Cost Center','class'=>'form-control','id'=>'CostCenter','required'=>true)); ?>
                            </div> 
                        </div>
                        
                        <div class="form-group has-info has-feedback">
                            <label class="col-sm-2 control-label"> Department </label>
                            
                            <div class="col-sm-6">
                                <select name="data[Show][Department]" required="" id="Dept" class="form-control" onchange="getdept(this.value,'Desgination')"   >
                                    <option value="" >Select</option>
                                    <?php foreach($dep as $val){?>
                                    <option value="<?php echo $val;?>" ><?php echo $val;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
        
                        <div class="form-group has-info has-feedback">
                            <label class="col-sm-2 control-label"> Designation </label>
                            <div class="col-sm-6">
                                <select name="data[Show][Designation]" required="" id="Desgination" class="form-control">
                                    <option value="" >Select</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group has-info has-feedback">
                            <div class="col-sm-8">
                                <button type="Save" value="SaveCode" class="btn btn-primary pull-right btn-new">Save</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <?php }?>
                <?php if(!empty($data1)){?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="box-header"><div class="box-name"><span>EMPLOYEE DETAILS</span></div></div>
                        <table class = "table table-striped table-hover  responstable">     
                            <thead>
                                <tr>
                                    <th style="text-align:center;" >BioCode</th>
                                    <th style="text-align:center;">Name</th>
                                    <th style="text-align:center;">CostCenter</th>
                                    <th style="text-align:center;">Department</th>
                                    <th style="text-align:center;">Degination</th>
                                    <th style="text-align:center;">TrainningStatus</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                            <?php foreach ($data1 as $post): ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $post['mas_Jclrentrydata']['BioCode']; ?></td>
                                <td style="text-align:center;"><?php echo $post['mas_Jclrentrydata']['EmpName']; ?></td>
                                <td style="text-align:center;"><?php echo $post['mas_Jclrentrydata']['CostCenter']; ?></td>
                                <td style="text-align:center;"><?php echo $post['mas_Jclrentrydata']['DepartMent']; ?></td>
                                <td style="text-align:center;"><?php echo $post['mas_Jclrentrydata']['Degination']; ?></td>
                                <td style="text-align:center;"><?php echo $post['mas_Jclrentrydata']['TrainningStatus']; ?></td>
                                <td style="text-align:center;"><i class="material-icons" style="font-size:20px;cursor: pointer;" onclick="delEmptyEmp('<?php echo $post['mas_Jclrentrydata']['Id']; ?>','<?php echo $post['mas_Jclrentrydata']['BioCode'];?>');">delete_forever</i></td>
                            </tr>
                            <?php $array[] = $id; endforeach;  ?>            
                            </tbody>   
                        </table>
                    </div>
                </div>
                <?php }?>
                
                <?php echo $this->Form->end(); ?>
            </div>       
        </div>
    </div>
</div>

