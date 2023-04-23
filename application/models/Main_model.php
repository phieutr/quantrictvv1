<?php
class Main_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function result_select($table, $data, $value)
    {
        $this->db->select($value);
        $this->db->where($data);

        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function delete($table, $data)
    {
        $this->db->delete($table, $data);
    }

    public function update($table, $data, $where)
    {
        $this->db->update($table, $data, $where);
    }

    public function select_all($table)
    {
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function insert($table, $data)
    {
        $this->db->insert($table, $data);
    }

    public function get_posts($table, $slug = FALSE)
    {
        if ($slug == FALSE) {
            $query = $this->db->get($table);
            return $query->result_array();
        }

        $query = $this->db->get_where($table, array('slug' => $slug));
        return $query->row_array();
    }

    public function result_select_order_by($table, $order_column, $order_by, $limit)
    {
        $this->db->order_by($order_column, $order_by);
        $this->db->limit($limit);


        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function row_select($table, $data, $value)
    {
        $this->db->select($value);
        $this->db->where($data);

        $query = $this->db->get($table);
        return $query->row_array();
    }
}
