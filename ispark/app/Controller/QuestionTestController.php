<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class QuestionTestController extends AppController {

    public $uses = array('QuestParagraph','QuestListTmp', 'QuestList','QuestUserTest','QuestUserAns','QuestUserMark');
     public $components = array('RequestHandler');
    public $helpers = array('Js');

    public function beforeFilter() {
        parent::beforeFilter();
        
        
        
        $this->Auth->allow('index', 'view_papers', 'start_exam', 'exam', 'result','view_result','view_result_report');
    }

    public function index() {
        
        $this->layout='view';
        if($this->request->is('POST'))
        {
            $mob_no = $this->request->data['QuestionTest']['mob_no'];
            $name = $this->request->data['QuestionTest']['name'];
            $post = $this->request->data['QuestionTest']['post'];
            
            
            
            $this->QuestUserTest->save(array('mob_no'=>$mob_no,'user_name'=>$name,'post_applied'=>$post,'created_at'=>date('Y-m-d H:i:s')));
            $testuserid = $this->QuestUserTest->getLastInsertID();
            $this->Session->write("testuserid",$testuserid);
            $this->Session->write("testusername",$name);
            $this->Session->write("testuserpost",$post);
            
            
            
            return $this->redirect(array('controller'=>'QuestionTest','action' => 'view_papers'));
        }
        
        
    }

    public function view_papers()
    {
        //echo $this->Session->check("testuserid"); exit;
        if(!$this->Session->check("testuserid"))
	{
            return $this->redirect(array('controller'=>'QuestionTest','action' => 'index'));
	}
        $userid = $this->Session->read("testuserid");
        $this->layout='view';
         $record_para = $this->QuestList->query("select * from tbl_quest_paragraph tqp ");
        $this->set('record_para',$record_para);
        
    }
    
    public function start_exam()
    {
        $id      =$_REQUEST['id'];
        
        if(!$this->Session->check("testuserid"))
	{
            return $this->redirect(array('controller'=>'QuestionTest','action' => 'index'));
	}
        
        $qry = "select * from tbl_quest_paragraph tqp where id='$id'";
        $record_para = $this->QuestList->query($qry);
        $record_data = $record_para['0']['tqp'];
        $passing_mark = $record_data['mark_pass'];
        $para_no_quest = $record_data['no_of_quest'];
        $total_mark = $record_data['total_mark'];
        
        $tat_time = $record_data['para_time'];
        $test_time_arr = explode(':',$tat_time);
        
        $hour = $test_time_arr[0];
        $hour = intval($hour);
        
        $minute = $test_time_arr[1];
        $minute = intval($minute);
        
        $second = $test_time_arr[2];
        $second = intval($second);
        
        $time_second = $hour*3600+$minute*60+$second;
        
        //$total_time = $minute*60+$second;
        echo $time_in = date('Y-m-d H:i:s');
        echo '<br/>';
        $strtime = strtotime("+$minute minutes", strtotime($time_in));
        $strtime = strtotime("+$second seconds", $strtime);
        echo $total_time = date('Y-m-d H:i:s',$strtime); 
                
        $this->Session->write('test_timeout',$total_time);
        $this->Session->write('test_tat_time',$tat_time);
        $this->Session->write('test_tat_in',$time_in);
        $this->Session->write('test_second',$time_second);
        $userid = $this->Session->read("testuserid");
        
        
        
        $this->QuestUserMark->save(array('test_id'=>$userid,'para_id'=>$id,'stage'=>'exam_start','para_total_mark'=>$total_mark,
                'para_pass_mark'=>$passing_mark,'para_no_quest'=>$para_no_quest,
                'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$userid));
        
        $mark_id = $this->QuestUserMark->getLastInsertID();
        $req = array('id'=>$id,'mark_id'=>$mark_id);
        $req_json = json_encode($req);
        $req_encrypt = base64_encode($req_json);
        
        return $this->redirect(array('controller'=>'QuestionTest','action' => "exam?req=$req_encrypt"));
        
        
    }

    public function exam()
    {
        if(!$this->Session->check("testuserid"))
	{
            $this->Session->setFlash(_('Session Has been expired'));
            return $this->redirect(array('controller'=>'QuestionTest','action' => 'index'));
	}
        
        
        $req_encrypt      =$_REQUEST['req'];
        $req_json = base64_decode($req_encrypt);
        $req = json_decode($req_json,true);
        
        $id      =$req['id'];
        $mark_id = $req['mark_id'];
        
        $test_act = $this->QuestUserMark->query("SELECT stage FROM `tbl_quest_user_mark` tqm WHERE mark_id='$mark_id'");
        $stage = $test_act['0']['tqm']['stage']; 
        
        if($stage=='exam_finish')
        {
            $this->Session->setFlash(_('Your Session Has been expired'));
            return $this->redirect(array('controller'=>'QuestionTest','action' => "view_result?para_id=$id&mark_id=$mark_id"));
        }
        else if($stage=='in_exam')
        {
             $this->QuestUserMark->query("update tbl_quest_user_mark set stage='Miscellaneous Activities',para_mark_obt='0',
                para_attempt_quest='0',para_right_ans='0',para_time_taken=TIMEDIFF(now(),created_at),"
                    . "para_test_result='Fail',updated_at=now() where mark_id='$mark_id'");
            
            $this->Session->setFlash(_('Your Session Has been expired due to Miscellaneous Activities'));
            return $this->redirect(array('controller'=>'QuestionTest','action' => "view_result?para_id=$id&mark_id=$mark_id"));
        }
        
        $test_act = $this->QuestUserMark->query("update tbl_quest_user_mark tqm set stage='in_exam' where mark_id='$mark_id'");
        
        
        
        $userid = $this->Session->read("userid");  
        $this->layout='view_exam';
        $qry = "select * from tbl_quest_paragraph tqp where id='$id'";
        $record_para = $this->QuestList->query($qry);
        $record_data = $record_para['0']['tqp'];
        $this->set('para',$record_data);
        $this->set('mark_id',$mark_id);
        
        
        $question_all = $this->QuestList->query("select * from tbl_quest tqt where para_id='$id' ");
        $this->set('question_all',$question_all);
        
    }
    
    
    public function result()
    {
        //print_r($this->request->data); exit;
        if(!$this->Session->check("testuserid"))
	{
            $this->Session->setFlash(_('Your Session Has been expired'));
            return $this->redirect(array('controller'=>'QuestionTest','action' => 'index'));
	}
        if($this->request->is('POST'))
        {
            $request = $this->request->data;
            
            $para_id = $request['para_id']; 
            $mark_id = $request['mark_id'];  
            $userid = $this->Session->read("testuserid");   
            
            $test_act = $this->QuestUserMark->query("SELECT stage FROM `tbl_quest_user_mark` tqm WHERE mark_id='$mark_id'");
            $stage = $test_act['0']['tqm']['stage']; 
            
            if($stage!='in_exam')
            {
                $this->Session->setFlash(_('Your Session Has been expired due to Miscellaneous Activities'));
                return $this->redirect(array('controller'=>'QuestionTest','action' => "view_result?para_id=$para_id&mark_id=$mark_id"));
            }
            
            
            $question_all = $this->QuestList->query("select * from tbl_quest tqt where para_id='$para_id' ");
            
            $para_attempt_quest = 0; $para_right_ans = 0; $obt_mark = 0;
            foreach($question_all as $quest)
            {
                $quest_id = $quest['tqt']['quest_id'];
                $answers = $request['quest_'.$quest_id];
                
                $real_answer_str = $quest['tqt']['ans1'];
                $real_answer_list = explode('#',$real_answer_str);
                
                /*print_r($answers);
                echo '<br/>';
                
                print_r($real_answer_list);
                echo '<br/>';
                $result = array_diff($answers,$real_answer_list);*/
                $mark = 0; 
                if(!empty($answers))
                {
                    $result = array_diff($answers,$real_answer_list);
                    if(empty($result))
                    {
                        $mark = $quest['tqt']['marks'];
                        $para_right_ans++;
                    }
                    $para_attempt_quest++;
                }
                else
                {
                    $mark = 0;
                }
                
                $ans = implode('#',$answers);
                $insert_ans = "insert into tbl_quest_user_ans set test_id='$userid',para_id='$para_id',quest_id='$quest_id',choice='$ans',created_at=now() ";
                $this->QuestUserAns->query($insert_ans);
                
                $obt_mark +=$mark; 
            }
            
            $qry = "select * from tbl_quest_paragraph tqp where id='$para_id'";
            $record_para = $this->QuestList->query($qry);
            $record_data = $record_para['0']['tqp'];
            $passing_mark = $record_data['mark_pass'];
            $para_no_quest = $record_data['no_of_quest'];
            
            $result_status = "";
            if($obt_mark>=$passing_mark)
            {
                $result_status= "Pass";
            }
            else
            {
                $result_status= "Fail";
            }
            
            
            $this->QuestUserAns->query("update tbl_quest_user_ans set mark_id='$mark_id' where test_id='$userid'");
            
            $this->QuestUserMark->query("update tbl_quest_user_mark set stage='exam_finish',para_mark_obt='$obt_mark',
                para_attempt_quest='$para_attempt_quest',
                para_right_ans='$para_right_ans',para_time_taken=TIMEDIFF(now(),created_at),"
                    . "para_test_result='$result_status',updated_at=now() where mark_id='$mark_id'");
            
            
            
            $req = array('para_id'=>$para_id,'mark_id'=>$mark_id);
            $req_json = json_encode($req);
            $req_encrypt = base64_encode($req_json);
            
            return $this->redirect(array('controller'=>'QuestionTest','action' => "view_result?req=$req_encrypt"));
            
        }
        exit;
        
    }
    
    public function view_result()
    {
        
        $req_encrypt      =$_REQUEST['req'];
        $req_json = base64_decode($req_encrypt);
        $req = json_decode($req_json,true);
        
        
        $para_id = $req['para_id']; 
        $mark_id = $req['mark_id'];  
        $userid = $this->Session->read("testuserid");
        $user_det = $this->QuestList->query("select * from tbl_quest_user_register tqu where reg_id='$userid'");
        $this->set('user_det',$user_det);
        
        $test_det = $this->QuestList->query("select * from tbl_quest_user_mark tqm where mark_id='$mark_id'");
        $this->set('test_det',$test_det);
        
        $qry = "select * from tbl_quest_paragraph tqp where id='$para_id'";
        $record_para = $this->QuestList->query($qry);
        $this->set('record_para',$record_para);
        
        //print_r($test_det); exit;
        $this->Session->destroy();
        $this->layout = 'view';
        
        
    }
    
    public function view_report()
    {
        $this->layout = 'home';
        $para_id = $_REQUEST['para_id'];
        
        $record_all = $this->QuestList->query("select * from tbl_quest tqt where para_id='$para_id' ");
        $record_para = $this->QuestList->query("select * from tbl_quest_paragraph tqp where id='$para_id' ");
        
        $this->set('quest_unique_id',$para_id);
        $this->set('record_all',$record_all);
        $this->set('record_para',$record_para);
    }
    public function view_para()
    {
        $this->layout = 'home';
        $record_para = $this->QuestList->query("select * from tbl_quest_paragraph tqp ");
        $this->set('record_para',$record_para);
    }
    
    public function view_para_detail()
    {
        $this->layout = 'home';
        $para_id = $_REQUEST['para_id'];
        
        $record_all = $this->QuestList->query("select * from tbl_quest tqt where para_id='$para_id' ");
        $record_para = $this->QuestList->query("select * from tbl_quest_paragraph tqp where id='$para_id' ");
        
        $this->set('quest_unique_id',$para_id);
        $this->set('record_all',$record_all);
        $this->set('record_para',$record_para);
    }
    
}

?>
