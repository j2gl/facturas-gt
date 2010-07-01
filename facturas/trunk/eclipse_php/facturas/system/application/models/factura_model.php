<?php

class Factura_model extends Model {

  private $the_table = 'fa_factura';

  function Factura_model() {
    parent::Model();
  }

  /**
   * Count all nits in db
   * @return unknown_type
   */
  function count_all() {
    return $this->db->count_all($this->the_table);
  }

  function get_last_factura() {
    //$sql = " select * from fa_factura where  record_version = ( select max(record_version) from fa_factura ) ";
    //$query = $this->db->query($sql);
    //return $query;

    $this->db->order_by('fecha','desc');
    $this->db->order_by('record_version','desc');
    return $this->db->get($this->the_table, 1, 0);

  }


  // get factura with paging
  function get_paged_list($limit = 10, $offset = 0) {
    $this->db->order_by('fecha','desc');
    $this->db->order_by('numero','desc');
    return $this->db->get($this->the_table, $limit, $offset);
    //return $this->db->get($this->the_table, 10, 10);
  }

  // get factura with paging
  function get_paged_list_order($limit = 10, $offset = 0, $order_by = '', $order_direction = 'asc') {
    $ar = array('fecha' => 'desc', 'numero' => 'desc');
    $this->db->order_by($ar);
    return $this->db->get($this->the_table  , $limit, $offset);
  }

  function get_quarter_summary_paged_list($limit = 10,$offset = 0) {
    if (empty($offset)) {
      $offset = 0;
    }
    $sql =
      " select year(fecha) as year, quarter(fecha) as quarter, sum(monto) as monto" .
      " from fa_factura " .
      " group by year(fecha), quarter(fecha) " .
      " order by year(fecha) desc, quarter(fecha) desc" .
      " limit $offset, $limit";

    $query = $this->db->query($sql);
    return $query;
  }

  function count_quarter_summary_paged_list() {
    $sql =
      " select   year(fecha), quarter(fecha) from fa_factura" .
      " group by year(fecha), quarter(fecha) " .
      " order by year(fecha) desc, quarter(fecha) desc";
    $query = $this->db->query($sql);
    return $query->num_rows;
  }





  // get factura
  function get_factura($serie, $numero) {
    $this->db->where('serie', $serie);
    $this->db->where('numero', $numero);
    return $this->db->get($this->the_table);
  }

  /**
   * add new factura
   */
  function add($data){
    $data['record_version'] = date('Y-m-d H:i:s');
    $this->db->insert($this->the_table, $data);
    return $this->db->insert_id();
  }

  function add_sin_nit($data) {
    $data['record_version'] = date('Y-m-d H:i:s');
    $sql = "INSERT INTO fa_factura (serie, numero, fecha, nit, monto, nombre, cons_final, anulado, record_version) VALUES('" .
            $this->db->escape_str($data['serie']) . "', '" .
            $this->db->escape_str($data['numero']) . "', '" .
            $this->db->escape_str($data['fecha']) . "', " .
            "null, " .
            //$this->db->escape_str($data['nit']) . "', " .
            $this->db->escape_str($data['monto']) . ", '" .
            $this->db->escape_str($data['nombre']) . "', '" .
            $this->db->escape_str($data['cons_final']) . "', '" .
            $this->db->escape_str($data['anulado']) . "', '" .
            $this->db->escape_str($data['record_version']) . "')";
    $this->db->query($sql);
  }

  /**
   * Update factura by id
   * @param $data
   * @param $serie
   * @param $numero
   * @return unknown_type
   */
  function update($data, $serie, $numero){
    $this->db->where('serie', $serie);
    $this->db->where('numero', $numero);
    $this->db->update($this->the_table, $data);
  }

  /**
   * delete factura by id
   */
  function delete($serie, $numero){
    $this->db->where('serie', $serie);
    $this->db->where('numero', $numero);
    $this->db->delete($this->the_table);
  }

}

