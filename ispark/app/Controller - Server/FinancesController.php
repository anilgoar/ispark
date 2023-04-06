<?php
class FinancesController extends AppController {
    public $uses = array('Addbranch','User','Access','Pages');
    
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'index','grn','grn_vendor','book_grn','imprest_allotment','pnl_field_entry');
        
        
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('BusinessCase'=>array(
                '67'=>array('ExpenseReports/index','View Budget / Business Case'),
            '141'=>array('Imprests/add_head_type','Add Head Type'),
            '142'=>array('Imprests/add_tds_section','Add TDS Section'),
            '133'=>array('Imprests/add_head','Add Expense Head'),
                '134'=>array('Imprests/add_sub_head','Add Expense Sub Head'),
                '84'=>array('Imprests/addunit','Add Unit'),
                '76'=>array('Imprests/imprest_manager_save','Add Imprest Manager'),
                '63'=>array('ExpenseEntries/initial_branch','Budget/Business Case Entry'),
            '70'=>array('ExpenseEntries/view','Pending Business Case'),
            '74'=>array('ExpenseEntries/business_case_ropen','Business Case Re-Open Request'),
                '75'=>array('ExpenseEntries/view_business_case_ropen','View Business Case Re-Open Request'),
                '64'=>array('ExpenseEntries/view_bm','Approve Bus. Case(BM)'),
                '65'=>array('ExpenseEntries/view_vh','Approve Bus. Case(VH)'),
                '66'=>array('ExpenseEntries/view_fh','Approve Bus. Case(FH)'),
                '165'=>array('ExpenseEntries/business_case_upload','Business Case File Upload'),
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function grn(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('GRN'=>array(
            '157'=>array('GrnBranches/grn_branch_access','Manage Access'),
            '68'=>array('GrnEntries/select_entry','GRN Processing'),
            '149'=>array('Gms/edit_grn_branch','GRN Pending'),
                '158'=>array('Gms/edit_imprest_branch','Imprest Pending'),
                '119'=>array('Gms/approve_grn','Grn Vendor First Approval'),
                '143'=>array('Gms/approve_grn2','Grn Vendor Second Approval'),
            '161'=>array('Gms/approve_imprest','Imprest First Approval'),
            '155'=>array('Gms/approve_imprest2','Imprest Second Approval'),    
            '162'=>array('Gms/view_grn','View GRN Vendor'),
                '163'=>array('Gms/view_imprest','View GRN Imprest'),
                '129'=>array('Gms/payment_processing','Payment Processing'),
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function grn_vendor(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('GrnVendor'=>array(
            '77'=>array('Imprests/vendor_save','Add Vendor'),
            '151'=>array('Imprests/tmp_view_branch_vendor','Pending Vendor'),
            '118'=>array('Imprests/tmp_view_vendor','Approve Vendor'),
            '152'=>array('Imprests/view_vendor','View Vendor'),
            '135'=>array('Imprests/vendor_add_head','Add Head/SubHead To Vendor'),    
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function book_grn(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('BookGRN'=>array(
            '78'=>array('GrnEntries/book_grn_no','Book GRN'),
            '79'=>array('Dispatches/index','Dispatch GRN'),
            '80'=>array('Dispatches/received','Receive GRN'),
            '130'=>array('Dispatches/view_envelope','Envelope Print'),
            '153'=>array('GrnEntries/get_pending_grn','Pending GRN'),    
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function imprest_allotment(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('Imprest Allotment'=>array(
            '73'=>array('Imprests/imprest_save','Imprest Allotment'),
            '156'=>array('Imprests/imprest_manager_save','Add Imprest Manager'),
            '131'=>array('Imprests/grn_payment','Payment Processing Vendor/Imprest'),
            '132'=>array('Imprests/grn_payment_salary','Payment Processing Salary'),
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    public function pnl_field_entry()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('Pnl Master'=>array(
            '166'=>array('Pnldetails/index','Add P&L Description Field'),
            '167'=>array('Pnldetails/pnl_records_add','P&L Description Entry')
            
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
    }
}
?>