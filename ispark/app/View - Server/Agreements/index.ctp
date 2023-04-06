<script>
function getCostCenter(val)
{
    $.post("Agreements/get_costcenter",{branch_name:val},function(data)
    {$("#cost_center").html(data);});
    
}

function write_costcenter()
{
    var text = "";
    var selText = "";
    //var arr = $('#AgreementCostCenter').val();
    //alert(text+arr);
    $("#AgreementCostCenter option:selected").each(function () {
   var $this = $(this);
   if ($this.length) 
   {
        if(selText!='')
        {selText +=", ";}
        selText += $this.text();
    //console.log(selText);
   }
});
    //alert(text+selText);
    $("#cost_center_text").html(text+selText);
}

function checkall1()
{
    if(document.getElementById("check1").checked)
    {
    $("#esc2InternalTo").val($("#esc1InternalTo").val());
    $("#esc2InternalCc").val($("#esc1InternalCc").val());
    $("#esc2InternalBc").val($("#esc1InternalBc").val());
    $("#esc2ExternalTo").val($("#esc1ExternalTo").val());
    $("#esc2ExternalCc").val($("#esc1ExternalCc").val());
    $("#esc2ExternalBc").val($("#esc1ExternalBc").val());
    }
    else
    {
    $("#esc2InternalTo").val('');
    $("#esc2InternalCc").val('');
    $("#esc2InternalBc").val('');
    $("#esc2ExternalTo").val('');
    $("#esc2ExternalCc").val('');
    $("#esc2ExternalBc").val('');
    }
}

