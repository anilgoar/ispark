<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('AppController', 'Controller');

class RideIsparksController extends AppController {

    //public $uses = array('PageRide');
    public $uses = array('PageMaster', 'PageRide');

    public function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow('add','save', 'view', 'assign_Access', 'view_access', 'edit_User', 'add_date', 'getMessage', 'getMessageDisplay', 'updateMessage', 'user_array', 'puser_array', 'change_password');
        $this->Auth->allow('save', 'index');
        $pages = explode(',', $this->Session->read("page_access"));
        if (in_array('40', $pages)) {
            $this->Auth->allow('view_users', 'edit_users');
        }
        if (in_array('8', $pages)) {
            $this->Auth->allow('manage_access', 'view_access');
        }
        if (in_array('15', $pages)) {
            $this->Auth->allow('create_User');
        }
    }

    public function index() {
        $id = $this->params['url']['user'];
        $rides = $this->PageMaster->query("SELECT * FROM pages_ride_ispark WHERE user_name='" . $id . "'");
        $this->set('rides', $rides);
        $this->layout = null;
        //$this->layout = 'home';        
    }

    public function save() {
        $ride = $this->params['url']['rides'];
        $username = $this->params['url']['user'];

        $ch = explode(",", $ride);
        $q1 = "SELECT id,parent_id from pages_master_ispark WHERE ";
        foreach ($ch as $ot) {
            $q1.="id='$ot' OR ";
        }
        $q1 = substr($q1, 0, -4);

        $dd = $this->PageMaster->query($q1);

        $p = array();
        //$ch = array();
        $child = "";
        foreach ($dd as $row) {
            if ($row['pages_master_ispark']['parent_id'] > 0) {
                //$p.=$row['pages_master_ispark']['parent_id'].",";
                array_push($p, $row['pages_master_ispark']['parent_id']);
                $child.=$row['pages_master_ispark']['id'] . ",";
                //array_push($ch,$row['pages_master_ispark']['id']);
            } else {
                //$p.=$row['pages_master_ispark']['id'].",";
                array_push($p, $row['pages_master_ispark']['id']);
            }
        }
		
        $pp = implode(",", array_unique($p));

        /* $check1 = "SELECT parent_id from pages_master_ispark WHERE ";
          foreach($ch as $row){
          $check1.="id='$row' OR ";
          }
          $check1 = substr($check1,0,-4);
          //echo $check1." qqqqqqq"; die();
          $obj = $this->PageMaster->query($check1);
          $arr_check = false;
          foreach($obj as $obj_e){
          if(in_array($obj_e['pages_master_ispark']['parent_id'], $ch)){
          $arr_check = true;
          }else{
          $arr_check = false;
          }
          }

          if($arr_check){

          }else{

          }
          echo json_encode($dd); die(); */

        $check = $this->PageMaster->query("select id from pages_ride_ispark WHERE user_name='" . $username . "'");
        if ($check[0]['pages_ride_ispark']['id'] == "") {
            $rides = $this->PageMaster->query("INSERT INTO pages_ride_ispark set user_name='" . $username . "', access='" . $child . "',parent_access='" . $pp . "'");
        } else {
            $rides = $this->PageMaster->query("Update pages_ride_ispark set access='" . $child . "',parent_access='" . $pp . "' WHERE user_name='" . $username . "'");
        }


        /* $email_id = $this->Session->read('email');
          $obj = $this->PageMaster->query("SELECT access,parent_access FROM pages_ride_ispark WHERE user_name='$email_id'");

          $arr = explode(",",$obj[0]['pages_ride_ispark']['access']);

          $query ="SELECT id,page_name,page_url FROM pages_master_ispark WHERE (";
          $q_as = ") AND parent_id='0'";
          foreach($arr as $ot){
          $query.="id='$ot' OR ";
          }
          $query = substr($query,0,-4);

          //$dd = $this->PageMaster->query("SELECT * FROM pages_master_ispark WHERE parent_id='0'");
          //$this->set('dd',$dd);

          $dd = $this->PageMaster->query($query.") AND parent_id='0'");
          //$this->set('dd',$dd);;
          $this->Session->write("dd",$dd); */
        
        $this->set('response', "save");
        $this->layout = null;
    }

}
?>

