<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function make_button_request($from,$footer,$text,$buttons)
{
    #$header_text = array('type'=>'text','body'=>array('text'=>$text));
    $body = array('text'=>$text);
    #$footer = array('text'=>$footer);
    
    $interactive_product = array('type'=>'button','body'=>$body,'action'=>$buttons);
    $interactive_req = array('recipient_type'=>'individual','to'=>$from,'type'=>'interactive','interactive'=>$interactive_product);
    $request_json = json_encode($interactive_req);
    return $request_json;
}

// $con = mysql_connect("localhost",'root','321*#LDtr!?*ktasb');
// $db = mysql_select_db("db_bill", $con);

$con = mysqli_connect("localhost","root","321*#LDtr!?*ktasb",'db_bill') or die("can not conncect");
$dialdee = mysqli_connect("192.168.10.23","root","Mas@1234",'db_dialdesk') or die("can not connect to dialdee");



//$qry ="select * from Attandence where MONTH(AttandDate) = MONTH(CURRENT_DATE())";
$qry ="SELECT * FROM `business_tickets` WHERE ticket_status ='0' and feedback_status='1'";
$ticket_rsc = mysqli_query($con,$qry);
#print($ticket_rsc);exit;
  
    while($ticket_det=mysqli_fetch_assoc($ticket_rsc))
    {

        $ticket_id = $ticket_det['id'];
        $chat_id = $ticket_det['chat_id'];
        
        $select_chat_det = "select * from chat_customer where id='$chat_id' limit 1";
        $chat_rsc = mysqli_query($dialdee,$select_chat_det);
        $chat_det = mysqli_fetch_assoc($chat_rsc);
        #print($chat_det);exit;
        $from = $chat_det['customer_no'];
        $api_key = $chat_det['api_key'];
        
        $label_msg ="Please provide feedback if your issue is resolved?";
        $feed_id_yes = "feed@$chat_id@$ticket_id@yes";
        $feed_id_no = "feed@$chat_id@$ticket_id@no";
        $button_yes = array('reply'=>array('id'=>$feed_id_yes,'title'=>'Yes'),'type'=>'reply');
        $button_no = array('reply'=>array('id'=>$feed_id_no,'title'=>'No'),'type'=>'reply');
        $button_reply = array($button_yes,$button_no);
        $buttons = array('buttons'=>$button_reply);
        #print($buttons);
        $data = make_button_request($from,$footer,$label_msg,$buttons);
        print_r($data);
        echo '<br/><br/>';
        
        $upd = "update business_tickets set feedback_status='0' where id='$ticket_id' limit 1";
        //echo $upd;
        $rsc_upd = mysqli_query($con,$upd);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://backend.aisensy.com/direct-apis/t1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization:Bearer $api_key"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        $err = curl_error($curl);    
        print_r($response);
        
        $resp = json_decode($response,true);
        $messages = $resp['messages'];
        $msg_id = "";$message_status = "";
        foreach($messages as $msg)
        {
            $msg_id = $msg['id'];
            $message_status = $msg['message_status'];
            if(empty($msg['id']))
            {
               $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://backend.aisensy.com/direct-apis/t1/messages',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                                "to": "'.$from.'.",
                                "type": "template",
                                "template": {
                                                "namespace": "0cd122c1_f653_48b5_bc4d_749dbe738366",
                                                "language": {
                                                    "policy": "deterministic",
                                                    "code": "en"
                                                },
                                                 "name": "feedback_response",
                                  "components": [
                                                        {
                                                            "type" : "button","sub_type" : "url","index": "0",
                                                            "parameters": [
                                                                {"type":"text","text":"'.$ticket_id.'"}
                                                                ] 
                                                        },
                                                        {
                                                        "type" : "button","sub_type" : "url","index": "1",
                                                        "parameters": [
                                                            {"type":"text","text":"'.$ticket_id.'"}
                                                                ] 
                                                        }
                                                 ]
                                                }
                              }
                    ',
                      CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer $api_key",
                        'Content-Type: application/json'
                      ),
                    ));

                    $response = curl_exec($curl);
                    
                    curl_close($curl);
                    echo $response; 
                    $resp = json_decode($response,true);
                    $messages = $resp['messages'];
                    $msg_id = "";$message_status = "";
                    foreach($messages as $msg)
                    {
                        $msg_id = $msg['id'];
                        $message_status = $msg['message_status'];
                    }
            }
        }
        $upd2 = "update business_tickets set feedback_msg_id='$msg_id',message_status='$message_status',feedback_msg_sent=now() where id='$ticket_id' limit 1";
        $rsc_upd = mysqli_query($con,$upd2);
    }




