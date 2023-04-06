<?php ?>
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

<?php  
//echo $this->Html->script('ecr');
//echo $this->Html->script('assets/main/dialdesk');
?>
<style>
/*ECR View Tree CSS*/
 ul.ecrtree, ul.ecrtree ul {
    list-style: none;
     margin: 0;
     padding: 0;
   } 
   ul.ecrtree ul {
     margin-left: 10px;
   }
   ul.ecrtree li {
     margin: 0;
     padding: 0 7px;
     line-height: 20px;
     color: #369;
     /*font-weight: bold;*/
     border-left:1px solid rgb(100,100,100);

   }
   ul.ecrtree li:last-child {
       border-left:none;
   }
   ul.ecrtree li:before {
      position:relative;
      top:-0.3em;
      height:1em;
      width:12px;
      color:white;
      border-bottom:1px solid rgb(100,100,100);
      content:"";
      display:inline-block;
      left:-7px;
   }
   ul.ecrtree li:last-child:before {
      border-left:1px solid rgb(100,100,100);   
}
 .ecr ul.ecrtree li{
        padding:5px;
        color:#616161;
    }
</style>
<script>
 $(document).ready(function(){ 
    <?php if(isset($cms) && $cms !=""){?>
        showHide('<?php echo $cms;?>')
    <?php }?>
});

function showHide(id){
    var i;
    for(i=0;i<=4;i++){
        if(parseInt(i) == parseInt(id)){
            document.getElementById("addtype"+i).style.display="block"; 
        }
        else{
            document.getElementById("addtype"+i).style.display="none";
        }
    }
}

$(function () {
    $(".panel-color-list>li>span").click(function(e) {
        $(".panel").attr('class','panel').addClass($(this).attr('data-style'));
    }); 	
});

</script>

<script>
    
    // JavaScript Document
