<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class CommonData{
    public $uses=array('PageMaster','User','Access','Pages');
    public function getMenu(){
        //$email_id = $this->Session->read('email');
        //echo $email_id." wwwwwwww";
        //$obj = $this->PageMaster->query("SELECT access FROM pages_ride WHERE user_name='$email_id'");
        
        /*$arr = explode(",",$obj[0]['pages_ride']['access']);
        
        $query ="SELECT id,page_name,page_url FROM pages_master WHERE (";
        $q_as = ") AND parent_id='0'";
        foreach($arr as $ot){
            $query.="id='$ot' OR ";
        }
        $query = substr($query,0,-4);        
        //echo $query.") AND parent_id='0'";
        $dd = $this->PageMaster->query("SELECT * FROM pages_master WHERE parent_id='0'");
        *///$this->set('dd',$dd);
        //$this->Session->write("dd",$dd);
        //$dd = $this->PageMaster->query($query.") AND parent_id='0'");
        //$this->set('dd',$dd);
        return "";
    }
}

