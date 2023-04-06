<?php
class InitialNotificationsController extends AppController {
    public $uses=array('po_number_particulars','AgreementParticular');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
	
    public function beforeFilter(){
        parent::beforeFilter();
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        $this->Auth->allow('po_notification','agreement_notification');    
    }
    
    public function po_notification() {
        if(isset($_REQUEST['dt'])){
            $this->layout='ajax';
            $exp=  explode('/', $_REQUEST['ct']);
            $cost_center=end($exp);
            $query =$this->po_number_particulars->find('first',array('conditions'=>array('cost_center'=>$cost_center),'order' => array('id DESC'),'limit' => 1));
            if(!empty($query)){
            $data=$query['po_number_particulars'];
            $dt=date('Y-m-d', strtotime($_REQUEST['dt']));
            $periodFrom = date('Y-m-d', strtotime($data['periodFrom']));
            $periodTo = date('Y-m-d', strtotime($data['periodTo']));
           
            if (($dt >= $periodFrom) && ($dt <= $periodTo)){
                echo "Your PO Number (".$data['poNumber'].") And PO Amount (".$data['poAmount'].") Inforce.";
            }
            else{
              echo "Your PO Number (".$data['poNumber'].") And PO Amount (".$data['poAmount'].") Lapsed.";
            }
            die;
            }
        }
   }
   
   public function agreement_notification() {
        if(isset($_REQUEST['dt'])){
            $this->layout='ajax';
            $exp=  explode('/', $_REQUEST['ct']);
            $cost_center=end($exp);
            $query =$this->AgreementParticular->find('first',array('conditions'=>array('cost_center'=>$cost_center),'order' => array('id DESC'),'limit' => 1));
            if(!empty($query)){
            $data=$query['AgreementParticular'];
            $dt=date('Y-m-d', strtotime($_REQUEST['dt']));
            $periodFrom = date('Y-m-d', strtotime($data['periodFrom']));
            $periodTo = date('Y-m-d', strtotime($data['periodTo']));
           
            if (($dt >= $periodFrom) && ($dt <= $periodTo)){
                echo "Your Agreement  Inforce.";
            }
            else{
              echo "Your Agreement Lapsed.";
            }
            die;
            }
        }
   }
   
   
   
   
   
	  
}
?>