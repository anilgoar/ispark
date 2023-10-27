<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not connect");
$Db = mysqli_select_db($con,"db_bill");

$first_day_this_month = date('01-m-Y'); 
$last_day_this_month  = date('t-m-Y');

// <!-------------------- Noida ho -----------------!>

$curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.himadievs.com/status-report/json/81/85",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $responsenew = mysqli_real_escape_string($con,$response);
  $JsonDe = json_decode($response,true);

$rec_arr = array();

 foreach($JsonDe as $checkouts)
 {

 	foreach($checkouts as $record)
 	{
      
        $rec = array();
        
        $rec['name'] = $record['name'];
        $rec['email'] = $record['email'];
        $rec['mobile'] = $record['mobile'];
        $rec['aadhar_no'] = $record['aadhar no'];
        $rec['color'] = $record['color'];

        $rec_arr[] = $rec;
 	}
 }

    foreach($rec_arr as $v)
    {
        $flag =false;

        if(!empty($v['aadhar_no']) && !empty($v['color']))
        {
            //$flag = true;
            $exist_from = mysqli_query($con,"select * from bgv_api where aadhar_no ='{$v['aadhar_no']}'");
            $exist_from_data = mysqli_fetch_assoc($exist_from);
            if(empty($exist_from_data))
            {
                $flag = true;
            }
        }

        if($flag)
        {
            $Insert = "insert into bgv_api set name='{$v['name']}', email='{$v['email']}', mobile='{$v['mobile']}', color='{$v['color']}', aadhar_no='{$v['aadhar_no']}', created_at=now()";
            $save = mysqli_query($con,$Insert) or die(mysqli_error($con));
        }
    }


}



/* <!-------------------- Noida-Trapezoid -----------------!> */

$curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.himadievs.com/status-report/json/81/86",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $responsenew = mysqli_real_escape_string($con,$response);
  $JsonDe = json_decode($response,true);
// echo "<pre>";
//  print_r($JsonDe);
// echo "</pre>";
// die;
$rec_arr = array();

 foreach($JsonDe as $checkouts)
 {

 	foreach($checkouts as $record)
 	{
      
        $rec = array();
        
        $rec['name'] = $record['name'];
        $rec['email'] = $record['email'];
        $rec['mobile'] = $record['mobile'];
        $rec['aadhar_no'] = $record['aadhar no'];
        $rec['color'] = $record['color'];

        $rec_arr[] = $rec;
 	}
 }

    foreach($rec_arr as $v)
    {
        $flag =false;
        if(!empty($v['aadhar_no']) && !empty($v['color']))
        {
            $exist_from = mysqli_query($con,"select * from bgv_api where aadhar_no ='{$v['aadhar_no']}'");
            $exist_from_data = mysqli_fetch_assoc($exist_from);
            if(empty($exist_from_data))
            {
                $flag = true;
            }
        }

        if($flag)
        {
            $Insert = "insert into bgv_api set name='{$v['name']}', email='{$v['email']}', mobile='{$v['mobile']}', color='{$v['color']}', aadhar_no='{$v['aadhar_no']}', created_at=now()";
            $save = mysqli_query($con,$Insert) or die(mysqli_error($con));
        }
    }


}


// <!-------------------- Ahmedabad JD -----------------!>

$curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.himadievs.com/status-report/json/81/87",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $responsenew = mysqli_real_escape_string($con,$response);
  $JsonDe = json_decode($response,true);

$rec_arr = array();

 foreach($JsonDe as $checkouts)
 {

 	foreach($checkouts as $record)
 	{
      
        $rec = array();
        
        $rec['name'] = $record['name'];
        $rec['email'] = $record['email'];
        $rec['mobile'] = $record['mobile'];
        $rec['aadhar_no'] = $record['aadhar no'];
        $rec['color'] = $record['color'];

        $rec_arr[] = $rec;
 	}
 }

    foreach($rec_arr as $v)
    {

        $flag =false;
        if(!empty($v['aadhar_no']) && !empty($v['color']))
        {
            $exist_from = mysqli_query($con,"select * from bgv_api where aadhar_no ='{$v['aadhar_no']}'");
            $exist_from_data = mysqli_fetch_assoc($exist_from);
            if(empty($exist_from_data))
            {
                $flag = true;
            }
        }

        if($flag)
        {
            $Insert = "insert into bgv_api set name='{$v['name']}', email='{$v['email']}', mobile='{$v['mobile']}', color='{$v['color']}', aadhar_no='{$v['aadhar_no']}', created_at=now()";
            $save = mysqli_query($con,$Insert) or die(mysqli_error($con));
        }


    }


}


// <!-------------------- Ahmedabad NK -----------------!>

$curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.himadievs.com/status-report/json/81/88",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $responsenew = mysqli_real_escape_string($con,$response);
  $JsonDe = json_decode($response,true);

$rec_arr = array();

 foreach($JsonDe as $checkouts)
 {

 	foreach($checkouts as $record)
 	{
      
        $rec = array();
        
        $rec['name'] = $record['name'];
        $rec['email'] = $record['email'];
        $rec['mobile'] = $record['mobile'];
        $rec['aadhar_no'] = $record['aadhar no'];
        $rec['color'] = $record['color'];

        $rec_arr[] = $rec;
 	}
 }

    foreach($rec_arr as $v)
    {
        $flag =false;
        if(!empty($v['aadhar_no']) && !empty($v['color']))
        {
            $exist_from = mysqli_query($con,"select * from bgv_api where aadhar_no ='{$v['aadhar_no']}'");
            $exist_from_data = mysqli_fetch_assoc($exist_from);
            if(empty($exist_from_data))
            {
                $flag = true;
            }
        }

        if($flag)
        {
            $Insert = "insert into bgv_api set name='{$v['name']}', email='{$v['email']}', mobile='{$v['mobile']}', color='{$v['color']}', aadhar_no='{$v['aadhar_no']}', created_at=now()";
            $save = mysqli_query($con,$Insert) or die(mysqli_error($con));
        }

    }


}


?>

