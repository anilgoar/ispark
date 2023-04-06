<?php ?>
<script>
function validateBand(){
    $("#msgerr").remove();
    var SlabFrom=$("#SlabFrom").val();
    var SlabTo=$("#SlabTo").val();
    
    if(SlabFrom ===""){
        $("#SlabFrom").focus();
        $("#SlabFrom").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter slab from.</span>");
        return false;
    }
    else if(SlabTo ===""){
        $("#SlabTo").focus();
        $("#SlabTo").after("<span id='msgerr' style='color:red;font-size:11px;'>Please enter slab to.</span>");
        return false;
    }
    else{
        return true;
    }
}
    
function addNew(){
    window.location="<?php echo $this->webroot;?>BandNameMasters";
}

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
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
            <div class="box-header"  >
                <div class="box-name">
                    <span>BAND MASTER</span>
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
                <?php echo $this->Form->create('BandNameMasters',array('action'=>'index','class'=>'form-horizontal','onSubmit'=>'return validateBand()')); ?>
                <div class="form-group"> 
                    <label class="col-sm-1 control-label">Band</label>
                    <div class="col-sm-2">
                        <select name="Band" id="Band" class="form-control" >
                            <option <?php if($row['Band']=="A"){echo "selected='selected'";}?> value="A" >A</option>
                            <option <?php if($row['Band']=="B"){echo "selected='selected'";}?> value="B" >B</option>
                            <option <?php if($row['Band']=="C"){echo "selected='selected'";}?> value="C" >C</option>
                            <option <?php if($row['Band']=="D"){echo "selected='selected'";}?> value="D" >D</option>
                            <option <?php if($row['Band']=="E"){echo "selected='selected'";}?> value="E" >E</option>
                            <option <?php if($row['Band']=="F"){echo "selected='selected'";}?> value="F" >F</option>
                            <option <?php if($row['Band']=="G"){echo "selected='selected'";}?> value="G" >G</option>
                            <option <?php if($row['Band']=="H"){echo "selected='selected'";}?> value="H" >H</option>
                            <option <?php if($row['Band']=="I"){echo "selected='selected'";}?> value="I" >I</option>
                            <option <?php if($row['Band']=="J"){echo "selected='selected'";}?> value="J" >J</option>
                            <option <?php if($row['Band']=="K"){echo "selected='selected'";}?> value="K" >K</option>
                            <option <?php if($row['Band']=="L"){echo "selected='selected'";}?> value="L" >L</option>
                            <option <?php if($row['Band']=="M"){echo "selected='selected'";}?> value="M" >M</option>
                            <option <?php if($row['Band']=="N"){echo "selected='selected'";}?> value="N" >N</option>
                            <option <?php if($row['Band']=="O"){echo "selected='selected'";}?> value="O" >O</option>
                            <option <?php if($row['Band']=="P"){echo "selected='selected'";}?> value="P" >P</option>
                            <option <?php if($row['Band']=="Q"){echo "selected='selected'";}?> value="Q" >Q</option>
                            <option <?php if($row['Band']=="R"){echo "selected='selected'";}?> value="R" >R</option>
                            <option <?php if($row['Band']=="S"){echo "selected='selected'";}?> value="S" >S</option>
                            <option <?php if($row['Band']=="T"){echo "selected='selected'";}?> value="T" >T</option>
                            <option <?php if($row['Band']=="U"){echo "selected='selected'";}?> value="U" >U</option>
                            <option <?php if($row['Band']=="V"){echo "selected='selected'";}?> value="V" >V</option>
                            <option <?php if($row['Band']=="W"){echo "selected='selected'";}?> value="W" >W</option>
                            <option <?php if($row['Band']=="X"){echo "selected='selected'";}?> value="X" >X</option>
                            <option <?php if($row['Band']=="Y"){echo "selected='selected'";}?> value="Y" >Y</option>
                            <option <?php if($row['Band']=="Z"){echo "selected='selected'";}?> value="Z" >Z</option>
                        </select>
                    </div>
                    
                </div>
        
                <div class="form-group">
                     <label class="col-sm-1 control-label">SlabFrom</label>
                    <div class="col-sm-2">
                        <input type="text" onkeypress="return isNumberDecimalKey(event,this)" id="SlabFrom" name="SlabFrom" value="<?php echo isset($row['SlabFrom'])?$row['SlabFrom']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                    
                    <label class="col-sm-1 control-label">SlabTo</label>
                    <div class="col-sm-2">
                        <input type="text" onkeypress="return isNumberDecimalKey(event,this)" id="SlabTo" name="SlabTo" value="<?php echo isset($row['SlabTo'])?$row['SlabTo']:'';?>" autocomplete="off" class="form-control" >
                    </div>
                    
                    
                    
                    
                    
                    <?php if(isset($row)){?>
                    <label class="col-sm-1 control-label">Status</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="Status" >
                            <option <?php if($row['Status']=="1"){echo "selected='selected'";}?> value="1" >Active</option>
                            <option <?php if($row['Status']=="0"){echo "selected='selected'";}?> value="0" >Deactive</option>
                        </select>
                    </div>
                    <?php }?>
                    
                    <div class="col-sm-2">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php if(isset($row)){?>
                        <input type="hidden" name="BandId" value="<?php echo isset($row['Id'])?$row['Id']:'';?>" >
                        <input type="button" onclick="addNew();"  value="Add New" class="btn pull-right btn-primary btn-new" style="margin-left:5px;">
                        <input type="submit" name="submit"  value="Update" class="btn pull-right btn-primary btn-new"  >
                        <?php }else{?>
                        <input type="submit"  name="submit"  value="Submit" class="btn pull-right btn-primary btn-new">
                        <?php }?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <?php if(!empty($DataArr)){?>
                        <table class = "table table-striped table-hover  responstable"  >     
                            <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th style="text-align:center;" >Band</th>
                                    <th style="text-align:center;">SlabFrom</th>
                                    <th style="text-align:center;">SlabTo</th>
                                    <th style="text-align:center;">Status</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>         
                                <?php $n=1; foreach ($DataArr as $val){?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td style="text-align:center;"><?php echo $val['BandNameMaster']['Band'];?></td>
                                    <td style="text-align:center;"><?php echo $val['BandNameMaster']['SlabFrom'];?></td>
                                    <td style="text-align:center;"><?php echo $val['BandNameMaster']['SlabTo'];?></td>
                                    <td style="text-align:center;"><?php if($val['BandNameMaster']['Status'] =="1"){echo "Active";}else{echo "Deactive";}?></td>
                                    <td style="text-align:center;">
                                        <a href="<?php $this->webroot;?>BandNameMasters?id=<?php echo base64_encode($val['BandNameMaster']['Id']);?>" ><span class='icon' ><i class="material-icons" style="font-size:20px;" >mode_edit</i></span></a>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>   
                        </table>
                        <?php } ?>
                        
                    </div>
                </div>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



