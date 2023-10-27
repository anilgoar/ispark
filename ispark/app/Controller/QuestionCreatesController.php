<?php

class QuestionCreatesController extends AppController {

    public $uses = array('QuestParagraph','QuestListTmp', 'QuestList','QuestUserAns','QuestUserMark');
     public $components = array('RequestHandler');
    public $helpers = array('Js');

    public function beforeFilter() {
        parent::beforeFilter();
        if(!$this->Session->check("userid"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
        $this->Auth->allow('index', 'add_question', 'delete_question', 'save_paragraph',
                'update_para','deactivate_para_detail','view_para','view_para_detail','view_user_test_result');
    }

    public function index() {
        $this->layout = 'home';
        $userid = $this->Session->read("userid");
        $unique_id = "";
        
        if(!$this->Session->check("quest_unique_id"))
        {
            $unique_id = "$userid".'_'.date('Ymdhis');
            $this->Session->write('quest_unique_id',$unique_id);
        }
        else
        {
            $unique_id = $this->Session->read("quest_unique_id");
        }
        
        $record_all = $this->QuestList->query("select * from tbl_quest_tmp tqt where para_id='$unique_id' ");
        
        $this->set('quest_unique_id',$unique_id);
        $this->set('record_all',$record_all);
        
    }

    public function add_question()
    {
        $userid = $this->Session->read("userid");
        $head_name      =$_REQUEST['head_name'];
        $para_graph     =$_REQUEST['para_graph'];
        $total_time     =$_REQUEST['total_time'];
        $passing        =$_REQUEST['passing'];
    
        $question       =addslashes($_REQUEST['question']);
        $ans_type       =$_REQUEST['ans_type'];
        $options        =addslashes($_REQUEST['options']);
        $ans            =addslashes($_REQUEST['ans']);
        $mark           =$_REQUEST['mark'];
        $unique_id      = $_REQUEST['unique_id'];
        
        if($head_name=='')
        {
            $record['status'] = "Error";
            $record['field'] = "head_name";
            $record['msg'] = "Please Fill Heading Name";
        }
        else if($para_graph=='')
        {
            $record['status'] = "Error";
            $record['field'] = "para_graph";
            $record['msg'] = "Please Fill Paragraph";
        }
        else if($total_time=='')
        {
            $record['status'] = "Error";
            $record['field'] = "para_time";
            $record['msg'] = "Please Fill Total Time";
        }
        else if($passing=='')
        {
            $record['status'] = "Error";
            $record['field'] = "passing_mark";
            $record['msg'] = "Please Fill Passing Mark";
        }
        else if($question=='')
        {
            $record['status'] = "Error";
            $record['field'] = "question";
            $record['msg'] = "Please Fill Question";
        }
        else if($ans_type=='')
        {
            $record['status'] = "Error";
            $record['field'] = "ans_type";
            $record['msg'] = "Please Choose Single/Multi Choise Answer";
        }
        else if($options=='')
        {
            $record['status'] = "Error";
            $record['field'] = "options";
            $record['msg'] = "Please Fill Field Options";
        }
        else if($ans=='')
        {
            $record['status'] = "Error";
            $record['field'] = "ans";
            $record['msg'] = "Please Fill Answers";
        }
        else if($mark=='')
        {
            $record['status'] = "Error";
            $record['field'] = "mark";
            $record['msg'] = "Please Fill Mark";
        }
        
        if(empty($record['status']))
        {
            $option_list = array();
            if(strpos($options, '#')===true)
            {
                $option_list = explode('#',$options);
                $option_list = array_filter($option_list);
                $options = implode('#',$option_list);
            }
            
            $ans_list = array();
            if(strpos($ans, '#')===true)
            {
                $ans_list = explode('#',$ans);
                $ans_list = array_filter($ans_list);
                $ans = implode('#',$ans_list);
            }
            
            $record_exist = $this->QuestList->query("select * from tbl_quest_tmp where para_id='$unique_id' and quest='$question' limit 1");
            
            if(!empty($record_exist))
            {
                $record['status'] = "Error";
                $record['field'] = "mark";
                $record['msg'] = "Question Allready Exist";
            }
            else
            {
                /*$ins = "INSERT INTO `tbl_quest_tmp` SET para_id='$unique_id',quest='$question',marks='$mark',ans_type='$ans_type',
                opt1='$options',ans1='$ans',
            created_at=now(),created_by='$userid'"; */
                $this->QuestListTmp->save(array('para_id'=>$unique_id,'quest'=>$question,'marks'=>$mark,'ans_type'=>$ans_type,
                'opt1'=>$options,'ans1'=>$ans,
            'created_at'=>date('Y-m-d H:i:s'),'created_by'=>"$userid"));
            
                $id = $this->QuestListTmp->getLastInsertID();
            
                $htm =  '<tr id="'.$id.'">';
                $htm .=  '<td>'.$question.'</td>';
                $htm .=  '<td>'.$options.'</td>';
                $htm .=  '<td>'.$ans_type.'</td>';
                $htm .=  '<td>'.$mark.'</td>';
                $htm .=  '<td>'.$ans.'</td>';
                $htm .=  '<td>'.'<a href="#" onclick="delete_quest('."'$id'".');" class="btn btn-primary btn-new pull-center">Delete</a>'.'</td>';
                $htm .=  '</tr>';

                $record_total = $this->QuestList->query("select sum(marks) total from tbl_quest_tmp tqt where para_id='$unique_id' ");
                
                $record['status'] = "Success";
                $record['field'] = $htm;
                $record['msg'] = "Question Added Successfully.";
                $record['id'] = $id;
                $record['total'] = $record_total['0']['0']['total'];
            }
        }
        
        
        echo json_encode($record); exit;
    }

    public function save_paragraph()
    {
        $userid = $this->Session->read("userid");
        $head_name      = addslashes($_REQUEST['head_name']); 
        $para_graph     = addslashes($_REQUEST['para_id']);
        $total_time     =$_REQUEST['para_time'];
        $passing        =$_REQUEST['passing_mark'];
    
        $unique_id      = $_REQUEST['unique_id'];
        $record_exist = $this->QuestListTmp->query("select * from tbl_quest_tmp where para_id='$unique_id'");
        
        if($head_name=='')
        {
            $record['status'] = "Error";
            $record['field'] = "head_name";
            $record['msg'] = "Please Fill Heading Name";
        }
        else if($para_graph=='')
        {
            $record['status'] = "Error";
            $record['field'] = "para_graph";
            $record['msg'] = "Please Fill Paragraph";
        }
        else if($total_time=='')
        {
            $record['status'] = "Error";
            $record['field'] = "para_time";
            $record['msg'] = "Please Fill Total Time";
        }
        else if($total_time=='00:00')
        {
            $record['status'] = "Error";
            $record['field'] = "para_time";
            $record['msg'] = "Please Fill Total Time";
        }
        else if($passing=='')
        {
            $record['status'] = "Error";
            $record['field'] = "passing_mark";
            $record['msg'] = "Please Fill Passing Mark";
        }
        else if($passing=='0')
        {
            $record['status'] = "Error";
            $record['field'] = "passing_mark";
            $record['msg'] = "Please Fill Passing Mark";
        }
        else if($passing>100)
        {
            $record['status'] = "Error";
            $record['field'] = "passing_mark";
            $record['msg'] = "Passing Marks should not be greater than 100%";
        }
        else if(empty($record_exist))
        {
            $record['status'] = "Error";
            $record['field'] = "add question";
            $record['msg'] = "Please Add Questions First";
        }
        
        if(empty($record['status']))
        {
            
                /*$ins = "INSERT INTO `tbl_quest_tmp` SET para_id='$unique_id',quest='$question',marks='$mark',ans_type='$ans_type',
                opt1='$options',ans1='$ans',
            created_at=now(),created_by='$userid'"; */
                
            
            
            
            
            
                $this->QuestParagraph->save(array('heading_name'=>$head_name,'paragraph'=>$para_graph,'para_time'=>$total_time,'total_mark'=>'0','passing_mark'=>$passing,
                'para_active'=>'1',
            'created_at'=>date('Y-m-d H:i:s'),'created_by'=>"$userid"));
                $para_id = $this->QuestParagraph->getLastInsertID();
            
                $record_all = $this->QuestList->query("select * from tbl_quest_tmp tqt where para_id='$unique_id' ");
                
                //print_r($record_all); exit;
                $no_of_quest = count($record_all);
                $total = 0;
                foreach($record_all as $record_old)
                {
                    $this->QuestList->saveAll(array('para_id'=>$para_id,'quest'=>$record_old['tqt']['quest'],'marks'=>$record_old['tqt']['marks'],
                'ans_type'=>$record_old['tqt']['ans_type'],'opt1'=>$record_old['tqt']['opt1'],'ans1'=>$record_old['tqt']['ans1'],
            'created_at'=>date('Y-m-d H:i:s'),'created_by'=>"$userid"));
                    
                  $total +=   $record_old['tqt']['marks'];
                  
                    
                }
                $passing_marks = round(($total*$passing)/100);
                $upd = "update tbl_quest_paragraph set total_mark='$total', mark_pass='$passing_marks',no_of_quest='$no_of_quest' where id='$para_id' limit 1"; 
                $this->QuestParagraph->query($upd);
                
                $this->QuestListTmp->query("delete from tbl_quest_tmp where para_id='$unique_id'");
                
                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px>"
                . "<b style=color:#FF0000>".'Questions Details Saved Successfully.'."</b></h4>"));
               return $this->redirect(array('controller'=>'QuestionCreates'));
            
        }
        
        
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px>"
                . "<b style=color:#FF0000>".$record['msg']."</b></h4>"));
        
         return $this->redirect(array('controller'=>'QuestionCreates'));
    }
    
    
    public function delete_question()
    {
        $row_id      =$_REQUEST['row_id'];
        $delete = "delete from tbl_quest_tmp where quest_id='$row_id'";
        $this->QuestList->query($delete);
        
        $record_total = $this->QuestList->query("select sum(marks) total from tbl_quest_tmp tqt where para_id='$unique_id' ");
        
        $record['status'] = "Success";
        $record['field'] = "delete";
        $record['msg'] = "Record has been deleted successfully.";
        $record['total'] = $record_total['0']['0']['total'];
        
        echo json_encode($record); exit;
        
    }
    
    public function deactivate_para_detail()
    {
        $this->layout = 'home';
        $para_id = $_REQUEST['para_id'];
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px>"
                . "<b style=color:#FF0000>".'Test Paper De-Activated Successfully.'."</b></h4>"));
        $record_all = $this->QuestList->query("update tbl_quest_paragraph set para_active='0' where id='$para_id' ");
         return $this->redirect(array('controller'=>'QuestionCreates','action'=>'view_para'));
    }
    
    public function view_para()
    {
        $this->layout = 'home';
        $record_para = $this->QuestList->query("select * from tbl_quest_paragraph tqp where para_active='1'");
        $this->set('record_para',$record_para);
    }
    
    
    public function view_para_detail()
    {
        $this->layout = 'home';
        $para_id = $_REQUEST['para_id'];
        
        $record_all = $this->QuestList->query("select * from tbl_quest tqt where para_id='$para_id' ");
        $record_para = $this->QuestList->query("select * from tbl_quest_paragraph tqp where id='$para_id' and para_active='1'");
        
        $this->set('quest_unique_id',$para_id);
        $this->set('record_all',$record_all);
        $this->set('record_para',$record_para);
    }
    
    public function view_user_test_result()
    {
       $this->layout = 'home';
       
       if($this->request->is('POST'))
       {
           $start_date = date('Y-m-d',strtotime($_REQUEST['FromDate']));
           $end_date = date('Y-m-d',strtotime($_REQUEST['ToDate']));
           $record_user = $this->QuestList->query("SELECT * FROM `tbl_quest_user_register` tqur
LEFT JOIN `tbl_quest_user_mark` tqum ON tqur.reg_id = tqum.test_id
LEFT JOIN `tbl_quest_paragraph` tp ON tqum.para_id = tp.id where date(tqur.created_at) between '$start_date' and '$end_date'");
           
           $fileName = "Test_Report_".date('Y_m_d_H_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$fileName.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

           
           echo '<table border="2">';
                echo '<thead><tr>';
                    echo '<th>Name</th>';
                    echo '<th>Mobile</th>';
                    echo '<th>Post</th>';
                    echo '<th>Date</th>';
                    echo '<th>Heading Name</th>';
                    echo '<th>Total Question</th>';
                    echo '<th>Total Mark</th>';
                    echo '<th>Passing Mark</th>';
                    echo '<th>Ateempt Question</th>';
                    echo '<th>Right Answer</th>';
                    echo '<th>Mark Obtain</th>';
                    echo '<th>Total Time Taken</th>';
                    echo '<th>Status</th>';
                echo '</tr></thead>';
                
                echo '<tbody>';
                
                foreach($record_user as $ru)
                {
                    echo '<tr>';
                        echo '<td>'.$ru['tqur']['user_name'];
                        echo '<td>'.$ru['tqur']['mob_no'];
                        echo '<td>'.$ru['tqur']['post_applied'];
                        echo '<td>'.$ru['tqur']['created_at'];
                        
                        echo '<td>'.$ru['tp']['heading_name'];
                        echo '<td>'.$ru['tp']['no_of_quest'];
                        echo '<td>'.$ru['tp']['total_mark'];
                        echo '<td>'.$ru['tp']['mark_pass'];
                        echo '<td>'.$ru['tqum']['para_attempt_quest'];
                        echo '<td>'.$ru['tqum']['para_right_ans'];
                        echo '<td>'.$ru['tqum']['para_mark_obt'];
                        echo '<td>'.$ru['tqum']['para_time_taken'];
                        echo '<td>'.$ru['tqum']['para_test_result'];
                        
                    echo '</tr>';
                }
                
                echo '</tbody>';
                
           echo '</table>'; exit;
       }
       
       
       //$this->set('record_user',$record_user);
    }
    
}

?>
