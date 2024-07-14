<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (CategoryController)
 * Task Class to control Categories related operations.
 * @author :M.Ali
 * @version : 1.5
 * @since : 19 Jun 2024
 */
class AllService extends BaseController
{
  /**
   * This is default constructor of the class
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->model('Service_model', 'sm');
    $this->isLoggedIn();
    // $this->module = 'Services';
  }

  /**
   * This is default routing method
   * It routes to default listing page
   */
  public function index()
  {
    redirect('services/listing');
  }


  // serviceControler
  public function listing($pagenation = null)
  {
    if (isset($_REQUEST['id'])) {
      $categoryId = $_REQUEST['id'];
    } else {
      $categoryId = "";
    }
    if (isset($_REQUEST['txt'])) {
      $txt = $_REQUEST['txt'];
    } else {
      $txt = "";
    }


    if (!$this->moduleHasAccess($categoryId, 'list') ||  ($categoryId == "" || $txt == "")) {
      $this->loadThis();
    } else {
      $searchText = '';
      if (!empty($this->input->post('searchText'))) {
        $searchText = $this->input->post('searchText');
      }

      $serviceType = '';
      if ($txt == "listing" || $txt == NULL) {
        $serviceType = '';
      } else {
        $trim = trim($txt);
        $str_txt = str_replace('_', ' ', $trim);
        $serviceType = $str_txt;
      }


      $data['searchText'] = $searchText;
      $data['category'] = $txt;
      $data['categorId'] = $categoryId;
      $data['params'] = 'services/add?txt=' . $txt . '&id=' . $categoryId;
      $data['params_self'] = '?txt=' . $txt . '&id=' . $categoryId;

      $this->load->library('pagination');

      $count = $this->sm->listingCount($searchText, $categoryId);
      $returns = $this->paginationCompress("package-type/", $count, 5, 3);
      $data['records'] = $this->sm->listing($searchText, $categoryId, $returns["page"], $returns["segment"]);

      $this->global['pageTitle'] = $txt;
      $this->loadViews("services/list", $this->global, $data, NULL);
    }
  }


  public function getServiceByScid()
  {

    $categoryId = $_REQUEST['id'] ?? '';
    $segment = $_REQUEST['segment'] ?? '';
    if (($categoryId == "")) {
      $json_data = ['status' => 'no-access'];
      echo json_encode($json_data);
    } else {
      $searchText = '';

      $data['categorId'] = $categoryId;
      $data['records'] = $this->sm->listingBySid($categoryId);

      echo json_encode($data['records']);
    }
  }

  public function add()
  {
    if (isset($_REQUEST['id'])) {
      $id = $_REQUEST['id'];
    } else {
      $id = "";
    }
    if (isset($_REQUEST['txt'])) {
      $category = $_REQUEST['txt'];
    } else {
      $category = "";
    }
    if (!$this->moduleHasAccess($id, 'create_records') || ($category == "" || $id == "")) {
      $this->loadThis();
    } else {

      $spid = SUPPLIER_USER;
      $supplier_info = $this->sm->getSupplierList($spid);
      $category_info = $this->cm->categoryExists($id);
      $subcategory_info = $this->cm->getSubcategoryList($id);

      if (empty($category_info)) {
        $this->loadThis();
      } else {

        $this->global['pageTitle'] = $category . ' : Add New Services';
        $data['categoryInfo'] = $category_info;
        $data['subcategoryInfo'] = $subcategory_info;
        $data['supplierInfo'] = $supplier_info;
        $data['parms'] = '?txt=' . $category . '&id=' . $id;

        $this->loadViews("services/add", $this->global, $data, NULL);
      }
    }
  }

