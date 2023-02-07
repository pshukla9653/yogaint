<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CommanModel extends CI_Model
{ 
	

     function __construct(){
     
          parent::__construct();
		  
     }

	 public function InsertData($tablename,$data){
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
	}

	public function getDatacount($field){
		$this->db->select('*');
		$this->db->from('tbl_enquiry');
		$this->db->where($field);	
		$query = $this->db->get();
		return $query->num_rows();
	}
	
}
?>