function checkall2()
{
    if(document.getElementById("check2").checked)
    {
    $("#esc3InternalTo").val($("#esc2InternalTo").val());
    $("#esc3InternalCc").val($("#esc2InternalCc").val());
    $("#esc3InternalBc").val($("#esc2InternalBc").val());
    $("#esc3ExternalTo").val($("#esc2ExternalTo").val());
    $("#esc3ExternalCc").val($("#esc2ExternalCc").val());
    $("#esc3ExternalBc").val($("#esc2ExternalBc").val());
    }
    else
    {
    $("#esc3InternalTo").val('');
    $("#esc3InternalCc").val('');
    $("#esc3InternalBc").val('');
    $("#esc3ExternalTo").val('');
    $("#esc3ExternalCc").val('');
    $("#esc3ExternalBc").val('');
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
<?php echo $this->Form->create('Agreement',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Agreement Entry</span>
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
		<h4 class="page-header"><?php echo $this->Session->flash(); ?></h4>
                <?php
                $array=array('branch_name'=>'Branch','cost_center'=>'Cost Center',
                'periodTo'=>'Select From Date','periodFrom'=>'Select To Date',
                'image_upload'=>'Image Upload','document_type'=>'Document Type');
                
                echo '<div class="form-group has-feedback">';
                
                $keys = array_keys($array);

                for($i=0; $i<count($keys); $i++)
                {
                    if($keys[$i]=='branch_name')
                    {
                        echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                        echo '<div class="col-sm-3">';
                        echo $this->Form->input($keys[$i],array('label'=>false,'options'=>$branch_master,'empty'=>
                        'Select'.$array[$keys[$i]],'class'=>'form-control','required'=>true,'onChange'=>'getCostCenter(this.value)'));
                        echo "</div>";
                    }

                    else if($keys[$i]=='cost_center')
                    {
                        echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                        echo '<div class="col-sm-3">';
                        echo '<div id="cost_center">';
                        echo $this->Form->input($keys[$i],array('label'=>false,'options'=>'','empty'=>
                        'Select'.$array[$keys[$i]],'required'=>true,'class'=>'form-control'));
                        echo "</div></div>";
                    }

                    else if($keys[$i]=='periodTo' || $keys[$i]=='periodFrom')
                    {
                        echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                        echo '<div class="col-sm-3">';
                        echo $this->Form->input($keys[$i],array('label'=>false,'Place Holder'=>
                        $array[$keys[$i]],'class'=>'form-control','type'=>'text','required'=>true,'onClick'=>"displayDatePicker('data[Agreement][{$keys[$i]}]');"));
                        echo "</div>";
                    }

                    else if($keys[$i]=='image_upload')
                    {
                        echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                        echo '<div class="col-sm-3">';
                        echo $this->Form->input($keys[$i].'.',array('label'=>false,'type'=>'file','multiple'=>true));
                        echo "</div>";
                    }

                    else if($keys[$i]=='document_type')
                    {
                        echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                        echo '<div class="col-sm-3">';
                        echo $this->Form->input($keys[$i],array('label'=>false,'options'=>array('Agreement'=>'Agreement','Extension Letter'=>'Extension Letter'),
                        'required'=>true,'class'=>'form-control','empty'=>'Select'));
                        echo "</div>";
                    }
                    
                    else
                    {
                        echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                        echo '<div class="col-sm-3">';
                        echo $this->Form->input($keys[$i],array('label'=>false,'type'=>'text','required'=>true,'class'=>'form-control'));
                        echo "</div>";
                    }
                    
                    if($i%2==1)
                    {
                        echo '</div>';
                        echo '<div class="form-group has-feedback">';
                    }
                }
                echo '</div>';
                ?>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">Cost Center Selected</label>
                    <font color="#5a8db6"><b><div id="cost_center_text"></div></b></font>
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
                    <span>Escalation 1 For 30 Days Before Agreement Ends</span>
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
                <?php
                $array=array('esc1.internal_to'=>'Internal To','esc1.external_to'=>'External To','esc1.internal_cc'=>'Internal CC',
                'esc1.external_cc'=>'External CC','esc1.internal_bc'=>'Internal BC',
                'esc1.external_bc'=>'External BC');
                
                echo '<div class="form-group has-feedback">';
                
                $keys = array_keys($array);

                for($i=0; $i<count($keys); $i++)
                {
                    if($keys[$i]=='esc1.internal_to')
                    {$flag = true;}else{ $flag = false;}
                    
                    echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                    echo '<div class="col-sm-3">';
                    echo $this->Form->textArea($keys[$i],array('label'=>false,'type'=>'text','required'=>$flag,'class'=>'form-control'));
                    echo "</div>";   
                    if($i%2==1)
                    {
                        echo '</div>';
                        echo '<div class="form-group has-feedback">';
                    }
                }
                echo '</div>';
                ?>
            </div>
	</div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Escalation 2 For 15 Days Before Agreement Ends</span>
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
            <input type="checkbox" name="check1" id="check1" value="All" onClick="checkall1()"> <b>Same As Above</b>
                <?php
                $array=array('esc2.internal_to'=>'Internal To','esc2.external_to'=>'External To','esc2.internal_cc'=>'Internal CC',
                'esc2.external_cc'=>'External CC','esc2.internal_bc'=>'Internal BC',
                'esc2.external_bc'=>'External BC');
                
                echo '<div class="form-group has-feedback">';
                
                $keys = array_keys($array);

                for($i=0; $i<count($keys); $i++)
                {
                    if($keys[$i]=='esc2.internal_to')
                    {$flag = true;}else{ $flag = false;}
                    
                    echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                    echo '<div class="col-sm-3">';
                    echo $this->Form->textArea($keys[$i],array('label'=>false,'type'=>'text','required'=>$flag,'class'=>'form-control'));
                    echo "</div>";   
                    
                    if($i%2==1)
                    {
                        echo '</div>';
                        echo '<div class="form-group has-feedback">';
                    }
                }
                echo '</div>';
                ?>
            </div>
	</div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Escalation 3 After Agreement Ends For Every Monday & Thursday</span>
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
                <input type="checkbox" name="check2" id="check2" value="All" onClick="checkall2()"> <b>Same As Above</b>
                <?php
                $array=array('esc3.internal_to'=>'Internal To','esc3.external_to'=>'External To','esc3.internal_cc'=>'Internal CC',
                'esc3.external_cc'=>'External CC','esc3.internal_bc'=>'Internal BC',
                'esc3.external_bc'=>'External BC');
                
                echo '<div class="form-group has-feedback">';
                
                $keys = array_keys($array);

                for($i=0; $i<count($keys); $i++)
                {
                    if($keys[$i]=='esc3.internal_to')
                    {$flag = true;}else{ $flag = false;}
                    
                    echo '<label class="col-sm-3 control-label">'.$array[$keys[$i]].'</label>';
                    echo '<div class="col-sm-3">';
                    echo $this->Form->textArea($keys[$i],array('label'=>false,'type'=>'text','required'=>$flag,'class'=>'form-control'));
                    echo "</div>";   
                    
                    if($i%2==1)
                    {
                        echo '</div>';
                        echo '<div class="form-group has-feedback">';
                    }
                }
                echo '</div>';
                ?>
                <div class="form-group has-success has-feedback">
                <label class="col-sm-6 control-label"></label>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-label-left">Submit</button>
                    </div>
                </div>
                
            </div>
	</div>
    </div>
</div>
<?php echo $this->Form->end(); ?>	

