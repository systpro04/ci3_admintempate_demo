<?php

class AdjustmentModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_adjustment( $group_id, $start, $length, $search, $order_by, $order_dir)
    {
        // $this->db->select('uni.item_code, des.design_name, cat.cat_type, uni.item_type, ss.item_size, des.gender_status, uni.quantity');
        // $this->db->select('uni.item_code, des.design_name, cat.cat_type, uni.item_type, ss.item_size, des.gender_status,uni.quantity, SUM(batch.b_remain_qty) as b_remain_qty');
        $this->db->select('uni.item_code, des.design_name, cat.cat_type, uni.item_type, ss.item_size, des.gender_status, uni.quantity, batch.b_remain_qty');
		// $this->db->distinct();
        $this->db->from('uni_inventory uni');
        $this->db->join('uni_stocksupply_v2 ss', 'ss.item_type = uni.item_type AND ss.item_design = uni.item_design AND ss.item_code = uni.item_code', 'inner');
        $this->db->join('uni_stockdesign_v2 des', 'des.design_type = uni.item_type AND des.design_id = uni.item_design', 'inner');
        $this->db->join('uni_stockcategory_v2 cat', 'cat.stock_id = uni.item_type', 'inner');
        $this->db->join('uni_batch batch', 'batch.group_id = uni.group_id AND batch.item_code = uni.item_code', 'inner');
        $this->db->where('uni.status', 'active');
        $this->db->where('uni.group_id', $group_id);
        $this->db->group_by('uni.item_code');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('uni.item_code', $search);
            $this->db->or_like('des.design_name', $search);
            $this->db->or_like('cat.cat_type', $search);
            $this->db->or_like('ss.item_size', $search);
            $this->db->or_like('des.gender_status', $search);
            $this->db->or_like('uni.quantity', $search);
            $this->db->or_like('batch.b_remain_qty', $search);
            $this->db->group_end();
        }
        $this->db->order_by($order_by, $order_dir);
        $this->db->limit($length, $start);
        $this->db->last_query();
        $result = $this->db->get();
		return $result->result_array();
    }

    public function insert_data($data)
    {
        return $this->db->insert('uni_admin_adjustment', $data);
    }
    
    public function get_adjustment_history($group_id, $start, $length, $search, $order_by, $order_dir)
    {
        $this->db->select('*');
        $this->db->from('uni_admin_adjustment adjust');
        $this->db->where('adjust.adj_new_groupid', $group_id);
        
        // $this->db->where('adjust.adj_new_status','pending');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('adjust.adj_new_itemcode', $search);
            $this->db->or_like('adjust.adj_new_design_name', $search);
            $this->db->or_like('adjust.adj_new_itemsize', $search);
            $this->db->or_like('adjust.adj_new_quantity', $search);
            $this->db->or_like('adjust.adj_new_batchqty', $search);
            $this->db->or_like('adjust.adj_new_date', $search);
            $this->db->or_like('adjust.adj_new_status', $search);
            $this->db->group_end();
        }
        $this->db->order_by($order_by, $order_dir);
        $this->db->limit($length, $start);
        $result = $this->db->get();
		return $result->result_array();
    }

    public function updateStatus($data, $adj_new_id)
    {
        $this->db->where('adj_new_id', $adj_new_id);
        return $this->db->update('uni_admin_adjustment', $data);
    }

    public function count_all(){
        $this->db->from('uni_admin_adjustment');
        return $this->db->count_all_results();
    }

    public function count_pending(){
        $this->db->where('adj.adj_new_status', 'pending');
        $this->db->from('uni_admin_adjustment adj');
        return $this->db->count_all_results();
    }
    public function count_approved(){
        $this->db->where('adj.adj_new_status', 'approved');
        $this->db->from('uni_admin_adjustment adj');
        return $this->db->count_all_results();
    }
    public function count_disapproved(){
        $this->db->where('adj.adj_new_status', 'disapproved');
        $this->db->from('uni_admin_adjustment adj');
        return $this->db->count_all_results();
    }
}


