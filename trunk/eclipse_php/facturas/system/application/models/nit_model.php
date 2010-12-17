<?php

class Nit_model extends Model {
	
  private $the_table = 'fa_nit';

  function Nit_model() {
    parent::Model();    
  }
  
  /**
   * Count all nits in db
   * @return unknown_type
   */
  function count_all() {
    return $this->db->count_all($this->the_table);
  }
  
  // get nit with paging
  function get_paged_list($limit = 10, $offset = 0) {
	$this->db->order_by('nombre','asc');
    return $this->db->get($this->the_table  , $limit, $offset);
  }
  
  // get nit by nit
  function get_by_id($id){
    $this->db->where('nit', $id);
    return $this->db->get($this->the_table);
  }
  
  /**
   * add new nit
   * @param $nit
   * @return unknown_type
   */
  function add($data){
	$this->db->insert($this->the_table, $data);
	return $this->db->insert_id();
  }
	
  /**
   * Update nit by id
   * @param $id
   * @param $data
   * @return unknown_type
   */
  function update($id, $data) {
	$record_version_old = $data['record_version'];
	$data['record_version'] = date('Y-m-d H:i:s');
	
	$this->db->where('nit', $id);
	$this->db->where('record_version', $record_version_old);
    $this->db->update($this->the_table, $data);
  }
  
  /**
   * delete nit by id
   * @param $id
   * @return unknown_type
   */
  function delete($id){
    $this->db->where('nit', $id);
    $this->db->delete($this->the_table);
  }
  
}

