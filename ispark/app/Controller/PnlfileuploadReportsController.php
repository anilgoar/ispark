<?php
class PnlfileuploadReportsController extends AppController {
    public $uses = array('Addbranch','InterviewMaster','VisitorMaster','InterviewQuestionmaster',
        'interviewquestion','maspackage','BandNameMaster','DesignationNameMaster','masband','StateMaster',
        'DepartmentNameMaster','CostCenterMaster','NewjclrMaster','LanguageMaster','Masjclrentry','TrainerMaster',
        'HRVisitorRecruiter','HRLogin','HRMeetPurpose','PnlFileUploadRecords');
        
    public function beforeFilter(){
        parent::beforeFilter(); 
        $this->Auth->allow(
            'index','deleteinterview','interviewleveldetails','interviewquestion','deletequestion','getquestion','viewvisitor',
            'interviewreport','show_visitor','export_visitor','getdesg','deletevisitor','deletehremp','getband','recruiter',
            'hrapproval','hrupdate','empdetails','get_emp','gettrainerdata','getcostcenter','getcostcenteredit','getprocessname',
                'hr_recruiter_add','hr_recruiter_edit','hr_mobile_user_add','hr_mobile_user_edit','hr_add_purpose_to_meet',
                'hr_delete_purpose_to_meet','resendinterview','pnl_data_report','report_analytics','get_analytics','get_cost_center'
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
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $branch_name    =   $request['PnlfileuploadReports']['branch_name'];
            
             /*
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            $Submit         =   $request['Submit'];
            $WhereDate      =   "DATE(created_at) >='$FromDate' AND DATE(created_at) <='$ToDate'";
            $whereBranch    =   $branch_name !="ALL"?"AND branch='$branch_name'":"";
            $where          =   "$WhereDate $whereBranch";
            
            $data   =   $this->PnlFileUploadRecords->query("SELECT * FROM `pnl_file_upload_records` 
                        WHERE 
                        $where order by branch
                        ");
            
        
            $data   =   $this->PnlFileUploadRecords->query("SELECT * FROM `pnl_file_upload_records` 
                        WHERE created_at=(SELECT MAX(created_at) FROM `pnl_file_upload_records`)
                        $where
                        GROUP BY branch,HeaderId");
            */
            
            //print_r($data);die;
            
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            
            $MonthList    =   $this->getMonth($request['FromDate'],$request['ToDate']);
            $Actual_Revenew=array(
                'net_revenue'=>'Net Revenue',
                'actual_salary'=>'Actual CTC',
                'D'=>'Direct Expenses',
                'I'=>'Indirect Expenses'
                );
                    
            ?>
            <table border="1">
                <tr>
                    <th>Branch</th>
                    <th>Actual Revenue</th>
                    <?php foreach($MonthList as $date){?>
                    <th><?php echo $date['month']."-".$date['year'];?></th>
                    <?php }?>
                    <th>Total</th>
                </tr>
                <?php $i=1; foreach($Actual_Revenew as $key=>$val){?>
                <tr>
                    <td style="border:none;"> <?php echo $i==2?$branch_name:"";?></td>
                    <td><?php echo $val;?></td>
                    
                    <?php 
                    $Total_Rev  =0;
                    foreach($MonthList as $date){
                        $finance_month          =   $date['month']."-".$date['year'];
                        $revenue_expence        =   $this->getrevenue_expences($branch_name,$finance_month,$key);
                        $Total_Rev              =   $Total_Rev+$revenue_expence;
                    ?>
                    <td><?php echo $revenue_expence;?></td>
                    <?php }?>
                    <td><?php echo $Total_Rev;?></td>
                </tr>
                <?php $i++; }?>
            </table>
            

          <!--
            <table border="1">     
                <thead>
                    <tr>
                        <th>Head Name</th>
                        <th>Branch</th>
                        <th>Finance Month</th>
                        <th>Cost Center</th>
                        <th>Head Value</th>
                        <th>Create Date</th> 
                        <th>Update Date</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($data as $row){
                        $rows=$row['pnl_file_upload_records'];
                        
                        if($rows['HeaderId'] =="net_revenue" || $rows['HeaderId'] =="actual_salary"){
                            if($rows['HeaderId'] =="net_revenue"){
                                $HeadVal="Net Revenue";
                            }
                            else if($rows['HeaderId'] =="actual_salary"){
                                $HeadVal="Actual CTC";
                            }
                        }
                        else{
                            $HeadVal=$this->getHeaderDes($rows['HeaderId']);
                        }       
                    ?>
                        <td><?php echo $HeadVal;?></td>
                        <td><?php echo $rows['branch'];?></td>
                        <td><?php echo $rows['finance_month'];?></td>
                        <td><?php echo $rows['CostCenter'];?></td>
                        <td><?php echo $rows['HeadValue'];?></td>
                        <td ><?php echo $rows['created_at']!=""?date("d-M-Y",strtotime($rows['created_at'])):''?></td>
                       <td ><?php echo $rows['updated_at']!=""?date("d-M-Y",strtotime($rows['updated_at'])):''?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
          -->
            <?php
            die;
               
        }    
    }
    
    public function pnl_data_report(){
        $this->layout='home';
        
        $branchName = $this->Session->read('branch_name');
        if($this->Session->read('role')=='admin' && $branchName =="HEAD OFFICE"){
            $BranchArray=$this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>array('active'=>1),'order'=>array('branch_name')));            
            $this->set('branchName',array_merge(array('ALL'=>'ALL'),$BranchArray));
        }
        else{
            $this->set('branchName',array($branchName=>$branchName)); 
        }
        
