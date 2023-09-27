<?php

class AdjustmentController extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdjustmentModel');

    }

    public function inv_adjustments()
    {

        $this->load->view('header');
        $this->load->view('inventory_adjustment/modal/adjustment_modal');
        $this->load->view('inventory_adjustment/inv_adjustment_ui');
        $this->load->view('inventory_adjustment/modal/adjustment_history_modal');
        $this->load->view('footer');
    }

    public function get_inventory_data()
    {
        $group_id   = $this->input->post('group_id');
        $start      = $this->input->post('start');
        $length     = $this->input->post('length');
        $search     = $this->input->post('search')['value'];
        $column     = isset($order[0]['column']) ? $order[0]['column'] : 0;
        $order_dir  = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';
        $columns    = [ 'item_code', 'design_name', 'cat_type', 'item_size', 'gender_status', 'quantity', 'b_remain_qty'];
        $order_by    = $columns[$column];

        $recordsTotal = count($this->AdjustmentModel->get_adjustment($group_id, 0, 99999,  $search, $order_by, $order_dir));
        $data = $this->AdjustmentModel->get_adjustment($group_id, $start, $length, $search, $order_by, $order_dir);

        $data_output = array(
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => intval($recordsTotal),
            'recordsFiltered' => intval($recordsTotal),
            'data' => $data,
        );
        echo json_encode($data_output);
    }

    public function update_inventory_data()
    {
        $data = array(
            'adj_new_itemcode'      =>      $this->input->post('adj_new_itemcode'),
            'adj_new_reason'        =>      $this->input->post('adj_new_reason'),
            'adj_new_cat_type'      =>      $this->input->post('adj_new_cat_type'),
            'adj_new_itemtype'      =>      $this->input->post('adj_new_itemtype'),
            'adj_new_design_name'   =>      $this->input->post('adj_new_design_name'),
            'adj_new_itemsize'      =>      $this->input->post('adj_new_itemsize'),
            'adj_old_quantity'      =>      $this->input->post('adj_old_quantity'),
            'adj_old_batchqty'      =>      $this->input->post('adj_old_batchqty'),
            'adj_new_gender_status' =>      $this->input->post('adj_new_gender_status'),
            'adj_new_quantity'      =>      $this->input->post('adj_new_quantity'),
            'adj_new_batchqty'      =>      $this->input->post('adj_new_batchqty'),
            'adj_new_date'          =>      $this->input->post('adj_new_date'),
            'adj_new_groupid'       =>      $this->input->post('adj_new_groupid'),
            'adj_new_status'        =>      'PENDING'
        );
        $update_result = $this->AdjustmentModel->insert_data($data);
        if ($update_result) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Error: Database insert failed.';
        }
        echo json_encode($response);
    }

    // public function inv_adjustment_history() 
    // {
    //     $this->load->view('header');
    //     $this->load->view('inventory_adjustment/modal/adjustment_history_modal');
    //     $this->load->view('inventory_adjustment/inv_adjustment_history_ui');
    //     $this->load->view('footer');
    // }

    public function view_adjustment_history()
    {
        $group_id   =   $this->input->post('group_id');
        $draw       =   $this->input->post('draw');
        $start      =   $this->input->post('start');
        $length     =   $this->input->post('length');
        $search     =   $this->input->post('search')['value'];
        $column = isset($order[0]['column']) ? $order[0]['column'] : 0;
        $order_dir = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

        $columns = ['adj_new_itemcode', 'adj_new_cat_type', 'adj_new_design_name', 'adj_old_quantity', 'adj_new_quantity', 'adj_old_batchqty', 'adj_new_batchqty', 'adj_new_date', 'adj_new_reason'];
        $order_by       = $columns[$column];
        
        $recordsTotal   = count($this->AdjustmentModel->get_adjustment_history($group_id, 0, 99999, $search, $order_by, $order_dir));
        $data = $this->AdjustmentModel->get_adjustment_history($group_id, $start, $length, $search, $order_by, $order_dir);


        $hist_output = array(
            'draw'              => $draw,
            'recordsTotal'      => intval($recordsTotal),
            'recordsFiltered'   => intval($recordsTotal),
            'data'              =>  $data,
        );
        echo json_encode($hist_output);
    }

    public function update_status()
    {
        $adj_new_id             = $this->input->post('adj_new_id');
        $adj_new_status         = $this->input->post('adj_new_status');
        $adj_new_approved_date  = date('Y-m-d h:i:s A');
        $data = array(
            'adj_new_status' =>  $adj_new_status,
            'adj_new_approved_date' => $adj_new_approved_date,
        );
        $update_result = $this->AdjustmentModel->updateStatus($data, $adj_new_id);
        
        $response = array();
        if ($update_result) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Error: Database update failed.';
        }
        echo json_encode($response);
    }
}




