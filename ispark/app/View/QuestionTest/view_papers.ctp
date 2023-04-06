

    


<div class="box-content">
            <div class="text-center">
                    <h3 class="page-header">Test Paper</h3>
            </div>
            <?php
               echo  $this->Session->flash();
            ?>
            <?php echo $this->Form->create('QuestionTest',array('url'=>'test_paper','autocomplete'=>"off")); ?>
                    <?php foreach($record_para as $rec) { ?>
                    <div class="form-group">
                    <?php echo '<a href="start_exam?id='.$rec['tqp']['id'].'" >'.$rec['tqp']['heading_name'].'</a>'; ?>
                    </div>
                    <?php } ?>
            <?php echo $this->Form->end(); ?>
    </div>