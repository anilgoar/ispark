<?php
class TrainingQuestionSetsController extends AppController {
    public $uses = array(
        'Addbranch','DepartmentNameMaster','TrainingQuestionSet','TrainingVideoSet','DesignationNameMaster','TrainingEmailSet',
        'TrainingProcess'
        );
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'index','view_question_set','view_question_pending_set','select_question','select_option','add_question','update_answer','delete_question',
            'upload_video','set_training_video','view_video_set','delete_video','editdept','get_set_no','set_training_mail',
            'view_mail','delete_mail','sent_delete_mail','sendmail','process_list','campaign_list','check_option','submit_question','check_video',
            'sent_training_details','send_view_mail','sendusermail','reset_training','training_process','delete_process',
            'training_campaign','delete_campaign','get_process_list','add_training_mail','Update_Percent'
            );
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 
        
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
    }
    
    public function process_list(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['Branch'];
            $Value          =   $request['Value'];            
            $conditoin      =   array("Branch='$Branch' AND Parent_Id IS NULL ORDER BY Process ASC");
            $data           =   $this->TrainingProcess->find('list',array('fields'=>array('Id','Process'),'conditions'=>$conditoin));
            
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                
                foreach ($data as $key=>$val){
                    $selected   =   $Value ==$key?"selected='selected'":"";
                    echo "<option $selected  value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function check_option(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            
            /*
            $Branch         =   "HEAD OFFICE";
            $Department     =   "INFORMATION TECHNOLOGY";
            $Desgination    =   "Executive - IT";
            $Process        =   "None Wise";
            $Campaign       =   "ARB";
            $Set_No         =   1;
            */
            
            $conditoin      =   array("Parent_Id IS NULL AND Branch='$Branch' AND Department='$Department' AND Designation='$Desgination' AND Set_No='$Set_No' AND SaveStatus='Pending'");
            $Pending        =   $this->TrainingQuestionSet->find('all',array('fields'=>array('Id','Answer'),'conditions'=>$conditoin));
            
            foreach($Pending as $row1){
                $Parent_Id      =   $row1['TrainingQuestionSet']['Id'];
                $Answer         =   $row1['TrainingQuestionSet']['Answer'];
                $Option         =   $this->TrainingQuestionSet->find('all',array('fields'=>array('Id'),'conditions'=>array('Parent_Id'=>$Parent_Id)));
  
                if(empty($Option)){
                    echo '1';die;
                }
                if(count($Option) < 2){
                    echo '2';die;
                }
                if($Answer ==""){
                    echo '3';die;
                }
            }
            die;
        }
    }
    
    public function campaign_list(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['Branch'];
            $Process        =   $request['Process'];
            $Value          =   $request['Value'];
            $conditoin      =   array("Branch='$Branch' AND Parent_Id='$Process' ORDER BY Campaign ASC");
            $data           =   $this->TrainingProcess->find('list',array('fields'=>array('Id','Process'),'conditions'=>$conditoin));
            
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    $selected   =   $Value ==$key?"selected='selected'":"";
                    echo "<option $selected  value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    
    
    public function view_question_pending_set(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            
            $Parent_Id      =   "Parent_Id IS NULL";
            $WBranch        =   "AND Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WSet_No        =   $Set_No !=""?"AND Set_No='$Set_No'":"";
            
            $conditoin      =   array("$Parent_Id $WBranch $WDepartment $WDesignation $WSet_No AND SaveStatus='Pending'");
            $data           =   $this->TrainingQuestionSet->find('all',array('conditions'=>$conditoin));
            if(!empty($data)){
            ?>
            <div class="col-sm-8" style="margin-top:-25px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th colspan="3" style="text-align: left;">Pending Question</th>
                        </tr>
                        <tr>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;foreach($data as $val){
                        $row=$val['TrainingQuestionSet'];
                        $data1           =   $this->TrainingQuestionSet->find('all',array('fields'=>array('Id','Question'),'conditions'=>array('Parent_Id'=>$row['Id'])));
                        ?>
                        <tr>
                            <td><?php echo $row['Question'];?></td>
                            <td>
                            <?php 
                            echo '<ol type="a">';
                            foreach($data1 as $va){
                                if($row['Answer'] ==$va['TrainingQuestionSet']['Id']){
                                    echo '<li>'.$va['TrainingQuestionSet']['Question'].'<span style="text-align: center;width:30px;margin-left:10px;" >&#10004;</span></li>';  
                                }
                                else{
                                    echo '<li>'.$va['TrainingQuestionSet']['Question'].'</li>';  
                                }
                            }
                            echo '</ol>';
                            ?> 
                           
                            </td>
                            
                            <td>
                                <span class='icon' ><i onclick="delete_question('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                            </td>
                            
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                
            </div>
            
            <?php
            }
            die;
        }
    }
    
    public function view_question_set(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            
            $Parent_Id      =   "Parent_Id IS NULL";
            //$WBranch        =   $Branch !="ALL"?"AND Branch='$Branch'":"";
            $WBranch        =   "AND Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WSet_No        =   $Set_No !=""?"AND Set_No='$Set_No'":"";
            
            $conditoin      =   array("$Parent_Id $WBranch $WDepartment $WDesignation $WSet_No AND SaveStatus='Done'");
            $data           =   $this->TrainingQuestionSet->find('all',array('conditions'=>$conditoin));
            if(!empty($data)){
            ?>
            <div class="col-sm-12" style="margin-top:-25px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;foreach($data as $val){
                        $row=$val['TrainingQuestionSet'];
                        $data1           =   $this->TrainingQuestionSet->find('all',array('fields'=>array('Id','Question'),'conditions'=>array('Parent_Id'=>$row['Id'])));
                        ?>
                        <tr  >
                            <td><?php echo $i++?></td>
                            <td><?php echo $row['Department']?></td>
                            <td><?php echo $row['Designation']?></td>
                            <td><?php echo $row['Question'];?></td>
                            <td>
                            <?php 
                            echo '<ol type="a">';
                            foreach($data1 as $va){
                                if($row['Answer'] ==$va['TrainingQuestionSet']['Id']){
                                    echo '<li>'.$va['TrainingQuestionSet']['Question'].'<span style="text-align: center;width:30px;margin-left:10px;" >&#10004;</span></li>';  
                                }
                                else{
                                    echo '<li>'.$va['TrainingQuestionSet']['Question'].'</li>';  
                                }
                            }
                            echo '</ol>';
                            ?> 
                          
                            </td>
                            
                            <td style="text-align: center;">
                                <span class='icon' ><i onclick="delete_question('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                            </td>
                            
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else{
                echo '<div class="col-sm-12" >Record Not Found.</div>';
            }
            die;
        }
    }
    
    public function select_question(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Set_No         =   $request['Set_No'];
            
            $Parent_Id      =   "Parent_Id IS NULL";
            $WBranch        =   "AND Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WSet_No        =   $Set_No !=""?"AND Set_No='$Set_No'":"";
            
            $conditoin      =   array("$Parent_Id $WBranch $WDepartment $WDesignation $WSet_No");
            $data           =   $this->TrainingQuestionSet->find('list',array('fields'=>array('Id','Question'),'conditions'=>$conditoin));
            
            echo "<option value=''>Select Question</option>";
            if(!empty($data)){
                foreach ($data as $key=>$val){
                    echo "<option  value='$key#####$val'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function select_option(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Id             =   $request['Id'];
            $Parent_Id      =   "Parent_Id='$Id'";
            $fields         =   array('Id','Question');
            $conditoin      =   array("$Parent_Id");
            
            $data           =   $this->TrainingQuestionSet->find('list',array('fields'=>$fields,'conditions'=>$conditoin));
            $data1          =   $this->TrainingQuestionSet->find('first',array('fields'=>array('Answer'),'conditions'=>array('Id'=>$Id)));
            $Answer         =   $data1['TrainingQuestionSet']['Answer'];
            if(!empty($data)){
            ?>
            <label>Option List</label>
            <ol type="A">
                <?php foreach ($data as $key=>$val){?>
                <li><?php echo $val?> <input type='radio' name='Answer' <?php if($Answer==$key){echo "checked";}?> onchange="update_answer('<?php echo $key?>','<?php echo $Id?>','update')" > <i onclick="update_answer('<?php echo $key?>','<?php echo $Id?>','delete')" class="fa fa-close" style="cursor:pointer;margin-left:10px;" ></i></li>
                <?php }?>
            </ol>
            <?php  
            }
            die;
        }
    }
    
    public function add_question(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            $Question       =   $request['Question'];
            $Parent_Id      =   $request['Parent_Id'];
            
            $data=array(
                'Branch'=>$Branch,
                'Department'=>$Department,
                'Designation'=>$Desgination,
                'Process'=>$Process,
                'Campaign'=>$Campaign,
                'Set_No'=>$Set_No,
                'Question'=>$Question,
            );
            
            $Parent_Id !=""?$data['Parent_Id']=$Parent_Id:$data['SaveStatus']="Pending";

            $count  =   $this->TrainingQuestionSet->find('count',array('conditions'=>$data));
            
            if($count > 0){
                echo "<span id='msgerr' style='color:red;font-size:11px;'>This question already exist.</span>";die;
            }
            else{
                if($this->TrainingQuestionSet->saveAll($data)){
                    echo "<span id='msgerr' style='color:green;font-size:11px;'>This question/option add successfully.</span>";die;
                }
                else{
                    echo "<span id='msgerr' style='color:green;font-size:11px;'>This question/option not added.</span>";die;
                }
            }  
            
        }
    }
    
    public function submit_question(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            
            $this->TrainingQuestionSet->query("UPDATE `Training_Question_Set` SET SaveStatus='Done' WHERE Branch='$Branch' AND Department='$Department' 
            AND Designation='$Desgination' AND `Process`='$Process' AND Campaign='$Campaign' AND Set_No='$Set_No' AND Parent_Id IS NULL");die;
            die;
        }
    }
    
    public function update_answer(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Type           =   $request['Type'];
            $Answer         =   $request['Answer'];
            $Parent_Id      =   $request['Parent_Id'];

            if($Type =="delete"){
                $this->TrainingQuestionSet->query("DELETE FROM `Training_Question_Set` WHERE Id='$Answer'");   
            }
            else{
                $this->TrainingQuestionSet->query("UPDATE `Training_Question_Set` SET Answer='$Answer' WHERE Id='$Parent_Id'");  
            }
            die;
        }
    }
    
    public function delete_question(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Id           =   $request['Id'];
            
            $this->TrainingQuestionSet->query("DELETE FROM `Training_Question_Set` WHERE Id='$Id'");
            $this->TrainingQuestionSet->query("DELETE FROM `Training_Question_Set` WHERE Parent_Id='$Id'");
            die;
        }
    }
    
    public function set_training_video(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 
        
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));
         
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['TrainingQuestionSets']['branch_name'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            $Percent        =   $request['Percent'];
            $Video_Name     =   $request['Video_Name'];
            
            $data=array(
                'Branch'=>$Branch,
                'Department'=>$Department,
                'Designation'=>$Desgination,
                'Process'=>$Process,
                'Campaign'=>$Campaign,
                'Set_No'=>$Set_No,
            );
            
            $this->set('data',$data);
            
            $count  =   $this->TrainingVideoSet->find('count',array('conditions'=>$data));
            
            if($count > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Set no '.$Set_No.' already exist in database.</span>');
                //$this->redirect(array('action'=>'set_training_video'));
            }
            else{
                $data['Percent']=$Percent;
                $data['Video_Name']=$Video_Name;
                $this->TrainingVideoSet->save($data);
                $lastid         =   $this->TrainingVideoSet->getLastInsertId();
                $video_temp     =   $_FILES['Video_File']['tmp_name']; 
                $video_info     =   explode(".",$_FILES['Video_File']['name']);
                $video_file     =   date('dmYhis').$lastid.'.'.$video_info['1'];
                $video_path     =   "/var/www/html/mascallnetnorth.in/ispark/app/webroot/training_video/";

                if(!file_exists($video_path)){ 
                    mkdir($video_path); 
                }

                if(move_uploaded_file($video_temp, $video_path.$video_file)){
                    $this->TrainingVideoSet->query("UPDATE `Training_Video_Set` SET Video_File='$video_file' WHERE `Id`='$lastid'");
                }
                
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This video upload successfully.</span>');
                //$this->redirect(array('action'=>'set_training_video'));
            }
            
        }
    }
    
    public function view_video_set(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $Set_No         =   $request['Set_No'];
            
            $WBranch        =   "Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WProcess       =   $Process !=""?"AND Process='$Process'":"";
            $WCampaign      =   $Campaign !=""?"AND Campaign='$Campaign'":"";
            
            $conditoin      =   array("$WBranch $WDepartment $WDesignation $WProcess $WCampaign");
            $data           =   $this->TrainingVideoSet->find('all',array('conditions'=>$conditoin,'order'=>'Set_No'));
            
            if(!empty($data)){
            ?>
            <script>
            function Show_Precent(id){
                if(confirm('Are you sure you want to edit this percent?')){
                    $(".Percent_Name").hide();
                    $(".Percent_Label,.icon").show();
                    $("#Percent_Label_"+id+",#icon_"+id).hide();
                    $("#Percent_Name_"+id).show(); 
                }
            }
            
            function Edit_Percent(Id){
                var Percent  =   $("#Update_Percent_"+Id).val();

                $.post("<?php echo $this->webroot;?>TrainingQuestionSets/Update_Percent",{Id:Id,Percent:Percent}, function(data){
                    view_video_set(); 
                });

            }
            </script>
            <div class="col-sm-12" style="margin-top:-25px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Process</th>
                            <th>Campaign</th>
                            <th>Set No</th>
                            <th>Percent</th>
                            <th>Video Name</th>
                            <th>Video File</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;foreach($data as $val){$row=$val['TrainingVideoSet'];?>
                        <tr>
                            <td><?php echo $i++?></td>
                            <td><?php echo $row['Department']?></td>
                            <td><?php echo $row['Designation']?></td>
                            <td><?php echo $this->PCNAME($row['Process']);?></td>
                            <td><?php echo $this->PCNAME($row['Campaign']);?></td>
                            <td><?php echo $row['Set_No'];?></td>
                            <td>
                                <span class="Percent_Label" id="Percent_Label_<?php echo $row['Id'];?>" ><?php echo $row['Percent'];?></span>
                                <span class="Percent_Name" id="Percent_Name_<?php echo $row['Id'];?>" style="display:none;"  >
                                    <input type="text" style="width:30px;" id="Update_Percent_<?php echo $row['Id']?>" value="<?php echo $row['Percent'];?>" >
                                    <input type="button" value="save" onclick="Edit_Percent('<?php echo $row['Id']?>')"  >
                                </span>
                                <span class="icon" id="icon_<?php echo $row['Id']?>" onclick="Show_Precent('<?php echo $row['Id']?>')" ><i class="material-icons" style="font-size:12px;cursor: pointer;margin-left: 10px;">mode_edit</i></span>
                            </td>
                            <td><?php echo $row['Video_Name'];?></td>
                            <td><?php echo $row['Video_File'];?></td>
                            
                            <td style="text-align: center;">
                                <span class='icon' ><i onclick="delete_video('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else{
                echo '<div class="col-sm-12" style="color:red;" >Record Not Found.</div>';
            }
            die;
        }
    }
    
    public function Update_Percent(){
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Id         =   $request['Id'];
            $Percent    =   $request['Percent'];
            $this->TrainingVideoSet->query("UPDATE `Training_Video_Set` SET Percent='$Percent' WHERE Id='$Id'");
            die;
        }
    }
    
    public function delete_video(){
        if($this->request->is('POST')){
            $request    =   $this->request->data;
            $Id         =   $request['Id'];
            $data       =   $this->TrainingVideoSet->find('first',array('conditions'=>array('Id'=>$Id)));
            $video_file =   $data['TrainingVideoSet']['Video_File'];
            $Branch     =   $data['TrainingVideoSet']['Branch'];
            $Department =   $data['TrainingVideoSet']['Department'];
            $Designation=   $data['TrainingVideoSet']['Designation'];
            $Process    =   $data['TrainingVideoSet']['Process'];
            $Campaign   =   $data['TrainingVideoSet']['Campaign'];
            $Set_No     =   $data['TrainingVideoSet']['Set_No'];

            $count      =   $this->TrainingQuestionSet->find('count',array('conditions'=>array('Branch'=>$Branch,'Department'=>$Department,'Designation'=>$Designation,'Process'=>$Process,'Campaign'=>$Campaign,'Set_No'=>$Set_No)));
            
            if($count > 0){
                echo '<span style="color:red;font-weight:bold;">This video set already used for question set.</span>';
            }
            else{
                $video_path =   "/var/www/html/mascallnetnorth.in/ispark/app/webroot/training_video/$video_file";
                /*
                if(unlink($video_path)){
                    $this->TrainingVideoSet->query("DELETE FROM `Training_Video_Set` WHERE Id='$Id'");
                }
                */
                unlink($video_path);
                $this->TrainingVideoSet->query("DELETE FROM `Training_Video_Set` WHERE Id='$Id'");
            }
            die;
        }
    }
    
    public function get_set_no(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            
            $WBranch        =   "Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WProcess       =   $Process !=""?"AND Process='$Process'":"";
            $WCampaign      =   $Campaign !=""?"AND Campaign='$Campaign'":"";
            
            $conditoin      =   array("$WBranch $WDepartment $WDesignation $WProcess $WCampaign");
            $data           =   $this->TrainingVideoSet->find('all',array('conditions'=>$conditoin,'order'=>'Set_No'));
            
            if(!empty($data)){
            ?>
            <option value="">Set No</option>
            <?php foreach($data as $row){?>
            <option value="<?php echo $row['TrainingVideoSet']['Set_No'];?>"><?php echo $row['TrainingVideoSet']['Set_No'].' ('.$row['TrainingVideoSet']['Video_Name'].')';?></option>
            <?php } ?>
            <?php
            }
            else{
                echo '<option value="">Set No</option>';
            }
            die;
        }
    }
    
    public function check_video(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            
            $count  =   $this->TrainingVideoSet->find('count',array('conditions'=>array('Branch'=>$Branch,'Department'=>$Department,'Designation'=>$Desgination,'Process'=>$Process,'Campaign'=>$Campaign)));
            
            if($count > 0){
                echo '1';
            }
            else{
                echo '';
            }
            die;
        }
    }

    public function set_training_mail(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 
        
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));        
        $this->set('DataArr',$this->TrainingEmailSet->find('all',array('conditions'=>array('Branch'=>$branchName,'Mail_Status'=>'Pending'))));
        
        if($this->request->is('POST')){
            $this->layout='ajax';
            $request        =   $this->request->data;
            $userid         =   $this->Session->read('userid');
            $Branch         =   $request['TrainingQuestionSets']['branch_name'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $User_Name      =   $request['User_Name'];
            $Email_Id       =   $request['Email_Id'];
            $Mobile_No      =   $request['Mobile_No'];
            $Submit         =   $request['Submit'];
            
            $count          =   $this->TrainingVideoSet->find('count',array('conditions'=>array('Branch'=>$Branch,'Department'=>$Department,'Designation'=>$Desgination,'Process'=>$Process,'Campaign'=>$Campaign)));
            
            if($Submit =="Add"){
                
                $data=array(
                    'Branch'=>$Branch,
                    'Department'=>$Department,
                    'Designation'=>$Desgination,
                    'Process'=>$Process,
                    'Campaign'=>$Campaign,
                    'Total_Set'=>$count,
                    'User_Name'=>$User_Name,
                    'Email_Id'=>$Email_Id,
                    'Mobile_No'=>$Mobile_No,
                    'User_Id'=>$userid,
                );

                if($this->TrainingEmailSet->save($data)){
                    echo '<span style="color:green;font-weight:bold;" >Data save successfully.</span>';die;
                    //$this->Session->setFlash('<span style="color:green;font-weight:bold;" >Data save successfully.</span>');
                    //$this->redirect(array('action'=>'set_training_mail'));
                }
                else{
                    echo '<span style="color:red;font-weight:bold;" >Data not save please try again later.</span>';die;
                    //$this->Session->setFlash('<span style="color:red;font-weight:bold;" >Data not save please try again later.</span>');
                    //$this->redirect(array('action'=>'set_training_mail'));
                }
            }
            
            if($Submit =="Send Mail"){
                $WBranch        =   "Branch='$Branch'";
                $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
                $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
                $WProcess       =   $Process !=""?"AND Process='$Process'":"";
                $WCampaign      =   $Campaign !=""?"AND Campaign='$Campaign'":"";

                $conditoin      =   array("$WBranch $WDepartment $WDesignation $WProcess $WCampaign AND Mail_Status='Pending'");
                $dataArr        =   $this->TrainingEmailSet->find('all',array('conditions'=>$conditoin));

                if(!empty($dataArr)){
                    foreach($dataArr as $val){
                        $row        =   $val['TrainingEmailSet'];

                        $lastid     =   $row['Id'];
                        $Email_Id   =   $row['Email_Id'];
                        $User_Name  =   $row['User_Name'];

                        $Url        =   'http://'.$_SERVER['HTTP_HOST'].'/hrvisitors/onlinetest/training.php?url='.base64_encode($lastid);

                        require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
                        $EmailText  =   '';
                        $to         =   array('Email'=>$Email_Id,'Name'=>$User_Name);	
                        $from       =   array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                        $reply      =   array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                        $Sub        =   "Training series of Mas Callnet";
                        $EmailText .=   "Dear $User_Name,<br/><br/>";
                        $EmailText .=   "Welcome to Mas Callnet Pvt Ltd,<br/><br/>";
                        $EmailText .=   "We welcome you to become our partner in career growth success. To become a part of our dynamic team, you are requested to please complete our online training module followed by basic tests. Below is the link which will take you on the journey of success.<br/><br/>";
                        $EmailText .=   "You simply need to click it and watch the video, after watching the video you need to give the online test displayed just below the video. The total number of video set and its corresponding test that you need to complete is given at the top left of the screen.<br/><br/>";
                        $EmailText .=   "$Url<br/><br/><br/>";
                        $EmailText .=   "After completing the training, please contact us.<br/><br/>";
                        $EmailText .=   "Best of luck<br/><br/>";
                        $EmailText .=   "<br/><br/>";
                        $EmailText .=   "With best compliments <br/><br/>";
                        $EmailText .=   "Mas Callnet<br/><br/>";

                        $emaildata  =   array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);

                        if(send_email($emaildata)){
                            $this->TrainingEmailSet->query("UPDATE `Training_Email_Set` SET Mail_Status='Send' WHERE Id='$lastid'");
                        }
                    }           
                }
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Mail send successfully.</span>');
                $this->redirect(array('action'=>'set_training_mail'));
            }
            
        }
    }
    
    public function add_training_mail(){
        $this->layout='ajax';
        if($this->request->is('POST')){
            
            $request        =   $this->request->data;

            $userid         =   $this->Session->read('userid');
            $Branch         =   $request['TrainingQuestionSets']['branch_name'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            $User_Name      =   $request['User_Name'];
            $Email_Id       =   $request['Email_Id'];
            $Mobile_No      =   $request['Mobile_No'];
            $Submit         =   $request['Submit'];
            
            $count          =   $this->TrainingVideoSet->find('count',array('conditions'=>array('Branch'=>$Branch,'Department'=>$Department,'Designation'=>$Desgination,'Process'=>$Process,'Campaign'=>$Campaign)));
            
                $data=array(
                    'Branch'=>$Branch,
                    'Department'=>$Department,
                    'Designation'=>$Desgination,
                    'Process'=>$Process,
                    'Campaign'=>$Campaign,
                    'Total_Set'=>$count,
                    'User_Name'=>$User_Name,
                    'Email_Id'=>$Email_Id,
                    'Mobile_No'=>$Mobile_No,
                    'User_Id'=>$userid,
                );

                if($this->TrainingEmailSet->save($data)){
                    echo '<span style="color:green;font-weight:bold;" >Data save successfully.</span>';die;
                }
                else{
                    echo '<span style="color:red;font-weight:bold;" >Data not save please try again later.</span>';die;
                }
               
        }
    }
    
    public function view_mail(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
             
            $WBranch        =   "Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WProcess       =   $Process !=""?"AND Process='$Process'":"";
            $WCampaign      =   $Campaign !=""?"AND Campaign='$Campaign'":"";
            
            $conditoin      =   array("$WBranch $WDepartment $WDesignation $WProcess $WCampaign AND Mail_Status='Pending'");
            $data           =   $this->TrainingEmailSet->find('all',array('conditions'=>$conditoin));

            if(!empty($data)){
            ?>
            <div class="col-sm-12" style="margin-top:-25px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Process</th>
                            <th>Campaign</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>Mail Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;foreach($data as $val){$row=$val['TrainingEmailSet'];?>
                        <tr>
                            <td><?php echo $i++?></td>
                            <td><?php echo $row['Department']?></td>
                            <td><?php echo $row['Designation']?></td>
                            <td><?php echo $this->PCNAME($row['Process']);?></td>
                            <td><?php echo $this->PCNAME($row['Campaign']);?></td>
                            <td><?php echo $row['User_Name'];?></td>
                            <td><?php echo $row['Email_Id'];?></td>
                            <td><?php echo $row['Mobile_No'];?></td>
                            <td><?php echo $row['Mail_Status'];?></td>
                            <td style="text-align: center;">
                                <span class='icon' ><i onclick="delete_mail('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else{
                echo '<div class="col-sm-12" style="color:red;" >Record Not Found.</div>';
            }
            die;
        }
    }
    
    public function send_view_mail(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
             
            $WBranch        =   "Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WProcess       =   $Process !=""?"AND Process='$Process'":"";
            $WCampaign      =   $Campaign !=""?"AND Campaign='$Campaign'":"";
            
            $conditoin      =   array("$WBranch $WDepartment $WDesignation $WProcess $WCampaign AND Mail_Status='Send'");
            $data           =   $this->TrainingEmailSet->find('all',array('conditions'=>$conditoin));

            if(!empty($data)){
            ?>
            <div class="col-sm-12" style="margin-top:-25px;">
                <table class = "table table-striped table-hover  responstable"  >     
                    <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Process</th>
                            <th>Campaign</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>Training Status</th>
                            <th>Mail Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;foreach($data as $val){$row=$val['TrainingEmailSet'];?>
                        <tr>
                            <td><?php echo $i++?></td>
                            <td><?php echo $row['Department']?></td>
                            <td><?php echo $row['Designation']?></td>
                            <td><?php echo $this->PCNAME($row['Process']);?></td>
                            <td><?php echo $this->PCNAME($row['Campaign']);?></td>
                            <td><?php echo $row['User_Name'];?></td>
                            <td><?php echo $row['Email_Id'];?></td>
                            <td><?php echo $row['Mobile_No'];?></td>
                            <td style="text-align: center;">
                                <?php if($row['All_Set_Mark'] !=""){?>
                                <table width="100%">
                                <tr>
                                    <td>SET</td>
                                    <td>Marks</td>
                                    <td>Req</td>
                                </tr>
                                <?php
                                $min_marks  =   0;
                                $req_marks  =   0;
                                $Total_Set  =   count(explode(",", $row['All_Set_Mark']));
                                foreach(explode(",", $row['All_Set_Mark']) as $Res){
                                    $Exp    =   explode("_", $Res);
                                    $Set    =   $Exp[0];
                                    $Marks  =   $Exp[1];
                      
                                    //$PerArr =   $this->TrainingVideoSet->query("SELECT Percent FROM `Training_Video_Set` WHERE `Branch`='$Branch' AND `Department`='$Department' AND `Designation`='$Desgination' AND `Process`='$Process' AND `Campaign`='$Campaign' AND Set_No='$Set'");
                                    //$Req    =   $PerArr[0]['Training_Video_Set']['Percent'];
                                    
                                    $Req    =   $Exp[2];
                                    
                                    $min_marks=$min_marks+$Marks;
                                    $req_marks=$req_marks+$Req;

                                    echo '<tr>';
                                    echo '<td>'.$Set.'</td>';
                                    echo '<td>'.$Marks.'%</td>';
                                    echo '<td>'.$Req.'%</td>';
                                    echo '<tr>';
                                }
                                ?>
                                <tr>
                                    <td>TOTAL</td>
                                    <td><?php echo round($min_marks*100/($Total_Set*100));?> %</td>
                                    <td><?php echo round($req_marks/$Total_Set);?> %</td>
                                </tr>
                                </table>
                                <?php }?>
                            </td>
                            <td style="text-align: center;">
                                <a href="#" onclick="sendemail('<?php echo $row['Id']?>')"><?php echo $row['Mail_Status'];?></a> |
                                <a href="<?php echo $this->webroot;?>TrainingQuestionSets/reset_training?url=<?php echo $row['Id']?>" onclick="return confirm('Are you sure you want to reset employee training test?');" >Reset</a>
                            </td>
                            <td style="text-align: center;">
                                <span class='icon' ><i onclick="delete_mail('<?php echo $row['Id'];?>');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else{
                echo '<div class="col-sm-12" style="color:red;" >Record Not Found.</div>';
            }
            die;
        }
    }
    
    public function sendmail(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            
            $Branch         =   $request['BranchName'];
            $Department     =   $request['Department'];
            $Desgination    =   $request['Desgination'];
            $Process        =   $request['Process'];
            $Campaign       =   $request['Campaign'];
            
            $WBranch        =   "Branch='$Branch'";
            $WDepartment    =   $Department !=""?"AND Department='$Department'":"";
            $WDesignation   =   $Desgination !=""?"AND Designation='$Desgination'":"";
            $WProcess       =   $Process !=""?"AND Process='$Process'":"";
            $WCampaign      =   $Campaign !=""?"AND Campaign='$Campaign'":"";

            $conditoin      =   array("$WBranch $WDepartment $WDesignation $WProcess $WCampaign AND Mail_Status='Pending'");
            $data        =   $this->TrainingEmailSet->find('all',array('conditions'=>$conditoin));
            
           
            
            if(!empty($data)){
                foreach($data as $val){
                    $row        =   $val['TrainingEmailSet'];
                    
                    $lastid     =   $row['Id'];
                    $Email_Id   =   $row['Email_Id'];
                    $User_Name  =   $row['User_Name'];

                    $Url        =   'http://'.$_SERVER['HTTP_HOST'].'/hrvisitors/onlinetest/training.php?url='.base64_encode($lastid);

                    require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
                    $EmailText  =   '';
                    $to         =   array('Email'=>$Email_Id,'Name'=>$User_Name);	
                    $from       =   array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                    $reply      =   array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                    $Sub        =   "Training series of Mas Callnet";
                    $EmailText .=   "Dear $User_Name,<br/><br/>";
                    $EmailText .=   "Welcome to Mas Callnet Pvt Ltd,<br/><br/>";
                    $EmailText .=   "We welcome you to become our partner in career growth success. To become a part of our dynamic team, you are requested to please complete our online training module followed by basic tests. Below is the link which will take you on the journey of success.<br/><br/>";
                    $EmailText .=   "You simply need to click it and watch the video, after watching the video you need to give the online test displayed just below the video. The total number of video set and its corresponding test that you need to complete is given at the top left of the screen.<br/><br/>";
                    $EmailText .=   "$Url<br/><br/><br/>";
                    $EmailText .=   "After completing the training, please contact us.<br/><br/>";
                    $EmailText .=   "Best of luck<br/><br/>";
                    $EmailText .=   "<br/><br/>";
                    $EmailText .=   "With best compliments <br/><br/>";
                    $EmailText .=   "Mas Callnet<br/><br/>";

                    $emaildata  =   array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);

                    if(send_email($emaildata)){
                        $this->TrainingEmailSet->query("UPDATE `Training_Email_Set` SET Mail_Status='Send' WHERE Id='$lastid'");
                    }
                }
                echo 'Mail Send Successfully';
            }        
            else{
                echo 'Record Not Found';
            }
            die;
        }
    }
    
    
    public function sendusermail(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Id             =   $request['Id'];

            $conditoin      =   array("Id='$Id'");
            $data           =   $this->TrainingEmailSet->find('first',array('conditions'=>$conditoin));
            
            
            
            $row            =   $data['TrainingEmailSet'];

            $lastid         =   $row['Id'];
            $Email_Id       =   $row['Email_Id'];
            $User_Name      =   $row['User_Name'];
            $Url            =   'http://'.$_SERVER['HTTP_HOST'].'/hrvisitors/onlinetest/training.php?url='.base64_encode($lastid);

            require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
            $EmailText  =   '';
            $to         =   array('Email'=>$Email_Id,'Name'=>$User_Name);	
            $from       =   array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
            $reply      =   array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
            $Sub        =   "Training series of Mas Callnet";
            $EmailText .=   "Dear $User_Name,<br/><br/>";
            $EmailText .=   "Welcome to Mas Callnet Pvt Ltd,<br/><br/>";
            $EmailText .=   "We welcome you to become our partner in career growth success. To become a part of our dynamic team, you are requested to please complete our online training module followed by basic tests. Below is the link which will take you on the journey of success.<br/><br/>";
            $EmailText .=   "You simply need to click it and watch the video, after watching the video you need to give the online test displayed just below the video. The total number of video set and its corresponding test that you need to complete is given at the top left of the screen.<br/><br/>";
            $EmailText .=   "$Url<br/><br/><br/>";
            $EmailText .=   "After completing the training, please contact us.<br/><br/>";
            $EmailText .=   "Best of luck<br/><br/>";
            $EmailText .=   "<br/><br/>";
            $EmailText .=   "With best compliments <br/><br/>";
            $EmailText .=   "Mas Callnet<br/><br/>";

            $emaildata  =   array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);

            if(send_email($emaildata)){
                echo 'Mail Send Successfully';
            }
            die;
        }
    }

    public function delete_mail(){
        if($_REQUEST['Id']){
            $Id         =   $_REQUEST['Id'];
            $this->TrainingEmailSet->query("DELETE FROM `Training_Email_Set` WHERE Id='$Id'");
            $this->redirect(array('action'=>'set_training_mail')); 
        }
    }
    
    public function sent_delete_mail(){
        if($_REQUEST['Id']){
            $Id         =   $_REQUEST['Id'];
            $this->TrainingEmailSet->query("DELETE FROM `Training_Email_Set` WHERE Id='$Id'");
            $this->redirect(array('action'=>'sent_training_details')); 
        }
    }
    
    
    
    public function sent_training_details(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            //$this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            $this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 
        
        $this->set('dep',$this->DepartmentNameMaster->find('list',array('fields'=>array('Department'),'conditions'=>array('Status'=>1),'order'=>'Department')));        
        $this->set('DataArr',$this->TrainingEmailSet->find('all',array('conditions'=>array('Branch'=>$branchName,'Mail_Status'=>'Send'))));
    }
    
    public function reset_training(){
        if($_REQUEST['url']){
            $Id         =   $_REQUEST['url'];
            $this->TrainingEmailSet->query("UPDATE `Training_Email_Set` SET `Set_No`='1',`Marks`='0',`All_Set_Mark`=NULL,`Test_Status`=NULL WHERE Id='$Id'");
            $this->TrainingEmailSet->query("DELETE FROM `Training_User_Test_Data` WHERE User_Id='$Id'");
            $this->redirect(array('action'=>'sent_training_details')); 
        }
    }
    
    public function training_process(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
            $this->set('DataArr',$this->TrainingProcess->find('all',array('conditions'=>array("Parent_Id IS NULL GROUP BY PROCESS ORDER BY PROCESS "))));
        
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
            $this->set('DataArr',$this->TrainingProcess->find('all',array('conditions'=>array("Branch='$branchName' AND Parent_Id IS NULL GROUP BY PROCESS ORDER BY PROCESS "))));
        }
        
        
        $data_Row   = array();
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $Data_Array =   $this->TrainingProcess->find('first',array('conditions'=>array("Id='{$_REQUEST['Id']}'")));
            $data_Row   =   $Data_Array['TrainingProcess']; 
        }
        
        $this->set('data',$data_Row); 
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['TrainingQuestionSets']['branch_name'];
            $Process        =   trim(addslashes($request['Process']));
            $Submit         =   $request['Submit'];
            $User_Id        =   $this->Session->read('email');
            
            if($Submit =="Submit"){
                
                $data=array(
                    'Branch'=>$Branch,
                    'Process'=>$Process,
                    'Create_By'=>$User_Id
                );

                $Exist  =   $this->TrainingProcess->find('all',array('conditions'=>array("Branch='$Branch' AND Process='$Process' AND Parent_Id IS NULL")));

                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already exist in database.</span>');
                    $this->redirect(array('action'=>'training_process'));
                }
                else{
                    if($this->TrainingProcess->save($data)){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Data save successfully.</span>');
                        $this->redirect(array('action'=>'training_process'));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Data not save please try again later.</span>');
                        $this->redirect(array('action'=>'training_process'));
                    }
                }
            
            }
            else if($Submit =="Update"){
                $Id =   $request['Id'];
                
                $data=array(
                    'Process'=>"'".$Process."'",
                    'Update_Date'=>"'".date('Y-m-d H:i:s')."'",
                    'Update_By'=>"'".$User_Id."'", 
                    );
                
                $Exist  =   $this->TrainingProcess->find('all',array('conditions'=>array("Branch='$Branch' AND Process='$Process' AND Parent_Id IS NULL AND Id !='$Id'")));

                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already exist in database.</span>');
                    $this->redirect(array('action'=>'training_process','?'=>array('Id'=>$Id)));
                }
                else{
                    if($this->TrainingProcess->updateAll($data,array('Id'=>$Id))){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Data update successfully.</span>');
                        $this->redirect(array('action'=>'training_process','?'=>array('Id'=>$Id)));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Data not update please try again later.</span>');
                        $this->redirect(array('action'=>'training_process','?'=>array('Id'=>$Id)));
                    }
                }  
            }  
        }
    }
    
    public function delete_process(){
        if($_REQUEST['Id']){
            $Id         =   $_REQUEST['Id'];
            $count      =   $this->TrainingVideoSet->find('count',array('conditions'=>array('Process'=>$Id)));
            $count1     =   $this->TrainingProcess->find('count',array('conditions'=>array('Parent_Id'=>$Id)));
            
            if($count > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already used for training set.</span>');
                $this->redirect(array('action'=>'training_process')); 
            }
            else if($count1 > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already used for campaign.</span>');
                $this->redirect(array('action'=>'training_process')); 
            }
            else{
                $this->TrainingProcess->query("DELETE FROM `Training_Process` WHERE Id='$Id'");
                $this->redirect(array('action'=>'training_process')); 
            }
        }
    }
    
    public function training_campaign(){
        $this->layout='home';
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));
            $this->set('branchName',$BranchArray);
            $DataArr    =   $this->TrainingProcess->find('all',array('conditions'=>array("Parent_Id IS NOT NULL ORDER BY PROCESS")));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
            $DataArr    =   $this->TrainingProcess->find('all',array('conditions'=>array("Branch='$branchName' AND Parent_Id IS NOT NULL ORDER BY PROCESS")));
        }
        
        $CampArr    =   array();
        
        
        foreach($DataArr as $rows){
            $row            =   $rows['TrainingProcess'];
            $Parent_Arr     =   $this->TrainingProcess->find('first',array('fields'=>array('Process'),'conditions'=>array("Id='{$row['Parent_Id']}'")));
            $Parent_Name    =   $Parent_Arr['TrainingProcess']['Process'];
            
            $CampArr[]=array('Id'=>$row['Id'],'Branch'=>$row['Branch'],'Process'=>$Parent_Name,'Campaign'=>$row['Process']);
        }
        
        $this->set('DataArr',$CampArr);
        
        $data_Row       =   array();
        $Parent_List    =   array();
        if(isset($_REQUEST['Id']) && $_REQUEST['Id'] !=""){
            $Data_Array =   $this->TrainingProcess->find('first',array('conditions'=>array("Id='{$_REQUEST['Id']}'")));
            $data_Row   =   $Data_Array['TrainingProcess'];
            
            $Parent_List =   $this->TrainingProcess->find('list',array('fields'=>array('Id','Process'),'conditions'=>array("Branch='{$data_Row['Branch']}' AND Parent_Id IS NULL ORDER BY PROCESS"))); 
        }
        
        $this->set('data',$data_Row); 
        $this->set('Parent_List',$Parent_List); 
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
  
            $Branch         =   $request['TrainingQuestionSets']['branch_name'];
            $Parent_Id      =   $request['Process'];
            $Campaign       =   trim(addslashes($request['Campaign']));
            $Submit         =   $request['Submit'];
            $User_Id        =   $this->Session->read('email');
            
            if($Submit =="Submit"){
                $data=array(
                    'Branch'=>$Branch,
                    'Parent_Id'=>$Parent_Id,
                    'Process'=>$Campaign,
                    'Create_By'=>$User_Id
                );

                $Exist  =   $this->TrainingProcess->find('all',array('conditions'=>array("Branch='$Branch' AND Process='$Campaign' AND Parent_Id='$Parent_Id'")));

                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already exist in database.</span>');
                    $this->redirect(array('action'=>'training_campaign'));
                }
                else{
                    if($this->TrainingProcess->save($data)){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Data save successfully.</span>');
                        $this->redirect(array('action'=>'training_campaign'));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Data not save please try again later.</span>');
                        $this->redirect(array('action'=>'training_campaign'));
                    }
                }
            }
            else if($Submit =="Update"){
                $Id =   $request['Id'];
                 $data=array(
                    //'Parent_Id'=>"'".$Parent_Id."'",
                    'Process'=>"'".$Campaign."'",
                    'Update_Date'=>"'".date('Y-m-d H:i:s')."'",
                    'Update_By'=>"'".$User_Id."'", 
                    );

                $Exist  =   $this->TrainingProcess->find('all',array('conditions'=>array("Branch='$Branch' AND Process='$Campaign' AND Parent_Id='$Parent_Id' AND Id !='$Id'")));

                if(!empty($Exist)){
                    $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This process already exist in database.</span>');
                    $this->redirect(array('action'=>'training_campaign','?'=>array('Id'=>$Id)));
                }
                else{
                    if($this->TrainingProcess->updateAll($data,array('Id'=>$Id))){
                        $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Data update successfully.</span>');
                        $this->redirect(array('action'=>'training_campaign','?'=>array('Id'=>$Id)));
                    }
                    else{
                        $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Data not update please try again later.</span>');
                        $this->redirect(array('action'=>'training_campaign','?'=>array('Id'=>$Id)));
                    }
                }
            }  
                
        }
    }
    
    public function delete_campaign(){
        if($_REQUEST['Id']){
            $Id         =   $_REQUEST['Id'];
            $count      =   $this->TrainingVideoSet->find('count',array('conditions'=>array('Campaign'=>$Id)));
            
            if($count > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This campaign already used for training set.</span>');
                $this->redirect(array('action'=>'training_campaign')); 
            }
            else{
                $this->TrainingProcess->query("DELETE FROM `Training_Process` WHERE Id='$Id'");
                $this->redirect(array('action'=>'training_campaign')); 
            }
        }
    }
    
    public function get_process_list(){
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $Branch         =   $request['Branch'];
                   
            $data=$this->TrainingProcess->find('list',array('fields'=>array('Id','Process'),'conditions'=>array("Branch='$Branch' AND Parent_Id IS NULL GROUP BY PROCESS ORDER BY PROCESS")));
  
            echo "<option value=''>Select</option>";
            if(!empty($data)){
                
                foreach ($data as $key=>$val){
                    echo "<option  value='$key'>$val</option>";
                } 
            } 
            die;
        }
    }
    
    public function PCNAME($Id){
        $data   =   $this->TrainingProcess->find('first',array('fields'=>array('Process'),'conditions'=>array("Id='$Id'")));
        return $data['TrainingProcess']['Process'];
    }
     
    
    
    
    
    
    
    
}
?>