function ecr_category()
{
	var  parent = $('#EcrText').val();
	if(parent == '') return;

	var category = document.getElementById("EcrCategory").value;
		
	var option = document.createElement('option');
	option.value=parent;
	option.text = parent;	
	var x = document.getElementById("EcrCategory");
	x.add(option);
	
	var table = document.getElementById("table");
    var row = table.insertRow(1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = parent;
    cell2.innerHTML = category;		

}
function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
// for category 2 in second frameset
$(document).ready(function()
{$("#category2").on('change',function(){
	$.post("Ecrs/get_label2",{
		parent_id : $('#category2').val(),
		type : 'type1'
		},
		function(data,status){
			 $('#type1').replaceWith(data);
			})})});
//end

// for category 3 in third frameset
$(document).ready(function()
{$("#category3").on('change',function(){
	$.post("Ecrs/get_label2",{
		parent_id : $('#category3').val(),
		type : 'type2'
		},
		function(data,status){
			 $('#type2').replaceWith(data);
			 $('#sub_type1').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_type1" required="required" class="form-control"></select>');
			})})});
//end

// for category 4 in fourth frameset
$(document).ready(function()
{$("#category4").on('change',function(){
	$.post("Ecrs/get_label2",{
		parent_id : $('#category4').val(),
		type : 'type3'
		},
		function(data,status){
			 $('#type3').replaceWith(data);
			 $('#sub_type2').replaceWith('<select name="data[Ecr][sub_type1]" class="form-control" id="sub_type2" required="required"></select>');
			 $('#EcrSubType2').replaceWith('<select name="data[Ecr][sub_type2]" class="form-control" required="required" id="EcrSubType2"></select>');
			 $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" class="form-control" name="data[Ecr][sub_type2]"></select>');
			})})});

//end

// for type 2 in third frameset
$(document).ready(function()
{$("body").on('change',"#type2",function(){$.post("Ecrs/get_label3",{
		parent_id : $('#type2').val(),
		type : 'sub_type1'
		},
		function(data,status){
			 $('#sub_type1').replaceWith(data);
			})})});


// end


// for type 3 in fourth frameset
$(document).ready(function()
{$("body").on('change',"#type3",function(){$.post("Ecrs/get_label3",{
		parent_id : $('#type3').val(),
		type : 'sub_type2'
		},
		function(data,status){
			 $('#sub_type2').replaceWith(data);
			 $('#sub_type2_2').replaceWith('<select required="required" class="form-control" id="sub_type2_2" name="data[Ecr][sub_type2]"></select>');
			})})});

// end

// for sub type2 2 in fourth frameset
$(document).ready(function()
{$("body").on('change',"#sub_type2",function(){$.post("Ecrs/get_label4",{ 
		parent_id : $('#sub_type2').val(),
		type : 'sub_type2_2'
		},
		function(data,status){
			 $('#sub_type2_2').replaceWith(data);
			})})});

// end
</script>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="row">
             <div class=" <?php if(!empty($data)){?>col-md-7<?php }else{?>col-md-12<?php }?>"> 
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                            <h5>PROSPACT MANAGE USER</h5>
                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading" onclick="showHide('0')" style="cursor:pointer;" >
                                        <h5>LABEL 1</h5>
                                            <div class="panel-ctrls .ticker" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype0" style="display:none;" >                     
                                    <?php echo $this->Form->create('Ecr',array('action'=>'create_category',"class"=>"form-horizontal row-border",'data-parsley-validate' )); ?>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">User</label>
                                            <div class="col-sm-6">
                                                <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'options'=>$user,'empty'=>'Select User','required'=>true));?>
                                                <div class="textmessage"><?php if(isset($cms) && $cms =="0"){echo $this->Session->flash();}?></div>
                                            </div>
                                        </div>    
                                        <div class="form-group">
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-2">
                                                <input type="submit" value="Add" class="btn-web btn" />
                                            </div>
                                        </div>
                                    <?php echo $this->Form->end(); ?>  
                                </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading" onclick="showHide('1')" style="cursor:pointer;" >
                                            <h5>LABEL 2</h5>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype1" style="display:none;">
                                    <?php echo $this->Form->create('Ecr',array('action'=>'create_type',"class"=>"form-horizontal row-border")); ?>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">User</label>
                                            <div class="col-sm-6">
                                                <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select User','id'=>'category1','required'=>true,"class"=>"form-control"));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Add Sub User 1</label>
                                            <div class="col-sm-6">
                                                <?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'options'=>$user,'empty'=>'Select User 1','autofill'=>'false','required'=>true));?>
                                                <div class="textmessage"><?php if(isset($cms) && $cms =="1"){echo $this->Session->flash();}?></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-4"></div>
                                            <div class="col-sm-2">
                                                <input type="submit" value="ADD" class="btn-web btn" />
                                            </div>
                                        </div>

                                    <?php echo $this->Form->end(); ?>
                                </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading" onclick="showHide('2')" style="cursor:pointer;" >
                                            <h5>LABEL 3</h5>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                   <div class="panel-body" id="addtype2"  name="addtype2" style="display:none;" >
                <?php echo $this->Form->create('Ecr',array('action'=>'create_sub_type1',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">User</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,"class"=>"form-control",'options'=>$Category,'empty'=>'Select User','id'=>'category2','required'=>true));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 1</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('type',array('label'=>false,"class"=>"form-control",'options'=>'','id'=>'type1','required'=>true));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Add Sub User 2</label>
                        <div class="col-sm-6">
                          <?php echo $this->Form->input('sub_type1',array('label'=>false,"class"=>"form-control",'options'=>$user,'empty'=>'Select User 2','autofill'=>'false','required'=>true));?>  
                          <div class="textmessage"><?php if(isset($cms) && $cms =="2"){echo $this->Session->flash();}?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="ADD" >
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading" onclick="showHide('3')" style="cursor:pointer;" >
                                            <h5>LABEL 4</h5>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype3"  name="addtype3" style="display:none;" >
                <?php echo $this->Form->create('Ecr',array('action'=>'create_sub_type2',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Select User</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select User','id'=>'category3','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 1</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type2','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 2</label>
                        <div class="col-sm-6">
                          <?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type1','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 3</label>
                        <div class="col-sm-6">
                          <?php echo $this->Form->input('sub_type2',array('label'=>false,'id'=>'rds','options'=>$user,'empty'=>'Select User 3','autofill'=>'false','required'=>true,"class"=>"form-control"));?>
                            <div class="textmessage"><?php if(isset($cms) && $cms =="3"){echo $this->Session->flash();}?></div>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="ADD" >
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                                    <div class="panel-heading" onclick="showHide('4')" style="cursor:pointer;" >
                                            <h5>LABEL 5</h5>
                                            <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'>
                                                
                                            </div>
                                    </div>
                                    <div class="panel-body" id="addtype4" style="display:none;" >
                                       
                <?php echo $this->Form->create('Ecr',array('action'=>'create_sub_type3',"class"=>"form-horizontal row-border")); ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">User</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('category',array('label'=>false,'options'=>$Category,'empty'=>'Select User','id'=>'category4','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 1</label>
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'type3','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 2</label>
                        <div class="col-sm-6">
                          <?php echo $this->Form->input('sub_type1',array('label'=>false,'options'=>'','id'=>'sub_type2','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sub User 3</label>
                        <div class="col-sm-6">
                          <?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'sub_type2_2','required'=>true,"class"=>"form-control"));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Add Sub User 4</label>
                        <div class="col-sm-6">
                          <?php echo $this->Form->input('sub_type3',array('label'=>false,'options'=>$user,'empty'=>'Select User 4','autofill'=>'false','required'=>true,"class"=>"form-control"));?>
                          <div class="textmessage"><?php if(isset($cms) && $cms =="4"){echo $this->Session->flash();}?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-2">
                          <input type="submit" class="btn-web btn"  value="ADD" >
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        <?php if(!empty($data)){?>
            <div class="col-xs-5">
                <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                    <div class="panel-heading">
                        <h5>PROSPACT MANAGE USER</h5>
                        <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                    </div>
                    <div class="panel-body">
                        <div class="ecr">
                        <ul class="ecrtree" >					                     
                        <?php 
                        foreach($data as $post1): 
                            if($post1['ClientCategory']['Label']==1){?>
                            <li><?php echo $post1['ClientCategory']['ecrName'];?>
                                <!--
                                <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#catdiv1" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                -->
                                <a href="<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post1['ClientCategory']['id'];?>" class="delete-ecr-icon" onclick="return confirm('Are you sure you want to delete this item?')" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                    <ul class="ecrtree" >
                                        <?php
                                        foreach($data as $post2):
                                            if($post2['ClientCategory']['Label']==2 && $post2['ClientCategory']['parent_id']==$post1['ClientCategory']['id']){?>
                                                <li><?php echo $post2['ClientCategory']['ecrName']."";?>
                                                     <!--
                                                    <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#catdiv2" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><div class="ripple-container"></div></label></a>
                                                    -->
                                                     <a href="<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post2['ClientCategory']['id'];?>" class="delete-ecr-icon"  onclick="return confirm('Are you sure you want to delete this item?')" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>
                                                    <ul class="ecrtree" >
                                                        <?php
                                                        foreach($data as $post3):
                                                            if($post3['ClientCategory']['Label']==3 && $post3['ClientCategory']['parent_id']==$post2['ClientCategory']['id']){?>
                                                                <li> 
                                                                    <?php echo $post3['ClientCategory']['ecrName']."";?>         	
                                                                     <!--
                                                                    <a href="#" class="edit-ecr-icon" data-toggle="modal" data-target="#catdiv3" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                    -->
                                                                     <a href="<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post3['ClientCategory']['id'];?>" class="delete-ecr-icon"  onclick="return confirm('Are you sure you want to delete this item?')" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>                               
                                                                    <ul class="ecrtree" >
                                                                        <?php
                                                                        foreach($data as $post4):
                                                                            if($post4['ClientCategory']['Label']==4 && $post4['ClientCategory']['parent_id']==$post3['ClientCategory']['id']){?>                                              
                                                                                <li>
                                                                                    <?php  echo $post4['ClientCategory']['ecrName'];?> 
                                                                                    <!--
                                                                                    <a href="#" data-toggle="modal" data-target="#catdiv4" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                                    -->
                                                                                    <a href="<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post4['ClientCategory']['id'];?>"  onclick="return confirm('Are you sure you want to delete this item?')" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a><br/>                                                                  
                                                                                    <ul class="ecrtree" >  
                                                                                        <?php
                                                                                        foreach($data as $post5): 
                                                                                            if($post5['ClientCategory']['Label']==5 && $post5['ClientCategory']['parent_id']==$post4['ClientCategory']['id']){?>                                                                                        
                                                                                                <li><?php echo $post5['ClientCategory']['ecrName'];?>                                                                                        
                                                                                                     <!--
                                                                                                    <a href="#" data-toggle="modal" data-target="#catdiv5" > <label class="btn btn-xs btn-midnightblue btn-raised"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <div class="ripple-container"></div></label></a>
                                                                                                    -->
                                                                                                     <a href="<?php echo $this->webroot;?>Ecrs/delete_ecr?id=<?php echo $post5['ClientCategory']['id'];?>"  onclick="return confirm('Are you sure you want to delete this item?')" ><label class="btn btn-xs tn-midnightblue btn-raised"><i class="fa fa-trash"></i></label></a>
                                                                                                </li>                                       
                                                                                        <?php }endforeach;?>      
                                                                                    </ul>
                                                                                </li>
                                                                        <?php }endforeach;?>
                                                                    </ul>
                                                                </li>
                                                        <?php }endforeach;?>									
                                                    </ul>
                                                </li>
                                            <?php }endforeach;?>
                                        </ul>
                                    </li>
                            <?php }endforeach;?>
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

            

    
            
        </div>
        

    </div>
</div>

<script>
function submitForm(form,path,msg,id){
    var formData = $(form).serialize(); 
    $.post(path,formData).done(function(data){
        $("#"+id).trigger('click');
        $("#show-ecr-message").trigger('click');
        $("#ecr-text-message").text(msg+' update successfully.');
    });
    return true;
}

function hidepopup(){
    location.reload(); 
}
</script>
<!--
<a class="btn btn-primary btn-lg" id="show-ecr-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
-->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                        <!--
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        -->
                    </div>
                    <div class="modal-body">
                        <p id="ecr-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
    </div>
</div>

<div class="modal fade" id="catdiv1"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5  class="modal-title">LABEL 1</h5>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_category',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">
                                   <?php 	
                                    $editcategory = isset($ecrcat1[1])?$ecrcat1[1]:'';
                                    if(!empty($editcategory))
                                    {
                                        $loop = explode('==>',$editcategory);
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                        {
                                            $row = explode('=>',$loop[$i]);
                                            
                                            
                                            
                                            echo '<div class="form-group">';
                                            echo '<label class="col-sm-3 control-label">Scenarios</label>';
                                            echo '<div class="col-sm-6">';
                                            echo $this->Form->input($row[0],array('label'=>false,'value'=>$row[1],'options'=>$user,'required'=>true,'class'=>'form-control'));
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                ?>

                                <?php }?>


                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat1" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_category','Scenarios','close-cat1')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat2").on('change',function(){
        $.post("Ecrs/edit_label2",{parent_id : $('#cat2').val()},function(data,status){
            $('#abc').html(data);
        });
    });
});
</script>

<!-- Category Div 2 -->
<div class="modal fade" id="catdiv2"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">LABEL 2</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_type',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                 <?php 	
                                    $editcategory = isset($ecrcat2[1])?$ecrcat2[1]:'';
                                    if(!empty($editcategory))
                                    {
                                ?>
                                <?php	$loop = explode('==>',$editcategory);
                                        $options = '';
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                        {
                                            $row = explode('=>',$loop[$i]);
                                            $options[$row[0]] = $row[1]; 
                                        }
                                        echo '<div class="form-group">';
                                        echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                            echo '<div class="col-sm-6">';
                                            echo $this->Form->input('category',array('label'=>false,'id'=>'cat2','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                            echo "</div>";
                                        echo "</div>";
                                ?>
                                <div id="abc"></div>
               
                                <?php }?>                
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat2" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_type','Sub Scenarios 1','close-cat2')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat3").on('change',function(){
	$.post("Ecrs/edit_label2_sub1",{parent_id : $('#cat3').val(),type : 'edittype1'},function(data,status){ 
            $('#edittype1').replaceWith(data);
            $('#abd').html("");	
	});
    });
    
    $('body').on('change',"#edittype1",function(){
	$.post("Ecrs/edit_label3",{parent_id : $('#edittype1').val()},function(data,status){
            $('#abd').html(data);
        });
    });

});


</script>

<!-- Category Div 3 -->
<div class="modal fade" id="catdiv3"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 2</h2>       
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_sub_type1',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                                <?php 	
                                $editcategory = isset($ecrcat3[1])?$ecrcat3[1]:'';
                                  if(!empty($editcategory))
                                    { ?>
                                <?php				
                                    $loop = explode('==>',$editcategory);
                                    $options = '';
                                    $count = count($loop);
                                    for($i = 0; $i<$count; $i++)
                                    {
                                        $row = explode('=>',$loop[$i]);
                                        $options[$row[0]] = $row[1]; 
                                    }
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                echo '<div class="col-sm-6">';
                                echo $this->Form->input('category',array('label'=>false,'id'=>'cat3','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                echo "</div>";
                                echo "</div>";
                                  ?>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Select Sub Scenarios 1</label>
                                        <div class="col-sm-6">
                                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'edittype1','required'=>true,'class'=>'form-control'));?>
                                        </div>
                                    </div>
                                    <div id="abd"></div>
                                
                                <?php }?>               
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat3" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_sub_type1','Sub Scenarios 2','close-cat3')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("body").on('change',"#cat4",function(){
	$.post("Ecrs/edit_label2_sub1",{parent_id : $('#cat4').val(),type : 'typ2'},function(data,status){
            $('#typ2').replaceWith(data);
            $('#sub_typ1').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_typ1" required="required" class="form-control"></select>');
            $('#abe').html('');
        });
    });
    
    $('body').on('change',"#typ2",function(){
	$.post("Ecrs/edit_label2_sub2",{parent_id : $('#typ2').val(),type : 'sub_typ1'},function(data,status){
            $('#sub_typ1').replaceWith(data);
            $('#abe').html("");
        });
    });
    
    $('body').on('change',"#sub_typ1",function(){
        $.post("Ecrs/edit_label3_sub1",{parent_id : $('#sub_typ1').val()},function(data,status){
            $('#abe').html(data);			 
        });
    });
    
});




