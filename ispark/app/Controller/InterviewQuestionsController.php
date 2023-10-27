<?php
class InterviewQuestionsController extends AppController {
    public $uses = array('QuestionPaper','User','DesignationNameMaster','InterviewQuiz','Masjclrentry','InterviewQuizResult','InterviewQuizAnswer','OnboardJoinAlert','InterviewMarking','InterviewPaperType');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','mcq_question','add_question_paper','edit_question_paper','delete_question_paper',
        'subjective_question','view_question','delete_question','edit_mcq_question','edit_subjective_question',
        'view_subjective_answer','mark_subjective_answer','report','getposition_marking','getposition','view_result','getpaper_name','getquestion','marking_formula','add_question_type','delete_question_type','delete_marking');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }


    public function index(){

        $this->layout='home'; 

    }


    public function mcq_question(){
        $this->layout='home';

        $userid = $this->Session->read("userid");

        $paper_name = $this->QuestionPaper->find("all",array('conditions'=>array('status'=>1),'order'=>array('paper_name')));
        $this->set('paper_name',$paper_name);

        $mcq_quest=$this->InterviewQuiz->find('all',array('conditions'=>array('type'=>'MCQ')));
        $this->set('mcq_quest',$mcq_quest);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                $dataArr = array();
                $data = $this->request->data;
                
                $paper_id = $data[1]['value'];
                $question = $data[3]['value'];
                $radio = $data[4]['value'];
               $answer = $data[5]['value'];
                
                $quest_type = $data['2']['value']; 

                

                //$question1 =  trim($question, "Powered by Froala Editor");
                $question1 = substr($question, 0, -229);
                if($question1 == "")
                {
                    // $this->Session->setFlash();
                    // $this->redirect(array('controller'=>'InterviewQuestions','action'=>'mcq_question'));
                    echo 'Please Enter Question';die;
                }
                $option = array();
                foreach($data as $d=>$k)
                {
                    
                    
                        
                    if($k['name'] != "ques_paper_id" && $k['name'] != "answer" && $k['name'] != "question" && $k['name'] != "_method" && $k['name'] != "POST" && $k['name'] != "custom" && $k['name'] != "quest_type")
                    {
                        $ds = $k['value'];
                        $dataArr[$k['name']] = addslashes($ds);
                        $option[] = $ds;
                        if ($quest_type!='Other')
                        {
                            $post_start = strpos($ds,'{'); 
                            $post_end = strpos($ds,'}'); 
                            if ($post_start)
                            {
                                $choice_marks = substr($ds,$post_start+1,$post_end); 
                                $dataArr[$k['name']] = addslashes(substr($ds,0,$post_start));
                                $choice_marks2 = str_replace(
                                    array("{","}"),
                                    array("", ""),
                                    $choice_marks
                                );
                                $dataArr[$k['name'].'_mark'] = addslashes($choice_marks2);

                            }
                        }
                    }
                        
                    
                }
                #print_r($dataArr);exit;
                #exit;
                // echo $answer;
                // print_r($option);die;

                $no_of_opt = count($option);
                $flag = false;
                if($radio == 'answer')
                {

                    if(in_array($answer,$option))
                    {
                        $flag = true;
                        $dataArr['type'] = "MCQ";
                    }
                }else{

                    $flag = true;
                    $dataArr['type'] = "MCQ-Self";
                    $answer = '';
                }

                

                if($flag)
                {
                    $paper_position       =   $this->QuestionPaper->find('first',array('conditions'=>array('id'=>$paper_id)));
                    $position    =   $paper_position['QuestionPaper']['position'];

                    $dataArr['no_of_opt'] = $no_of_opt;
                    $dataArr['ques_paper_id'] = $paper_id;
                    $dataArr['quest_type'] = $quest_type;
                    $dataArr['position'] = $position;
                    $dataArr['answer'] = $answer;
                    $dataArr['question'] = $question1;
                    $dataArr['created_at'] = date('Y-m-d H:i:s');
                    $dataArr['created_by'] = $userid;

                    #print_r($dataArr);die;

                    $this->InterviewQuiz->save($dataArr);
                    // $this->Session->setFlash('Question Add Successfully');
                    // $this->redirect(array('controller'=>'InterviewQuestions','action'=>'mcq_question'));
                    echo '1';die;

                }else{

                    // $this->Session->setFlash('Please Enter Correct Answer');
                    // $this->redirect(array('controller'=>'InterviewQuestions','action'=>'mcq_question'));
                    echo 'Please Enter Correct Answer';die;
                }


            }

        }     
    }

    public function subjective_question(){
        $this->layout='home';

        $userid = $this->Session->read("userid");

        $paper_name = $this->QuestionPaper->find("list",array('fields'=>array('id','paper_name'),'conditions'=>array('status'=>1),'order'=>array('paper_name')));
        $this->set('paper_name',$paper_name);

        $sub_quest=$this->InterviewQuiz->find('all',array('conditions'=>array('type'=>'Subjective')));
        $this->set('sub_quest',$sub_quest);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;
                // EmpOnService
                $dataArr = array();
                $data = $this->request->data;
                $data_val = $data['InterviewQuestions'];

                $question = $this->request->data['question'];
                $dataArr['ques_paper_id'] = $data_val['ques_paper_id'];


                $paper_position       =   $this->QuestionPaper->find('first',array('conditions'=>array('id'=>$data_val['ques_paper_id'])));
                $position    =   $paper_position['QuestionPaper']['position'];

                $dataArr['position'] = $position;

                //$question1 =  trim($question, "Powered by Froala Editor");
                $question1 = substr($question, 0, -229);
                if($question1 == "")
                {
                    $this->Session->setFlash('Please Enter Question');
                    $this->set('ques_id',$data_val['ques_paper_id']);
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'subjective_question'));
                }else{

                    $dataArr['type'] = "Subjective";
                    $dataArr['question'] = $question1;
                    $dataArr['created_at'] = date('Y-m-d H:i:s');
                    $dataArr['created_by'] = $userid;

                    $this->InterviewQuiz->save($dataArr);
                    $this->Session->setFlash('Question Add Successfully');
                    $this->set('ques_id',$data_val['ques_paper_id']);
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'subjective_question'));
                }

            }

        }     
    }

    public function add_question_paper(){
        $this->layout='home';
        

        $userid = $this->Session->read("userid");

        $PostionArray=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation','Designation'),'conditions'=>array('Status'=>1),'GROUP'=>array('Designation')));            
        $new_post = array();
        foreach($PostionArray as $position)
        {
            $new_post[$position] = strtoupper($position);
        }
        $this->set('positionName',$new_post);

        $Question = $this->QuestionPaper->find("all",array('conditions'=>array('status'=>1),'order'=>array('position','priority')));
        $this->set('Question_Arr',$Question);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                $data = array();
                $ques = $this->request->data;
                $ques = $ques['InterviewQuestion'];

                $position = $ques['position'];
                $type =  $ques['type'];
                $paper_name = $ques['paper_name'];
                $paper_time = $ques['exam_time'];
                $paper_marks = $ques['paper_marks'];

                //$priority_Arr = $this->QuestionPaper->find("first",array('conditions'=>array('status'=>1,'position'=>$position)));
                $priority_Arr=$this->QuestionPaper->query("select max(priority) as priority from question_paper where status='1' and position = '$position'");
                if(!empty($priority_Arr))
                {
                    $priority = $priority_Arr[0][0]['priority']+1;
                }else{
                    $priority = 1;
                }
                
                //$priority =  $ques['priority'];
                
              
                $data['position'] = $position;
                $data['type'] = $type;
                $data['paper_name'] = $paper_name;
                $data['paper_time'] = $paper_time;
                $data['paper_marks'] = $paper_marks;
                $data['priority'] = $priority;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = $userid;
                //print_r($data);die;
                $this->QuestionPaper->save($data);
                $this->Session->setFlash('Question Paper Add Successfully');
                $this->redirect(array('controller'=>'InterviewQuestions','action'=>'add_question_paper'));
                

            }

        }     
    }

    public function edit_question_paper(){
        $this->layout='home';
        
        $id = $this->request->query['id'];

        $PostionArray=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation','Designation'),'conditions'=>array('Status'=>1),'GROUP'=>array('Designation')));            
        $this->set('positionName',$PostionArray);

        $edit_Ques=$this->QuestionPaper->find('first',array('conditions'=>array('id'=>$id)));

        $this->set('edit_Ques',$edit_Ques);
          
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {  
                
                $ques = $this->request->data;
                //print_r($ques);die;

                $userid = $this->Session->read("userid");

                $ques = $ques['InterviewQuestion'];
                $position = $ques['position'];
                $type     =  $ques['type'];
                $paper_name = $ques['paper_name'];
                $paper_time = $ques['exam_time'];
                $paper_marks = $ques['paper_marks'];
                $priority =  $ques['priority'];

                $update_id =  $ques['close_id'];
                

                $dataArr=array(

                    'position'=>"'".$position."'",
                    'type'=>"'".$type."'",
                    'paper_name'=>"'".$paper_name."'",
                    'paper_time'=>"'".$paper_time."'",
                    'paper_marks'=>"'".$paper_marks."'",
                    'priority'=>"'".$priority."'",
                    'updated_at'=>"'".date('Y-m-d H:i:s')."'",
                    'updated_by'=>"'".$userid."'"
                );

                $save = $this->QuestionPaper->updateAll($dataArr,array('id'=>$update_id));
                if($save)
                {
                    $this->Session->setFlash('Question Paper Updated Successfully');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'add_question_paper'));
                }
                
                

            }

        }     
    }


    public function delete_question_paper()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];

            $dataArr['status'] = "'0'";
            $save = $this->QuestionPaper->updateAll($dataArr,array('id'=>$id));   
    
        }
        $this->redirect(array('controller'=>'InterviewQuestions','action'=>'add_question_paper'));
    }

    public function view_question(){
        $this->layout='home';
        

        $PostionArray=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation','Designation'),'conditions'=>array('Status'=>1),'GROUP'=>array('Designation')));            
        $this->set('positionName',array_merge(array('ALL'=>'ALL'),$PostionArray));

        $paper_name = $this->QuestionPaper->find("list",array('fields'=>array('id','paper_name'),'conditions'=>array('status'=>1),'order'=>array('paper_name')));
        $paper_name1 = array('ALL'=>'ALL')+$paper_name;
        $this->set('paper_name',$paper_name1);
        
        if($this->request->is('Post'))
        {

            $type = $this->request->data['InterviewQuestions']['type'];
            $position = $this->request->data['InterviewQuestions']['position'];


            if($type !="ALL"){$condition['ques_paper_id']=$type;}else{unset($condition['ques_paper_id']);}
            if($position !="ALL"){$condition['position']=$position;}else{unset($condition['position']);}

            $condition['active']=1;
            //print_r($condition);die;
            $data = $this->InterviewQuiz->find('all',array('conditions'=>$condition,'order'=>array('question')));
            //print_r($data);die;
            

            $this->set('data',$data);
        }  
    }

    public function delete_question()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];

            $dataArr['active'] = "'0'";
            $save = $this->InterviewQuiz->updateAll($dataArr,array('id'=>$id));
            if($save)   
            {
                echo "Deleted Successfully";die;
            }
    
        }
        //$this->redirect(array('controller'=>'InterviewQuestions','action'=>'view_question'));
    }

    public function edit_mcq_question(){
        $this->layout='home';
        
        $id = $this->request->query['id'];

        $paper_name = $this->QuestionPaper->find("list",array('fields'=>array('id','paper_name'),'conditions'=>array('status'=>1),'order'=>array('paper_name')));
        $this->set('paper_name',$paper_name);

        $edit_mcq=$this->InterviewQuiz->find('first',array('conditions'=>array('id'=>$id)));

        $no_of_opt = $edit_mcq['InterviewQuiz']['no_of_opt'];

        $answer = array();
        $option_marks = array();

        for($x = 1; $x <= $no_of_opt; $x++) {

            $answer[] = $edit_mcq['InterviewQuiz']['choice'.$x];
            //$option_marks[] = $edit_mcq['InterviewQuiz']['choice'.$x.'_mark'];

          }


        $this->set('option',$answer);
        $this->set('edit_mcq',$edit_mcq);
          
        if($this->request->is('Post')){

            if(!empty($this->request->data))
            {  
                //print_r($this->request->data);die;

                $userid = $this->Session->read("userid");
                $dataArr = array();
                $data = $this->request->data;
                $data_val = $data['InterviewQuestions'];

                $question = $this->request->data['question'];
                $update_id = $this->request->data['update_id'];
                $answer = $this->request->data['answer'];
                $radio = $this->request->data['custom'];
                $quest_type = $this->request->data['quest_type'];

                //$question1 =  trim($question, "Powered by Froala Editor");
                $question1 = substr($question, 0, -229);
                if($question1 == "")
                {
                    $this->Session->setFlash('Please Enter Question');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'edit_mcq_question?id='.$update_id));
                }
                $option = array();
                foreach($data_val as $k=>$v)
                {
                    if($k != "ques_paper_id")
                    {
                        
                        $dataArr[$k] = "'".addslashes($v)."'";
                        $option[] = $v;
                        
                    }

                    
                    
                }
                //print_r($dataArr);die;
                if ($quest_type!='Other')
                {
                    for($i=1;$i<5; $i++)
                    {
                        $key = 'choice'.$i;
                        $ds = $data_val['choice'.$i];
                        #print_r($ds);exit;
                    $post_start = strpos($ds,'{'); 
                    $post_end = strpos($ds,'}'); 
                    if ($post_start)
                    {
                        $choice_marks = substr($ds,$post_start+1,$post_end); 
                        $dataArr[$key] = "'".addslashes(substr($ds,0,$post_start))."'";
                        $choice_marks2 = str_replace(
                            array("{","}"),
                            array("", ""),
                            $choice_marks
                        );
                        $dataArr[$key.'_mark'] = "'".addslashes($choice_marks2)."'";

                    }
                    }
                    
                }
               
                $no_of_opt = count($option);
                $flag =false;
                if($radio == 'answer')
                {
                    if(in_array($answer,$option))
                    {
                        $flag =true;
                        $dataArr['type'] = "'MCQ'";
                    }
                }else{

                    $flag = true;
                    $dataArr['type'] = "'MCQ-Self'";
                    
                }
                

                if($flag)
                {
                    $paper_position       =   $this->QuestionPaper->find('first',array('conditions'=>array('id'=>$data_val['ques_paper_id'])));
                    $position    =   $paper_position['QuestionPaper']['position'];

                    $dataArr['quest_type'] = "'".$quest_type."'";
                    $dataArr['no_of_opt'] = "'".$no_of_opt."'";
                    $dataArr['ques_paper_id'] = "'".$data_val['ques_paper_id']."'";
                    $dataArr['position'] = "'".$position."'";
                    $dataArr['answer'] = "'".$answer."'";
                    $dataArr['question'] = "'".$question1."'";
                    $dataArr['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                    $dataArr['updated_by'] = "'".$userid."'";
                    //print_r($dataArr);die;
                    //$this->InterviewQuiz->save($dataArr);
                    $save = $this->InterviewQuiz->updateAll($dataArr,array('id'=>$update_id));
                    $this->Session->setFlash('Question Updated Successfully');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'view_question'));

                }else{

                    $this->Session->setFlash('Please Enter Correct Answer');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'edit_mcq_question?id='.$update_id));
                }


            }

        }     
    }


    public function edit_subjective_question(){
        $this->layout='home';
        
        $id = $this->request->query['id'];

        $paper_name = $this->QuestionPaper->find("list",array('fields'=>array('id','paper_name'),'conditions'=>array('status'=>1),'order'=>array('paper_name')));
        $this->set('paper_name',$paper_name);

        $edit_subjective=$this->InterviewQuiz->find('first',array('conditions'=>array('id'=>$id)));

        $this->set('edit_sub',$edit_subjective);
          
        if($this->request->is('Post')){

            if(!empty($this->request->data))
            {  

                $dataArr = array();

                $userid = $this->Session->read("userid");
                $data = $this->request->data;
                $data_val = $data['InterviewQuestions'];

                $question = $this->request->data['question'];
                $update_id = $this->request->data['update_id'];
                $dataArr['ques_paper_id'] = $data_val['ques_paper_id'];

                $paper_position       =   $this->QuestionPaper->find('first',array('conditions'=>array('id'=>$data_val['ques_paper_id'])));
                $position    =   $paper_position['QuestionPaper']['position'];

                $dataArr['position'] = "'".$position."'";


                $question1 = substr($question, 0, -229);
                if($question1 == "")
                {
                    $this->Session->setFlash('Please Enter Question');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'edit_subjective_question?id='.$update_id));

                }else{

                    $dataArr['question'] = "'".$question1."'";
                    $dataArr['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                    $dataArr['updated_by'] = "'".$userid."'";

                    $save = $this->InterviewQuiz->updateAll($dataArr,array('id'=>$update_id));
                    $this->Session->setFlash('Question Updated Successfully');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'view_question'));
                }

            }

        }     
    }


    public function view_subjective_answer(){
        $this->layout='home';
        
        //$paper_name = $this->InterviewQuizAnswer->find("list",array('fields'=>array('interview_id','name'),'conditions'=>array('OR'=>array('paper_type'=>'Subjective','paper_type'=>'MCQ-Self')),'GROUP'=>array('interview_id')));
        $paper_name=$this->InterviewQuizAnswer->query("select * from interview_quiz_answer where (paper_type = 'Subjective' || paper_type='MCQ-Self') group by interview_id");
        //print_r($paper_name);die;
        $this->set('paper_name',$paper_name);

        // $paper = $this->QuestionPaper->find("list",array('fields'=>array('id','paper_name'),'conditions'=>array(),'order'=>array('paper_name')));
        // $this->set('paper',$paper);

        $PostionArray=$this->DesignationNameMaster->find('list',array('fields'=>array('Designation','Designation'),'conditions'=>array('Status'=>1),'GROUP'=>array('Designation')));            
        $this->set('positionName',$PostionArray);

        if(isset($_REQUEST['paper_name']) && $_REQUEST['position'] !="")
        {
            //print_r($_REQUEST);die;
            $interview_id = $_REQUEST['paper_name'];
            $position = $_REQUEST['position'];
            $paper = $_REQUEST['paper'];
            $wheretag = '';

            if($position !="")
            {
                $wheretag .= "and qr.position = '$position'";
            }
            //echo "SELECT * FROM interview_quiz_result qr LEFT JOIN interview_quiz_answer qa ON qr.paper_id = qa.paper_id WHERE qa.interview_id='$interview_id' and qapaper_id='$paper' AND qr.status='Check by Admin' AND (qa.paper_type = 'Subjective' || qa.paper_type='MCQ-Self') $wheretag ";die;
            $subjective_data=$this->InterviewQuizResult->query("SELECT * FROM interview_quiz_result qr LEFT JOIN interview_quiz_answer qa ON qr.paper_id = qa.paper_id WHERE qa.interview_id='$interview_id' and qa.paper_id='$paper' AND qr.status='Check by Admin' AND (qa.paper_type = 'Subjective' || qa.paper_type='MCQ-Self') $wheretag ");
           
            $data_Arr = array();
            foreach($subjective_data as $subject)
            {
                $paper_id = $subject['qa']['paper_id'];
                $ques_id = $subject['qa']['ques_id'];

                $exist_paper = $this->QuestionPaper->find('first',array('conditions'=>array('id'=>$paper_id)));
                $exist_question = $this->InterviewQuiz->find('first',array('conditions'=>array('id'=>$ques_id)));

                $Get_name['paper_name'] = $exist_paper['QuestionPaper']['paper_name'];
                $Get_name['question'] = $exist_question['InterviewQuiz']['question'];

                $data_Arr[] = array_merge($subject,$Get_name);
            }
            if(!empty($data_Arr)){?>
            <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
            <form action="mark_subjective_answer" method="post">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Name</th>
                            <th>Position</th>
                            <!-- <th>Paper Name</th> -->
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Create Date</th>
                            <th style="text-align: center;">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($data_Arr as $data){ 
                            $interview_id = $data['qr']['interview_id'];
                            $ques_id = $data['qa']['ques_id'];
                            $paper_id = $data['qr']['paper_id'];
                            $result = $data['qr']['result'];?>
                         <tr>
                            <td><?php echo $n++;?></td>
                            <td><?php echo $data['qa']['name'];?></td>
                            <td><?php echo $data['qa']['position'];?></td>
                            <!-- <td><?php //echo $data['paper_name'];?></td> -->
                            <td><?php echo $data['question'];?></td>
                            <td><?php echo $data['qa']['ans'];?></td>
                            <td><?php if(!empty($data['qr']['created_at'])){ echo date_format(date_create($data['qr']['created_at']),"d-M-Y"); } ?></td>
                            <td style="text-align: center;">
                            <!-- <a href="#" onclick="Setstatus('Correct','<?php //echo $interview_id; ?>','<?php //echo $ques_id; ?>')">Correct</a>
                           || 
                           <a href="#" onclick="Setstatus('Wrong','<?php //echo $interview_id; ?>','<?php //echo $ques_id; ?>')">Wrong</a></td> -->
                           <label>Right</label>
                           <input type="radio" id="right" name="status[<?php echo $interview_id; ?>][<?php echo $ques_id; ?>]" id="correct" value = "correct" required>
                           <label>Wrong</label>
                           <input type="radio" id="wrong" name="status[<?php echo $interview_id; ?>][<?php echo $ques_id; ?>]" id="wrong" value="wrong" required>
                         </tr>

                      <?php }?>
                    </tbody>
                </table>
                <input type="hidden" name="result" value="<?php echo $result; ?>">
                <input type="hidden" name="paper_id" value="<?php echo $paper_id; ?>">
                <input type="Submit" name="submit" value="Update" class="btn pull-right btn-primary btn-new" style="margin-right: 20px;">
                </form>
            </div><?php } die;
        }  
        
        

    }
    public function mark_subjective_answer()
    {
        if($this->request->is('Post')){
            
            if(!empty($this->request->data))
            {
                //print_r($this->request->data);die;
                $data = $this->request->data['status'];
                $paper_id = $this->request->data['paper_id'];

                $result = $this->request->data['result'];

                //$question_Arr = $this->InterviewQuizAnswer->find("all",array('conditions'=>array('paper_type'=>'Subjective','paper_type'=>'MCQ-Self','correct_ans'=>'')));
                $question_Arr=$this->InterviewQuizAnswer->query("SELECT * FROM interview_quiz_answer WHERE correct_ans = '' AND (paper_type = 'Subjective' || paper_type='MCQ-Self')");
                //print_r($question_Arr);die;
                $dataArr = array();
                $right_ans = 0;
                $wrong_ans = 0;
                foreach($question_Arr as $ques)
                {
                    $interview_id = $ques['interview_quiz_answer']['interview_id'];
                    $ques_id = $ques['interview_quiz_answer']['ques_id'];

                    $user_ans = $data[$interview_id][$ques_id];
                    if($user_ans == 'correct')
                    {
                        $right_ans +=1;
                        //$this->InterviewQuizAnswer->query("UPDATE `interview_quiz_answer` SET `correct_ans`='Right' WHERE interview_id='$interview_id' AND ques_id='$ques_id' ");
                    }
                    if($user_ans == 'wrong')
                    {
                        $wrong_ans +=1;
                        //$this->InterviewQuizAnswer->query("UPDATE `interview_quiz_answer` SET `correct_ans`='Right' WHERE interview_id='$interview_id' AND ques_id='$ques_id' ");
                    }
                }
                
                $paper_name=$this->QuestionPaper->find('first',array('conditions'=>array('id'=>$paper_id)));

                $total_ques =$this->InterviewQuiz->find('count',array('conditions'=>array('ques_paper_id'=>$paper_id)));
                $paper_marks = $paper_name['QuestionPaper']['paper_marks'];

                $total_ans =  $right_ans + $wrong_ans;

                $marks = $paper_marks/$total_ques;

                $result_marks = $marks*$right_ans;
                // $count1 = $result_marks / $paper_marks;
                // $count2 = $count1 * 100;
                $result_marks1 = round($result_marks,2);
                $count = $result + $result_marks1;

                $status = '';
                if($count < '70')
                {
                    $status .=  "Should not hire";
                }
                else if($count >= '70' && $count <= '79')
                {
                    $status .=  "Risky";
                }
                else if($count >= '80' && $count <= '89')
                {
                    $status .=  "Average";
                }
                else if($count >= '90')
                {
                    $status .=  "Ideal";
                }
                // echo $status;
                // echo $count;die;

                $save = $this->InterviewQuizResult->updateAll(array('status'=>"'".$status."'",'result'=>"'".$count."'"),array('interview_id'=>$interview_id,'paper_id'=>$paper_id));
                if($save)
                {
                    $this->Session->setFlash('Answer Updated Successfully');
                    $this->redirect(array('controller'=>'InterviewQuestions','action'=>'view_subjective_answer'));
                }
                
                
            }
        }
    }

    public function report()
    {
        $this->layout='home';

        $interview_name = $this->InterviewQuizResult->find("list",array('fields'=>array('interview_id','name'),'group'=>array('interview_id')));

        //print_r($interview_name);die;

        $this->set('interview_name',array('ALL'=>'ALL')+$interview_name);

        if(isset($_REQUEST['name']) && $_REQUEST['name'] !="")
        {

            //print_r($_REQUEST);die;
            $wheretag = '';
            if($_REQUEST['name'] !="ALL")
            {
                $wheretag .= " and interview_id = '{$_REQUEST['name']}'";
            }
           
            if($_REQUEST['position'] !="ALL")
            {
                $wheretag .= " and position = '{$_REQUEST['position']}'";
            }

            $from = date("Y-m-d",strtotime($_REQUEST['From']));
            $to = date("Y-m-d",strtotime($_REQUEST['To']));

            $qry = "select * from interview_quiz_result where DATE(created_at)>='$from' AND DATE(created_at)<='$to'  $wheretag group by interview_id";
            $data_arr   =   $this->InterviewQuizResult->query($qry);

            if(!empty($data_arr))
            {
               ?>
                <div class="col-sm-12" style="overflow-y:scroll;height:500px; " >
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Candidate Name</th>
                            <th>Mobile Number</th>
                            <th>Position Applied For</th>
                            <th style="text-align: center;">View Result</th>
                        </tr>
                    </thead>
                    <tbody> 
                      
                        <?php $n=1; foreach($data_arr as $data){ $name = base64_encode($data['interview_quiz_result']['interview_id']);
                            $position = base64_encode($data['interview_quiz_result']['position']);?>
                         <tr>
                         <td><?php if(!empty($data['interview_quiz_result']['created_at'])){ echo date_format(date_create($data['interview_quiz_result']['created_at']),"d-M-Y"); } ?></td>
                            <td><?php echo $data['interview_quiz_result']['name'];?></td>
                            <td><?php echo $data['interview_quiz_result']['phone_no'];?></td>
                            <td><?php echo $data['interview_quiz_result']['position'];?></td>
                            <td style="text-align: center;"> <a href="view_result?interview_id=<?php echo $name;?>&position=<?php echo $position;?>"><i class="fa fa-eye"></i></a></td>
                         </tr>

                      <?php }?>
                      
                    </tbody>   
                </table>
            </div>

            <?php }die;
            //$dataArr     =   $this->ContinuouslyLeave->find('all',array('conditions'=>array_merge($condition,array('Status'=>1)))); 
        }
    }

    public function view_result()
    {
        $this->layout='home';
        
        if(isset($_REQUEST['interview_id'])){
            $name = base64_decode($_REQUEST['interview_id']);
            $position = base64_decode($_REQUEST['position']);
            $data=$this->InterviewQuizResult->find('all',array('conditions'=>array('interview_id'=>$name)));
            //print_r($data);die;
            $DataArr = array();
            foreach($data as $d)
            {
                $paper_id = $d['InterviewQuizResult']['paper_id'];

                $paper=$this->QuestionPaper->find('first',array('conditions'=>array('id'=>$paper_id)));
                $paper_name = $paper['QuestionPaper']['paper_name'];

                $paper_question=$this->InterviewQuiz->find('first',array('conditions'=>array('ques_paper_id'=>$paper_id)));
                $paper_type = $paper_question['InterviewQuiz']['type'];

                

                $process['paper_name'] = $paper_name;
                $process['paper_type'] = $paper_type;

                $DataArr[] = array_merge($d,$process);
            }

            $ques_ans = $this->InterviewQuizAnswer->find("all",array('conditions'=>array('interview_id'=>$name)));
            $DataQ = array();
            foreach($ques_ans as $q)
            {
                $inter=$this->InterviewQuiz->find('first',array('conditions'=>array('id'=>$q['InterviewQuizAnswer']['ques_id'])));
                $paper_type = $inter['InterviewQuiz']['quest_type'];


                $paper_id = $q['InterviewQuizAnswer']['paper_id'];
                $ques_id = $q['InterviewQuizAnswer']['ques_id'];
                $paper_Arr=$this->QuestionPaper->find('first',array('conditions'=>array('id'=>$paper_id)));
                $paper_name = $paper_Arr['QuestionPaper']['paper_name'];
                $ques_Arr=$this->InterviewQuiz->find('first',array('conditions'=>array('id'=>$ques_id)));
                $ques_name = $ques_Arr['InterviewQuiz']['question'];

                $process1['paper_name'] = $paper_name;
                $process1['ques_name'] = $ques_name;
                $process1['quest_type'] = $paper_type;

                $DataQ[] = array_merge($q,$process1);
            }
             //print_r($DataQ);die;

            $this->set('name',$data[0]['InterviewQuizResult']['name']);
            $this->set('position',$position);
            $this->set('data',$DataArr);
            $this->set('ques_ans',$DataQ);
        }

    }

    public function getposition_marking()
    {
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !=""){
            
            $conditoin['interview_id']=$_REQUEST['name'];
            //print_r($conditoin);die;
            $data = $this->InterviewQuizAnswer->find('list',array('fields'=>array('position','position'),'conditions'=>$conditoin,'group' =>array('position')));
            

            //echo "<option value='ALL'>ALL</option>";

            if(!empty($data)){
                
                foreach ($data as $val){
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
        }
    }

    public function getposition()
    {
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !=""){
            
            $conditoin['interview_id']=$_REQUEST['name'];
            //print_r($conditoin);
            $data = $this->InterviewQuizResult->find('list',array('fields'=>array('position','position'),'conditions'=>$conditoin,'group' =>array('interview_id')));
            //print_r($data);die;

            echo "<option value='ALL'>ALL</option>";

            if(!empty($data)){
                
                foreach ($data as $val){
                    echo "<option value='$val'>$val</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
        }
    }
    public function getpaper_name()
    {
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !=""){
            
            $conditoin['interview_id']=$_REQUEST['name'];
            
            $data = $this->InterviewQuizResult->find('all',array('conditions'=>$conditoin));

            //echo "<option value='ALL'>ALL</option>";

            if(!empty($data)){

                foreach ($data as $val){

                    $paper_id = $val['InterviewQuizResult']['paper_id'];

                    $name = $this->QuestionPaper->find('all',array('conditions'=>array('id'=>$paper_id)));
                    //print_r($name);
                    echo $paper_name = $name[0]['QuestionPaper']['paper_name'];
                    echo $id = $name[0]['QuestionPaper']['id'];

                    echo "<option value='$id'>$paper_name</option>";
                }
                die;
            }
            else{
                echo "";die;
            }
            
            
        }
    }

    public function getquestion()
    {
        if(isset($_REQUEST['name']) && $_REQUEST['name'] !=""){
            
            $conditoin['ques_paper_id']=$_REQUEST['name'];
            $ques_paper_id = $_REQUEST['name'];

            if($_REQUEST['type'] == 'MCQ')
            {
                $data=$this->InterviewQuiz->query("select * from interview_quiz where ques_paper_id ='$ques_paper_id' and  (type = 'MCQ' || type='MCQ-Self') and active='1'");
            }else if($_REQUEST['type'] == 'Subjective')
            {
                $data=$this->InterviewQuiz->query("select * from interview_quiz where ques_paper_id ='$ques_paper_id' and type = 'Subjective' and active='1'");
            }
            

            //echo "<option value='ALL'>ALL</option>";

            if(!empty($data)){
                echo "<table class = 'table table-striped table-hover  responstable' style='margin-top:-10px;' >";    
                   echo  "<thead>";
                        echo "<tr><th colspan='15' style='text-align: center;' >MCQ Questions</th></tr>";
                        echo "<tr>
                            <th style='text-align: center;'>SNo.</th>
                            <th style='text-align: center;'>Question</th>
                            <th style='text-align: center;'>Assign To Job Role</th>
                        </tr>";
                    echo "</thead>
                    <tbody>";
                    $i=1; foreach ($data as $val){
                        echo "<tr>";
                        echo "<td style='text-align: center;'>".$i++."</td>";
                        echo "<td style='text-align: center;'>".$val['interview_quiz']['question']."</td>";
                        echo "<td style='text-align: center;'>".$val['interview_quiz']['position']."</td>";
                    
                    echo "</tr>";
                    }
                   echo" </tbody>   
                </table> ";die;
                   
            }
            else{
                echo "";die;
            }
            
            
        }
    }

    public function marking_formula()
    {
        $this->layout='home';

        $data = $this->InterviewMarking->find('all');
        $this->set('data',$data);

        if($this->request->is('Post')){

            if(!empty($this->request->data))
            { 
                //print_r($this->request->data);die;
                $democratic = $this->request->data['democratic'];
                $let_it_be = $this->request->data['let_it_be'];
                $situational = $this->request->data['situational'];
                $authoritative = $this->request->data['authoritative'];

                $Compromising = $this->request->data['Compromising'];
                $Collaborating = $this->request->data['Collaborating'];
                $Competing = $this->request->data['Competing'];
                $Avoiding = $this->request->data['Avoiding'];
                $Accomodating = $this->request->data['Accomodating'];

                $Good = $this->request->data['Good'];
                $Average = $this->request->data['Average'];
                $Below_Average = $this->request->data['Below_Average'];
                $Poor = $this->request->data['Poor'];
                
                $result = $this->request->data['result'];
                $dataArr = array();

                $dataArr['Democratic'] = $democratic;
                $dataArr['Let_it_be'] = $let_it_be;
                $dataArr['Situational'] = $situational;
                $dataArr['Authoritative'] = $authoritative;

                $dataArr['Compromising'] = $Compromising;
                $dataArr['Collaborating'] = $Collaborating;
                $dataArr['Competing'] = $Competing;
                $dataArr['Avoiding'] = $Avoiding;
                $dataArr['Accomodating'] = $Accomodating;
                $dataArr['Good'] = $Good;
                $dataArr['Average'] = $Average;
                $dataArr['Below_Average'] = $Below_Average;
                $dataArr['Poor'] = $Poor;

                $dataArr['result'] = $result;

                $this->InterviewMarking->save($dataArr);
                $this->Session->setFlash('Formula Add Successfully');
                $this->redirect(array('controller'=>'InterviewQuestions','action'=>'marking_formula'));

            }
        }

    }

    public function add_question_type()
    {
        $this->layout='home';

        $data = $this->InterviewPaperType->find('all');
        $this->set('data',$data);

        if($this->request->is('Post')){

            //print_r($this->request->data);die;

            $paper_type     =   addslashes(trim($this->request->data['paper_type']));
            $submit         =   trim($this->request->data['submit']);
            
            $data=array(
                'paper_type'=>$paper_type,
                'status'=>'1'
            );
            
            $row=$this->InterviewPaperType->find('count',array('conditions'=>$data));
            if($row > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This Paper Type already exist in database.</span>');
                $this->redirect(array('controller'=>'InterviewQuestions','action'=>'add_question_type')); 
            }
            else{
                $this->InterviewPaperType->save($data);
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This Paper Type create successfully.</span>');
                $this->redirect(array('controller'=>'InterviewQuestions','action'=>'add_question_type'));  
            }
                     
            
        }

    }

    public function delete_question_type()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];

            $dataArr['status'] = "'0'";
            $save = $this->InterviewPaperType->updateAll($dataArr,array('id'=>$id));
            if($save)   
            {
                echo "Deleted Successfully";die;
            }
    
        }
        //$this->redirect(array('controller'=>'InterviewQuestions','action'=>'view_question'));
    }
    public function delete_marking()
    {
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            
            $id = $_REQUEST['Id'];

            $save = $this->InterviewMarking->query("DELETE FROM `interview_marking` WHERE id='$id'");
            echo "Deleted Successfully";die;

            //$save = $this->InterviewMarking->updateAll($dataArr,array('id'=>$id));
            if($save)   
            {
                echo "Deleted Successfully";die;
            }
    
        }
    }

    
      
}
?>