<?php
class FinanceReportsController extends AppController {
    public $uses = array('Addbranch','User','Access','Pages');
    
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'index','report');
        
        
        
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
				'221'=>array('AutomailMasters','Auto Mail For GRN Payment'),
                '224'=>array('ExpenseReports/voucher_new_report','GRN Voucher New Entry Report'), 
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
                    '183'=>array('GrnReports/pnl_revenue_report','P&L Revenue Wise Report'),
            '148'=>array('GrnReports/pnl_branch_wise_report','P&L Branch Wise Report'),
             '174'=>array('GrnReports/pnl_summary_report','P&L Summary Report'),
                    '71'=>array('ExpenseReports/pnl_process_wise_report','P&L Process Wise Report'),
            '186'=>array('ProvisionReports/pnl_basic','Budget Report'),
                    
                    '213'=>array('PnlManuals/index','P&L File Uploads'),
                    '207'=>array('BillGenerations/bill_det_up_report','P&l Detail Updation'),
                    '214'=>array('PnlfileuploadReports/pnl_data_report','PNL Data Export'),
					
                )
                )
            ;
        
		
        $this->set('pages',$pageName);
        
    }
    
    
    
    public function report()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Invoice'=>array(
                '122'=>array('GrnReports/inv_vch_report','Invoice Voucher Report'),
                '23'=>array('Reports/bill_genrate_report',"Invoice Reports"),
                '22'=>array('Reports/report',"OutStanding Reports"),
                '82'=>array('BillApprovalStages/invoice_export',"Invoice Export"), 
                '223'=>array('BillApprovalStages/invoice_image_export',"Invoice View"),
                ),
                'Collection Reports'=>array(
                '24'=>array('CollectionReports/index',"Collection Reports"),
                '39'=>array('CollectionReports/view_report',"Collection Planning"),    
                '218'=>array('CollectionPtps/collection_ptp',"Collection PTP"),
                '211'=>array('CollectionReports/report_collection_tracking',"Collection Tracking Report"),
                '219'=>array('CollectionReports/collection_tracking_ptp_report',"Collection EPTP"),    
                    '27'=>array('Reports/ptp',"Ptp Report"),
                    '217'=>array('CollectionReports/report_eptp_tracking',"EPTP Tracking Report"),
                ),
                'Bill Reports'=>array(
                 '25'=>array('BillApprovalStages/view',"Bill Stages"),
                '26'=>array('BillGenerations/index',"Bill Edited"),   
                    ),
                'Provision Reports'=>array(
                
                '27'=>array('ProvisionReports/index',"Provision Report"),
                '181'=>array('ProvisionReports/provision_edit_report',"Provision Edit Report"),
                ),
                'Doc Reports'=>array(
                '55'=>array('VailidationReports/index',"Doc Validation Details"),
                '60'=>array('VailidationRejects/index',"Doc Rejected Mis"),
                  ),
                'Master Report'=>array(
                '61'=>array('MasterBranchReports/index',"Master Branch Report"),
                '62'=>array('MasterNationalReports/index',"Master National Report"),
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName); 
        
    }
    
    
    
    
    
}
?>