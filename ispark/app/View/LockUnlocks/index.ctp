<?php ?>
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
                    <span>LOCK UNLOCK</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content box-con">
                <?php echo $this->Form->create('LockUnlocks',array('class'=>'form-horizontal','action'=>'index','id'=>'showDetails')); ?>
                <div class="form-group">  
                    <div class="col-sm-6">
                        <table class = "table table-striped table-hover  responstable" style="font-size: 13px;" >     
                           <thead>
                               <tr>
                                   <th>Branch</th>
                                   <!--
                                   <th style="width: 60px;text-align: center;" >Leave</th>
                                   -->
                                   <th style="width: 60px;text-align: center;">OD</th>
                                   <th style="width: 150px;text-align: center;">Exception</th>
                                   <!--
                                   <th style="width: 150px;text-align: center;">Override Leave</th>
                                   <th style="width: 150px;text-align: center;">Override OD</th>
                                   <th style="width: 150px;text-align: center;">Override Exception</th>
                                   -->
                               </tr>
                            </thead>
                            <tbody> 
                                <?php foreach ($branchName as $branch){ ?>
                                <tr>
                                    <td><?php echo $branch; ?><input type="hidden" name="BranchName[]" value="<?php echo $branch; ?>"></td>
                                    <!--
                                    <td><center><input <?php if($lokulok[$branch]['Leave']=="Yes"){echo "checked";} ?>  class="checkbox" type="checkbox" value="Yes" name="Leave[<?php echo $branch; ?>]"></center></td>
                                    -->
                                    <td><center><input <?php if($lokulok[$branch]['OD']=="Yes"){echo "checked";} ?> class="checkbox" type="checkbox" value="Yes" name="OD[<?php echo $branch; ?>]"></center></td>
                                    <td><center><input <?php if($lokulok[$branch]['Exception']=="Yes"){echo "checked";} ?> class="checkbox" type="checkbox" value="Yes" name="Exception[<?php echo $branch; ?>]"></center></td>
                                    <!--
                                    <td><center><input <?php if($lokulok[$branch]['OverrideLeave']=="Yes"){echo "checked";} ?> class="checkbox" type="checkbox" value="Yes" name="OverrideLeave[<?php echo $branch; ?>]"></center></td>
                                    <td><center><input <?php if($lokulok[$branch]['OverrideOD']=="Yes"){echo "checked";} ?> class="checkbox" type="checkbox" value="Yes" name="OverrideOD[<?php echo $branch; ?>]"></center></td>
                                    <td><center><input <?php if($lokulok[$branch]['OverrideException']=="Yes"){echo "checked";} ?> class="checkbox" type="checkbox" value="Yes" name="OverrideException[<?php echo $branch; ?>]"></center></td>
                                    -->
                                </tr>
                                <?php }?>
                            </tbody>   
                       </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-4">
                        <input onclick='return window.location="<?php echo $_SERVER['HTTP_REFERER'];?>"' type="button" value="Back" class="btn btn-primary btn-new pull-right" style="margin-left: 5px;" />
                        <?php echo $this->Form->submit('Submit', array('div'=>false, 'name'=>'Submit','class'=>'btn btn-primary pull-right btn-new'));?>
                    </div>
                    <div class="col-sm-10">
                    <span><?php echo $this->Session->flash(); ?></span>
                    </div>
                </div>
    
                
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>	
</div>