  public function addNew()
  {
    //   echo '<pre>'; print_r($_POST); die;
    $category = "";
    $id = "";
    if (isset($_REQUEST['id'])) {
      $id = $_REQUEST['id'];
    }
    if (isset($_REQUEST['txt'])) {
      $category = $_REQUEST['txt'];
    }

    $serviceLabel = ($this->input->post('serviceLabel'));
    if ((!$this->moduleHasAccess($id, 'create_records') || empty($serviceLabel)) || ($category == "" || $id == "")) {
      $this->loadThis();
    } else {

      $this->load->library('form_validation');

      $this->form_validation->set_rules('title', 'Title', 'trim|callback_html_clean|required|max_length[256]');
      $this->form_validation->set_rules('description', 'Description', 'trim|required');
      $this->form_validation->set_rules('status', 'status', 'trim|callback_html_clean|required');
      $this->form_validation->set_rules('subcatId', 'category', 'trim|callback_html_clean|required');
      $this->form_validation->set_rules('priceChild', 'Price Child', 'trim|callback_html_clean|required|max_length[256]');
      $this->form_validation->set_rules('priceAdult', 'Price Child', 'trim|callback_html_clean|required|max_length[256]');
      $this->form_validation->set_rules('type', 'Type', 'trim|callback_html_clean|required');
      $this->form_validation->set_rules('vehicleCode', 'vehicle Code', 'trim|callback_html_clean|required');
      $this->form_validation->set_rules('inclusion', 'Inclusion', 'trim|required');
      $this->form_validation->set_rules('exclusion', 'Exclusion', 'trim|required');
      $this->form_validation->set_rules('terms', 'Terms & Services', 'trim|required');

      // $this->form_validation->set_rules('userfile','Image','trim|required');
      $this->form_validation->set_rules('thumbnailImage', 'thumbnail', 'callback_validate_file');

      if ($this->form_validation->run() == FALSE) {
        $this->add();
      } else {

        $data = array();

        $slolInfo = "";
        if (strtolower($serviceLabel) == ATTRACTION) {
          $slolInfo = $this->session->userdata('slot_data');
          if ($slolInfo) {
            $slolInfo = json_encode($slolInfo);
          } else {
            $slolInfo = "";
          }
        }

        $data['serviceTitle'] = ($this->input->post('title'));
        $data['serviceDescription'] = ($this->input->post('description'));
        $data['serviceType'] = str_replace('-', ' ', $category);
        $data['supplierId'] = '0';
        $data['supplierName'] = "";
        $data['categoryId'] = $id;
        $data['subcategoryId'] = ($this->input->post('subcatId'));
        $data['status'] = ($this->input->post('status'));
        $data['createdBy'] = $this->vendorId;
        $data['createdDtm'] = date('Y-m-d H:i:s');

        $extra_info['prices'] = array(
          'priceChild' => $this->input->post('priceChild') ?? 0.00,
          'priceAdult' => $this->input->post('priceAdult') ?? 0.00,
          'priceAdultL' => $this->input->post('priceAdultLabel') ?? '',
          'priceChildL' => $this->input->post('priceChildLabel') ?? ''
        );


        $extra_info['others'] = array(
          'categoryLabel' => strtolower(($this->input->post('serviceLabel'))),
          'type' => ($this->input->post('type')),
          'vehicleCode' => ($this->input->post('vehicleCode')),
          'Totalslot' => $slolInfo,
          'inclusion' => ($this->input->post('inclusion')),
          'exclusion' => ($this->input->post('exclusion')),
          'termsAndService' => ($this->input->post('terms'))
        );
        $data['extraInfo'] = json_encode($extra_info);

        $thumbnail_image =  $this->do_upload($data['serviceTitle'], 'thumbnailImage', '/uploads/services/');
        ($thumbnail_image == "error") ? $this->add($category, $id) : $data['serviceBanner'] = $thumbnail_image;

        if (!empty($_FILES['serviceImage']['name']['0'])) {

          $uploads = $this->upload_multiple($data['serviceTitle'], 'serviceImage', '/uploads/services/');
          if (isset($uploads['error']) == "error") {
            redirect('services/add?txt=' . $category . '&id=' . $id);
          } else {
            $data['serviceImages'] = json_encode($uploads);
          }
        }
        $result1 = $this->sm->addNewServices($data);

        if ($result1 > 0) {
          $this->session->set_flashdata('success', 'New Service created successfully');
          $this->session->unset_userdata('slot_data');
        } else {
          $this->session->set_flashdata('error', 'Service creation failed');
          redirect('services/add?txt=' . $category . '&id=' . $id);
        }

        redirect('services/listing?txt=' . $category . '&id=' . $id);
      }
    }
  }
  public function edit()
  {
    // pre($_REQUEST['maincatid']);
    // die;
    $serviceType = "";
    $serviceId = "";
    $maincatid = "";
    if (isset($_REQUEST['id'])) {
      $serviceId = $_REQUEST['id'];
    }
    if (isset($_REQUEST['txt'])) {
      $serviceType = $_REQUEST['txt'];
    }
    if (isset($_REQUEST['maincatid'])) {
      $maincatid = $_REQUEST['maincatid'];
    }

    if (!$this->moduleHasAccess($maincatid, 'edit_records') || ($serviceType == "" || $serviceId == "" || $maincatid == "")) {
      $this->loadThis();
    } else {
      $maincatid = $_REQUEST['maincatid'];

      $data = array();
      $spid = SUPPLIER_USER;
      $data['supplierInfo'] = $this->sm->getSupplierList($spid);

      $data['categoryInfo'] = $this->sm->getAllCategory();
      $data['subcategoryInfo'] = $this->sm->getAllSubCategory($maincatid);
      $data['serviceInfo'] = $this->sm->getServiceInfo($serviceId);
      $data['serviceType'] = $serviceType;

      $this->global['pageTitle'] = $serviceType . ' : Edit';
      $data['parms'] = '?txt=' . $serviceType . '&id=' . $serviceId . '&maincatid=' . $maincatid;
      $this->loadViews("services/edit", $this->global, $data, NULL);
    }
  }

