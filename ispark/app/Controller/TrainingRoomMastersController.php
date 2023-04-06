<?php
class TrainingRoomMastersController extends AppController {
    public $uses = array('Addbranch','Masjclrentry','Masattandance','MasJclrMaster','User','TrainingRoomMaster','DepartmentNameMaster');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow('index','deletesource','show_room');
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
            //$this->set('branchName',$BranchArray);
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        } 

        $this->set('DataArr',$this->TrainingRoomMaster->find('all',array('conditions'=>array('BranchName'=>$branchName))));
        
       
        if($this->request->is('Post')){
            $BranchName     =   $this->request->data['TrainingRoomMasters']['branch_name']; 
            $Room           =   addslashes(trim($this->request->data['Room']));
            $data           =   array('BranchName'=>$BranchName,'Room'=>$Room);
              
            $row=$this->TrainingRoomMaster->find('count',array('conditions'=>$data));

            if($row > 0){
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >This room already exist in database.</span>');
                $this->redirect(array('controller'=>'TrainingRoomMasters')); 
            }
            else{
                $this->TrainingRoomMaster->save($data);
                $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This training room create successfully.</span>');
                $this->redirect(array('controller'=>'TrainingRoomMasters'));   
            }
        }  
    }
    
    public function show_room(){
        $this->layout='ajax';
        if(isset($_REQUEST['BranchName']) && $_REQUEST['BranchName'] !=""){
            
            if($_REQUEST['BranchName'] !="ALL"){$conditoin['BranchName']=$_REQUEST['BranchName'];}else{unset($conditoin['BranchName']);}
            $DataArr=$this->TrainingRoomMaster->find('all',array('conditions'=>$conditoin));
            
            if(!empty($DataArr)){   
            ?>
            <table class = "table table-striped table-hover  responstable"  >     
                <thead>
                    <tr>
                        <th style="width:50px;">SNo</th>
                        <th>BranchName</th>
                        <th>Training Room</th>
                        <th style="width:40px;" >Action</th>
                    </tr>
                </thead>
                <tbody>         
                    <?php $n=1; foreach ($DataArr as $val){?>
                    <tr>
                        <td><?php echo $n++;?></td>
                        <td><?php echo $val['TrainingRoomMaster']['BranchName'];?></td>
                        <td><?php echo $val['TrainingRoomMaster']['Room'];?></td>
                        <td style="text-align: center;">
                            <span class='icon' ><i onclick="actionlist('<?php $this->webroot;?>TrainingRoomMasters/deletesource?id=<?php echo base64_encode($val['TrainingRoomMaster']['Id']);?>','delete');"  class="material-icons" style="font-size:20px;cursor: pointer;" >delete</i></span>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>   
            </table>
            <?php   
            }
            else{
                echo "";
            }
            die;
        }
        
    }
    
    public function deletesource(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $id= base64_decode($_REQUEST['id']);
            $this->TrainingRoomMaster->query("DELETE FROM `TrainingRoomMaster` WHERE Id='$id'");
            $this->Session->setFlash('<span style="color:green;font-weight:bold;" >This training room details delete successfully.</span>');
            $this->redirect(array('action'=>'index'));
        }
     
    }
    
    
}
?>