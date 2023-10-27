<?php
class FinancesController extends AppController {
    public $uses = array('Addbranch','User','Access','Pages');
    
    public function beforeFilter(){
        parent::beforeFilter();  
        $this->Auth->allow(
            'index',
                'grn',
                'imprest',
                'grn_vendor',
                'book_grn',
                'imprest_allotment',
                'pnl_field_entry',
                'action_window',
                'agreement',
                'business_dashboard',
                'hr_mgt',
                'collection',
                'billing',
                'manage_access',
                'masters',
                'prospect',
                'it_asset_report',
                'salary',
                'dashboard',
                'cost_center',
                'users',
                'po',
                'issue_tracker',
                'business_target',
                'book_details',
                'salary_master',
                'it_mgt',
                'provision'
                );
        
        
        
        if(!$this->Session->check("username")){
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
    }
    
    public function dashboard()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        $this->set('page_access',$page_access);
        
         
          $pageName = 
            array('Dashboards'=>array(
                '38'=>array('Provisions/dashboard','Outstanding Dashboard'),
            '42'=>array('Dashboards/index','Business Dashboard'),
            '124'=>array('GrnReports/grn_dashboard','GRN Dashboard'),
            '138'=>array('Dashboards/get_data','New Business Dashboard'),
             '138'=>array('Dashboards/get_data','New Business Dashboard'),   
                ),
                'Business Dashboards'=>array(
                '43'=>array('Dashs/view','Business Dashboard'),
            '176'=>array('Targets/view_freeze_request','View Business Dashboard'),
            '177'=>array('Targets/view_freeze_request_for_approval','GRN Dashboard'),
            '180'=>array('MailSchedulers/business_dashboard_schedular','Business Mail Scheduler'),
             '202'=>array('MailSchedulers/salary_schedular','Salary Mail Scheduler'),   
            '203'=>array('MailSchedulers/profit_and_loss_schedular','Profit and Loss Mail Scheduler'),           
            '204'=>array('MailSchedulers/budget_schedular','Budget Mail Scheduler'),
            '205'=>array('MailSchedulers/indirect_expenses_schedular','Indirect Expenses Mail Scheduler'),
            '187'=>array('Targets/asp_delete','Aspirational Delete'),        
                    
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
    }
    
    public function masters()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Masters'=>array(
                '1'=>array('Addbranches/index','Add Branch'),
                '2'=>array('Addclients/index','Add Client'),
                '142'=>array('Imprests/add_tds_section','Add TDS Section'),
                '61'=>array('MasterBranchReports/index','Master Branch Report'),
                '62'=>array('MasterNationalReports/index','Master National Report'),
                '199'=>array('IsparkDepartments/index','Department Master'),
                '200'=>array('IsparkDepartments/process','Process Master')
                ),
                
                
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function cost_center()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array(
                'Cost Center'=>array(
                    '3'=>array('costCenterMasters/index','Cost Center'),
                    '56'=>array('cost_center_masters/tmp_view','First Approval Cost Center'),
                '54'=>array('cost_center_masters/tmp_view','Cost Center Approval'), 
                '30'=>array('costCenterMasters/view','Edit Cost Center'),
                '58'=>array('CostCenterEmails/index','Cost Center Email'),
                '175'=>array('CostCenterMasters/tmp_view_pending','Cost Center Pending'),
                 ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function users()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array(
                'User'=>array(
                '15'=>array('Users/Create_user','Create User'),
                '40'=>array('Users/view_users','View User')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function agreement()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Agreement'=>array(
                '44'=>array('Agreements/index','Upload Agreement'),
                '45'=>array('Agreements/view','View Agreement')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function po()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('PO'=>array(
                '46'=>array('PoNumbers',"Create PO"),
                '47'=>array('PoNumbers/view',"View PO"),
                ),
                )
            
            ;
        
        $this->set('pages',$pageName); 
        
    }
    
    public function grn_create()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('GRN'=>array(
                '44'=>array('Agreements','Upload Agreement'),
                '45'=>array('Agreements/view','View Agreement')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    
    public function index(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('BusinessCase'=>array(
                '142'=>array('Imprests/add_tds_section','Add TDS Section'), 
                '133'=>array('Imprests/add_head','Add Expense Head'),
                '134'=>array('Imprests/add_sub_head','Add Expense Sub Head'),
                '76'=>array('Imprests/imprest_manager_save','Add Imprest Manager'),
                '84'=>array('Imprests/addunit','Add Unit'),
                '141'=>array('Imprests/add_head_type','Add Head Type'),
                
                '63'=>array('ExpenseEntries/initial_branch','Budget/Business Case Entry'),
                '67'=>array('ExpenseReports/index','View Budget / Business Case'),
                '74'=>array('ExpenseEntries/business_case_ropen','Business Case Re-Open Request'),
                '75'=>array('ExpenseEntries/view_business_case_ropen','View Business Case Re-Open Request'),
                '165'=>array('ExpenseEntries/business_case_upload','Business Case File Upload'),
                '70'=>array('ExpenseEntries/view','Pending Business Case'),
                
                
                
            
            
                
                '64'=>array('ExpenseEntries/view_bm','Approve Bus. Case(BM)'),
                '65'=>array('ExpenseEntries/view_vh','Approve Bus. Case(VH)'),
                '66'=>array('ExpenseEntries/view_fh','Approve Bus. Case(FH)'),
                
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
                
                '119'=>array('Gms/approve_grn','Grn Vendor First Approval'),
                '143'=>array('Gms/approve_grn2','Grn Vendor Second Approval'),
            '162'=>array('Gms/view_grn','View GRN Vendor'),
                '188'=>array('Gms/delete_grn_request','GRN Delete Request'),
                '189'=>array('Gms/delete_grn_request_approve','Approve GRN Delete Request'),
                '129'=>array('Gms/payment_processing','GRN Payment Processing'),
                ),
                'Grn Vendor'=>array(
            '77'=>array('Imprests/vendor_save','Add Vendor'),
            '151'=>array('Imprests/tmp_view_branch_vendor','Pending Vendor'),
            '118'=>array('Imprests/tmp_view_vendor','Approve Vendor'),
            '152'=>array('Imprests/view_vendor','View Vendor'),
            '135'=>array('Imprests/vendor_add_head','Add Head/SubHead To Vendor'),    
                
                ),
                'Book GRN'=>array(
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
    
    public function imprest(){
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
          $pageName = 
            array('Imprest'=>array(
                '158'=>array('Gms/edit_imprest_branch','Imprest Pending'),
            '161'=>array('Gms/approve_imprest','Imprest First Approval'),
            '155'=>array('Gms/approve_imprest2','Imprest Second Approval'),    
                '163'=>array('Gms/view_imprest','View GRN Imprest'),
                ),
                'Imprest Allotment'=>array(
            '73'=>array('Imprests/imprest_save','Imprest Allotment'),
            '156'=>array('Imprests/imprest_manager_save','Add Imprest Manager'),
            '131'=>array('Imprests/grn_payment','Payment Processing Vendor/Imprest'),
            '132'=>array('Imprests/grn_payment_salary','Payment Processing Salary'),
                
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
            '167'=>array('Pnldetails/pnl_records_add','P&L Description Entry'),
                '206'=>array('BillGenerations/get_update_bill_det','P&l Detail Updation')
            
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
    }
    
    public function action_window()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('ActionWindow'=>array(
                '57'=>array('Actions/index','Action Window')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    
    public function business_target()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Business Target'=>array(
                '48'=>array('Targets/index',"Add Target"),
                '50'=>array('Targets/upload_target',"Upload Target"),
                '59'=>array('CostCenterActs/index',"Close Cost Center for Bussiness Dashboard"),
                ),
                'Business Report'=>array(
                '49'=>array('DashReports/index',"Business Report")
                ),
                )
            
            ;
        
        $this->set('pages',$pageName); 
        
    }
    
    public function book_details()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Book Details'=>array(
                '1'=>array('Books/index',"Day Book Upload"),
                '1'=>array('Books/export1',"Day Book Export"),
                '1'=>array('Books/export12',"Day Book BreakUp Export"),
                '1'=>array('Books/export11',"Day Book summary"),
                '1'=>array('Books/save_status',"Day Book Status Update"),
                '1'=>array('Books/viewfundflow',"Create Fund Flow"),
                '1'=>array('Books/index',"View Fund Flow"),
                ),
                
                )
            
            ;
        
        $this->set('pages',$pageName); 
        
    }
    
    public function salary_master()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Book Details'=>array(
                '140'=>array('SalaryHeads/index',"Salary Head"),
                '139'=>array('GrnReports/salary_upload',"Salary Upload"),
                '140'=>array('GrnReports/salary_vch_report',"Salary Voucher Report"),
                '140'=>array('SalaryHeads/proportionate_cost_distribution',"Salary Proportionate")
                ),
                
                )
            
            ;
        
        $this->set('pages',$pageName); 
        
    }
    
    
    public function business_dashboard()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Business Dashboard'=>array(
                '59'=>array('Agreements/index','Close Cost Center for Bussiness Dashboard'),
                '42'=>array('Dashboards/index','Dashboard Entry'),
                '49'=>array('DashReports/index','Business Report'),
                '48'=>array('Targets/index','Add Target'),
                '50'=>array('Targets/upload_target','Upload Target'),
                '180'=>array('MailSchedulers/business_dashboard_schedular','Business Mail Scheduler'),
                '180'=>array('MailSchedulers/business_dashboard_schedular','Business Mail Scheduler'),
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function hr_mgt()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('HR'=>array(
                '202'=>array('MailSchedulers/salary_schedular','Salary Mail Scheduler')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function collection()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Collection'=>array(
                '29'=>array('Collections/index','Collection'),
                '28'=>array('Receipts/index','Receipts'),
                '28'=>array('Receipts/index','Receipts'),
                '123'=>array('Collections/view_payment','View Collection'),
                '222'=>array('Collections/approve_payment','Approve Collection'),
                
                '39'=>array('CollectionReports/view_report','Collection Planning'),
                '24'=>array('CollectionReports/index','Collection Reports'),
                '86'=>array('Connectivities/index','Connectivity Details'),
                '38'=>array('Provisions/dashboard','Outstanding Dashboard')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    
    public function billing()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('Billing'=>array(
                
                '4'=>array('InitialInvoices',"Initial Invoice/Proforma"),
                '20'=>array('InitialInvoices/download_proforma',"View & Approve Proforma Invoice"),
                '137'=>array('InitialInvoices/download_proforma_branch',"View/Download Proforma Invoice"),
                '6'=>array('InitialInvoices/branch_view',"View Initial Invoice"),
               '9'=>array('InitialInvoices/view_admin',"ADD PO"),
                '11'=>array('InitialInvoices/check_po',"Approve PO"),
                '12'=>array('InitialInvoices/view_grn',"ADD GRN"),
                '13'=>array('InitialInvoices/check_grn',"Approve GRN"),
                '7'=>array('InitialInvoices/download',"Download Invoice"),
                
                '21'=>array('InitialInvoices/view_invoice',"Edit Invoices"),
                
                
                
                '28'=>array('Receipts',"Receipt Entry"),
                
                '168'=>array('InitialInvoices/view_status_change_request',"Invoice Status Change Approval"),
                '169'=>array('InitialInvoices/delete_invoice',"Aprroval Invoice Delete Request"),
                
                '14'=>array('InitialInvoices/download_grn',"Download PDF"),
                
                
                
                
                
                '5'=>array('InitialInvoices/view',"View And Approve Invoice"),
                 '19'=>array('InitialInvoices/view_ahmd',"View Invoice To Ahmedabad"),
                
                '16'=>array('InitialInvoices/approve_ahmd',"Submit to Client"),
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function manage_access()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('manage_access'=>array(
                '8'=>array('Users/view_access','Manage Access')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    
    
    public function prospect()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('prospect'=>array(
                '109'=>array('prospects/index','Add Product'),
                '110'=>array('prospects/save_sales','Create Prospect'),
                '112'=>array('prospects/view_sales','View Prospect'),
                
                '111'=>array('prospects/view_approve_sales','Approve Prospect'),
                '110'=>array('prospects/view_follow','Follow Up'),
                '165'=>array('prospects/lead_source_master','Add Lead Source'),
                '173'=>array('prospects/disapproved_sales','DisApproved Prospect'),
                '114'=>array('Ecrs/index','User Management'),
                '115'=>array('prospects/view_report','Prospect Report')
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function it_asset_report()
   {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('it_assets'=>array(
                '89'=>array('Connectivities/export1','IT Assets Reports'),
                '91'=>array('Connectivities/view','IT View'),
                '88'=>array('AssetsManagements/index','Hardware Details'),
                '87'=>array('Connectivities/mobile','Mobile Details')
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function salary()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('salary'=>array(
                '108'=>array('Jclrs/account','Account Validation'),
                '116'=>array('Jclrs/appointment_letter','Appointment Letter'),
                '105'=>array('Attendances/discardsalary','Discard Attendances'),
                '102'=>array('Attendances/showfile','Download Attendance File'),
                '94'=>array('Attendances/index','Upload Attendance'),
                '106'=>array('Attendances/discardincentive','Discard Incentive'),
                '97'=>array('Attendances/typeformat','Incentive Entry'),
                '99'=>array('Attendances/exportincentive','Export Incentive'),
                '107'=>array('Jclrs/exportjclr','Export JCLR Data'),
                '93'=>array('Jclrs/index','JCLR Entry'),
                '100'=>array('Attendances/exportleave','Export Leave'),
                '96'=>array('Attendances/exportsalary','Export Salary'),
                '103'=>array('Jclrs/view','Salary BreakUp'),
                '95'=>array('Attendances/salaryprocess','Salary Process'),
                '103'=>array('Jclrs/view','Salary BreakUp'),
                
                '101'=>array('Attendances/savefile','Save Attendance File'),
                '140'=>array('GrnReports/salary_vch_report','Salary Voucher Report')
                
                
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function issue_tracker()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('manage_access'=>array(
                '31'=>array('Issues/issue_submit','Create Issue'),
				'36'=>array('Issues/view_user_issue','Issue Opened'),
                '220'=>array('Issues/issue_close','Issue Close'),
                '34'=>array('Issues/View_issue','View issue'),
                '33'=>array('Issues/issue_allocate','Issue View And Allocate'),
                '37'=>array('IssueReports/show_issue_status','Show Status'),
                '32'=>array('IssueReports/view_issue_report','Issue Report')
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function it_mgt()
    {
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('itt'=>array(
                '185'=>array('ItWorks/index','Dashboard'),
                '86'=>array('Connectivities/index','Connectivity Details'),
                '87'=>array('Connectivities/mobile','Mobile Details'),
                
                '88'=>array('AssetsManagements/index','Hardware Details'),
                '89'=>array('Connectivities/export1','IT Assets Reports'),
                '91'=>array('Connectivities/view','IT View'),
                '191'=>array('AssetsCategoryMasters/index','Add Category'),
                '192'=>array('AssetsCategoryMasters/assets_sub_category','Add Sub Category'),
                '193'=>array('AssetsCategoryMasters/assets_dropdown_master','Dropdown Master'),
                '194'=>array('AssetsCategoryMasters/add_assets_details','Add Assets Details'),
                '195'=>array('AssetsCategoryMasters/upload_assets_stocks','Upload Assets Stocks'),
                '196'=>array('AssetsCategoryMasters/allocate_assets_stocks','Allocate Assets Stocks'),
                '209'=>array('AssetsCategoryMasters/print_label','Print Label'),
                '197'=>array('AssetsCategoryMasters/ticket_creation','Ticket Creation'),
                '198'=>array('AssetsCategoryMasters/ticket_solution','Ticket Solution'),
                '215'=>array('AssetsCategoryMasters/ticket_solution_report','Ticket Solution Report'),
                '201'=>array('AssetsCategoryMasters/not_working_assets','Not Working Assets'),
                '210'=>array('AssetsCategoryMasters/download_assets_data','Download Assets Stocks'),
                
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
    public function provision()
    { 
        $this->layout='home';
        $page_access=explode(',',$this->Session->read("page_access"));
        
        $this->set('page_access',$page_access);
        
        $pageName = 
            array('provision'=>array(
                '41'=>array('Provisions','Create Provision'),
                '42'=>array('Provisions/uploadProvision','Upload Provision'),
                '178'=>array('Provisions/view','View & Edit Provision'),
                '179'=>array('Provisions/view_provision_change_request','Provision Approve'),
                '52'=>array('Revenues/index','Provision To BPS'),
                
                ),
                )
            
            ;
        
        $this->set('pages',$pageName);
        
    }
    
}
?>