        if($this->request->is('POST')){
            $request        =   $this->request->data;
            $branch_name    =   $request['PnlfileuploadReports']['branch_name'];
            
            $FromDate       =   date("Y-m-d",strtotime($request['FromDate']));
            $ToDate         =   date("Y-m-d",strtotime($request['ToDate']));
            
            $WhereDate      =   "BETWEEN '$FromDate' AND '$ToDate'";
            $ddBranch       =   $branch_name !="ALL"?"AND dd.branch='$branch_name'":"";
            $dd1Branch      =   $branch_name !="ALL"?"AND dd1.branch='$branch_name'":"";
            $where          =   "$WhereDate $whereBranch";
            
            $data           =   $this->PnlFileUploadRecords->query("
            SELECT dd.*,group_cost_center,cost_center_type FROM (SELECT dd.*
            FROM `pnl_file_upload_records` dd
            WHERE DATE(dd.created_at) $WhereDate $ddBranch AND 
            dd.created_at = (SELECT MAX(created_at) FROM pnl_file_upload_records AS dd1 WHERE 
             dd.CostCenter=dd1.CostCenter AND dd.finance_month=dd1.finance_month AND dd.branch=dd1.branch
            AND DATE(dd1.created_at) $WhereDate $dd1Branch 
             ) )AS dd
            INNER JOIN cost_master cm ON dd.CostCenter=cm.cost_center
            ORDER BY dd.branch,STR_TO_DATE(CONCAT('01-',dd.finance_month),'%d-%b-%y')
            ");

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table border="1">     
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Head Name</th>
                        <th>Finance Month</th>
                        <th>Cost Center</th>
                        <th>Group Cost Center</th>
                        <th>Cost Center Type</th>
                        <th>Head Value</th>
                        <th>Create Date</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;foreach($data as $row){
           
                        $rows   =   $row['dd'];
                        $rowsc  =   $row['cm'];
            
                        if($rows['HeaderId'] =="net_revenue"){
                            $HeadVal="Net Revenue";
                        }
                        else if($rows['HeaderId'] =="actual_salary"){
                            $HeadVal="Actual CTC";
                        }
                        else if($rows['HeaderId'] =="future_revenue"){
                            $HeadVal="Future Revenue Adjustment";
                        }
                        else{
                            $HeadVal=$this->getHeaderDes($rows['HeaderId']);
                        }         
                    ?>
                        <td><?php echo $rows['branch'];?></td>
                        <td><?php echo $HeadVal;?></td>
                        <td><?php echo $rows['finance_month'];?></td>
                        <td><?php echo $rows['CostCenter'];?></td>
                        <td><?php echo $rowsc['group_cost_center'];?></td>
                        <td><?php echo $rowsc['cost_center_type'];?></td>
                        <td><?php echo $rows['HeadValue'];?></td>
                        <td ><?php echo $rows['created_at']!=""?date("d-M-Y",strtotime($rows['created_at'])):''?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
            die;
               
        }    
    }
    
    function getHeaderDes($Id){
        $data   =   $this->PnlFileUploadRecords->query("SELECT HeadingDesc FROM `tbl_bgt_expenseheadingmaster` WHERE HeadingId='$Id'");
        return $data[0]['tbl_bgt_expenseheadingmaster']['HeadingDesc'];  
    }
    
    function getMonth($startDate, $endDate) {
        $months = array();
        while (strtotime($startDate) <= strtotime($endDate)) {
            $months[] = array('year' => date('y', strtotime($startDate)), 'month' => date('M', strtotime($startDate)), );
            $startDate = date('d M Y', strtotime($startDate.
                '+ 1 month'));
        }
        
        return $months;
    }
    
    function getrevenue_expences($branch_name,$finance_month,$HeaderId){
        
        $whereBranch    =   $branch_name !="ALL"?"AND branch='$branch_name'":"";
        
        $ddBranch       =   $branch_name !="ALL"?"AND dd.branch='$branch_name'":"";
        $dd1Branch      =   $branch_name !="ALL"?"AND dd1.branch='$branch_name'":"";
        
        if($HeaderId =="net_revenue" || $HeaderId =="actual_salary"){
            /*
            $data           =   $this->PnlFileUploadRecords->query("SELECT SUM(HeadValue) as HeadValue FROM `pnl_file_upload_records`
                                    WHERE finance_month='$finance_month' AND HeaderId='$HeaderId' $whereBranch
                                    ");*/
            
            $data =   $this->PnlFileUploadRecords->query("
            SELECT SUM(dd.HeadValue) as HeadValue
            FROM `pnl_file_upload_records` dd
            WHERE dd.finance_month='$finance_month' AND dd.HeaderId='$HeaderId'  $ddBranch AND 
            dd.created_at = (SELECT MAX(created_at) FROM pnl_file_upload_records AS dd1 WHERE 
             dd.CostCenter=dd1.CostCenter AND dd.finance_month=dd1.finance_month AND dd.branch=dd1.branch
            AND dd1.finance_month='$finance_month' AND dd1.HeaderId='$HeaderId' $dd1Branch 
            )
            "); 
        }
        else{
            if($HeaderId =="I"){
                /*
                $data2           =   $this->PnlFileUploadRecords->query("SELECT SUM(HeadValue) as HeadValue FROM `pnl_file_upload_records`
                                    WHERE finance_month='$finance_month' AND HeaderId='future_revenue' $whereBranch
                                    "); 
                
                */
                $data2 =   $this->PnlFileUploadRecords->query("
                SELECT SUM(dd.HeadValue) as HeadValue
                FROM `pnl_file_upload_records` dd
                WHERE dd.finance_month='$finance_month' AND dd.HeaderId='future_revenue'  $ddBranch AND 
                dd.created_at = (SELECT MAX(created_at) FROM pnl_file_upload_records AS dd1 WHERE 
                 dd.CostCenter=dd1.CostCenter AND dd.finance_month=dd1.finance_month AND dd.branch=dd1.branch
                AND dd1.finance_month='$finance_month' AND dd1.HeaderId='future_revenue' $dd1Branch 
                )
                "); 
                
                
            }
            /*
            $data3           =   $this->PnlFileUploadRecords->query("SELECT SUM(HeadValue) as HeadValue FROM `pnl_file_upload_records` t1,`tbl_bgt_expenseheadingmaster` t2 
                                WHERE t1.HeaderId=t2.HeadingId AND t1.finance_month='$finance_month' AND t2.Cost='$HeaderId' $whereBranch");
            */
            
            $data3 =   $this->PnlFileUploadRecords->query("                
            SELECT SUM(dd.HeadValue) AS HeadValue FROM(SELECT * FROM `pnl_file_upload_records` dd 
            WHERE dd.finance_month='$finance_month' $ddBranch AND dd.created_at = 
            (SELECT MAX(created_at) FROM pnl_file_upload_records AS dd1 WHERE dd.CostCenter=dd1.CostCenter AND 
            dd.finance_month=dd1.finance_month AND dd.branch=dd1.branch AND dd1.finance_month='$finance_month' $dd1Branch ) )AS dd
            INNER JOIN tbl_bgt_expenseheadingmaster t2 ON dd.HeaderId=t2.HeadingId AND t2.Cost='$HeaderId'
            "); 
            
            $data[0][0]['HeadValue']=$data2[0][0]['HeadValue']+$data3[0][0]['HeadValue']; 
        } 
        
        return $data[0][0]['HeadValue'] !=""?$data[0][0]['HeadValue']:0;   
    }
    
    
    
    public function report_analytics()
    {
        
        $this->layout='home';
        $userid = $this->Session->read('userid');
        $branch_name = $this->Session->read('branch_name');
        
        $month_arr_new = array();
        $month_arr = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
            'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
        $Year_Start = 19;
        $Year_End = 24;
        
        for($a = $Year_Start;$a<=$Year_End; $a++)
        {
            foreach($month_arr as $mnt)
            {
                $mnts = $mnt.'-'.$a;
                $month_arr_new[$mnts] = $mnts;
            }
            
        }
        
        //if($this->Session->read('role')=='admin')
        if(1)
        {
            $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"active='1'",'order'=>array('branch_name'=>'asc')));
            $branch_master = array('All'=>'All') + $branch_master;
            $cost_master = $this->CostCenterMaster->find('list',array('fields'=> array('group_cost_center','group_cost_center'),'conditions'=>"active='1'",'order'=>array('group_cost_center'=>'asc')));
        }
        else
        {
            $branch_master = $this->Addbranch->find('list',array('fields' =>array('branch_name','branch_name'),'conditions'=>"branch_name ='$branch_name' and active='1'",'order'=>array('branch_name'=>'asc')));
            $branch_master =  $branch_master;
            $cost_master = $this->CostCenterMaster->find('list',array('fields'=> array('group_cost_center','group_cost_center'),'conditions'=>"branch ='$branch_name' and active='1'",'order'=>array('group_cost_center'=>'asc')));
        }
        
        $this->set('branch_master',$branch_master);
        $this->set('month_master',$month_arr_new);
        $this->set('group_master',$cost_master);
    }
    
    public function get_analytics()
    {
        
        
        $userid = $this->Session->read('userid');
        $request = $this->params->query;
        
        $category = $request['category'];
        $from = $request['from'];
        $to = $request['to'];
        $group = $request['group'];
        $branch = $request['branch'];
        $type = $request['type'];
        
        $query_1 = "";
        
        if($category=='Group')
        {
            if($group=='All')
            {
                
            }
            else
            {
                $query_1 = " and cm1.group_cost_center='$group'";
            }
        }
        else
        {
            if($branch=='All')
            {
                
            }
            else
            {
                $query_1 = " and cm1.branch='$branch'";
            }
        }
        
        $month_start = substr($from,4); 
        $month_end = substr($to,4); ;
        
        $month_arr = array('Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun',
            'Jul'=>'Jul','Aug'=>'Aug','Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec');
        
        $Year_Start = 19;
        $Year_End = 24;
        $start = false;
        
        
        if($type=='summary')
        {
            if($category=='Group')
            {

                for($a = $month_start;$a<=$month_end; $a++)
                {
                    foreach($month_arr as $mnt)
                    {
                        $mnts = $mnt.'-'.$a; 
                        if($from==$mnts)
                        {
                            $start = true;
                        }

                        if($start)
                        {
                            $month_arr_n[] = $mnts; 
                            $query_2 = " AND pfur1.finance_month='$mnts'";
                            $max_Qr = "SELECT cm1.branch,MAX(created_at) max_date FROM `pnl_file_upload_records` pfur1 
                            INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                            WHERE 1=1 $query_1 $query_2 group by cm1.branch"; 
                            $branch_wise_max_date = $this->PnlFileUploadRecords->query($max_Qr);

                            //print_r($branch_wise_max_date); exit;

                            foreach($branch_wise_max_date as $record)
                            {
                                $created_at = $record['0']['max_date'];
                                $branch_n = $record['cm1']['branch'];
                                $branch_qr = " and cm1.branch='$branch_n'";


                                    // Taking Net Revenue
                                    $data_revenue_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='net_revenue' $query_1 $query_2 $branch_qr 
                                AND created_at='$created_at'";

    //                                if($branch_n=='NOIDA')
    //                                {
    //                                    echo $data_revenue_qr; exit;
    //                                }
                                $branch_wise_revenue = $this->PnlFileUploadRecords->query($data_revenue_qr);    

                                //print_r($branch_wise_revenue); exit;


                                $record_total_master[$mnts]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];
                                $record_master[$mnts][$branch_n]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];

                                    // Taking CTC 
                                    $data_ctc_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='actual_salary' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at'";
                                $branch_wise_ctc = $this->PnlFileUploadRecords->query($data_ctc_qr);    
                                 //print_r($branch_wise_ctc); exit;   
                                $record_total_master[$mnts]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];
                                $record_master[$mnts][$branch_n]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];

                                    // Taking Direct Cost 
                                    $data_direct_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='D'
                                WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at' "; 
                                $branch_wise_direct = $this->PnlFileUploadRecords->query($data_direct_qr);


                                    $record_total_master[$mnts]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];
                                    $record_master[$mnts][$branch_n]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];

                                //print_r($record_total_master); exit;   
                                // Taking InDirect Cost 
                                   $data_indirect_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='I'
                               WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_indirect = $this->PnlFileUploadRecords->query($data_indirect_qr);

                               foreach($branch_wise_indirect as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$mnts][$branch_n]['Indirect Expenses'] += $br['0']['HeadValue'];
                               } 

                               // Taking InDirect Cost (future_revenue)
                                   $data_future_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               WHERE HeaderId ='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_future = $this->PnlFileUploadRecords->query($data_future_qr);

                               foreach($branch_wise_future as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$mnts][$branch_n]['Indirect Expenses'] += $br['0']['HeadValue'];
                               }  
                            }

                        }
                        if($to==$mnts)
                        {
                            $start = false;
                        }
                    }            
                }


                //print_r($record_master); exit;

                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=pnl_analytic_summary.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
                $branch_master_n = array_unique($branch_master_n);
                $month_arr_n =array_unique($month_arr_n);
                
                //print_r($month_arr_n); exit;
                echo "<table border='1'>";
                echo '<tr><th>'."$group $from to $to".'</th></tr>';
                echo '<tr>';
                echo '<th></th>';
                foreach($month_arr_n as $mnt)
                {
                    echo '<td align="center">'."=text(\"$mnt\",\"mmm-yy\")".'</td>';
                }
                echo '<td align="center">Total</td>';
                echo '</tr>';
                $header = array('Revenue','CTC','Direct Expenses','Indirect Expenses');

                foreach($header as $head)
                {
                    $total = 0;
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                    foreach($month_arr_n as $mnt)
                    {
                        echo '<td align="center">'.round($record_total_master[$mnt][$head]/100000,2).'</td>';
                        $total +=round($record_total_master[$mnt][$head]/100000,2);
                    }
                    echo "<td  align=\"center\">$total</td>";
                    echo '</tr>';
                    echo '<tr></tr>';
                }

                $total_op = 0;
                echo '<tr>';
                echo '<th>OP</th>';
                foreach($month_arr_n as $mnt)
                {
                    echo '<td align="center">'.round(round($record_total_master[$mnt]['Revenue']/100000,2)-round($record_total_master[$mnt]['CTC']/100000,2)-round($record_total_master[$mnt]['Direct Expenses']/100000,2)-round($record_total_master[$mnt]['Indirect Expenses']/100000,2),2).'</td>';
                    $total_op += round(round($record_total_master[$mnt]['Revenue']/100000,2)-round($record_total_master[$mnt]['CTC']/100000,2)-round($record_total_master[$mnt]['Direct Expenses']/100000,2)-round($record_total_master[$mnt]['Indirect Expenses']/100000,2),2);
                }
                echo "<td  align=\"center\">$total_op</td>";
                echo '</tr>';
                echo '<tr></tr>';

                $total = 0;
                echo '<tr>';
                echo '<th>% OP</th>';
                foreach($month_arr_n as $mnt)
                {
                    echo '<td align="center">'.round(round(round($record_total_master[$mnt]['Revenue']/100000,2)-round($record_total_master[$mnt]['CTC']/100000,2)-round($record_total_master[$mnt]['Direct Expenses']/100000,2)-round($record_total_master[$mnt]['Indirect Expenses']/100000,2),2)*100/round($record_total_master[$mnt]['Revenue']/100000,2),2).'%</td>';
                    $total += round($record_total_master[$mnt]['Revenue']/100000,2);
                }
                echo "<td align=\"center\">".round($total_op*100/$total,2)."%</td>";
                echo '</tr>';
                echo '<tr></tr>';

                echo '</table>';

                exit;
            }
            if($category=='Branch')
            {

                for($a = $month_start;$a<=$month_end; $a++)
                {
                    foreach($month_arr as $mnt)
                    {
                        $mnts = $mnt.'-'.$a; 
                        if($from==$mnts)
                        {
                            $start = true;
                        }

                        if($start)
                        {
                            $month_arr_n[] = $mnts; 
                            $query_2 = " AND pfur1.finance_month='$mnts'";
                            $max_Qr = "SELECT cm1.branch,MAX(created_at) max_date FROM `pnl_file_upload_records` pfur1 
                            INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                            WHERE 1=1 $query_1 $query_2 group by cm1.branch";  
                            $branch_wise_max_date = $this->PnlFileUploadRecords->query($max_Qr);

                            //print_r($branch_wise_max_date); exit;

                            foreach($branch_wise_max_date as $record)
                            {
                                $created_at = $record['0']['max_date'];
                                $branch_n = $record['cm1']['branch'];
                                $branch_qr = " and cm1.branch='$branch_n'";


                                    // Taking Net Revenue
                                    $data_revenue_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='net_revenue' $query_1 $query_2 $branch_qr 
                                AND created_at='$created_at'";

    //                                if($branch_n=='NOIDA')
    //                                {
    //                                    echo $data_revenue_qr; exit;
    //                                }
                                $branch_wise_revenue = $this->PnlFileUploadRecords->query($data_revenue_qr);    

                                //print_r($branch_wise_revenue); exit;


                                $record_total_master[$mnts]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];
                                $record_master[$mnts]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];

                                    // Taking CTC 
                                    $data_ctc_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='actual_salary' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at'";
                                $branch_wise_ctc = $this->PnlFileUploadRecords->query($data_ctc_qr);    
                                 //print_r($branch_wise_ctc); exit;   
                                $record_total_master[$mnts]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];
                                $record_master[$mnts][$branch_n]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];

                                    // Taking Direct Cost 
                                    $data_direct_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='D'
                                WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at' "; 
                                $branch_wise_direct = $this->PnlFileUploadRecords->query($data_direct_qr);


                                    $record_total_master[$mnts]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];
                                    $record_master[$mnts][$branch_n]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];

                                //print_r($record_total_master); exit;   
                                // Taking InDirect Cost 
                                   $data_indirect_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='I'
                               WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_indirect = $this->PnlFileUploadRecords->query($data_indirect_qr);

                               foreach($branch_wise_indirect as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$mnts][$branch_n]['Indirect Expenses'] += $br['0']['HeadValue'];
                               } 

                               // Taking InDirect Cost (future_revenue)
                                   $data_future_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               WHERE HeaderId ='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_future = $this->PnlFileUploadRecords->query($data_future_qr);

                               foreach($branch_wise_future as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$mnts][$branch_n]['Indirect Expenses'] += $br['0']['HeadValue'];
                               }  
                            }

                        }
                        if($to==$mnts)
                        {
                            $start = false;
                        }
                    }            
                }


                //print_r($record_master); exit;

                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=pnl_analytic_summary.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
                $branch_master_n = array_unique($branch_master_n);
                $month_arr_n =array_unique($month_arr_n);
                
                //print_r($month_arr_n); exit;
                echo "<table border='1'>";
                if($category=='Group')
                {
                    echo '<tr><th>'."$group $from to $to".'</th></tr>';
                }
                else
                {
                    echo '<tr><th>'."$branch $from to $to".'</th></tr>';
                }
                echo '<tr>';
                echo '<th></th>';
                foreach($month_arr_n as $mnt)
                {
                    echo '<td align="center">'."=text(\"$mnt\",\"mmm-yy\")".'</td>';
                }
                echo '<td align="center">Total</td>';
                echo '</tr>';
                $header = array('Revenue','CTC','Direct Expenses','Indirect Expenses');

                foreach($header as $head)
                {
                    $total = 0;
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                    foreach($month_arr_n as $mnt)
                    {
                        echo '<td align="center">'.round($record_total_master[$mnt][$head]/100000,2).'</td>';
                        $total +=round($record_total_master[$mnt][$head]/100000,2);
                    }
                    echo "<td  align=\"center\">$total</td>";
                    echo '</tr>';
                    echo '<tr></tr>';
                }

                $total_op = 0;
                echo '<tr>';
                echo '<th>OP</th>';
                foreach($month_arr_n as $mnt)
                {
                    echo '<td align="center">'.round(round($record_total_master[$mnt]['Revenue']/100000,2)-round($record_total_master[$mnt]['CTC']/100000,2)-round($record_total_master[$mnt]['Direct Expenses']/100000,2)-round($record_total_master[$mnt]['Indirect Expenses']/100000,2),2).'</td>';
                    $total_op += round(round($record_total_master[$mnt]['Revenue']/100000,2)-round($record_total_master[$mnt]['CTC']/100000,2)-round($record_total_master[$mnt]['Direct Expenses']/100000,2)-round($record_total_master[$mnt]['Indirect Expenses']/100000,2),2);
                }
                echo "<td  align=\"center\">$total_op</td>";
                echo '</tr>';
                echo '<tr></tr>';

                $total = 0;
                echo '<tr>';
                echo '<th>% OP</th>';
                foreach($month_arr_n as $mnt)
                {
                    echo '<td align="center">'.round(round(round($record_total_master[$mnt]['Revenue']/100000,2)-round($record_total_master[$mnt]['CTC']/100000,2)-round($record_total_master[$mnt]['Direct Expenses']/100000,2)-round($record_total_master[$mnt]['Indirect Expenses']/100000,2),2)*100/round($record_total_master[$mnt]['Revenue']/100000,2),2).'%</td>';
                    $total += round($record_total_master[$mnt]['Revenue']/100000,2);
                }
                echo "<td align=\"center\">".round($total_op*100/$total,2)."%</td>";
                echo '</tr>';
                echo '<tr></tr>';

                echo '</table>';

                exit;
            }
        }
        else
        {
            if($category=='Group')
            {

                for($a = $month_start;$a<=$month_end; $a++)
                {
                    foreach($month_arr as $mnt)
                    {
                        $mnts = $mnt.'-'.$a; 
                        if($from==$mnts)
                        {
                            $start = true;
                        }

                        if($start)
                        {
                            $month_arr_n[] = $mnts; 
                            $query_2 = " AND pfur1.finance_month='$mnts'";
                            $max_Qr = "SELECT cm1.branch,MAX(created_at) max_date FROM `pnl_file_upload_records` pfur1 
                            INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                            WHERE 1=1 $query_1 $query_2 group by cm1.branch"; 
                            $branch_wise_max_date = $this->PnlFileUploadRecords->query($max_Qr);

                            //print_r($branch_wise_max_date); exit;

                            foreach($branch_wise_max_date as $record)
                            {
                                $created_at = $record['0']['max_date'];
                                $branch_n = $record['cm1']['branch'];
                                $branch_qr = " and cm1.branch='$branch_n'";
                                $branch_master_n[] = $branch_n;

                                    // Taking Net Revenue
                                    $data_revenue_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='net_revenue' $query_1 $query_2 $branch_qr 
                                AND created_at='$created_at'";

    //                                if($branch_n=='NOIDA')
    //                                {
    //                                    echo $data_revenue_qr; exit;
    //                                }
                                $branch_wise_revenue = $this->PnlFileUploadRecords->query($data_revenue_qr);    

                                //print_r($branch_wise_revenue); exit;


                                $record_total_master[$mnts]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];
                                $record_master[$mnts][$branch_n]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];

                                    // Taking CTC 
                                    $data_ctc_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='actual_salary' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at'";
                                $branch_wise_ctc = $this->PnlFileUploadRecords->query($data_ctc_qr);    
                                 //print_r($branch_wise_ctc); exit;   
                                $record_total_master[$mnts]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];
                                $record_master[$mnts][$branch_n]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];

                                    // Taking Direct Cost 
                                    $data_direct_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='D'
                                WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at' "; 
                                $branch_wise_direct = $this->PnlFileUploadRecords->query($data_direct_qr);


                                    $record_total_master[$mnts]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];
                                    $record_master[$mnts][$branch_n]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];

                                //print_r($record_total_master); exit;   
                                // Taking InDirect Cost 
                                   $data_indirect_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='I'
                               WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_indirect = $this->PnlFileUploadRecords->query($data_indirect_qr);

                               foreach($branch_wise_indirect as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$mnts][$branch_n]['Indirect Expenses'] += $br['0']['HeadValue'];
                               } 

                               // Taking InDirect Cost (future_revenue)
                                   $data_future_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               WHERE HeaderId ='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_future = $this->PnlFileUploadRecords->query($data_future_qr);

                               foreach($branch_wise_future as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$mnts][$branch_n]['Indirect Expenses'] += $br['0']['HeadValue'];
                               }  
                            }

                        }
                        if($to==$mnts)
                        {
                            $start = false;
                        }
                    }            
                }

                //print_r($record_master); exit;
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=pnl_analytic_details.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                //print_r($month_arr_n); exit;
                
                $branch_master_n = array_unique($branch_master_n);
                $month_arr_n =array_unique($month_arr_n);
                
                echo "<table border='1'>";
                echo '<tr><th>'."$group $from to $to".'</th></tr>';
                
                echo '<tr>';
                echo '<th></th>';
                foreach($branch_master_n as $branch_n)
                {
                    echo '<td align="center" colspan="'.count($month_arr_n).'">'."$branch_n".'</td>';
                }
                echo '<td align="center">Total</td>';
                echo '</tr>';
                
                echo '<tr>';
                echo '<th></th>';
                foreach($branch_master_n as $branch_n)
                {
                    foreach($month_arr_n as $mnt)
                    {
                        echo '<td align="center">'."=text(\"$mnt\",\"mmm-yy\")".'</td>';
                    }
                    
                }
                
                echo '<td align="center">Total</td>';
                echo '</tr>';
                
                $header = array('Revenue','CTC','Direct Expenses','Indirect Expenses');

                foreach($header as $head)
                {
                    $total = 0;
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                    foreach($month_arr_n as $mnt)
                    {
                        foreach($branch_master_n as $branch_n)
                        {
                            echo '<td align="center">'.round($record_master[$mnt][$branch_n][$head]/100000,2).'</td>';
                            $total +=round($record_master[$mnt][$branch_n][$head]/100000,2);
                        }
                    }
                    echo "<td  align=\"center\">$total</td>";
                    echo '</tr>';
                    echo '<tr></tr>';
                }

                $total_op = 0;
                echo '<tr>';
                echo '<th>OP</th>';
                foreach($month_arr_n as $mnt)
                {
                    foreach($branch_master_n as $branch_n)
                    {
                        echo '<td align="center">'.round(round($record_master[$mnt][$branch_n]['Revenue']/100000,2)-round($record_master[$mnt][$branch_n]['CTC']/100000,2)-round($record_master[$mnt][$branch_n]['Direct Expenses']/100000,2)-round($record_master[$mnt][$branch_n]['Indirect Expenses']/100000,2),2).'</td>';
                        $total_op += round(round($record_master[$mnt][$branch_n]['Revenue']/100000,2)-round($record_master[$mnt][$branch_n]['CTC']/100000,2)-round($record_master[$mnt][$branch_n]['Direct Expenses']/100000,2)-round($record_master[$mnt][$branch_n]['Indirect Expenses']/100000,2),2);
                    }
                }
                echo "<td  align=\"center\">$total_op</td>";
                echo '</tr>';
                echo '<tr></tr>';

                $total = 0;
                echo '<tr>';
                echo '<th>% OP</th>';
                foreach($month_arr_n as $mnt)
                {
                    foreach($branch_master_n as $branch_n)
                    {
                        echo '<td align="center">'.round(round(round($record_master[$mnt][$branch_n]['Revenue']/100000,2)-round($record_master[$mnt][$branch_n]['CTC']/100000,2)-round($record_master[$mnt][$branch_n]['Direct Expenses']/100000,2)-round($record_master[$mnt][$branch_n]['Indirect Expenses']/100000,2),2)*100/round($record_master[$mnt][$branch_n]['Revenue']/100000,2),2).'%</td>';
                        $total += round($record_master[$mnt][$branch_n]['Revenue']/100000,2);
                    }
                }
                echo "<td align=\"center\">".round($total_op*100/$total,2)."%</td>";
                echo '</tr>';
                echo '<tr></tr>';

                echo '</table>';

                exit;
            }
            if($category=='Branch')
            {

                for($a = $month_start;$a<=$month_end; $a++)
                {
                    foreach($month_arr as $mnt)
                    {
                        $mnts = $mnt.'-'.$a; 
                        if($from==$mnts)
                        {
                            $start = true;
                        }

                        if($start)
                        {
                            $month_arr_n[] = $mnts; 
                            $query_2 = " AND pfur1.finance_month='$mnts'";
                            $max_Qr = "SELECT cm1.branch,MAX(created_at) max_date FROM `pnl_file_upload_records` pfur1 
                            INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                            WHERE 1=1 $query_1 $query_2 group by cm1.branch";  
                            $branch_wise_max_date = $this->PnlFileUploadRecords->query($max_Qr);

                            //print_r($branch_wise_max_date); exit;

                            foreach($branch_wise_max_date as $record)
                            {
                                $created_at = $record['0']['max_date'];
                                $branch_n = $record['cm1']['branch'];
                                $branch_qr = " and cm1.branch='$branch_n'";
                                $branch_master_n[] = $branch_n;

                                    // Taking Net Revenue
                                    $data_revenue_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='net_revenue' $query_1 $query_2 $branch_qr 
                                AND created_at='$created_at'";

    //                                if($branch_n=='NOIDA')
    //                                {
    //                                    echo $data_revenue_qr; exit;
    //                                }
                                $branch_wise_revenue = $this->PnlFileUploadRecords->query($data_revenue_qr);    

                                //print_r($branch_wise_revenue); exit;


                                $record_total_master[$mnts]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];
                                $record_master[$branch_n][$mnts]['Revenue'] += $branch_wise_revenue['0']['0']['HeadValue'];

                                    // Taking CTC 
                                    $data_ctc_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                WHERE HeaderId='actual_salary' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at'";
                                $branch_wise_ctc = $this->PnlFileUploadRecords->query($data_ctc_qr);    
                                 //print_r($branch_wise_ctc); exit;   
                                $record_total_master[$mnts]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];
                                $record_master[$branch_n][$mnts]['CTC'] += $branch_wise_ctc['0']['0']['HeadValue'];

                                    // Taking Direct Cost 
                                    $data_direct_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                                INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                                INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='D'
                                WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                                AND created_at='$created_at' "; 
                                $branch_wise_direct = $this->PnlFileUploadRecords->query($data_direct_qr);


                                    $record_total_master[$mnts]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];
                                    $record_master[$branch_n][$mnts]['Direct Expenses'] += $branch_wise_direct['0']['0']['HeadValue'];

                                //print_r($record_total_master); exit;   
                                // Taking InDirect Cost 
                                   $data_indirect_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               INNER JOIN `tbl_bgt_expenseheadingmaster` head ON pfur1.HeaderId = head.HeadingId AND head.cost='I'
                               WHERE HeaderId !='actual_salary' and HeaderId !='net_revenue' and HeaderId !='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_indirect = $this->PnlFileUploadRecords->query($data_indirect_qr);

                               foreach($branch_wise_indirect as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$branch_n][$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                               } 

                               // Taking InDirect Cost (future_revenue)
                                   $data_future_qr = "SELECT SUM(HeadValue) HeadValue FROM `pnl_file_upload_records` pfur1 
                               INNER JOIN cost_master cm1 ON pfur1.CostCenter = cm1.cost_center
                               WHERE HeaderId ='future_revenue' $query_1 $query_2 $branch_qr
                               AND created_at='$created_at'";
                               $branch_wise_future = $this->PnlFileUploadRecords->query($data_future_qr);

                               foreach($branch_wise_future as $br)
                               {
                                   $record_total_master[$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                                   $record_master[$branch_n][$mnts]['Indirect Expenses'] += $br['0']['HeadValue'];
                               }  
                            }

                        }
                        if($to==$mnts)
                        {
                            $start = false;
                        }
                    }            
                }


                //print_r($record_master); exit;

                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=pnl_analytic_details.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
                $branch_master_n = array_unique($branch_master_n);
                $month_arr_n =array_unique($month_arr_n);
                
                //print_r($month_arr_n); exit;
                echo "<table border='1'>";
                if($category=='Group')
                {
                    echo '<tr><th>'."$group $from to $to".'</th></tr>';
                }
                else
                {
                    echo '<tr><th>'."$branch $from to $to".'</th></tr>';
                }
                echo '<tr>';
                echo '<th></th>';
                foreach($branch_master_n as $branch_n)
                {
                    echo '<td align="center" colspan="'.count($month_arr_n).'">'."$branch_n".'</td>';
                }
                echo '<td align="center">Total</td>';
                echo '</tr>';
                
                echo '<tr>';
                echo '<th></th>';
                foreach($branch_master_n as $branch_n)
                {
                    foreach($month_arr_n as $mnt)
                    {
                        echo '<td align="center">'."=text(\"$mnt\",\"mmm-yy\")".'</td>';
                    }
                    
                }
                echo '<td align="center">Total</td>';
                echo '</tr>';
                
                $header = array('Revenue','CTC','Direct Expenses','Indirect Expenses');

                foreach($header as $head)
                {
                    $total = 0;
                    echo '<tr>';
                    echo '<th>'.$head.'</th>';
                    foreach($branch_master_n as $branch_n)
                    {
                        foreach($month_arr_n as $mnt)
                        {
                            echo '<td align="center">'.round($record_master[$branch_n][$mnt][$head]/100000,2).'</td>';
                            $total +=round($record_master[$branch_n][$mnt][$head]/100000,2);
                        }
                    }
                    echo "<td  align=\"center\">$total</td>";
                    echo '</tr>';
                    echo '<tr></tr>';
                }

                $total_op = 0;
                echo '<tr>';
                echo '<th>OP</th>';
                foreach($branch_master_n as $branch_n)
                {
                    foreach($month_arr_n as $mnt)
                    {
                        echo '<td align="center">'.round(round($record_master[$branch_n][$mnt]['Revenue']/100000,2)-round($record_master[$branch_n][$mnt]['CTC']/100000,2)-round($record_master[$branch_n][$mnt]['Direct Expenses']/100000,2)-round($record_master[$branch_n][$mnt]['Indirect Expenses']/100000,2),2).'</td>';
                        $total_op += round(round($record_master[$branch_n][$mnt]['Revenue']/100000,2)-round($record_master[$branch_n][$mnt]['CTC']/100000,2)-round($record_master[$branch_n][$mnt]['Direct Expenses']/100000,2)-round($record_master[$branch_n][$mnt]['Indirect Expenses']/100000,2),2);
                    }
                }
                echo "<td  align=\"center\">$total_op</td>";
                echo '</tr>';
                echo '<tr></tr>';

                $total = 0;
                echo '<tr>';
                echo '<th>% OP</th>';
                foreach($branch_master_n as $branch_n)
                {
                    foreach($month_arr_n as $mnt)
                    {
                        echo '<td align="center">'.round(round(round($record_master[$branch_n][$mnt]['Revenue']/100000,2)-round($record_master[$branch_n][$mnt]['CTC']/100000,2)-round($record_master[$branch_n][$mnt]['Direct Expenses']/100000,2)-round($record_master[$branch_n][$mnt]['Indirect Expenses']/100000,2),2)*100/round($record_master[$branch_n][$mnt]['Revenue']/100000,2),2).'%</td>';
                        $total += round($record_master[$branch_n][$mnt]['Revenue']/100000,2);
                    }
                }
                echo "<td align=\"center\">".($total_op*100/$total)."%</td>";
                echo '</tr>';
                echo '<tr></tr>';

                echo '</table>';

                exit;
            }
        }
        
        
        
        
        
        exit;
    }
    
    public function get_cost_center()
    {
        $cost_center_type = $this->request->data['cost_center_type'];
        $cost_master = $this->CostCenterMaster->find('list',array('fields'=> array('group_cost_center','group_cost_center'),'conditions'=>"group_cost_center!='' and group_cost_center is not null and cost_center_type='$cost_center_type' and active='1'",'order'=>array('group_cost_center'=>'asc')));
        echo '<option value="">Select</option>';
        foreach($cost_master as $key=>$value)
        {
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
        
        exit;
    }
    
}
?>