</script>

<!-- Category Div 4 -->
<div class="modal fade" id="catdiv4"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 3</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_sub_type2',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                               
                              <?php 	
                                $editcategory = isset($ecrcat4[1])?$ecrcat4[1]:'';
                                  if(!empty($editcategory))
                                    {
                                        $loop = explode('==>',$editcategory);
                                        $options = '';
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                        {
                                            $row = explode('=>',$loop[$i]);
                                            $options[$row[0]] = $row[1];
                                        }
                                        echo '<div class="form-group">';
                                        echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                        echo '<div class="col-sm-6">';
                                        echo $this->Form->input('category',array('label'=>false,'id'=>'cat4','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                        echo "</div>";
                                        echo "</div>";
                                  ?>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Select Sub Scenarios 1</label>
                                            <div class="col-sm-6">
                                            <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'typ2','required'=>true,'class'=>'form-control'));?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Select Sub Scenarios 2</label>
                                            <div class="col-sm-6">
                                            <?php echo $this->Form->input('sub_type',array('label'=>false,'options'=>'','id'=>'sub_typ1','required'=>true,'class'=>'form-control'));?>
                                            </div>
                                        </div>
                                        <div id="abe"></div>
                                <?php }?>              
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat4" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_sub_type2','Sub Scenarios 3','close-cat4')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#cat5").on('change',function(){
	$.post("Ecrs/edit_label2_sub1",{parent_id : $('#cat5').val(),type : 'typ3'},function(data,status){
             $('#typ3').replaceWith(data);
             $('#sub_type2').replaceWith('<select name="data[Ecr][sub_type1]" id="sub_type2" required="required" class="form-control"></select>');
             $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]" class="form-control"></select>');
             $('#abf').html("");
        });
    });
    
    $('body').on('change',"#typ3",function(){
	$.post("Ecrs/edit_label2_sub2",{parent_id : $('#typ3').val(),type : 'editsub_type2'},function(data,status){
            $('#editsub_type2').replaceWith(data);
            $('#abf').html("");
            $('#sub_type2_2').replaceWith('<select required="required" id="sub_type2_2" name="data[Ecr][sub_type2]" class="form-control"></select>');
        });
    });
    
    $('body').on('change',"#editsub_type2",function(){
	$.post("Ecrs/edit_label3_sub2",{parent_id : $('#editsub_type2').val(),type : 'editsub_type2_2'},function(data,status){
            $('#editsub_type2_2').replaceWith(data);
            $('#abf').html("");
        });
    });
    
    $('body').on('change',"#editsub_type2_2",function(){
	$.post("Ecrs/edit_label4_sub1",{parent_id : $('#editsub_type2_2').val()},function(data,status){
            $('#abf').html(data);			 
        });
    });

    
});




