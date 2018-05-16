<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();	
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->library('user_agent');
		$this->load->helper('directory');
		$this->load->helper('security');
		$this->load->model('Admin_model');
			
		}
	public function index()
	{
		
		
		
		echo "Coming soon";exit;
		
		if($this->session->userdata('userdetails'))
		{
			$admindetails=$this->session->userdata('userdetails');
			if($admindetails['role_id']==1){
				$data['hospital_list']=$this->Admin_model->get_hospitals_list_monthwise(date('Y'));
				$sevendays_list=$this->Admin_model->get_last_sevendays_hospital_list();
				if(count($sevendays_list)>0){
				$data['sevendays_list']=count($sevendays_list);
				}else{
					$data['sevendays_list']='';
				}
				$this->load->view('admin/dashboard',$data);
			}else if($admindetails['role_id']==2){
				$hos_details=$this->Admin_model->get_hospital_details($admindetails['a_id']);
			
				$data['patients_list']=$this->Admin_model->get_hospitals_patient_list_monthwise($hos_details['hos_id'],date('Y'));
				$data['new_patients_list']=$this->Admin_model->get_hospitals_new_patient_list_monthwise($hos_details['hos_id'],date('Y'));
				$data['reschudle_patients_list']=$this->Admin_model->get_hospitals_reschudle_patient_list_monthwise($hos_details['hos_id'],date('Y'));

				$new_patient_sevendays_list=$this->Admin_model->get_last_sevendays_hospital_new_patient_list($hos_details['hos_id']);
				$new_all_patient_list=$this->Admin_model->get_hospital_new_patient_list($hos_details['hos_id']);

				$reschedulepatient_sevendays_list=$this->Admin_model->get_last_sevendays_hospital_reschedule_patient_list($hos_details['hos_id']);

				$reschedule_all_patient_list=$this->Admin_model->get_hospital_reschedule_patient_list($hos_details['hos_id']);

				$edit_prescriptions_list=$this->Admin_model->get_hospital_edit_prescriptions_list($hos_details['hos_id']);
				if(count($new_patient_sevendays_list)>0){
				$data['newpatient_last_seven']=count($new_patient_sevendays_list);
				}else{
					$data['newpatient_last_seven']='';
				}
				if(count($new_all_patient_list)>0){
				$data['total_newpatient_list']=count($new_all_patient_list);
				}else{
					$data['total_newpatient_list']='';
				}
				if(count($reschedulepatient_sevendays_list)>0){
				$data['reschedule_last_seven']=count($reschedulepatient_sevendays_list);
				}else{
					$data['reschedule_last_seven']='';
				}if(count($reschedule_all_patient_list)>0){
				$data['total_reschudle_patient_list']=count($reschedule_all_patient_list);
				}else{
					$data['total_reschudle_patient_list']='';
				}
				if(count($edit_prescriptions_list)>0){
				$data['prescriptions_list']=count($edit_prescriptions_list);
				}else{
					$data['prescriptions_list']='';
				}
				//echo '<pre>';print_r($data);exit;

				$this->load->view('hospital/dashboard',$data);
			}else if($admindetails['role_id']==3){
				redirect('resources/desk');
			}else if($admindetails['role_id']==4){
				redirect('users');
			}else if($admindetails['role_id']==5){
				redirect('lab/patient_list');
			}else if($admindetails['role_id']==6){
				redirect('resources/worksheet');
			}else if($admindetails['role_id']==8){
				redirect('admin/chat');
			}
			$this->load->view('html/footer');

		}else{
			$this->session->set_flashdata('loginerror','Please login to continue');
			redirect('admin');
		}
	}
	public function changepassword()
	{
		if($this->session->userdata('userdetails'))
		{
			$admindetails=$this->session->userdata('userdetails');
				$this->load->view('html/changepassword');
				$this->load->view('html/footer');
			
		}else{
			$this->session->set_flashdata('loginerror','Please login to continue');
			redirect('admin');
		}
	}
	public function changepasswordpost(){
	 
		if($this->session->userdata('userdetails'))
		{
			$admindetails=$this->session->userdata('userdetails');
			$post=$this->input->post();
			$admin_details = $this->Admin_model->get_adminpassword_details($admindetails['a_id']);
			if($admin_details['a_password']== md5($post['oldpassword'])){
				if(md5($post['password'])==md5($post['confirmpassword'])){
						$updateuserdata=array(
						'a_password'=>md5($post['confirmpassword']),
						'a_org_password'=>$post['confirmpassword'],
						'a_updated_at'=>date('Y-m-d H:i:s'),
						);
						//echo '<pre>';print_r($updateuserdata);exit;
						$upddateuser = $this->Admin_model->update_admin_details($admindetails['a_id'],$updateuserdata);
						if(count($upddateuser)>0){
							$this->session->set_flashdata('success',"password successfully updated");
							redirect('dashboard/changepassword');
						}else{
							$this->session->set_flashdata('error',"technical problem will occurred. Please try again.");
							redirect('dashboard/changepassword');
						}
					
				}else{
					$this->session->set_flashdata('error',"Password and Confirm password are not matched");
					redirect('dashboard/changepassword');
				}
				
			}else{
				$this->session->set_flashdata('error',"Old password are not matched");
				redirect('dashboard/changepassword');
			}
				
			
		}else{
			 $this->session->set_flashdata('error','Please login to continue');
			 redirect('');
		} 
	 
	}
	public function logout(){
		$userinfo = $this->session->userdata('userdetails');
        $this->session->unset_userdata($userinfo);
		$this->session->sess_destroy('userdetails');
		$this->session->unset_userdata('userdetails');
        redirect('admin');
	}
	
	public function emps(){
		
		
		/*test*/
		$this->zend->load('Zend/Barcode');
		//generate barcode
		
		/*test*/
		
		$filename = $this->security->sanitize_filename($this->input->post('name'), TRUE);
		$data=array('d_name'=>$filename);
		$logindatasave = $this->Doctor_model->save_doctors($data);
		
		
		
		$file = Zend_Barcode::draw('code128', 'image', array('text' => $logindatasave), array());
		$code = time().$logindatasave;
		 $store_image1 = imagepng($file, $this->config->item('documentroot')."assets/barcodes/{$code}.png");
			
		$this->Doctor_model->update_doctors_details($logindatasave,$code);
		//echo '<pre>';print_r($test);
		
		
	}
	
	
	
}
