<?php

class AdjustmentModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_adjustment( $group_id, $start, $length, $search, $order_by, $order_dir)
    {
        // $this->db->select('uni.item_code, des.design_name, cat.cat_type, uni.item_type, ss.item_size, des.gender_status, uni.quantity');
        // $this->db->select('uni.item_code, ss.item_design, des.design_name, cat.cat_type, uni.item_type, ss.item_size, des.gender_status,uni.quantity, SUM(batch.b_remain_qty) as b_remain_qty');
        $this->db->select('uni.inv_id, batch.batch_id, ss.item_design, uni.item_code, des.design_name, cat.cat_type, uni.item_type, ss.item_size, des.gender_status, uni.quantity, batch.b_remain_qty');
		// $this->db->distinct();
        $this->db->from('uni_inventory uni');
        $this->db->join('uni_stocksupply_v2 ss', 'ss.item_type = uni.item_type AND ss.item_design = uni.item_design AND ss.item_code = uni.item_code', 'inner');
        $this->db->join('uni_stockdesign_v2 des', 'des.design_type = uni.item_type AND des.design_id = uni.item_design', 'inner');
        $this->db->join('uni_stockcategory_v2 cat', 'cat.stock_id = uni.item_type', 'inner');
        $this->db->join('uni_batch batch', 'batch.group_id = uni.group_id AND batch.item_code = uni.item_code', 'inner');
        $this->db->where('uni.status', 'active');
        $this->db->where('uni.group_id', $group_id);
        $this->db->where('uni.quantity != batch.b_remain_qty');
        // $this->db->group_by('uni.item_code');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('uni.item_code', $search);
            $this->db->or_like('ss.item_design', $search);
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
        $this->db->from('uni_admin_adjustment adj');
        // $this->db->join('uni_stocksupply_v2 sup', 'sup.item_type = adj.adj_item_type AND sup.item_design = adj.adj_item_design AND sup.item_code = adj.adj_itemcode');
        // $this->db->join('uni_stockdesign_v2 des', 'des.design_id = adj.adj_item_design AND des.design_type = adj.adj_item_type', 'inner');
        // $this->db->join('uni_stockcategory_v2 cat', 'cat.stock_id = adj.adj_item_type', 'inner');
        // $this->db->join('uni_batch batch', 'batch.group_id = adj.adj_group_id AND batch.item_code = adj.adj_itemcode', 'inner');
        $this->db->where('adj.adj_new_groupid', $group_id);
        // $this->db->group_by('adj.adj_itemcode'); 
        // $this->db->where('adj.adj_status','pending');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('adj.adj_itemcode', $search);
            $this->db->or_like('cat.cat_type', $search);
            $this->db->or_like('des.design_name', $search);
            $this->db->or_like('sup.item_size', $search);
            $this->db->or_like('adj.adj_newQTY', $search);
            $this->db->or_like('adj.adj_oldQTY', $search);
            $this->db->or_like('adj.adj_date', $search);
            $this->db->or_like('adj.adj_status', $search);
            $this->db->group_end();
        }
        $this->db->order_by($order_by, $order_dir);
        $this->db->limit($length, $start);
        $result = $this->db->get();
		return $result->result_array();
    }

    public function update_inventory_and_batch_quantity($inv_id, $quantity, $batch_id, $b_remain_qty) 
    {
        $this->db->set('quantity', $quantity, false);
        $this->db->where('inv_id', $inv_id);
        $quantity_update = $this->db->update('uni_inventory');
    
        $this->db->set('b_remain_qty', $b_remain_qty, false);
        $this->db->where('batch_id', $batch_id);
        $batch_update = $this->db->update('uni_batch');
    
        return $quantity_update && $batch_update;
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
        $this->db->from('uni_admin_adjustment adj');
        $this->db->where('adj.adj_new_status', 'pending');
        return $this->db->count_all_results();
    }
    public function count_approved(){
        $this->db->from('uni_admin_adjustment adj');
        $this->db->where('adj.adj_new_status', 'approved');
        return $this->db->count_all_results();
    }
    public function count_disapproved(){
        $this->db->from('uni_admin_adjustment adj');
        $this->db->where('adj.adj_new_status', 'disapproved');
        return $this->db->count_all_results();
    }
}