</script>

<!-- Category Div 4 -->
<div class="modal fade" id="catdiv5"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2  class="modal-title">Update Sub Scenarios 4</h2>      
            </div>
            <?php echo $this->Form->create('Ecrs',array('action'=>'update_sub_type3',"class"=>"form-horizontal row-border")); ?>           
                <div class="modal-body">
                    <div class="panel-body detail">
                        <div class="tab-content">
                            <div class="tab-pane active" id="horizontal-form">          
                               
                              <?php 	
                                $editcategory = isset($ecrcat5[1])?$ecrcat5[1]:'';
                                if(!empty($editcategory))
                                { ?>
                                <?php				
                                        $loop = explode('==>',$editcategory);
                                        $options = '';
                                        $count = count($loop);
                                        for($i = 0; $i<$count; $i++)
                                                {
                                                        $row = explode('=>',$loop[$i]);
                                                        $options[$row[0]] = $row[1]; 
                                                }
                                        echo '<div class="form-group">';
                                        echo '<label class="col-sm-3 control-label">Select Scenarios</label>';
                                        echo '<div class="col-sm-6">';
                                        echo $this->Form->input('category',array('label'=>false,'id'=>'cat5','options'=>$options,'empty'=>'Select Scenarios','required'=>true,'class'=>'form-control'));
                                        echo "</div>";
                                        echo "</div>";
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Sub Scenarios 1</label>
                                    <div class="col-sm-6">
                                    <?php echo $this->Form->input('type',array('label'=>false,'options'=>'','id'=>'typ3','required'=>true,'class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Sub Scenarios 2</label>
                                    <div class="col-sm-6">
                                    <?php echo $this->Form->input('sub_type',array('label'=>false,'options'=>'','id'=>'editsub_type2','required'=>true,'class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Select Sub Scenarios 3</label>
                                    <div class="col-sm-6">
                                    <?php echo $this->Form->input('sub_type2',array('label'=>false,'options'=>'','id'=>'editsub_type2_2','required'=>true,'class'=>'form-control'));?>
                                    </div>
                                </div>
                                <div id="abf"></div>
                            <?php }?>              
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-cat5" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" onclick="return submitForm(this.form,'<?php echo $this->webroot;?>Ecrs/update_sub_type3','Sub Scenarios 4','close-cat5')"  value="Submit" class="btn-web btn">
                </div>
            <?php echo $this->Form->end(); ?>   
        </div>
    </div>
</div>



<!--

<div class="row">
	<div class="col-xs-12">
            <form method="post">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Prospect List</span>
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
                    
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom"  id="table_id">
				<?php  $i=0; ?>
					<thead>
						<tr class="active">
                                                    <td align="center"><input type="checkbox" value="1" name="checkAll" id="checkAll" onclick="checkAllBox()" /><b>Sr. No.</b></td>
                                                        <td align="center"><b>Client Name</b></td>
							<td align="center"><b>Product Name</b></td>
                                                        <td align="center"><b>Introduction</b></td>
                                                        <td align="center"><b>Contact No</b></td>
                                                        <td align="center"><b>Email</b></td>
                                                        <td align="center"><b>Address</b></td>
                                                        <td align="center"><b>Remarks</b></td>
						</tr>
					</thead>
                                        <tbody>

						<?php foreach ($sales_master as $post): ?>
						<tr class="<?php   $i++;?>">
                                                    <td align="center"><input type="checkbox" name="check[]" value="<?php echo $post['sc']['Id']; ?>" id="check<?php echo $post['sc']['Id']; ?>" /><?php echo $i; ?></td>
                                                        <td align="center"><?php echo $this->Html->link($post['sc']['ClientName'],array('controller'=>'Addproducts','action'=>'create_cover','?'=>array('Id'=>$post['sc']['Id']),'full_base' => true)); ?></td>
							<td align="center"><?php echo $post['sp']['ProductName']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Introduction']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['ContactNo']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Email']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Address']; ?></td>
                                                        <td align="center"><?php echo $post['sc']['Remarks']; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php unset($sales_master); ?>
					</tbody>
				</table>
			</div>
                    
                    
		</div>
            <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-4">
                            <button value="Approve" name="Approve" class="btn btn-primary">Approve</button>
                            <button value="DisApprove" name="DisApprove" class="btn btn-primary">DisApprove</button>
                        </div>
                        
                    </div>
            </form>
	</div>
</div>

-->





<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#table_id').dataTable();
    });
</script>