  public function editService()
  {
    $category = "";
    $id = "";
    $maincatid = "";
    if (isset($_REQUEST['id'])) {
      $id = $_REQUEST['id'];
    }
    if (isset($_REQUEST['txt'])) {
      $category = $_REQUEST['txt'];
    }
    if (isset($_REQUEST['maincatid'])) {
      $maincatid = $_REQUEST['maincatid'];
    }

    $serviceLabel = ($this->input->post('serviceLabel'));
    if ((!$this->moduleHasAccess($maincatid, 'edit_records') || empty($serviceLabel)) || ($category == "" || $id == "" || $maincatid == "")) {
      $this->loadThis();
    } else {

      $data = array();
      $maincatid = $_REQUEST['maincatid'];
      $slolInfo = "";
      if (strtolower($serviceLabel) == ATTRACTION) {
        $slolInfo = $this->session->userdata('slot_data');
        if ($slolInfo) {
          $slolInfo = json_encode($slolInfo);
        } else {
          $slolInfo = "";
        }
      }

      $data['serviceTitle'] = ($this->input->post('title'));
      $data['serviceDescription'] = ($this->input->post('description'));
      $data['serviceType'] = str_replace('-', ' ', $category);
      // $data['supplierId'] = '';
      // $data['supplierName'] = "";
      $data['categoryId'] = $maincatid;
      $data['subcategoryId'] = ($this->input->post('subcatId'));
      $data['status'] = ($this->input->post('status'));
      $data['createdBy'] = $this->vendorId;
      $data['createdDtm'] = date('Y-m-d H:i:s');

     $extra_info['prices'] = array(
        'priceChild' => $this->input->post('priceChild') ?? 0.00,
        'priceAdult' => $this->input->post('priceAdult') ?? 0.00,
        'priceAdultL' => $this->input->post('priceAdultLabel') ?? '',
        'priceChildL' => $this->input->post('priceChildLabel') ?? ''
      );


      $extra_info['others'] = array(
        'categoryLabel' => strtolower(($this->input->post('serviceLabel'))),
        'type' => ($this->input->post('type')),
        'vehicleCode' => $this->input->post('vehicleCode'),
        'Totalslot' => $slolInfo,
        'inclusion' => $this->input->post('inclusion'),
        'exclusion' => ($this->input->post('exclusion')),
        'termsAndService' => ($this->input->post('terms'))
      );
      $data['extraInfo'] = json_encode($extra_info);


      if ($_FILES['thumbnailImage']['name']) {
        $thumbnail_image =  $this->do_upload($data['serviceTitle'], 'thumbnailImage', '/uploads/services/');
        ($thumbnail_image == "error") ? $this->edit() : $data['serviceBanner'] = $thumbnail_image;
      }
      if (!empty($_FILES['serviceImage']['name']['0'])) {

        $uploads = $this->upload_multiple($data['serviceTitle'], 'serviceImage', '/uploads/services/');
        if (isset($uploads['error']) == "error") {
          redirect('services/edit?txt=' . $category . '&id=' . $id);
        } else {
          $data['serviceImages'] = json_encode($uploads);
        }
      }

      $result1 = $this->sm->editServices($data, $id);

      if ($result1 > 0) {
        $this->session->set_flashdata('success', $data['serviceTitle'] . ' updated successfully');
        redirect('services/listing?txt=' . $category . '&id=' . $maincatid);
      } else {
        $this->session->set_flashdata('error', $data['serviceTitle'] . ' updation failed, please try again.');
        redirect('services/edit?txt=' . $category . '&id=' . $id);
      }
    }
  }



