<?php 
echo $this->Html->css('jquery-ui');
echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
$(function () {
    $("#InstallationDate").datepicker1({
        changeMonth: true,
        changeYear: true
    });
});

function isNumberDecimalKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}

function isNumberKey(e,t){
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}
</script>
<style>
.form-group .form-control, .form-group .input-group {
    margin-bottom: -20px;
}
.form-horizontal .control-label {
    padding-top: 0px;
}
.control-label{
    font-size: 11px;
}
</style>
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
<div class="col-xs-12 col-sm-12">
    <div class="box" >
        <div class="box-header"  >
            <div class="box-name">
                <span>ADD ASSETS</span>
            </div>
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
            <div class="no-move"></div>
        </div>
          
        <div class="box-content box-con">
            <div style="margin-left:0px;" ><?php echo $this->Session->flash(); ?></div>
            <?php if(isset($RowData['Id']) && $RowData['Id'] !=""){?>
            <?php echo $this->Form->create('AssetsManagements',array('class'=>'form-horizontal','action'=>'update','onSubmit'=>'return validateOdApply()')); ?>
            <?php }else{?>
            <?php echo $this->Form->create('AssetsManagements',array('class'=>'form-horizontal','action'=>'index','onSubmit'=>'return validateOdApply()')); ?>
            <?php }?>
        
   
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Location</label>
                <div class="col-sm-2">
                    <select  name="data[AssetsManagements][Location]" id="Location" class="form-control" required="" >
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Location']) && $RowData['Location']=="Server Room"){echo "selected='selected'";} ?>  value="Server Room">Server Room</option>
                        <option <?php if(isset($RowData['Location']) && $RowData['Location']=="Training Room"){echo "selected='selected'";} ?> value="Training Room" >Training Room</option>
                        <option <?php if(isset($RowData['Location']) && $RowData['Location']=="IT Room"){echo "selected='selected'";} ?> value="IT Room">IT Room</option>
                        <option <?php if(isset($RowData['Location']) && $RowData['Location']=="Desktops"){echo "selected='selected'";} ?> value="Desktops">Desktops</option>
                    </select>
               </div>
                
                <label for="pwd" class="control-label col-sm-1">Branch</label>
                <div class="col-sm-2">
                    <select  name="data[AssetsManagements][Branch]" id="Branch" class="form-control" required="" >
                        <option value="">Select</option>
                        <?php foreach($branchName as $key=>$val){?>
                        <option <?php if(isset($RowData['Branch']) && $RowData['Branch']==$val){echo "selected='selected'";} ?> value="<?php echo $val;?>"><?php echo $val;?></option>
                        <?php }?>
                    </select>
                </div>
        
                <label for="pwd" class="control-label col-sm-1">Server&nbsp;Name</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][ServerName]" id="ServerName" value="<?php echo isset($RowData['ServerName'])?$RowData['ServerName']:"";?>" placeholder="Server Name" class="form-control" required="">
                </div>
        
                <label for="Processor" class="control-label col-sm-1">Brand</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Brand]" id="Brand"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Brand']) && $RowData['Brand']=="HP"){echo "selected='selected'";} ?> value="HP">HP</option>
                        <option <?php if(isset($RowData['Brand']) && $RowData['Brand']=="Dell"){echo "selected='selected'";} ?> value="Dell">Dell</option>
                        <option <?php if(isset($RowData['Brand']) && $RowData['Brand']=="IBM"){echo "selected='selected'";} ?> value="IBM">IBM</option>
                        <option <?php if(isset($RowData['Brand']) && $RowData['Brand']=="Assembled"){echo "selected='selected'";} ?> value="Assembled">Assembled</option>
                    </select>
                </div> 
            </div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Motherboard</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][MotherBoard]" id="MotherBoard" class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['MotherBoard']) && $RowData['MotherBoard']=="Intel"){echo "selected='selected'";} ?> value="Intel">Intel</option>
                        <option <?php if(isset($RowData['MotherBoard']) && $RowData['MotherBoard']=="Intel S1200RP"){echo "selected='selected'";} ?> value="Intel S1200RP">Intel S1200RP</option>
                        <option <?php if(isset($RowData['MotherBoard']) && $RowData['MotherBoard']=="Intel E98683"){echo "selected='selected'";} ?> value="Intel E98683">Intel E98683</option>                
                    </select>
               </div>
         
                <label for="pwd" class="control-label col-sm-1">Processor-1</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Processor1]" id="Processor1"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Processor1']) && $RowData['Processor1']=="Intel Xeon"){echo "selected='selected'";} ?> value="Intel Xeon">Intel Xeon</option>
                       <option <?php if(isset($RowData['Processor1']) && $RowData['Processor1']=="Core-2-Duo"){echo "selected='selected'";} ?> value="Core-2-Duo">Core-2-Duo</option>
                       <option <?php if(isset($RowData['Processor1']) && $RowData['Processor1']=="Core-i3"){echo "selected='selected'";} ?> value="Core-i3">Core-i3</option>
                       <option <?php if(isset($RowData['Processor1']) && $RowData['Processor1']=="Core-i5"){echo "selected='selected'";} ?> value="Core-i5">Core-i5</option>
                       <option <?php if(isset($RowData['Processor1']) && $RowData['Processor1']=="Core-i7"){echo "selected='selected'";} ?> value="Core-i7">Core-i7</option>
                       
                    </select>
                </div>
             
                <label for="pwd" class="control-label col-sm-1">Core-1</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Core1]" id="Core1"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Core1']) && $RowData['Core1']=="4 Core"){echo "selected='selected'";} ?> value="4 Core">4 Core</option>
                        <option <?php if(isset($RowData['Core1']) && $RowData['Core1']=="8 Core"){echo "selected='selected'";} ?> value="8 Core">8 Core</option> 
                    </select>
                </div>
         
                <label for="pwd" class="control-label col-sm-1">Processor-2</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Processor2]" id="Processor2" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Processor2']) && $RowData['Processor2']=="Intel Xeon"){echo "selected='selected'";} ?> value="Intel Xeon">Intel Xeon</option>
                        <option <?php if(isset($RowData['Processor2']) && $RowData['Processor2']=="None"){echo "selected='selected'";} ?> value="None">None </option>
                        <option <?php if(isset($RowData['Processor2']) && $RowData['Processor2']=="Slot Not Available"){echo "selected='selected'";} ?> value="Slot Not Available">Slot Not Available</option>
                    </select>
                </div>      
            </div>
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Core-2</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Core2]" id="Core2"  class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Core2']) && $RowData['Core2']=="4 Core"){echo "selected='selected'";} ?> value="4 Core">4 Core</option>
                        <option <?php if(isset($RowData['Core2']) && $RowData['Core2']=="8 Core"){echo "selected='selected'";} ?> value="8 Core">8 Core</option> 
                    </select>
                </div>

                <label for="pwd" class="control-label col-sm-1">Speed</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Speed]" id="Speed"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="1.7x GHz"){echo "selected='selected'";} ?> value="1.7x GHz">1.7x GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="1.8x GHz"){echo "selected='selected'";} ?> value="1.8x GHz">1.8x GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="2.0 GHz"){echo "selected='selected'";} ?> value="2.0 GHz">2.0 GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="2.1x GHz"){echo "selected='selected'";} ?> value="2.1x GHz">2.1x GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="2.4x GHz"){echo "selected='selected'";} ?> value="2.4x GHz">2.4x GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="2.6x GHz"){echo "selected='selected'";} ?> value="2.6x GHz">2.6x GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="3.0 GHz"){echo "selected='selected'";} ?> value="3.0 GHz">3.0 GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="3.1x GHz"){echo "selected='selected'";} ?> value="3.1x GHz">3.1x GHz</option>
                        <option <?php if(isset($RowData['Speed']) && $RowData['Speed']=="3.2x GHz"){echo "selected='selected'";} ?> value="3.2x GHz">3.2x GHz</option>                 
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Generation</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Generation]" id="Generation"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Generation']) && $RowData['Generation']=="Generation 1"){echo "selected='selected'";} ?> value="Generation 1">Generation 1</option>
                        <option <?php if(isset($RowData['Generation']) && $RowData['Generation']=="Generation 2"){echo "selected='selected'";} ?> value="Generation 2">Generation 2</option>
                        <option <?php if(isset($RowData['Generation']) && $RowData['Generation']=="Generation 3"){echo "selected='selected'";} ?> value="Generation 3">Generation 3</option>
                    </select>
                </div>
 
                <label for="pwd" class="control-label col-sm-1">Hard&nbsp;Disk-1</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][HardDisk1]" id="HardDisk1"  class="form-control" required="">
                       <option value="">Select</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="80 GB"){echo "selected='selected'";} ?> value="80 GB">80 GB</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="160 GB"){echo "selected='selected'";} ?> value="160 GB">160 GB</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="250 GB"){echo "selected='selected'";} ?> value="250 GB">250 GB</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="320 GB"){echo "selected='selected'";} ?> value="320 GB">320 GB</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="500 GB"){echo "selected='selected'";} ?> value="500 GB">500 GB</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="1 TB"){echo "selected='selected'";} ?> value="1 TB">1 TB</option>
                       <option <?php if(isset($RowData['HardDisk1']) && $RowData['HardDisk1']=="2 TB"){echo "selected='selected'";} ?> value="2 TB">2 TB</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Hard&nbsp;Disk-2</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][HardDisk2]" id="HardDisk2"  class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="80 GB"){echo "selected='selected'";} ?> value="80 GB">80 GB</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="160 GB"){echo "selected='selected'";} ?> value="160 GB">160 GB</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="250 GB"){echo "selected='selected'";} ?> value="250 GB">250 GB</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="320 GB"){echo "selected='selected'";} ?> value="320 GB">320 GB</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="500 GB"){echo "selected='selected'";} ?> value="500 GB">500 GB</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="1 TB"){echo "selected='selected'";} ?> value="1 TB">1 TB</option>
                        <option <?php if(isset($RowData['HardDisk2']) && $RowData['HardDisk2']=="2 TB"){echo "selected='selected'";} ?> value="2 TB">2 TB</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Hard&nbsp;Disk-3</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][HardDisk3]" id="HardDisk3"  class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="80 GB"){echo "selected='selected'";} ?> value="80 GB">80 GB</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="160 GB"){echo "selected='selected'";} ?> value="160 GB">160 GB</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="250 GB"){echo "selected='selected'";} ?> value="250 GB">250 GB</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="320 GB"){echo "selected='selected'";} ?> value="320 GB">320 GB</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="500 GB"){echo "selected='selected'";} ?> value="500 GB">500 GB</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="1 TB"){echo "selected='selected'";} ?> value="1 TB">1 TB</option>
                        <option <?php if(isset($RowData['HardDisk3']) && $RowData['HardDisk3']=="2 TB"){echo "selected='selected'";} ?> value="2 TB">2 TB</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Hard&nbsp;Disk-4</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][HardDisk4]" id="HardDisk4"  class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="80 GB"){echo "selected='selected'";} ?> value="80 GB">80 GB</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="160 GB"){echo "selected='selected'";} ?> value="160 GB">160 GB</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="250 GB"){echo "selected='selected'";} ?> value="250 GB">250 GB</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="320 GB"){echo "selected='selected'";} ?> value="320 GB">320 GB</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="500 GB"){echo "selected='selected'";} ?> value="500 GB">500 GB</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="1 TB"){echo "selected='selected'";} ?> value="1 TB">1 TB</option>
                        <option <?php if(isset($RowData['HardDisk4']) && $RowData['HardDisk4']=="2 TB"){echo "selected='selected'";} ?> value="2 TB">2 TB</option>
                    </select>
                </div>
            </div>
                  
            <div class="box-header"><div class="box-name"><span>RAM DETAILS</span></div></div>
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Type</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RamType]" id="RamType"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RamType']) && $RowData['RamType']=="DDR-1"){echo "selected='selected'";} ?> value="DDR-1">DDR-1</option>
                        <option <?php if(isset($RowData['RamType']) && $RowData['RamType']=="DDR-2"){echo "selected='selected'";} ?> value="DDR-2">DDR-2</option>
                        <option <?php if(isset($RowData['RamType']) && $RowData['RamType']=="DDR-3"){echo "selected='selected'";} ?> value="DDR-3">DDR-3</option>
                        <option <?php if(isset($RowData['RamType']) && $RowData['RamType']=="DDR-4"){echo "selected='selected'";} ?> value="DDR-4">DDR-4</option> 
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-1</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot1]" id="RAMSlot1"  class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot1']) && $RowData['RAMSlot1']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot1']) && $RowData['RAMSlot1']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot1']) && $RowData['RAMSlot1']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot1']) && $RowData['RAMSlot1']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot1']) && $RowData['RAMSlot1']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>   
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-2</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot2]" id="RAMSlot2" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot2']) && $RowData['RAMSlot2']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot2']) && $RowData['RAMSlot2']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot2']) && $RowData['RAMSlot2']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot2']) && $RowData['RAMSlot2']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot2']) && $RowData['RAMSlot2']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-3</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot3]" id="RAMSlot3" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot3']) && $RowData['RAMSlot3']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot3']) && $RowData['RAMSlot3']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot3']) && $RowData['RAMSlot3']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot3']) && $RowData['RAMSlot3']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot3']) && $RowData['RAMSlot3']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
            </div>
      
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-4</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot4]" id="RAMSlot4" class="form-control">
                        <option value="">Select</option>
                       <option <?php if(isset($RowData['RAMSlot4']) && $RowData['RAMSlot4']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                       <option <?php if(isset($RowData['RAMSlot4']) && $RowData['RAMSlot4']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                       <option <?php if(isset($RowData['RAMSlot4']) && $RowData['RAMSlot4']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                       <option <?php if(isset($RowData['RAMSlot4']) && $RowData['RAMSlot4']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                       <option <?php if(isset($RowData['RAMSlot4']) && $RowData['RAMSlot4']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
 
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-5</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot5]" id="RAMSlot5" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot5']) && $RowData['RAMSlot5']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot5']) && $RowData['RAMSlot5']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot5']) && $RowData['RAMSlot5']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot5']) && $RowData['RAMSlot5']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot5']) && $RowData['RAMSlot5']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>    
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-6</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot6]" id="RAMSlot6" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot6']) && $RowData['RAMSlot6']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot6']) && $RowData['RAMSlot6']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot6']) && $RowData['RAMSlot6']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot6']) && $RowData['RAMSlot6']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot6']) && $RowData['RAMSlot6']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-7</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot7]" id="RAMSlot7" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot7']) && $RowData['RAMSlot7']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot7']) && $RowData['RAMSlot7']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot7']) && $RowData['RAMSlot7']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot7']) && $RowData['RAMSlot7']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot7']) && $RowData['RAMSlot7']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
               </div>
            </div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-8</label> 
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot8]" id="RAMSlot8" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot8']) && $RowData['RAMSlot8']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot8']) && $RowData['RAMSlot8']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot8']) && $RowData['RAMSlot8']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot8']) && $RowData['RAMSlot8']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot8']) && $RowData['RAMSlot8']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
               </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-9</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot9]" id="RAMSlot9" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot9']) && $RowData['RAMSlot9']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot9']) && $RowData['RAMSlot9']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot9']) && $RowData['RAMSlot9']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot9']) && $RowData['RAMSlot9']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot9']) && $RowData['RAMSlot9']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
    
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-10</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot10]" id="RAMSlot10" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot10']) && $RowData['RAMSlot10']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot10']) && $RowData['RAMSlot10']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot10']) && $RowData['RAMSlot10']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot10']) && $RowData['RAMSlot10']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot10']) && $RowData['RAMSlot10']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>    
                    </select>
                </div>
               
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-11</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot11]" id="RAMSlot11" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot11']) && $RowData['RAMSlot11']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot11']) && $RowData['RAMSlot11']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot11']) && $RowData['RAMSlot11']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot11']) && $RowData['RAMSlot11']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot11']) && $RowData['RAMSlot11']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-12</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot12]" id="RAMSlot12" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot12']) && $RowData['RAMSlot12']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot12']) && $RowData['RAMSlot12']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot12']) && $RowData['RAMSlot12']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot12']) && $RowData['RAMSlot12']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot12']) && $RowData['RAMSlot12']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-13</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot13]" id="RAMSlot13" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot13']) && $RowData['RAMSlot13']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot13']) && $RowData['RAMSlot13']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot13']) && $RowData['RAMSlot13']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot13']) && $RowData['RAMSlot13']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot13']) && $RowData['RAMSlot13']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-14</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot14]" id="RAMSlot14" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot14']) && $RowData['RAMSlot14']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot14']) && $RowData['RAMSlot14']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot14']) && $RowData['RAMSlot14']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot14']) && $RowData['RAMSlot14']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot14']) && $RowData['RAMSlot14']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
 
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-15</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot15]" id="RAMSlot15" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot15']) && $RowData['RAMSlot15']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot15']) && $RowData['RAMSlot15']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot15']) && $RowData['RAMSlot15']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot15']) && $RowData['RAMSlot15']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot15']) && $RowData['RAMSlot15']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">RAM&nbsp;Slot-16</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][RAMSlot16]" id="RAMSlot16" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['RAMSlot16']) && $RowData['RAMSlot16']=="1GB"){echo "selected='selected'";} ?> value="1GB">1GB</option>
                        <option <?php if(isset($RowData['RAMSlot16']) && $RowData['RAMSlot16']=="2GB"){echo "selected='selected'";} ?> value="2GB">2GB</option>
                        <option <?php if(isset($RowData['RAMSlot16']) && $RowData['RAMSlot16']=="4GB"){echo "selected='selected'";} ?> value="4GB">4GB</option>
                        <option <?php if(isset($RowData['RAMSlot16']) && $RowData['RAMSlot16']=="8GB"){echo "selected='selected'";} ?> value="8GB">8GB</option>
                        <option <?php if(isset($RowData['RAMSlot16']) && $RowData['RAMSlot16']=="16GB"){echo "selected='selected'";} ?> value="16GB">16GB</option>    
                    </select>
                </div>     
            </div>
           
            <div class="box-header"><div class="box-name"><span>PRI DETAILS</span></div></div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">PRI Card</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][PriCard]" id="PriCard" class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['PriCard']) && $RowData['PriCard']=="Sangoma"){echo "selected='selected'";} ?> value="Sangoma">Sangoma</option>
                        <option <?php if(isset($RowData['PriCard']) && $RowData['PriCard']=="Digium"){echo "selected='selected'";} ?> value="Digium">Digium</option>
                        <option <?php if(isset($RowData['PriCard']) && $RowData['PriCard']=="GSM Getway"){echo "selected='selected'";} ?> value="GSM Getway">GSM Getway</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Port</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Port]" id="Port" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Port']) && $RowData['Port']=="2"){echo "selected='selected'";} ?> value="2">2</option>
                        <option <?php if(isset($RowData['Port']) && $RowData['Port']=="4"){echo "selected='selected'";} ?> value="4">4</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">PRI</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][ConnectedPri]" id="ConnectedPri" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['ConnectedPri']) && $RowData['ConnectedPri']=="1"){echo "selected='selected'";} ?> value="1">1</option>
                        <option <?php if(isset($RowData['ConnectedPri']) && $RowData['ConnectedPri']=="2"){echo "selected='selected'";} ?> value="2">2</option>
                        <option <?php if(isset($RowData['ConnectedPri']) && $RowData['ConnectedPri']=="3"){echo "selected='selected'";} ?> value="3">3</option>
                        <option <?php if(isset($RowData['ConnectedPri']) && $RowData['ConnectedPri']=="4"){echo "selected='selected'";} ?> value="4">4</option>
                    </select>
                </div>
            </div>
            
            <div class="box-header"><div class="box-name"><span>SOFTWARE DETAILS</span></div></div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Software</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][Software]" id="Software" class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['Software']) && $RowData['Software']=="Dialer"){echo "selected='selected'";} ?> value="Dialer">Dialer</option>
                        <option <?php if(isset($RowData['Software']) && $RowData['Software']=="CRM"){echo "selected='selected'";} ?> value="CRM">CRM</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Software&nbsp;Type</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][SoftwareType]" id="SoftwareType" class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['SoftwareType']) && $RowData['SoftwareType']=="Vicidialnow"){echo "selected='selected'";} ?> value="Vicidialnow">Vicidialnow</option>
                        <option <?php if(isset($RowData['SoftwareType']) && $RowData['SoftwareType']=="Goautodial"){echo "selected='selected'";} ?> value="Goautodial">Goautodial</option>
                        <option <?php if(isset($RowData['SoftwareType']) && $RowData['SoftwareType']=="Vicibox"){echo "selected='selected'";} ?> value="Vicibox">Vicibox</option>
                    </select>
                </div>
                
                <label for="pwd" class="control-label col-sm-1">OS</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][OS]" id="OS" class="form-control" required="">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['OS']) && $RowData['OS']=="Centos"){echo "selected='selected'";} ?> value="Centos">Centos</option>
                        <option <?php if(isset($RowData['OS']) && $RowData['OS']=="Goautodial"){echo "selected='selected'";} ?> value="Goautodial">Goautodial</option>
                        <option <?php if(isset($RowData['OS']) && $RowData['OS']=="Vicidialnow"){echo "selected='selected'";} ?> value="Vicidialnow">Vicidialnow</option>
                    </select>
                </div>
            </div>
            
            <div class="box-header"><div class="box-name"><span>IP DETAILS</span></div></div>
               
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Local&nbsp;IP-1</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][LocalIp1]" id="LocalIp1" value="<?php echo isset($RowData['LocalIp1'])?$RowData['LocalIp1']:"";?>" placeholder="Local IP" class="form-control">
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Local&nbsp;IP-2</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][LocalIp2]" id="LocalIp2" value="<?php echo isset($RowData['LocalIp2'])?$RowData['LocalIp2']:"";?>" placeholder="Local IP" class="form-control">
                </div>
                
                <label for="pwd" class="control-label col-sm-1">Local&nbsp;IP-3</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][LocalIp3]" id="LocalIp3" value="<?php echo isset($RowData['LocalIp3'])?$RowData['LocalIp3']:"";?>" placeholder="Local IP" class="form-control">
                </div>
               
                <label for="pwd" class="control-label col-sm-1">Static IP</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][StaticIp]" id="StaticIp" value="<?php echo isset($RowData['StaticIp'])?$RowData['StaticIp']:"";?>" placeholder="Static IP" class="form-control">
                </div>
            </div>
            
            <div class="box-header"><div class="box-name"><span>PROCESS/AGENT DETAILS</span></div></div>

            <div class="form-group">
                <div class="col-xs-6 col-sm-6" >
                    <div class="box" style='height:150px;'>
                        <div class="box-header" ><div class="box-name" style="background-color: #436e90;color:#FFF;"><span>ADD PROCESS</span></div></div>
                        <div class="box-content box-con table-responsive">
                            <div class="form-group">
                                <label for="pwd" class="control-label col-sm-2">Process</label>
                                <div class="col-sm-4">
                                    <input type="text" id="txtName"  placeholder="Process" class="form-control" autocomplete="off" >
                                </div>
           
                                <label for="pwd" class="control-label col-sm-2">Agent</label>
                                <div class="col-sm-2">
                                    <input type="text" id="txtCountry" onkeypress="return isNumberKey(event,this)" maxlength="3" placeholder="" class="form-control" autocomplete="off" >
                                </div>
                                <div class="col-sm-1">
                                    <input type="button" onclick="Add()" value="Add" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6" style='overflow-y:auto;height:150px;'>
                    <script type="text/javascript">
                    window.onload = function () {
                        //Build an array containing Customer records.
                        var customers = new Array();
                        <?php if(isset($RowData['Agents']) && $RowData['Agents']!=""){$AgentArr= explode(",", $RowData['Agents']);}?>
                        <?php if(isset($RowData['Process']) && $RowData['Process']!=""){?>
                            <?php foreach(explode(",", $RowData['Process']) as $key=>$val){?>
                                customers.push(["<?php echo $val;?>", "<?php echo $AgentArr[$key];?>"]);
                            <?php } ?>    
                        <?php } ?>

                        //customers.push(["John Hammond", "United States"]);
                        //customers.push(["Mudassar Khan", "India"]);
                        //customers.push(["Suzanne Mathews", "France"]);
                        //customers.push(["Robert Schidner", "Russia"]);

                        //Add the data rows.
                        for (var i = 0; i < customers.length; i++) {
                            AddRow(customers[i][0], customers[i][1]);
                        }
                    };
 
                    function Add() {
                        $("#msgerr").remove();
                        var txtName = document.getElementById("txtName");
                        var txtCountry = document.getElementById("txtCountry");
                        
                        if($.trim(txtName.value)===""){
                            $("#txtName").focus();
                            $("#txtName").after("<br/><span id='msgerr' style='color:red;font-size:11px;'>Enter process</span>");
                            return false;
                        }
                        else if($.trim(txtCountry.value)===""){
                            $("#txtCountry").focus();
                            $("#txtCountry").after("<br/><span id='msgerr' style='color:red;font-size:11px;'>Agent no</span>");
                            return false;
                        }
                        else{
                            AddRow(txtName.value, txtCountry.value);
                            txtName.value = "";
                            txtCountry.value = "";
                        }
                    }
 
                    function Remove(button) {
                        //Determine the reference of the Row using the Button.
                        var row = button.parentNode.parentNode;
                        var name = row.getElementsByTagName("TD")[0].innerHTML;
                        if (confirm("Are you sure you want to delete this record.")) {

                            //Get the reference of the Table.
                            var table = document.getElementById("tblCustomers");

                            //Delete the Table row using it's Index.
                            table.deleteRow(row.rowIndex);
                        }
                    };
 
                    function AddRow(name, country) {
                        //Get the reference of the Table's TBODY element.
                        var tBody = document.getElementById("tblCustomers").getElementsByTagName("TBODY")[0];

                        //Add Row.
                        row = tBody.insertRow(-1);

                        //Add Name cell.
                        var cell = row.insertCell(-1);
                        cell.innerHTML =  "<input type='hidden' name='data[AssetsManagements][Process][]' value='"+name+"'>"+name;

                        //Add Country cell.
                        cell = row.insertCell(-1);
                        cell.innerHTML = "<input type='hidden' name='data[AssetsManagements][Agents][]' value='"+country+"'>"+country;

                        //Add Button cell.
                        cell = row.insertCell(-1);
                        var btnRemove = document.createElement("INPUT");
                        btnRemove.type = "button";
                        btnRemove.value = "Remove";
                        btnRemove.setAttribute("onclick", "Remove(this);");
                        cell.appendChild(btnRemove);
                    }
                </script>
                <style>
                    #tblCustomers tr td{
                        padding:0px;
                        padding-top: 2px;
                        padding-left: 2px;
                        text-align: center;
                    }
                </style>
                <table id="tblCustomers" cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                        <tr style='background-color: #436e90;color:#FFF;'>
                            <th style='text-align: left;'>Process</th>
                            <th style='text-align: left;'>Agent</th>
                            <th style='text-align: left;'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>    
            </div>
            
            <div class="box-header"><div class="box-name"><span>VENDOR DETAILS</span></div></div>
            
            <div class="form-group">
                <label for="pwd" class="control-label col-sm-1">Vendor&nbsp;Name</label>
                <div class="col-sm-2">
                    <select name="data[AssetsManagements][VendorName]" id="VendorName" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(isset($RowData['VendorName']) && $RowData['VendorName']=="A-One"){echo "selected='selected'";} ?> value="A-One">A-One</option>
                        <option <?php if(isset($RowData['VendorName']) && $RowData['VendorName']=="SBC"){echo "selected='selected'";} ?> value="SBC">SBC</option>
                        <option <?php if(isset($RowData['VendorName']) && $RowData['VendorName']=="Swastik"){echo "selected='selected'";} ?> value="Swastik">Swastik</option>
                        <option <?php if(isset($RowData['VendorName']) && $RowData['VendorName']=="Goodwill"){echo "selected='selected'";} ?> value="Goodwill">Goodwill</option>
                    </select>
                </div>
                    
                <label for="pwd" class="control-label col-sm-1">Install&nbsp;Date</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][InstallationDate]" id="InstallationDate" value="<?php echo isset($RowData['InstallationDate']) && $RowData['InstallationDate'] !=""?date('d-M-Y',strtotime($RowData['InstallationDate'])):"";?>" placeholder="Date" class="form-control">
                </div>
                    
                <label for="pwd" class="control-label col-sm-1">Rent&nbsp;Amount</label>
                <div class="col-sm-2">
                    <input type="text" name="data[AssetsManagements][RentAmount]" id="RentAmount" onkeypress="return isNumberDecimalKey(event,this)" maxlength="10" value="<?php echo isset($RowData['RentAmount'])?$RowData['RentAmount']:"";?>" placeholder="Amount" class="form-control">
               </div>
            </div>
             
            <div class="form-group" style="margin-bottom:-10px;">
                <div class="col-sm-12 text-right">   
                    <?php if(isset($RowData['Id']) && $RowData['Id'] !=""){?>
                        <input type="hidden" name="data[AssetsManagements][Id]" id="Id" value="<?php echo isset($RowData['Id'])?$RowData['Id']:"";?>" class="form-control">
                        <a href="<?php echo $this->webroot;?>AssetsManagements"><button type="button" class="btn" style="background-color: #436e90;color:#FFF;" >Add New</button></a>
                        <button type="submit" class="btn" style="background-color: #436e90;color:#FFF;" >Update</button>
                        <?php }else{?>
                        <button type="submit" class="btn" style="background-color: #436e90;color:#FFF;">Submit</button>
                    <?php } ?> 
                </div>   
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
   </div>
