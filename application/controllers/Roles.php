<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Roles (RolesController)
 * Roles Class to control role related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 22 Jan 2021
 */
class Roles extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model', 'rm');
        $this->isLoggedIn();   
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('roles/roleListing');
    }
    
    /**
     * This function is used to load the role list
     */
    function roleListing()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->rm->roleListingCount($searchText);

			$returns = $this->paginationCompress ( "roles/roleListing/", $count, 5 );
            
            $data['roleRecords'] = $this->rm->roleListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = ' : Roles Listing';
            
            $this->loadViews("roles/list", $this->global, $data, NULL);
        }
    }

    function getRoleType()
    {
        $roleId = $_REQUEST['role_id'] ?? '';
        if($roleId == '')
        {
            echo json_encode(['status' => false, 'message' => 'Role is required', 'data' => (object) array()]);
        }
        else
        {      
            $data = $this->rm->getRow(['roleId' => $roleId] ,['role_type','role']);
            if(!empty($data)){
                if($data->role_type == SYSTEM_ADMIN){
                    $data->role  = "System Administrator";
                }else if($data->role_type == AGENT_USER){
                    $data->role  = "Agent | Agencies (B2B)";
                }else if($data->role_type == REGULAR_USER){
                    $data->role  = "Regular User (B2C)";
                }else if($data->role_type == SUPPLIER_USER){
                    $data->role  = "Supplier User (B2C)";
                }else {
                    $data->role  = "Unknown!";
                    $data->role_type  = 0;
                }
                echo json_encode(['status' => true , 'message' => 'success', 'data' => $data]);
            }else {
                echo json_encode(['status' => false, 'message'=> 'Data not found', 'data' => (object) array()]);
                
            }
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = ' : Add New Role';
            $this->loadViews("roles/add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkRoleExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewRole()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('role','Role Text','trim|required|max_length[50]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            $this->form_validation->set_rules('role_type','User Type','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $roleText = $this->security->xss_clean($this->input->post('role'));
                $status = $this->security->xss_clean($this->input->post('status'));
                $role_type = $this->security->xss_clean($this->input->post('role_type'));
                
                $roleInfo = array('role'=>$roleText, 'status'=>$status,'role_type'=>$role_type, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->rm->addNewRole($roleInfo);
                
                if($result > 0)
                {
                    $this->addRoleMatrix($result);
                    $this->session->set_flashdata('success', 'New Role created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role creation failed');
                } 
                
                redirect('roles/roleListing');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $roleId : Optional : This is user id
     */
    function edit($roleId = NULL)
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            if($roleId == null)
            {
                redirect('roles/roleListing');
            }
            
            $data['roleInfo'] = $this->rm->getRoleInfo($roleId);
            $roleAccessMatrix = $this->rm->getRoleAccessMatrix($roleId);
            $data['roleAccessMatrix'] = json_decode($roleAccessMatrix->access);
            $data['moduleList'] = $this->config->item('moduleList');
            
            $this->global['pageTitle'] = ' : Edit Role';
            
            $this->loadViews("roles/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editRole()
    {
        if(!$this->isAdmin())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $roleId = $this->input->post('roleId');
            
            $this->form_validation->set_rules('role','Role Text','trim|required|max_length[50]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            $this->form_validation->set_rules('role_type','User Type','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($roleId);
            }
            else
            {
                $roleText = $this->security->xss_clean($this->input->post('role'));
                $status = $this->security->xss_clean($this->input->post('status'));
                $roleType = $this->security->xss_clean($this->input->post('role_type'));
                
                $roleInfo = array('role'=>$roleText, 'status'=>$status, 'role_type' => $roleType, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->rm->editRole($roleInfo, $roleId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Role updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Role updation failed');
                }
                
                redirect('roles/roleListing');
            }
        }
    }

    function roleDelete()
    {
        if(!$this->isAdmin())
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $roleId = $this->input->post('roleId');
            $roleInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->rm->deleteRole($roleId, $roleInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

    /**
     * This method used to build access matrix for role from configuration
     * and insert default entry into the database
     * @param number $roleId : This is role id
     */
    private function addRoleMatrix($roleId)
    {
        $this->load->config('modules');

        $modules = $this->config->item('moduleList');

        $accessMatrix = array('roleId'=>$roleId, 'access'=>json_encode($modules), 'createdBy'=> $this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

        $this->rm->insertAccessMatrix($accessMatrix);
    }

    /**
     * This method used to update the access rights for the role
     */
    public function storeAccessMatrix()
    {
        $roleId = $this->input->post('roleIdForMatrix');
        $postParams = $this->input->post('access');

        $this->load->config('modules');

        $modules = $this->config->item('moduleList');
        $modules2 = [];

        foreach($modules as $module) {
            $singleModule = ['module'=>$module['module']];
            foreach($module as $keyMod=>$valMod) {
                if(isset($postParams[$module['module']][$keyMod])) {
                    $singleModule[$keyMod] = $postParams[$module['module']][$keyMod] == 'on' ? 1 : $postParams[$module['module']][$keyMod];
                } else {
                    $singleModule[$keyMod] = 0;
                }
            }
            $modules2[] = $singleModule;
        }

        $accessMatrix = ['access'=>json_encode($modules2), 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s')];

        $updated = $this->rm->updateAccessMatrix($roleId, $accessMatrix);

        if($updated){
            $this->session->set_flashdata('success', 'Access matrix updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Access matrix updation failed');
        }

        redirect('roles/edit/'.$roleId);
    }
}


?>