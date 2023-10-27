<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//die;

include('report-send.php');
// $con = mysql_connect("localhost",'root','321*#LDtr!?*ktasb');
// $db = mysql_select_db("db_bill", $con);

$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not connect");
$Db = mysqli_select_db($con,"db_bill");


//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="SELECT * FROM `masjclrentry`  WHERE STATUS='1'";
$DataArr = mysqli_query($con,$qry);

$delete_record = mysqli_query($con,"Delete from continuously_leave");

if(!empty($DataArr))
{
    
    $flag = false;
    $ins_qry = array();
    $a= 0;
    
    while($data=mysqli_fetch_assoc($DataArr))
    {

        $EmpCode = $data['EmpCode'];
        
        // DATE(ImportDate)>'2023-06-16' AND
        $emp_qry = "SELECT * FROM Attandence WHERE EmpCode='$EmpCode' and  DATE(AttandDate) BETWEEN DATE_FORMAT(SUBDATE(NOW(),INTERVAL 1 MONTH),'%Y-%m-01') AND CURDATE() ORDER BY AttandDate ASC";
        $emp_data = mysqli_query($con,$emp_qry);
        $a= 0;
        $i = 0;
        $break = 0;
        
        $from_date = "";
        $end_date="";
        while($emp_arr = mysqli_fetch_assoc($emp_data))
        {
            $status = $emp_arr['Status'];
   
            if($status == 'A')
            {
                $a++;
                if(empty($from_date))
                {
                    $from_date = $emp_arr['AttandDate'];    
                }
                
                // if($a>3 && !empty($ins_qry[$emp_arr['EmpCode']]['end_date']))
                // {
                //     $ins_qry[$emp_arr['EmpCode']]['end_date'] = $emp_arr['AttandDate'];
                // }
                //$from_date = $emp_arr['AttandDate']; 
                if($a>3 && !empty($ins_qry[$emp_arr['EmpCode']]['end_date']))
                {
                    $ins_qry[$emp_arr['EmpCode']]['end_date'] = $emp_arr['AttandDate'];
                }
                
            }else{
                
                $a = 0;                
                $from_date = "";
                $end_date = "";
            }
            
            if($a > 3)
            {
                //$from_date = $emp_arr['AttandDate'];
                $end_date = $emp_arr['AttandDate'];
                
                $ins_qry[$emp_arr['EmpCode']]['EmpName'] = $emp_arr['EmpName'];
                $ins_qry[$emp_arr['EmpCode']]['LeaveDate'] = $emp_arr['AttandDate'];
                $ins_qry[$emp_arr['EmpCode']]['BranchName'] = $emp_arr['BranchName'];
                $ins_qry[$emp_arr['EmpCode']]['CostCenter'] = $emp_arr['CostCenter'];
                $ins_qry[$emp_arr['EmpCode']]['from_date'] = $from_date;
                $ins_qry[$emp_arr['EmpCode']]['end_date'] = $end_date;
                $ins_qry[$emp_arr['EmpCode']]['EmpStatus'] = $emp_arr['EmpStatus'];
            
            }
            
        }
        
    }
    
    //print_r($ins_qry);die;
    foreach($ins_qry as $key=>$data)
    {
        $flag = true;
        $exist_id = mysqli_query($con,"select * from continuously_leave where EmpCode ='$key'  and from_date='{$data['from_date']}' and to_date='{$data['end_date']}'");
        $exist_data = mysqli_fetch_assoc($exist_id);

        $exist_from = mysqli_query($con,"select * from continuously_leave where EmpCode ='$key'  and from_date='{$data['from_date']}'");
        $exist_from_data = mysqli_fetch_assoc($exist_from);
        
        if(!empty($exist_data))
        {
            $flag = false;

        }
        // if(!empty($exist_from_data))
        // {
        //     mysqli_query($con,"update continuously_leave1 SET to_date='{$data['end_date']}' WHERE EmpCode ='$key' and from_date='{$data['from_date']}'");
        //     $flag = false;

        // }
        if($flag)
        {
            $qry2 = "INSERT INTO continuously_leave (EmpCode,EmpName,BranchName,CostCenter,EmpStatus,created_at,from_date,to_date) values('$key','{$data['EmpName']}','{$data['BranchName']}','{$data['CostCenter']}','{$data['EmpStatus']}',now(),'{$data['from_date']}','{$data['end_date']}')";
            $save = mysqli_query($con,$qry2) or die(mysqli_error($con));
        }


    }

    
}