// class AdjustmentController extends CI_Controller 
// {
//     public function __construct()
//     {
//         parent::__construct();
//         $this->load->library('session');
//         $this->load->model('Adjustment/AdjustmentModel');
//         $this->load->model('MainModel');

//     }

//     public function inv_adjustments()
//     {
// 		// $emp_log = $this->session->userdata('emp_id');
//         // if(isset($emp_log)):
// 		// 	$admin_status = $this->MainModel->check_if_exists_ADMIN($emp_log);
// 		// 	if($admin_status['count'] > 0):

//             $this->load->view('header');
//             $this->load->view('inventory_adjustment/modal/adjustment_modal');
//             $this->load->view('inventory_adjustment/modal/adjustment_history_modal');
//             $this->load->view('inventory_adjustment/inv_adjustment_ui');
//             $this->load->view('footer');
        
// 		// 	else:
// 		// 		$this->load->view('Access_Denied');
// 		// 	endif;
// 		// else:
// 		// 	session_destroy();
// 		// 	header('Location: /hrms/employee/index.php');
// 		// endif;
//     }

//     public function get_inventory_data()
//     {
//         $group_id   = $this->input->post('group_id');
//         $start      = $this->input->post('start');
//         $length     = $this->input->post('length');
//         $search     = $this->input->post('search')['value'];
//         $column     = $this->input->post('order')[0]['column'];
//         $order_dir  = $this->input->post('order')[0]['dir'];
//         $columns    = [ 'item_code', 'design_name', 'cat_type', 'item_size', 'gender_status', 'quantity', 'b_remain_qty'];
//         $order_by   = $columns[$column];
    
//         // $groupOptions = [
//         //     ['group_id' => 1, 'group_name' => 'Corporate'],
//         //     ['group_id' => 2, 'group_name' => 'Alturas Mall'],
//         //     ['group_id' => 3, 'group_name' => 'Plaza Marcela'],
//         //     ['group_id' => 4, 'group_name' => 'Island City Mall'],
//         //     ['group_id' => 6, 'group_name' => 'Alturas Talibon'],
//         //     ['group_id' => 7, 'group_name' => 'Alturas Tubigon'],
//         //     ['group_id' => 8, 'group_name' => 'Central Distribution Center'],
//         //     ['group_id' => 11,'group_name' => 'Ubay Distribution Center'],
//         //     ['group_id' => 12,'group_name' => 'Cortes Construction'],
//         //     ['group_id' => 13,'group_name' => 'Ubay Dressing Plant']
//         // ];
//         // $selectOptions = '';
//         // foreach ($groupOptions as $option) {
//         //     $selected = ($option['group_id'] ==  $group_id) ? 'selected' : '';
//         //     $selectOptions .= "<option value='{$option['group_id']}' $selected>{$option['group_name']}</option>";
//         // }

//         $recordsTotal = count($this->AdjustmentModel->get_adjustment($group_id, 0, 99999, $search, $order_by, $order_dir));
//         $data = $this->AdjustmentModel->get_adjustment($group_id, $start, $length, $search, $order_by, $order_dir);

//         $data_output = array(
//             'draw' => intval($this->input->post('draw')),
//             'recordsTotal' => intval($recordsTotal),
//             'recordsFiltered' => intval($recordsTotal),
//             'data' => $data,
//             // 'selectOptions' => $selectOptions
//         );
//         echo json_encode($data_output);
//     }

//     public function update_inventory_data()
//     {
//         $data = array(
//             'adj_new_itemcode'      =>      $this->input->post('adj_new_itemcode'),
//             'adj_new_reason'        =>      $this->input->post('adj_new_reason'),
//             'adj_new_cat_type'      =>      $this->input->post('adj_new_cat_type'),
//             'adj_new_itemtype'      =>      $this->input->post('adj_new_itemtype'),
//             'adj_new_design_name'   =>      $this->input->post('adj_new_design_name'),
//             'adj_new_itemsize'      =>      $this->input->post('adj_new_itemsize'),
//             'adj_old_quantity'      =>      $this->input->post('adj_old_quantity'),
//             'adj_old_batchqty'      =>      $this->input->post('adj_old_batchqty'),
//             'adj_new_gender_status' =>      $this->input->post('adj_new_gender_status'),
//             'adj_new_quantity'      =>      $this->input->post('adj_new_quantity'),
//             'adj_new_batchqty'      =>      $this->input->post('adj_new_batchqty'),
//             'adj_new_date'          =>      $this->input->post('adj_new_date'),
//             'adj_new_groupid'       =>      $this->input->post('adj_new_groupid'),
//             'adj_new_status'        =>      'PENDING'
//         );
//         $update_result = $this->AdjustmentModel->insert_data($data);
//         if ($update_result) {
//             $response['success'] = true;
//         } else {
//             $response['success'] = false;
//             $response['message'] = 'Error: Database insert failed.';
//         }
//         echo json_encode($response);
//     }