</div>
        
 
<div class="col-xs-12 col-sm-12">
    <div class="box" >
        <div class="box-header"  >
            <div class="box-name">
                <span>VIEW ASSETS</span>
                <a href="<?php echo $this->webroot;?>AssetsManagements/export" style="padding: 0 20px; color:#000000;">Export<i class="fa fa-file-excel-o"></i></a>
            </div>
            <div class="box-icons">


        &nbsp;
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
            <div class="no-move"></div>
        </div>


         <div class="box-content box-con table-responsive" style="overflow-x:auto;padding: 1px;" >
             <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">    
                <thead>
                    <tr style="background-color: #436e90;color:#FFF;">
                        <th style="text-align: center;">Server&nbsp;Name</th>
                        <th style="text-align: center;">Branch</th>
                        <th style="text-align: center;">Location</th>
                        <th style="text-align: center;">Brand</th>
                        <th style="text-align: center;">MotherBoard</th>
                        <th style="text-align: center;">Processor&nbsp;1</th>
                        <th style="text-align: center;">Core&nbsp;1</th>
                        <th style="text-align: center;">Processor&nbsp;2</th>
                        <th style="text-align: center;">Core&nbsp;2</th>
                        <th style="text-align: center;">Speed</th>
                        <th style="text-align: center;">Generation</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-1</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-2</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-3</th>
                        <th style="text-align: center;">Hard&nbsp;Disk-4</th>
                        <th style="text-align: center;">RAM&nbsp;Type</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-1</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-2</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-3</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-4</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-5</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-6</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-7</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-8</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-9</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-10</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-11</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-12</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-13</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-14</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-15</th>
                        <th style="text-align: center;">RAM&nbsp;Slot-16</th>
                        <th style="text-align: center;">PRI&nbsp;Card</th>
                        <th style="text-align: center;">Port</th>
                        <th style="text-align: center;">PRI</th>
                        <th style="text-align: center;">Software</th>
                        <th style="text-align: center;">Software&nbsp;Type</th>
                        <th style="text-align: center;">OS</th>
                        <th style="text-align: center;">Local&nbsp;IP-1</th>
                        <th style="text-align: center;">Local&nbsp;IP-2</th>
                        <th style="text-align: center;">Local&nbsp;IP-3</th>
                        <th style="text-align: center;">Static&nbsp;IP</th>
                        <th style="text-align: center;">Process</th>
                        <th style="text-align: center;">Agents</th>
                        <th style="text-align: center;">Total&nbsp;Agents</th>
                        <th style="text-align: center;">Vendor&nbsp;Name</th>
                        <th style="text-align: center;">Install&nbsp;Date</th>
                        <th style="text-align: center;">Rent&nbsp;Amount</th>
                       <th style="text-align: center;">Create&nbsp;Date</th>
                       <th style="text-align: center;">Create&nbsp;By</th>
                       <th style="text-align: center;">Update&nbsp;Date</th>
                       <th style="text-align: center;">Update&nbsp;By</th>
                       <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>         
                <?php foreach($data as $row){?> 
                <tr>
                    <td><?php echo $row['AssetsManagement']['ServerName']?></td>
                    <td><?php echo $row['AssetsManagement']['Branch']?></td>
                    <td><?php echo $row['AssetsManagement']['Location']?></td>
                    <td><?php echo $row['AssetsManagement']['Brand']?></td>
                    <td><?php echo $row['AssetsManagement']['MotherBoard']?></td>
                    <td><?php echo $row['AssetsManagement']['Processor1']?></td>
                    <td><?php echo $row['AssetsManagement']['Core1']?></td>
                    <td><?php echo $row['AssetsManagement']['Processor2']?></td>
                    <td><?php echo $row['AssetsManagement']['Core2']?></td>
                    <td><?php echo $row['AssetsManagement']['Speed']?></td>
                    <td><?php echo $row['AssetsManagement']['Generation']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk1']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk2']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk3']?></td>
                    <td><?php echo $row['AssetsManagement']['HardDisk4']?></td>
                    <td><?php echo $row['AssetsManagement']['RamType']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot1']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot2']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot3']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot4']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot5']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot6']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot7']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot8']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot9']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot10']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot11']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot12']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot13']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot14']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot15']?></td>
                    <td><?php echo $row['AssetsManagement']['RAMSlot16']?></td>
                    <td><?php echo $row['AssetsManagement']['PriCard']?></td>
                    <td><?php echo $row['AssetsManagement']['Port']?></td>
                    <td><?php echo $row['AssetsManagement']['ConnectedPri']?></td>
                    <td><?php echo $row['AssetsManagement']['Software']?></td>
                    <td><?php echo $row['AssetsManagement']['SoftwareType']?></td>
                    <td><?php echo $row['AssetsManagement']['OS']?></td>
                    <td><?php echo $row['AssetsManagement']['LocalIp1']?></td>
                    <td><?php echo $row['AssetsManagement']['LocalIp2']?></td>
                    <td><?php echo $row['AssetsManagement']['LocalIp3']?></td>
                    <td><?php echo $row['AssetsManagement']['StaticIp']?></td>
                    <td><?php foreach(explode(",", $row['AssetsManagement']['Process']) as $process){ echo $process."<br/>";}?></td>
                    <td><?php foreach(explode(",", $row['AssetsManagement']['Agents']) as $agents){ echo $agents."<br/>";}?></td>
                    <td><?php echo $row['AssetsManagement']['TotalAgents']?></td>
                    <td><?php echo $row['AssetsManagement']['VendorName']?></td>
                    <td><?php echo $row['AssetsManagement']['InstallationDate'] !=""?date('d-m-Y',strtotime($row['AssetsManagement']['InstallationDate'])):"";?></td>
                    <td><?php echo $row['AssetsManagement']['RentAmount']?></td>
                    <td><?php echo $row['AssetsManagement']['CreateDate'] !=""?date('d-m-Y',strtotime($row['AssetsManagement']['CreateDate'])):"";?></td>
                    <td><?php echo $row['AssetsManagement']['CreateBy']?></td>           
                    <td><?php echo $row['AssetsManagement']['UpdateDate'] !=""?date('d-m-Y',strtotime($row['AssetsManagement']['UpdateDate'])):"";?></td>
                    <td><?php echo $row['AssetsManagement']['UpdateBy']?></td>
                    <td>
                        <a href="<?php echo $this->webroot;?>AssetsManagements/index?Id=<?php echo $row['AssetsManagement']['Id']?>"> <span class="fa fa-edit" style="font-size:15px;" ></span></a>
                        <a href="<?php echo $this->webroot;?>AssetsManagements/delete?Id=<?php echo $row['AssetsManagement']['Id']?>" onclick="return confirm('Are you sure you want to delete this record?');" > <span class="fa fa-trash" style="font-size:15px;" ></span></a>
                    </td>
                </tr>
                <?php }?>
            </tbody>   
            </table>
        </div>
    </div>
</div>





