<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	
	 
	public function index()
	{ 
		
		$interest_ids =  array(
			1 => 'Krishnamacharya Yoga Mandiram',
			2 => 'Isha Foundation',
			3 => 'Ramamani Iyengar Memorial Yoga Institute',
			4 => 'Kaivalyadhama Yoga Institute',
			5 => 'The Art of Living',
		  
		  );
		  
		  $dates_ids = array(
			1 => '1 march 2023',
			2 => '2 march 2023',
			3 => '3 march 2023',
			4 => '4 march 2023',
			5 => '5 march 2023',
			6 => '6 march 2023',
			7 => '7 march 2023',
		  
		  );
		 $total = 80; 

		$var = $_POST['interest_id'];

		$key = array_keys($interest_ids, $var)[0];

		$data['date1'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date1'=>1));
		$data['date2'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date2'=>1));
		$data['date3'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date3'=>1));
		$data['date4'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date4'=>1));
		$data['date5'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date5'=>1));
		$data['date6'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date6'=>1));
		$data['date7'] = $this->CommanModel->getDatacount(array('interest_id'=>$key,'date7'=>1));

		$row['date1'] = ($total - $data['date1']) < 0 ? 0 : ($total - $data['date1']);
		$row['date2'] = ($total - $data['date2']) < 0 ? 0 : ($total - $data['date2']);
		$row['date3'] = ($total - $data['date3']) < 0 ? 0 : ($total - $data['date3']);
		$row['date4'] = ($total - $data['date4']) < 0 ? 0 : ($total - $data['date4']);
		$row['date5'] = ($total - $data['date5']) < 0 ? 0 : ($total - $data['date5']);
		$row['date6'] = ($total - $data['date6']) < 0 ? 0 : ($total - $data['date6']);
		$row['date7'] = ($total - $data['date7']) < 0 ? 0 : ($total - $data['date7']);
		
		header('Content-Type: application/json');
		echo json_encode($row);
		die;
	}

	function sendEnqery(){
		
		$interest_ids =  array(
			1 => 'Krishnamacharya Yoga Mandiram',
			2 => 'Isha Foundation',
			3 => 'Ramamani Iyengar Memorial Yoga Institute',
			4 => 'Kaivalyadhama Yoga Institute',
			5 => 'The Art of Living',
		  
		  );
		  
		  $dates_ids = array(
			1 => '1 march 2023',
			2 => '2 march 2023',
			3 => '3 march 2023',
			4 => '4 march 2023',
			5 => '5 march 2023',
			6 => '6 march 2023',
			7 => '7 march 2023',
		  
		  );
		$data = $_POST;
		  
		  $dates = $_POST['date'];
		
		unset($data['action']);	
		$data['interest_id'] = array_keys($interest_ids, $data['interest'])[0];
		foreach($dates_ids as $key=>$value){
			foreach($data['date'] as $k=>$v){
				if($value == $v){
					$data['date'.$key] = 1;
				}
			}
		}
		$data['date'] ='';
		foreach ($dates as $arr => $dv){ 
			$data['date'] .= "<li>".$dv."</li>";
			
		  }
	  
		
			$data['interest'] = "<li>".$data['interest']."</li>";
		  
		$insert = $this->CommanModel->InsertData('tbl_enquiry', $data);
		if($insert){
			echo "<script>window.location = 'http://localhost/yogaint/thanks.php';</script>";
		}
	}
}