  // Custom callback function to validate post input
  public function html_clean($s, $v)
  {
    return strip_tags((string) $s);
  }

  // Custom callback function to validate file input
  public function validate_file($str)
  {
    if (empty($_FILES['thumbnailImage']['name'])) {
      $this->form_validation->set_message('validate_file', 'The {field} field is required.');
      return FALSE;
    } else {
      return TRUE;
    }
  }
  // This function to upload files 
  public function do_upload($title,  String $input_name, String $upload_path = "/uploads/")
  {
    $subStr = substr($title, 0, 15);
    $toLower = strtolower($subStr);
    $c = array(',', '&', '!', '@', '#', '$', '%', '^', '*', '=', '+', ';', ':', "'", '"', ',', '.', '?', '/', ' ');
    $new_name = str_replace($c, '',  $toLower);


    $config['upload_path'] = "." . $upload_path;
    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size'] = 1024 * 2; // Maximum file size in kilobytes
    //   $config['max_width'] = 1024;
    //   $config['max_height'] = 768;
    $config['file_name'] = $new_name . '_' . time(); // Customize the file name
    $this->load->library('upload', $config);
    $result = "";
    if (!$this->upload->do_upload($input_name)) {
      $this->session->set_flashdata('error', $this->upload->display_errors());
      $result = 'error';
    } else {
      $result =  $upload_path . $this->upload->data('orig_name');
    }

    return  $result;
  }


  // This function to upload multiple files 
  public function upload_multiple($title,  String $input_name, String $upload_path = "/uploads/")
  {
    $subStr = substr($title, 0, 15);
    $cr = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', ':', ';', '"', "'", ',', '.', '/', ' ');
    $prefix_name = str_replace($cr, '', $subStr);
    $strToLower = strtolower($prefix_name);
    // Set up the upload configuration
    $config['upload_path'] = '.' . $upload_path;
    $config['allowed_types'] = '*';
    $config['max_size'] = (1024 * 2); // Maximum file size in kilobytes
    // $config['max_width'] = 1024; // Maximum width allowed
    // $config['max_height'] = 768; // Maximum height allowed
    $config['encrypt_name'] = FALSE; // Keep original filename

    $this->load->library('upload', $config);

    // Process each uploaded file

    $result = array();
    foreach ($_FILES[$input_name]['name'] as $key => $filename) {

      // $this->preAndDie($_FILES[$input_name]['tmp_name']);

      $_FILES['userfle']['name'] = $_FILES[$input_name]['name'][$key];
      $_FILES['userfle']['type'] = $_FILES[$input_name]['type'][$key];
      $_FILES['userfle']['tmp_name'] = $_FILES[$input_name]['tmp_name'][$key];
      $_FILES['userfle']['error'] = $_FILES[$input_name]['error'][$key];
      $_FILES['userfle']['size'] = $_FILES[$input_name]['size'][$key];

      // Generate custom filename
      $custom_filename = $strToLower . '_' . $key;

      // Set custom filename in upload configuration
      $config['file_name'] = $custom_filename;
      $this->upload->initialize($config);

      if ($this->upload->do_upload('userfle')) {
        $result['data'][] = $upload_path . $this->upload->data('orig_name');
      } else {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        $result['error'] = "error";
      }
    }

    // Process upload results
    return $result;
  }

  public function commonDelete()
  {

    $id = $this->input->post('id');
    $tbname = $this->input->post('tbname');
    $colname = $this->input->post('colName');
    $info = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

    if ($tbname == 'users') {
      $data['bSupplierId'] = 0;
      $this->sm->updateSupplier($data, $id);
    }
    $result = $this->sm->deleteCommon($id, $colname, $tbname, $info);


    if ($result > 0) {
      echo (json_encode(array('status' =>  TRUE)));
    } else {
      echo (json_encode(array('status' => FALSE)));
    }
  }

  public function saveServiceDataToSession()
  {
    $data = json_decode($this->input->post('data')); // Get data from AJAX request
    $this->session->set_userdata('slot_data', $data); // Store data in session
    echo count((array) $data);
  }

  function moduleHasAccess($maincatid, $permission)
  {
    $subCate = new  Category_model();
    $category_now =  $subCate->getCategoryRow($maincatid);
    if (!empty($category_now)) {

      if (check_permission($category_now->categoryName, $permission)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}