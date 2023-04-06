<?php
class FinanceReportsController extends AppController {
    public $uses = array('Addbranch','User','Access','Pages');
    
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'index');
        
        
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('GRN'=>array(
            
            '69'=>array('ExpenseReports/imprest_report_breakup','GRN Report'),
            '144'=>array('ExpenseReports/imprest_report','GRN Report Process Wise'),
            '117'=>array('ExpenseReports/grn_report','GRN Voucher Report'),
                '120'=>array('ExpenseReports/grn_reject_report','GRN Reject Report'),
                '145'=>array('GrnReports/file_report','GRN Filing Report'),
                '164'=>array('GrnReports/grn_gst_report','GRN GST Report'),
                ),
                'Imprest'=>array(
                 '146'=>array('GrnReports/grn_imprest_report','Imprest Voucher Report'),
            '72'=>array('ExpenseReports/imprest_detail','Imprest Detail'),
            '83'=>array('ExpenseReports/imprest_report2','Imprest Report'),
                
                ),
                'TDS'=>array(
                    '85'=>array('ExpenseReports/view_tds','Export TDS'),
            '147'=>array('ExpenseReports/view_section_tds','Section Wise TDS'),
                ),
                'PnL'=>array(
                    '71'=>array('GrnReports/pnl_revenue_report','P&L Process Wise Report'),
            '148'=>array('GrnReports/pnl_branch_wise_report','P&L Branch Wise Report'),
             '174'=>array('GrnReports/pnl_summary_report','P&L Summary Report'),       
                )
                
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    
    
    
    
    
    
    
    
}
?>