//     // public function inv_adjustment_history() 
//     // {
//         // $emp_log = $this->session->userdata('emp_id');
//         // if(isset($emp_log)):
// 		// 	$admin_status = $this->MainModel->check_if_exists_ADMIN($emp_log);
// 		// 	if($admin_status['count'] > 0):

//             // $this->load->view('header');
//             // $this->load->view('inventory_adjustment/modal/adjustment_history_modal');
//             // $this->load->view('inventory_adjustment/inv_adjustment_history_ui');
//             // $this->load->view('footer');

//         // 	else:
// 		// 		$this->load->view('Access_Denied');
// 		// 	endif;
// 		// else:
// 		// 	session_destroy();
// 		// 	header('Location: /hrms/employee/index.php');
// 		// endif;
//     // }

//     public function view_adjustment_history()
//     {
//         $group_id   =   $this->input->post('group_id');
//         $draw       =   $this->input->post('draw');
//         $start      =   $this->input->post('start');
//         $length     =   $this->input->post('length');
//         $search     =   $this->input->post('search')['value'];
//         $column     =   $this->input->post('order')[0]['column'];
//         $order_dir  =   $this->input->post('order')[0]['dir'];

//         $columns    = ['adj_new_itemcode', 'adj_new_cat_type', 'adj_new_design_name', 'adj_old_quantity', 'adj_new_quantity', 'adj_old_batchqty', 'adj_new_batchqty', 'adj_new_date', 'adj_new_reason', 'adj_new_status'];
//         $order_by   = $columns[$column];

//         // $groupOptions = [
//         //     ['group_id' => 1, 'group_name' => 'Corporate'],
//         //     ['group_id' => 2, 'group_name' => 'Alturas Mall'],
//         //     ['group_id' => 3, 'group_name' => 'Plaza Marcela'],
//         //     ['group_id' => 4, 'group_name' => 'Island City Mall'],
//         //     ['group_id' => 6, 'group_name' => 'Alturas Talibon'],
//         //     ['group_id' => 7, 'group_name' => 'Alturas Tubigon'],
//         //     ['group_id' => 8, 'group_name' => 'Central Distribution Center'],
//         //     ['group_id' => 11,'group_name' => 'Ubay Distribution Center'],
//         //     ['group_id' => 12,'group_name' => 'Cortes Construction'],
//         //     ['group_id' => 13,'group_name' => 'Ubay Dressing Plant']
//         // ];
//         // $selectOptions = '';
//         // foreach ($groupOptions as $option) {
//         //     $selected = ($option['group_id'] == $group_id) ? 'selected' : '';
//         //     $selectOptions .= "<option value='{$option['group_id']}' $selected>{$option['group_name']}</option>";
//         // }
        
//         $recordsTotal   = count($this->AdjustmentModel->get_adjustment_history($group_id, 0, 99999, $search, $order_by, $order_dir));
//         $data           = $this->AdjustmentModel->get_adjustment_history($group_id, $start, $length, $search, $order_by, $order_dir);

//         $hist_output = array(
//             'draw'              => $draw,
//             'recordsTotal'      => intval($recordsTotal),
//             'recordsFiltered'   => intval($recordsTotal),
//             'data'              =>  $data,
//             // 'selectOptions'     => $selectOptions,
//         );
        
//         echo json_encode($hist_output);
//     }

//     public function update_status()
//     {
//         $adj_new_id             = $this->input->post('adj_new_id');
//         $adj_new_status         = $this->input->post('adj_new_status');
//         $adj_new_approved_date  = date('Y-m-d h:i:s A');
//         $data = array(
//             'adj_new_status' =>  $adj_new_status,
//             'adj_new_approved_date' => $adj_new_approved_date,
//         );
//         $update_result = $this->AdjustmentModel->updateStatus($data, $adj_new_id);
        
//         $response = array();
//         if ($update_result) {
//             $response['success'] = true;
//         } else {
//             $response['success'] = false;
//             $response['message'] = 'Error: Database update failed.';
//         }
//         echo json_encode($response);
//     }
// }
