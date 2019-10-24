<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questionnaire extends MX_Controller {

	public $data = array();
	
	 function __construct(){
		parent::__construct();
		$this->checkLogin();
        $this->load->helper('url');
         $this->load->library('session');
		//$this->load->library('MdsCore');
	 }

	public function index($pre_page=NULL)
	{
		$this->loadMDSPager('qu_questionnaire'); 
		//$this->loadMDSPager('qu_questionnaire'); 
	}
	
	public function search ($mode=null,$clinic="null",$is_soap=0,$is_general=0,$gender="Both",$srch = null){
	
		$data["mode"] = $mode;
		$this->load->model("mquestionnaire");
		$this->load->helper('text');
		$data["questionnaire_list"] = $this->mquestionnaire->get_questionnaire_search($mode,$clinic,$is_soap,$is_general,$gender,$srch);
		$this->load->vars($data);
		$this->load->view('questionnaire_search_view');
	}

	public function get_notes_answer_list($epicode_id,$visit_typ,$visit){
		$this->load->model("mquestionnaire");
		$data["clinic_previous_record_list"] = $this->mquestionnaire->get_notes_list($epicode_id,$visit_typ);
		//print_r($data["clinic_previous_record_list"]);
		if ($visit_typ == 'clinic_visits'){
			$data["status"] = $visit['status'];
			$data["continue"] = 'clinic/visit_view/'.$visit["clinic_visits_id"];
			$data["id"] = 'clinic_visits_id';
		}
		else if($visit_typ == 'admission'){
		 if (isset($visit["OutCome"])&&($visit["OutCome"])){
				$data["status"] = $visit["OutCome"];
			}
			$data["continue"] = 'admission/view/'.$visit["ADMID"];
			$data["id"] = 'ADMID';
		}
		$data["visit"] = $visit;
		$data["epicode_id"] = $epicode_id;
		if (!empty($data["clinic_previous_record_list"])){
			for($i=0;$i<count($data["clinic_previous_record_list"]);++$i){
				$data["clinic_previous_record_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["clinic_previous_record_list"][$i]["qu_quest_answer_id"]);
				if (!empty($data["clinic_previous_record_list"][$i]["data"])){
					for($j=0;$j<count($data["clinic_previous_record_list"][$i]["data"]);++$j){
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "Select"){ //answer type select
							$ans = $this->mpersistent->open_id($data["clinic_previous_record_list"][$i]["data"][$j]["answer"],"qu_select", "qu_select_id");
							if (isset($ans["select_text"])){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
								$data["clinic_previous_record_list"][$i]["data"][$j]["select_default"] = $ans["select_default"];
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "MultiSelect"){ //answer type multi-select
							$user_answeres = explode(",", $data["clinic_previous_record_list"][$i]["data"][$j]["answer"]);
							
							$output_answer = '';
							for ($ua=0; $ua < count($user_answeres); ++$ua){
								if ($user_answeres[$ua] >0){
									$ans = $this->mpersistent->open_id($user_answeres[$ua],"qu_select", "qu_select_id");
									$output_answer .=$ans["select_text"].', ';
								}
							}
							if (isset($output_answer)){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] =$output_answer;
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM"){
							$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["clinic_previous_record_list"][$i]["data"][$j]["qu_question_id"]);
							if (!empty($data['pain_diagram_info'])){ 
								//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
								$data['diagram'.$data['clinic_previous_record_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
							}
						}
					}
				}
			}
		}
		$this->load->vars($data);
		$this->load->view('questionnaire_notes_answer_list_view');	
	}	
	public function get_SOAP_answer_list($epicode_id,$visit_typ,$visit){
		$this->load->model("mquestionnaire");
		$data["clinic_previous_record_list"] = $this->mquestionnaire->get_previous_record_list($epicode_id,$visit_typ);
		//print_r($data["clinic_previous_record_list"]);
		if ($visit_typ == 'clinic_visits'){
			$data["status"] = $visit['status'];
			$data["continue"] = 'clinic/visit_view/'.$visit["clinic_visits_id"];
			$data["id"] = 'clinic_visits_id';
		}
		else if($visit_typ == 'admission'){
		 if (isset($visit["OutCome"])&&($visit["OutCome"])){
				$data["status"] = $visit["OutCome"];
			}
			$data["continue"] = 'admission/view/'.$visit["ADMID"];
			$data["id"] = 'ADMID';
		}
		$data["visit"] = $visit;
		$data["epicode_id"] = $epicode_id;
		if (!empty($data["clinic_previous_record_list"])){
			for($i=0;$i<count($data["clinic_previous_record_list"]);++$i){
				$data["clinic_previous_record_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["clinic_previous_record_list"][$i]["qu_quest_answer_id"]);
				if (!empty($data["clinic_previous_record_list"][$i]["data"])){
					for($j=0;$j<count($data["clinic_previous_record_list"][$i]["data"]);++$j){
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "Select"){ //answer type select
							$ans = $this->mpersistent->open_id($data["clinic_previous_record_list"][$i]["data"][$j]["answer"],"qu_select", "qu_select_id");
							if (isset($ans["select_text"])){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
								$data["clinic_previous_record_list"][$i]["data"][$j]["select_default"] = $ans["select_default"];
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "MultiSelect"){ //answer type multi-select
							$user_answeres = explode(",", $data["clinic_previous_record_list"][$i]["data"][$j]["answer"]);
							
							$output_answer = '';
							for ($ua=0; $ua < count($user_answeres); ++$ua){
								if ($user_answeres[$ua] >0){
									$ans = $this->mpersistent->open_id($user_answeres[$ua],"qu_select", "qu_select_id");
									$output_answer .=$ans["select_text"].', ';
								}
							}
							if (isset($output_answer)){
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] =$output_answer;
							}
							else {
								$data["clinic_previous_record_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						
						if ($data["clinic_previous_record_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM"){
							$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["clinic_previous_record_list"][$i]["data"][$j]["qu_question_id"]);
							if (!empty($data['pain_diagram_info'])){ 
								//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
								$data['diagram'.$data['clinic_previous_record_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
							}
						}
					}
				}
			}
		}
		$this->load->vars($data);
		$this->load->view('questionnaire_soap_answer_list_view');	
	}
	
	
	public function get_answer_list($pid,$visit_typ,$visit){
		$this->load->model("mquestionnaire");
		$data["patient_questionnaire_answer_list"] = $this->mquestionnaire->get_answer_list($pid,"patient");
		$data["status"] =null;
		if ($visit_typ == 'clinic'){
			$data["status"] = $visit['status'];
			$data["continue"] = 'clinic/visit_view/'.$visit["clinic_visits_id"];
			$data["id"] = 'clinic_visits_id';
		}
		else if($visit_typ == 'admission'){
		 if (isset($visit["OutCome"])&&($visit["OutCome"])){
				$data["status"] = $visit["OutCome"];
			}
			$data["continue"] = 'admission/view/'.$visit["ADMID"];
			$data["id"] = 'ADMID';
		}
		$data["visit"] = $visit;
		$data["pid"] = $pid;
		if (!empty($data["patient_questionnaire_answer_list"])){
			for($i=0;$i<count($data["patient_questionnaire_answer_list"]);++$i){
				$data["patient_questionnaire_answer_list"][$i]["data"] = $this->mquestionnaire->get_clinic_patient_answer_list($data["patient_questionnaire_answer_list"][$i]["qu_quest_answer_id"]);
				if (!empty($data["patient_questionnaire_answer_list"][$i]["data"])){
					for($j=0;$j<count($data["patient_questionnaire_answer_list"][$i]["data"]);++$j){
						if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "Select"){ //answer type select
							$ans = $this->mpersistent->open_id($data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"],"qu_select", "qu_select_id");
							if (isset($ans["select_text"])){
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = $ans["select_text"];
							}
							else {
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "MultiSelect"){ //answer type multi-select
							$user_answeres = explode(",", $data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"]);
							
							$output_answer = '';
							for ($ua=0; $ua < count($user_answeres); ++$ua){
								if ($user_answeres[$ua] >0){
									$ans = $this->mpersistent->open_id($user_answeres[$ua],"qu_select", "qu_select_id");
									$output_answer .=$ans["select_text"].', ';
								}
							}
							if (isset($output_answer)){
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] =$output_answer;
							}
							else {
								$data["patient_questionnaire_answer_list"][$i]["data"][$j]["answer"] = '';
							}
						}
						
						if ($data["patient_questionnaire_answer_list"][$i]["data"][$j]["question_type"] == "PAIN_DIAGRAM"){
							$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data["patient_questionnaire_answer_list"][$i]["data"][$j]["qu_question_id"]);
							if (!empty($data['pain_diagram_info'])){ 
								//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
								$data['diagram'.$data['patient_questionnaire_answer_list'][$i]["data"][$j]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
							}
						}
					}
				}
			}
		}
		$this->load->vars($data);
		$this->load->view('questionnaire_answer_list_view');	
	}
	public function load($q_id,$pid,$link_type=null,$link_id=null){
		
		if (!Modules::run('security/check_view_access','questionnaire','can_create')){
			$data["error"] =" User group '".$this->session->userdata('UserGroup')."' have no rights to view this data";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}		
		if (!$q_id){
			$data["error"] =" Questionnaire not valid";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		if (!$pid){
			$data["error"] =" Patient ID not  valid";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		$data = array();
		$this->load->database();
		$this->load->model("mquestionnaire");
        $data['questionnaire_info'] = $this->mquestionnaire->get_questionnaire_info($q_id);
		
		if($data['questionnaire_info']["soap_type"] != "F.Notes"){ //if its a NOTES create a new each time
           $quest_ans_info = $this->mquestionnaire->get_opened_questionnaite($q_id,$pid,$link_type,$link_id);
        }
        else {
             $quest_ans_info = array();
        }
        if (!empty($quest_ans_info )){
			header("Status: 200");
			header("Location: ".site_url("questionnaire/edit/".$q_id.'/'.$pid.'/'.$link_type.'/'.$link_id.'/'.$quest_ans_info["qu_quest_answer_id"].'?CONTINUE='.$_GET["CONTINUE"])); 
			return;
		}
		$this->load->model('mpersistent');
		$this->load->library('form_validation');
		
		if (empty($data['questionnaire_info'])){ 
			$data['error'] = "Questionnaire not found";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			return;
		}
		$data['question_list'] = $this->mquestionnaire->get_question_list($q_id);
		if(isset($data['question_list']) && count($data['question_list'])){
			for($i=0; $i < count($data['question_list']); ++$i){
				if ($data['question_list'][$i]['question_type'] == "Select"){
					$data['select'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] == "MultiSelect"){
					$data['mselect'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] == "PAIN_DIAGRAM"){
					$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data['question_list'][$i]['qu_question_repos_id']);
					if (!empty($data['pain_diagram_info'])){ 
						//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						$data['diagram'.$data['question_list'][$i]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
					}
				}
			}
		}

        $data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
		if (empty($data["patient_info"])){
			$data["error"] ="Patient not found";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		
		$data["mode"] = "RUN";
		$data["link_type"] = $link_type;
		$data["link_id"] = $link_id;
		$data["CONTINUE"] = null;
		if (isset($_GET["CONTINUE"])){
			$data["CONTINUE"] = $_GET["CONTINUE"];
		}
		$this->load->vars($data);
		$this->load->view('questionnaire_view');		
	}
	
	public function edit($q_id,$pid,$link_type=null,$link_id=null,$quest_answer=null){
		if (!Modules::run('security/check_view_access','questionnaire','can_create')){
			$data["error"] =" User group '".$this->session->userdata('UserGroup')."' have no rights to view this data";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}		
		if (!$q_id){
			$data["error"] =" Questionnaire not valid";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		if (!$pid){
			$data["error"] =" Patient ID not  valid";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		if (!$quest_answer){
			$data["error"] =" quest_answer not  valid";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		$data = array();
		$this->load->database();
		$this->load->model("mquestionnaire");
		$this->load->model('mpersistent');
		$this->load->library('form_validation');
		$data['questionnaire_info'] = $this->mquestionnaire->get_questionnaire_info($q_id);
		$data['quest_answer_id'] = $quest_answer;
		$data['answer_info'] = $this->mquestionnaire->get_clinic_patient_answer_list($quest_answer);
		if (empty($data['questionnaire_info'])){ 
			$data['error'] = "Questionnaire not found";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			return;
		}
		$data['question_list'] = $this->mquestionnaire->get_question_list($q_id);
		if(isset($data['question_list']) && count($data['question_list'])){
			for($i=0; $i < count($data['question_list']); ++$i){
				if ($data['question_list'][$i]['question_type'] == "Select"){
					$data['select'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] == "MultiSelect"){
					$data['mselect'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] == "PAIN_DIAGRAM"){
					$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data['question_list'][$i]['qu_question_repos_id']);
					if (!empty($data['pain_diagram_info'])){ 
						//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						$data['diagram'.$data['question_list'][$i]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
					}
				}
			}
		}

        $data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
		if (empty($data["patient_info"])){
			$data["error"] ="Patient not found";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			exit;
		}
		
		$data["mode"] = "RUN";
		$data["link_type"] = $link_type;
		$data["link_id"] = $link_id;
		$data["quest_answer_id"] = $quest_answer;
		$data["CONTINUE"] = null;
		if (isset($_GET["CONTINUE"])){
			$data["CONTINUE"] = $_GET["CONTINUE"];
		}
		$this->load->vars($data);
		$this->load->view('questionnaire_view');		
	}
	
	public function move_question(){
		$qid  = $_GET["qid"]; 
		$pos= $_GET["pos"];
		if (!$qid || !$pos){
			echo -1;
			return;
		}
		$this->load->database();
		$this->load->model("mpersistent");
		//update($table=null,$key_field=null,$id=null,$data)
		echo $this->mpersistent->update("qu_question","qu_question_id",$qid,array("show_order"=>$pos));

	}	
	public function update_order(){
		$qid  = $_GET["qid"]; 
		$pos= $_GET["pos"];
		if (!$qid || !$pos){
			echo -1;
			return;
		}
		$this->load->database();
		$this->load->model("mpersistent");
		//update($table=null,$key_field=null,$id=null,$data)
		echo $this->mpersistent->update("qu_question","qu_question_id",$qid,array("show_order"=>$pos));

	}
	public function add_question(){
		$quest_id = $_GET["quest_id"]; 
		$qid= $_GET["qid"];
		if (!$quest_id || !$qid){
			echo -1;
			return;
		}
		$this->load->database();
		$this->load->model("mpersistent");
		$this->load->model("mquestionnaire");
		
		$count = $this->count_all_question($quest_id);
		$question = $this->mquestionnaire->is_question_exsist($quest_id,$qid);
		if (!empty($question)){
			echo $this->mpersistent->update("qu_question","qu_question_id",$question["qu_question_id"],array("active"=>1));
		}
		else{
			echo $this->mpersistent->create("qu_question",array("qu_question_id"=>$this->get_unique_id(),"qu_questionnaire_id"=>$quest_id,"qu_question_repos_id"=>$qid,"active"=>"1","show_order"=>$count+1));
			}
	}
	private function get_unique_id(){
		$yyyy = substr(date("Y/m/d"),0,4);
		$mm = substr(date("Y/m/d"),5,2);
		$dd = substr(date("Y/m/d"),8,2);
		//echo $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,14);
		//echo $yyyy.$mm.$dd.time();
		//echo $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,8);
		//return $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,8);
		return substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,8);
	}	
	function is_question_exsist($quest_id,$qid){
		$this->load->database();
		$this->load->model("mquestionnaire");
		$count =  $this->mquestionnaire->count_question($quest_id, $qid);
		if ($count>0){
			return true;
		}
		return false;
	}
			
	function count_all_question($quest_id){
		$this->load->database();
		$this->load->model("mquestionnaire");
		return $this->mquestionnaire->count_all_question($quest_id);
	}
	public function remove($quest_id){
		if(!is_numeric($quest_id))
			return;
		$this->load->model("mpersistent");	
		$s = $this->mpersistent->update("qu_question","qu_question_id",$quest_id,array("active"=>0));
		if ($s>0){
			header("Status: 200");
			header("Location: ".site_url($_GET["CONTINUE"])); 
		}
	}
	public function qprint($id=null){
		$this->open($id,true);
	}
	public function open($id=null,$is_print=false){
		$data = array();
		$this->load->database();
		$this->load->model("mquestionnaire");
		$this->load->model("mpersistent");
		$this->load->library('form_validation');
		$data['questionnaire_info'] = $this->mquestionnaire->get_questionnaire_info($id);
		if (empty($data['questionnaire_info'])){ 
			$data['error'] = "Questionnaire not found";
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			return;
		}
		if ($data["questionnaire_info"]['show_in_clinic']>0){
			$ops = explode("|", $data["questionnaire_info"]['show_in_clinic']);
			foreach ($ops as $key => $value) {
				$data['clinic_info'][] = $this->mpersistent->open_id($value,"clinic","clinic_id");
			}
		}
		if ($data["questionnaire_info"]['show_in_visit']>0){
			$data['visit_info'] = $this->mpersistent->open_id($data["questionnaire_info"]['show_in_visit'],"visit_type","VTYPID");
		}
		$data['question_list'] = $this->mquestionnaire->get_question_list($id);
		//print_r($data['question_list'] );
		if(isset($data['question_list']) && count($data['question_list'])){
			for($i=0; $i < count($data['question_list']); ++$i){
				if ($data['question_list'][$i]['question_type'] == "Select"){
					$data['select'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] == "MultiSelect"){
					$data['mselect'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] == "PAIN_DIAGRAM"){
					$data['pain_diagram_info'] = $this->mquestionnaire->get_diagram_info($data['question_list'][$i]['qu_question_repos_id']);
					if (!empty($data['pain_diagram_info'])){ 
						//$data['clinic_diagram_info'] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						$data['diagram'.$data['question_list'][$i]['qu_question_id']] = $this->mpersistent->open_id($data['pain_diagram_info']["cln_diagram_id"],"clinic_diagram","clinic_diagram_id");
						
					}
				}
			}
		}
		//        print_r($data);
		//exit;
		$data["mode"] = "VIEW";
		$this->load->vars($data);
		if ($is_print){
			$this->load->view('questionnaire_print_view');
		}
		else{
			$this->load->view('questionnaire_view');
		}
	}
	public function rec_delete ($quest_answer){
		$this->load->model("mquestionnaire");
		return $this->mquestionnaire->delete_question_answer($quest_answer);
	}
	
	public function delete ($quest_answer,$pid,$link_id){
		if (empty($data['questionnaire_info'])){ 
			$continue ='';
			if(isset($_GET["continue"])){
				$continue = $_GET["continue"];
			}
			$text = '&nbsp;&nbsp;Do you really want to delete this entry?<br><br>';
			$text .= '<a class="btn btn-danger" href="'.site_url("questionnaire/delete_confirm/".$quest_answer.'/'.$pid.'/'.$link_id.'?continue='.$continue).'">Delete</a>&nbsp;&nbsp;';
			$text .= '<button onclick="window.history.back();" class="btn btn-default">Return to the record</button>&nbsp;&nbsp;';
			$data['error'] = $text;
			$this->load->vars($data);
			$this->load->view('questionnaire_error');
			return;
		}
	}	
	public function delete_confirm ($quest_answer,$pid,$link_id){

		$this->load->model("mquestionnaire");
		if ($this->rec_delete($quest_answer)){
				$this->session->set_flashdata('msg', 'REC: ' . 'Questionnaire removed');
				$continue =null;
				if(isset($_GET["continue"])){
					$continue = $_GET["continue"];
				}
				header("Status: 200");
				if ($continue){
				header("Location: ".site_url($continue)); 
				}
				else{
					header("Location: ".site_url('clinic/visit_view/'.$link_id.'/'.$pid)); 
				}
				return;
		}
	}
	public function save(){
		$this->load->database();
		$this->load->model("mquestionnaire");
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
		if ($_POST["qu_questionnaire_id"]){
			$data['question_list'] = $this->mquestionnaire->get_question_list($_POST["qu_questionnaire_id"]);
		}
		if(isset($data['question_list']) && count($data['question_list'])){
			for($i=0; $i < count($data['question_list']); ++$i){
				if ($data['question_list'][$i]['question_type'] == "Select"){
						$data['select'.$data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_repos_id']);
				}
				if ($data['question_list'][$i]['question_type'] != "Header"){
					if ($data['question_list'][$i]['question_type'] == "Yes_No"){
						$this->form_validation->set_rules($data['question_list'][$i]["qu_question_repos_id"], '"'.$data['question_list'][$i]["question"].'"', "xss_clean");
					}
					elseif ($data['question_list'][$i]['question_type'] == "Text"){
						$this->form_validation->set_rules($data['question_list'][$i]["qu_question_repos_id"], '"'.$data['question_list'][$i]["question"].'"', "xss_clean");
					}
					else{
						$this->form_validation->set_rules($data['question_list'][$i]["qu_question_repos_id"], '"'.$data['question_list'][$i]["question"].'"', "xss_clean");
					}
				}
			}
		}	

		$this->form_validation->set_rules("CONTINUE", "CONTINUE", "xss_clean");
		$this->form_validation->set_rules("link_id", "link_id", "xss_clean|required");
		$this->form_validation->set_rules("link_type", "link_type", "xss_clean|required");
		if ($this->form_validation->run() == FALSE){
			if (isset($_POST['quest_answer_id'])&&($_POST['quest_answer_id']>0)){
				$this->load($_POST["qu_questionnaire_id"],$_POST["PID"],$_POST["link_type"],$_POST["link_id"],$_POST['quest_answer_id']);
			}
			else{
				$this->load($_POST["qu_questionnaire_id"],$_POST["PID"],$_POST["link_type"],$_POST["link_id"]);
			}
		}
		else{
			if (isset($_POST['quest_answer_id'])&&($_POST['quest_answer_id']>0)){
					$this->rec_delete($_POST['quest_answer_id']);
			}			
			$this->load->model("mpersistent");
			$data['questionnaire_info'] = $this->mquestionnaire->get_questionnaire_info($_POST["qu_questionnaire_id"]);
			$qu_quest_answer_id = $this->get_unique_id();
			$sve_data = array(
			"qu_quest_answer_id"=>$qu_quest_answer_id,
			"qu_questionnaire_id"=>$_POST["qu_questionnaire_id"],
			"active"=>"1"
			);
			$sve_data["link_type"] = $_POST["link_type"];
			$sve_data["link_id"] = $_POST["link_id"];
			$res  = $this->mpersistent->create("qu_quest_answer",$sve_data);
			
			$ans_data_array = array();	
			//print_r($data['question_list'])."<br>";
			//print_r($_POST);
			//exit;
			if(isset($data['question_list']) && count($data['question_list'])){
				for($i=0; $i < count($data['question_list']); ++$i){
					//if ($data['question_list'][$i]["question_type"] == "Header") continue;
					$ans = isset($_POST[$data['question_list'][$i]['qu_question_repos_id']])?$_POST[$data['question_list'][$i]['qu_question_repos_id']]:"";
					$ans_data = array(
					"qu_answer_id"=>$this->get_unique_id(),
					"qu_quest_answer_id"=>$qu_quest_answer_id,
					"qu_question_id"=>$data['question_list'][$i]['qu_question_repos_id'],
					"answer"=>isset($_POST[$data['question_list'][$i]['qu_question_repos_id']])?$_POST[$data['question_list'][$i]['qu_question_repos_id']]:"",
					"answer_type"=>$data['question_list'][$i]['question_type'],
					"answer_order"=>$i,
					"CreateDate"=>date("Y-m-d H:i:s"),
					"CreateUser"=>$this->session->userdata("FullName"),
					"active"=>"1"
					);
					if ($ans!="")array_push($ans_data_array,$ans_data );
				}
			}
			$status = $this->mpersistent->insert_batch("qu_answer",$ans_data_array);	
			
			if (!$status){
				$data['error'] = "Questionnaire  couldnt save";
				$this->load->vars($data);
				$this->load->view('questionnaire_error');
				return;
			}
			if (isset($_POST["CONTINUE"])){
				$this->session->set_flashdata('msg', 'REC: ' . 'Questionnaire saved');
				header("Status: 200");
				header("Location: ".site_url($_POST["CONTINUE"])); 
				return;
			}
			else{
				if ($data['questionnaire_info']["show_in_patient"] == 1){
					header("Status: 200");
					header("Location: ".site_url("patient/view/".$_POST["PID"])); 
					return;
				}
			}
		}
	}
        
        public function save_questionnaire(){
        
                //print_r($_POST);
        $frm = 'qu_questionnaire';
        if (!file_exists('application/forms/' . $frm . '.php')) {
            die("Form " . $frm . "  not found");
        }
        include 'application/forms/' . $frm . '.php';
        $data["form"] = $form;
        //print_r($data);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
        for ($i = 0; $i < count($form["FLD"]); ++$i) {
            $this->form_validation->set_rules(
                $form["FLD"][$i]["name"], '"' . $form["FLD"][$i]["label"] . '"', $form["FLD"][$i]["rules"]
            );
        }
        $this->form_validation->set_rules($form["OBJID"]);

        if ($this->form_validation->run() == FALSE) {
            $this->load->vars($data);
            echo Modules::run('form/create', 'qu_questionnaire');
        } else {
            
                       //echo $this->input->post("show_in_clinic");
                  $clinics = implode('|', $this->input->post("show_in_clinic"));
                  $clinics .= "|";
                  //echo $clinics;
                  //die();
            
            $sve_data = array(
                'name'        => $this->input->post("name"),
                'code'  => $this->input->post("code"),
                'description'    => $this->input->post("description"),
                'applicable_to'                => $this->input->post("applicable_to"),
                'show_in_patient' => $this->input->post("show_in_patient"),
                'show_in_admission'           => $this->input->post("show_in_admission"),
                'show_in_clinic'                   => $clinics,
                'soap_type'                => $this->input->post("soap_type"),
                'show_in_visit'             => $this->input->post("show_in_visit"),
                'active'             => $this->input->post("active")

                

            );
            $id = $this->input->post($form["OBJID"]);
            $status = false;
			
            if ($id > 0) {
                $status = $this->mpersistent->update($frm, $form["OBJID"], $id, $sve_data);
                $this->session->set_flashdata(
                    'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' Updated'
                );
				if ( $status){
					header("Status: 200");
					if (isset($_POST["CONTINUE"])){
						header("Location: ".site_url($_POST["CONTINUE"])); 
						return;
					}
					else{
						header("Location: ".site_url($form["NEXT"].'/'.$status));
						return;
					}
				}
            } else {
                
                $status = $this->mpersistent->create($frm, $sve_data);				
		$this->session->set_flashdata(
                    'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))).' created'
                );
				if ( $status>0){
					//echo Modules::run($form["NEXT"], $status);
					header("Status: 200");
					if (isset($_POST["CONTINUE"]) && $_POST["CONTINUE"]!=''){
						header("Location: ".site_url($_POST["CONTINUE"]));
						return;
					}
					else{
						header("Location: ".site_url($form["NEXT"].'/'.$status));
						return;
					}
				}
            }
            echo "ERROR in saving";
        }
            
            
        }
	
	public function add_option($qu_id){
		
	}
	
   private function loadMDSPager($fName) {
        $path='application/forms/' . $fName . '.php';
        require $path;
        $frm = $form;
        $columns = $frm["LIST"];
        $table = $frm["TABLE"];
        $sql = "SELECT ";

        foreach ($columns as $column) {
            $sql.=$column . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql.=" FROM $table ";
        $this->load->model('mpager');
        $this->mpager->setSql($sql);
        $this->mpager->setDivId('prefCont');
        $this->mpager->setSortorder('asc');
        //set colun headings
        $colNames = array();
        foreach ($frm["DISPLAY_LIST"] as $colName) {
            array_push($colNames, $colName);
        }
        $this->mpager->setColNames($colNames);

        //set captions
        $this->mpager->setCaption($frm["CAPTION"]);
        //set row id
        $this->mpager->setRowid($frm["ROW_ID"]);

        //set column models
        foreach ($frm["COLUMN_MODEL"] as $columnName => $model) {
            if (gettype($model) == "array") {
                $this->mpager->setColOption($columnName, $model);
            }
        }

        //set actions
        $action = $frm["ACTION"];
        $this->mpager->gridComplete_JS = "function() {
            var c = null;
            $('.jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'yellow','cursor':'pointer'});
            }).mouseout(function(e){
                $(this).css('background',c);
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='$action'+rowId;
            });
            }";

        //report starts
        if(isset($frm["ORIENT"])){
            $this->mpager->setOrientation_EL($frm["ORIENT"]);
        }
        if(isset($frm["TITLE"])){
            $this->mpager->setTitle_EL($frm["TITLE"]);
        }

//        $pager->setSave_EL($frm["SAVE"]);
        $this->mpager->setColHeaders_EL(isset($frm["COL_HEADERS"])?$frm["COL_HEADERS"]:$frm["DISPLAY_LIST"]);
        //report endss

        $data['pager']=$this->mpager->render(false);
        $data["pre_page"] = $fName;
        $this->load->vars($data);
		$this->load->view('questionnaire');
//        return "<h1>$sql";
    }	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */