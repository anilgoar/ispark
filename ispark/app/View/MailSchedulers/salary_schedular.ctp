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

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header"  >
                <div class="box-name">
                    <span>Salary Mail Schedule</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content box-con" >
            <div class="widget-content nopadding">
                <h3><?php echo $this->Session->flash(); ?></h3>
                <form method="post" class="form-horizontal">
                <?php echo $this->Form->create('MailSchedulers',array('class'=>'form-horizontal')); ?>    
                    <table class="table table-bordered data-table" id="table_id">
                        <tr>
                            <th>Branch</th>
                            <th>Mail To</th>
                            <th>Mail CC</th>
                            <th>Mail BCC</th>
                            <th>Mail Send</th>
                            <th>Select</th>
                            <th>Time</th>
                        </tr>
                        <?php $i=1; foreach($BranchArr as $branchid=>$branch) { ?>
                        <tr>
                            <th>
                                <input type="text" id="Branch<?php echo $branchid; ?>"  name="Report[<?php echo $branchid; ?>][Branch]" placeholder="Branch Name" class="col-xs-10 col-sm-12" value="<?php echo $branch; ?>"  readonly=""  /></th>
                            <th>
                                <textarea          id="MailTo<?php echo $branchid; ?>"  name="Report[<?php echo $branchid; ?>][ReportTo]" placeholder="Mail To" class="col-xs-10 col-sm-12"><?php echo $MailSchedular[$branch]['ReportTo']; ?></textarea>
                            </th>
                            <th>
                                <textarea   readonly=""       id="MailCC<?php echo $branchid; ?>"  name="Report[<?php echo $branchid; ?>][ReportCC]" placeholder="Mail CC" class="col-xs-10 col-sm-12"   ><?php echo $MailSchedular[$branch]['ReportCC']; ?></textarea>
                            </th>
                            
                            <th>
                                <textarea    readonly=""      id="MailBCC<?php echo $branchid; ?>" name="Report[<?php echo $branchid; ?>][ReportBCC]" placeholder="Mail BCC" class="col-xs-10 col-sm-12"  ><?php echo $MailSchedular[$branch]['ReportBCC']; ?></textarea>
                            </th>
                            
                            <th>
                                <select            id="ScheduleType<?php echo $branchid; ?>" name="Report[<?php echo $branchid; ?>][ScheduleType]" placeholder="Schedule Type" class="col-xs-10 col-sm-12" onchange="get_schedule('<?php echo $branchid; ?>',this.value);">
                                <?php $days = explode(',',$MailSchedular[$branch]['ScheduleType']); ?>
                                    <option value="Select" >Select</option>
                                    <option value="Hour" <?php if(in_array('Hour',$days)) { echo "selected";} ?>>Hourly</option>
                                    <option value="Day" <?php if(in_array('Day',$days)) { echo "selected";} ?>>Daily</option>
                                    <option value="Week" <?php if(in_array('Week',$days)) { echo "selected";} ?>>Weekly</option>
                                    <option value="Month" <?php if(in_array('Month',$days)) { echo "selected";} ?>>Monthly</option>
                                </select>
                            </th>
                            <th><select           id="ScheduleTime<?php echo $branchid; ?>"  name="Report[<?php echo $branchid; ?>][ScheduleTime]" value="<?php echo $MailSchedular[$branch]['ScheduleType']; ?>" class="col-xs-10 col-sm-12">
                                    <option value="">Select</option>
                                    <?php if(!empty($MailSchedular[$branch]['ScheduleType'])) 
                                    { 
                                        if($MailSchedular[$branch]['ScheduleType']=='Week')
                                        {?>
                                          
                                            <option value="Sunday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Sunday') echo "Selected"; ?>>Sunday</option>
                                            <option value="Monday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Monday') echo "Selected"; ?>>Monday</option>
                                            <option value="Tuesday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Tuesday') echo "Selected"; ?>>Tuesday</option>
                                            <option value="Wednesday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Wednesday') echo "Selected"; ?>>Wednesday</option>
                                            <option value="Thursday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Thursday') echo "Selected"; ?>>Thursday</option>
                                            <option value="Friday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Friday') echo "Selected"; ?>>Friday</option>
                                            <option value="Saturday" <?php if($MailSchedular[$branch]['ScheduleTime']=='Saturday') echo "Selected"; ?>>Saturday</option>
                                  <?php }
                                        else if($MailSchedular[$branch]['ScheduleType']=='Month')
                                        {
                                            for($i=1;$i<=28;$i++)
                                            {?>
                                            <option value="<?php echo $i; ?>" <?php if($MailSchedular[$branch]['ScheduleTime']==$i) echo "Selected"; ?> ><?php echo $i; ?></option>   
                                        <?php    }
                                        }
                                    
                                    }
                                    ?>
                                </select>
                            </th>
                            <th>
                                <select         id="ScheduleValue<?php echo $branchid; ?>"        name="Report[<?php echo $branchid; ?>][ScheduleValue]" class="form-control"  style="width:110px;">
                                    <option value="<?php echo $row['ScheduleTime'];?>"><?php echo $row['ScheduleTime'];?></option>
                                    <?php
                                    //$i = "01";
                                    for($i=1;$i<=24;$i++)
                                    {
                                        if($i<='9') { $j="0".$i.":00:00";  } else { $j=$i.":00:00"; }
                                    ?>
                                    <option value="<?php echo $j;?>" <?php if($MailSchedular[$branch]['ScheduleValue']==$i) { echo 'Selected'; } ?>><?php echo $j;?></option>			
                                    <?php } ?>
                                </select>
                            </th>
                        </tr> 
                        <?php } ?>
                    </table>
                    <button class="btn btn-primary" type="submit" name="submit" value="submit">
                        Save Mail Schedule
                    </button>
                <?php echo $this->Form->end(); ?>
            </div>
</div>
        </div>
    </div>	
</div>



<script>
    function get_schedule(branch,val)
    {
        if(val=='Hour')
        {
            $('#'+'ScheduleTime'+branch).html('');
            $('#'+'ScheduleTime'+branch).prop('readonly',true);
        }
        else if(val=='Day')
        {
            $('#'+'ScheduleTime'+branch).html('');
            $('#'+'ScheduleTime'+branch).prop('readonly',true);
        }
        else if(val=='Week')
        {
            var options='<option value="">Select</option>';
            options +='<option value="Sunday">Sunday</option>';
            options +='<option value="Monday">Monday</option>';
            options +='<option value="Tuesday">Tuesday</option>';
            options +='<option value="Wednesday">Wednesday</option>';
            options +='<option value="Thursday">Thursday</option>';
            options +='<option value="Friday">Friday</option>';
            options +='<option value="Saturday">Saturday</option>';
            
            $('#'+'ScheduleTime'+branch).html(options);
            $('#'+'ScheduleTime'+branch).prop('readonly',false);
        }
        else if(val=='Month')
        {
            var options='<option value="">Select</option>';
            for(var i=1; i<=28; i++)
            {
                options +='<option value="'+i+'">'+i+'</option>';
            }
            options +='<option value="MonthEnd">MonthEnd</option>';
            $('#'+'ScheduleTime'+branch).html(options);
            $('#'+'ScheduleTime'+branch).prop('readonly',false);
        }
        
        
        
    }
    function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }

</script>