   
<!-- File: /app/View/UserType/index.ctp -->
  <div  class="row">
    
            <div class="col-xs-12 col-sm-12">
                <div class="box">
                    <div class="box-content" style="margin-left: 100px;margin-right: 100px;">
                            <div class="text-center">
                    <h3 class="page-header">Time <span id="timer" style="color: green;">00</span></h3>
                </div>
    <div class="text-center">
            <h3 class="page-header"><?php echo $para['heading_name']; ?></h3>
    </div>
    
    <?php echo $this->Form->create('QuestionTest',array('url'=>'result','id'=>'exam','name'=>'exam','autocomplete'=>"off",'class'=>'form-horizontal')); ?>
                        <div class="form-group" style="margin-left: 10px;margin-right: 10px;">
            <?php echo $para['paragraph']; ?>
            </div>
            <table style="margin-left: 20px;margin-right: 20px;">
        
    
            <?php $srno=1; 
                foreach($question_all as $quest) { $quest_id = $quest['tqt']['quest_id']; ?>
            <tr>
               <th colspan="2"> <?php echo "<b>Q. $srno {$quest['tqt']['quest']} </b>"; ?></th>
            </tr>
    
            <?php   $option_counting = array('1'=>'a)','2'=>'b)','3'=>'c)','4'=>'d)','5'=>'e)','6'=>'f)','7'=>'g)',
                '8'=>'h)','9'=>'i)','10'=>'j)','19'=>'k)','12'=>'l)','13'=>'m)','14'=>'n)','15'=>'o)','16'=>'p)','17'=>'q)');
                    $opt_str = $quest['tqt']['opt1']; 
                    $opt_arr = explode('#',$quest['tqt']['opt1']);
                    $opt_no = 1;
                    
                    foreach($opt_arr as $opt)
                    {
                        if($opt_no%2==1)
                        {
                            echo '<tr>';
                        }
                        echo '<td><input type="checkbox" id="quest_'.$quest_id.'_'.$opt_no.'" name="quest_'.$quest_id.'[]" value="'.$opt.'" /> &nbsp;'.$option_counting[$opt_no].'&nbsp;'.$opt.'</td>';
                        
                        if($opt_no%2==0 )
                        { 
                            echo '</tr>';
                        }
                        $opt_no++;
                    }
            ?>
            
            
            <?php $srno++; } ?>
            </table>
                        <br/>
            <div class="text-center">
                <input type="hidden" id="para_id" name="para_id" value="<?php echo $para['id']; ?>" />
                <input type="hidden" id="mark_id" name="mark_id" value="<?php echo $mark_id; ?>" />
                <input type="submit" id="btnsubmit" name="btnsubmit" value="Save & Close" class="btn btn-primary btn-new pull-center" />
            </div>
    <?php echo $this->Form->end(); ?>
</div>
                </div>
            </div>
	</div>  

<script>
    
    function save_user_answers()
    {
        document.getElementById('exam').submit();
    }
    
    
    var timer = <?php echo $this->Session->read('test_second'); ?>;
    function show_timer() {
        timer = timer-1;
        
        var hour = Math.floor(timer/3600);
        var minute = Math.floor(Math.floor(timer%3600)/60);
        var second = Math.floor(Math.floor(timer%3600)%60);
        var string_hour = "";
        var string_minute = "";
        var string_second = "";
        
        if(hour<10)
        {
            string_hour = "0"+hour;
        }
        else
        {
            string_hour = hour;
        }
        
        if(minute<10)
        {
            string_minute = "0"+minute;
        }
        else
        {
            string_minute = minute;
        }
        
        if(second<10)
        {
            string_second = "0"+second;
        }
        else
        {
            string_second = second;
        }
        
        
        document.getElementById('timer').innerHTML = string_hour+':'+string_minute+':'+string_second;
        if(timer==0 || timer=='0')
        {
           
           save_user_answers();
        }
}


    setInterval(show_timer, 1000);
    
    
    
</script> 
