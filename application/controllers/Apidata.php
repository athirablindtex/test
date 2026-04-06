<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apidata extends CI_Controller {
	var $token;
	var $user_id;
	var $user_type;
	public function __construct(){
				parent::__construct();
				$this->load->model(array('logmodel','employeemodel','permissionmodel','permissionsmodel'));
				$headers = apache_request_headers();
				$imei=@$headers['imei_id'];
				//$expire=@$headers['access_token_expire'];
				$cur=strtotime(date('Y-m-d H:i:s'));
				$token1="+>4k&dT&uuN+4G^f,#ykMU,v;2gMTQUK<>a_XSS993-RSr)JMn";
				if($token1 == @$headers['token_valid']){
						if($imei!=''){
								$chk=array('imei_number'=>$imei);
								$this->db->where($chk);
								$cuser=$this->employeemodel->gets_data()->row();
									if($cuser){
											$data['status']=1;
											$data['message']='OK';
											$this->user_id=$cuser->id;
										}	
									else{
											$data['status']=0;
											$data['message']='Invalid Token';
										}
								}
							else{
									$this->tokkenmodel->record_ip();
									$data['status']=0;
									$data['message']='Invalid Token';
								}
							if(@$data['status']!=1){
									echo json_encode($data);
									exit;
								}
						}
					else{
							$this->tokkenmodel->record_ip();
							$this->load->view('welcome_message');
							exit;
						}
					
			}
	//check login
		function single_api(){
				$fld=$this->logmodel->get_fields();
				$exclude=array('id','employee_id','entry_date','audio');
				$dates=array('entry_date');
				$times=array('start_time1');
				$dt=$this->input->post();
				$ins=array();
				$entry=array();
				foreach($dt as $key=>$value){
						if(in_array($key,$fld)){
								if(in_array($key,$dates)){
										$ins[$key]=date('Y-m-d',strtotime($value));
									}
								else if(in_array($key,$times)){
										$ins[$key]=date('Y-m-d H:i:s',strtotime($value));
									}
								else if(!in_array($key,$exclude)){
										$ins[$key]=$value;
									}
							}
					}
				if($this->input->post('audio')){
						$name=rand(1,100000000000000000000000000).'.mp3';
						$path = APPPATH . '../uploads/audio/' . $name;
						if(file_put_contents($path, base64_decode($this->input->post('audio')))){
								$ins['audio']=$name;
							}
					}	
				if($ins){
						$ins['employee_id']=$this->user_id;
						$this->logmodel->save($ins);
					}
				$data['status']=1;
				echo json_encode($data);
			}

			function multiple_api(){
				$fld=$this->logmodel->get_fields();
				$exclude=array('id','employee_id','entry_date','audio');
				$dates=array('entry_date');
				$times=array('start_time1');
				$dt=$this->input->post();
				$ins=array();
				$entry=array();
				foreach($dt as $key=>$values){
						if(in_array($key,$fld)){
								if(in_array($key,$dates)){
										$i=0;
										foreach($values as $value){
												$ins[$i++][$key]=date('Y-m-d',strtotime($value));
											}
									}
								else if(in_array($key,$times)){
										$i=0;
										foreach($values as $value){
												$ins[$i++][$key]=date('Y-m-d H:i:s',strtotime($value));
											}
									}
								else if(!in_array($key,$exclude)){
										$i=0;
										foreach($values as $value){
												$ins[$i++][$key]=$value;
											}
									}
							}
					}
				$i=0;
				foreach($dt['audio'] as $aud ){
						if($aud!=""){
							$name=rand(10,1000000000000000000).'.mp3';
							$path = APPPATH . '../uploads/audio/' . $name;
							if(file_put_contents($path, base64_decode($aud))){
									$ins[$i++]['audio']=$name;
								}
						}	
					}
				if($ins){
						for($i=0;$i<count($ins);$i++){
								$ins[$i]['employee_id']=$this->user_id;		
							}
						$this->logmodel->save_batch($ins);
					}
				$data['posts']=$dt;
				$data['insert']=$ins;
				$data['status']=1;
				echo json_encode($data);
			}
	function insert_permission_single(){
			$data['status']=0;
			$post=$this->input->post();
			$fields=array('permission','status');
			if($post['permission']!=""){
					$data["status"]=1;
					$id=0;
					$dt=array('employee'=>$this->user_id,'permission'=>$post['permission'],'permission_time'=>$post['permission_time']);
					$dt['status']=$post["status"];
					$dt['last_updated']=date('Y-m-d');
					$this->permissionmodel->save($dt,$id);
				}
			else{
					$data['message']="Enter any permission";
				}
			echo json_encode($data);
		}
	function insert_permission_multiple(){
			$data['status']=1;
			$post=$this->input->post();
			$fields=array('permission','status');
			$i=0;
			foreach($post["permission"] as $per){
					if($per!=""){
							$id=0;
							$dt=array('employee'=>$this->user_id,'permission'=>$per,'permission_time'=>$post['permission_time'][$i]);
							$dt["status"]=$post["status"][$i];
							$dt['last_updated']=date('Y-m-d');
							$this->permissionmodel->save($dt,$id);
							$i++;
						}
				}
			echo json_encode($data);
		}
		function insert_user_permission_single(){
			$fields=array('pr_contact','pr_record','pr_phone','pr_sms','pr_storage','permission_time');
			$post=$this->input->post();
			$fields=array('permission','status');
			$data["status"]=1;
			$id=0;
			$dt=array('employee'=>$this->user_id);
			foreach($post as $key=>$value){
					if(in_array($key,$fields)){
							$dt[$key]=$value;
						}
				}
			$dt['last_updated']=date('Y-m-d');
			$this->permissionsmodel->save($dt,$id);
			echo json_encode($data);
		}
	function insert_user_permission_multiple(){
			$fields=array('pr_contact','pr_record','pr_phone','pr_sms','pr_storage','permission_time');
			$exist_arrays=array();
			$post=$this->input->post();
			foreach($post as $key=>$value){
					if(is_array($value) && in_array($key,$fields)){
							$exist_arrays[$key]=$value;
						}
				}
			$insert=array();
			if($exist_arrays){
					foreach($exist_arrays as $key=>$value){
							$i=0;
							foreach($value as $vl){
									$insert[$i++][$key]=$vl;
								}
						}
				}
			$i=0;
			foreach($insert as $in){
					$in[$i]['employee']=$this->user_id;	
					$in[$i++]['last_updated']=date('Y-m-d H:i:s');
				}	
			if($insert){
					$this->permissionsmodel->save_batch($insert);
					$data['status']=1;
				}
			else{
					$data['status']=0;
					$data['message']="no data inserted";
				}
			echo json_encode($data);
		}
}
