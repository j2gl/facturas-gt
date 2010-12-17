<?php
class Facturacion extends Controller {

  private $limit = 30;

  //private $limit_add_factura = 100;

  function Facturacion() {
    parent::Controller();

    // load library
    $this->load->library(array('table','validation'));

    // load helper
    $this->load->helper('url');

    // load model
    $this->load->model('factura_model', '', TRUE);
    $this->load->model('nit_model', '', TRUE);
  }

  function buscar_nombre($nit) {
  	$data = $this->nit_model->get_by_id($nit);

  	if ($data->num_rows == 0) {
  	  $result->nombre = '';
  	}
  	else {
  		$data = $data->result();
  		$result->nombre = $data[0]->nombre;
  	}
  	$this->output->set_output(json_encode($result));

  }

  function index($offset = 0) {
    // offset
    $uri_segment = 3;
    $offset = $this->uri->segment($uri_segment);

    // load data
    //$facturas = $this->factura_model->get_paged_list($this->limit, $offset)->result();

    // generate pagination
    $this->load->library('pagination');
    $config['base_url'] = site_url('facturacion/index/');
    $config['total_rows'] = $this->factura_model->count_all();
    $config['per_page'] = $this->limit;
    $config['uri_segment'] = $uri_segment;
    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();

    $data['table'] = $this->_generate_facturas_table($offset);

    // load view
    $this->load->view('facturasList', $data);
  }


  function _generate_summary_table($offset = 0) {

    // load data
    $result_set = $this->factura_model->get_quarter_summary_paged_list($this->limit, $offset)->result();

    // generate table data
    $this->load->library('table');
    $this->table->set_empty("&nbsp;");
    $this->table->set_heading('A&ntilde;o', 'Trimestre', 'Total');

    $i = 0;
    foreach ($result_set as $row){
      $this->table->add_row(
        $row->year,
        $row->quarter,
        $row->monto
      );
    }
    return $this->table->generate();
  }

  function show_summary($offset = 0) {
    // offset
    $uri_segment = 3;
    $offset = $this->uri->segment($uri_segment);

    // generate pagination
    $this->load->library('pagination');
    $config['base_url'] = site_url('facturacion/show_summary/');
    $config['total_rows'] = $this->factura_model->count_quarter_summary_paged_list();
    $config['per_page'] = $this->limit;
    $config['uri_segment'] = $uri_segment;
    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();

    $data['table'] = $this->_generate_summary_table($offset);

    $data['link_back'] = anchor('facturacion/index/','Regresar a listado de facturas', array('class'=>'link'));

    // load view
    $this->load->view('facturasSummary', $data);
  }

  // validation fields
  function _set_fields() {
    $fields['serie'] = 'serie';
    $fields['numero'] = 'numero';
    $fields['fecha'] = 'fecha';
    $fields['nit'] = 'nit';
    $fields['monto'] = 'monto';
    $fields['nombre'] = 'nombre';
    $fields['cons_final'] = 'C/F';
    $fields['anulado'] = 'anulado';
    $fields['record_version'] = 'record_version';

    $this->validation->set_fields($fields);
  }


  function _set_general_rules() {
    $rules['serie'] = 'trim';
    $rules['numero'] = 'trim|required|numeric|is_natural_no_zero';
    $rules['fecha'] = 'trim|required|callback_valid_date';
    $rules['nit'] = 'trim';
    $rules['monto'] = 'numeric';
    $rules['nombre'] = 'trim';
    $rules['cons_final'] = 'required';
    $rules['anulado'] = 'required';
    return $rules;
  }
  // validation rules
  function _set_rules() {
    $rules = $this->_set_general_rules();
    $this->validation->set_rules($rules);

    $this->validation->set_message('required', '* requerido');
    $this->validation->set_message('isset', '* requerido');
    $this->validation->set_error_delimiters('<p class="error">', '</p>');
  }

  // date_validation callback
  function valid_date($str) {
    //if(!ereg("^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$", $str)) {
	if(!preg_match("/^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$/", $str)) {
      $this->validation->set_message('valid_date', 'Formato de la fecha no es valido. dd-mm-yyyy');
      return false;
    }
    else {
      return true;
    }
  }

  function _generate_facturas_table($offset = 0) {

    // load data
    $facturas = $this->factura_model->get_paged_list($this->limit, $offset)->result();

    // generate table data
    $this->load->library('table');
    $this->table->set_empty("&nbsp;");
    $this->table->set_heading('Serie', 'No.', 'Nit', 'Fecha', 'Nombre', 'Monto', 'C/F', 'Anulado', '');

    $i = 0;
    foreach ($facturas as $factura){
      $this->table->add_row(
        $factura->serie,
        $factura->numero,
        $factura->nit,
        date('d-m-Y',strtotime($factura->fecha)),
        $factura->nombre,
        $factura->monto,
        $factura->cons_final,
        $factura->anulado,
        anchor('facturacion/view/'.$factura->nit,'view',array('class'=>'view')).' '.
        anchor('facturacion/update/'.$factura->nit,'update',array('class'=>'update')).' '.
        anchor('facturacion/delete/' . $factura->serie . '/' .$factura->numero,'delete',
          array('class'=>'delete','onclick'=>"return confirm('Esta seguro de borrar la factura $factura->serie - $factura->numero?')"))
      );
    }
    return $this->table->generate();
  }

  function add($message = '') {
    $this->_set_fields();

    // prefill form values
    $registro = $this->factura_model->get_last_factura()->row();
    $this->validation->serie = $registro->serie;
    $this->validation->numero = $registro->numero + 1;
    $this->validation->fecha = date('d-m-Y',strtotime($registro->fecha));
    $this->validation->cons_final = true;
    $this->validation->anulado = 'S';
    $this->validation->cons_final = 'S';
    $this->validation->monto = 0.00;
    $this->input->post('cons_final');
    $data['action'] = site_url('facturacion/add_buscar_nit');
    $data['message'] = $message;
    $data['link_back'] = anchor('facturacion/index/', 'Regresar a listado.', array('class'=>'back'));
    $data['table'] = $this->_generate_facturas_table();

    // load view
    $this->load->view('facturasAdd', $data);

  }


  /**
   * Search the nit
   * @return unknown_type
   */
  function add_buscar_nit() {

    // set validation properties
    $this->_set_fields();
    $this->_set_rules();

    $rules = $this->_set_general_rules();

    $cons_final = ($this->input->post('cons_final') == 'S' ? true : false);
    $anulado = ($this->input->post('anulado') == 'S' ? true : false);

    if ( ($cons_final == false) && ($anulado == false) ) {
      $rules['nit'] = 'trim|required';
    }
    else {
      $rules['nit'] = 'trim';
    }
    //var_dump($rules);
    $this->validation->set_rules($rules);

    // run validation
    if ($this->validation->run() == FALSE) {
      $data['message'] = '';
      $data['action'] = site_url('facturacion/add_buscar_nit');
      $data['link_back'] = anchor('facturacion/index/', 'Regresar a listado.', array('class'=>'back'));
      $data['table'] = $this->_generate_facturas_table();
      $this->load->view('facturasAdd', $data);
    }
    else {

      // Busca el nit en la base de datos
      $id = $this->input->post('nit');
      $registro_nit = $this->nit_model->get_by_id($id)->row();

      if ( (empty($registro_nit)) &&  ($anulado == false) && ($cons_final == false) ){
        //validation fields
        $fields['nit'] = 'nit';
        $fields['nombre'] = 'nombre';
        $fields['record_version'] = 'record_version';
        $this->validation->set_fields($fields);

        // set common properties
        $data['title'] = 'Agregar nuevo nit';
        $data['message'] = '';
        $data['action'] = site_url('nit/addNit');
        $data['link_back'] = anchor('facturacion/add/', 'Regresar' , array('class'=>'back'));

        // load view
        $this->load->view('nitEdit', $data);

      }
      else {
        $this->add_factura();
        /*
        $this->_set_fields();
        $data['message'] = '';
        $data['action'] = site_url('facturacion/add_factura');
        $data['link_back'] = anchor('facturacion/index/', 'Regresar a listado.', array('class'=>'back'));

        // prefill form values
        $this->validation->serie = $this->input->post('serie');
        $this->validation->numero = $this->input->post('numero');
        $this->validation->fecha = $this->input->post('fecha');
        $this->validation->cons_final = true;
        $this->validation->nit = $id;
        if (!empty($registro_nit)) {
          $this->validation->nombre = $registro_nit->nombre;
        }
        $this->validation->monto = 0.00;

        $this->load->view('facturasAddResto', $data);
        */
      }
    }
  }

  function add_factura() {

    $id = $this->input->post('nit');


    // set validation properties
    $this->_set_fields();
    $this->_set_rules();

    $rules = $this->_set_general_rules();

    $cons_final = ($this->input->post('cons_final') == 'S' ? true : false);
    $anulado = ($this->input->post('anulado') == 'S' ? true : false);

    if ( ($cons_final == false) && ($anulado == false) ) {
      $rules['nit'] = 'trim|required';
    }
    else {
      $rules['nit'] = 'trim';
    }
    $this->validation->set_rules($rules);

    // run validation
    if ($this->validation->run() == FALSE) {
      $data['message'] = '';
      $data['action'] = site_url('facturacion/add_buscar_nit');
      $data['link_back'] = anchor('facturacion/index/', 'Regresar a listado.', array('class'=>'back'));
      $this->load->view('facturasAddResto', $data);
    }
    else {
      // save data
      $anulado = ($this->input->post('anulado') == 'S' ? true : false);
      $cons_final = ($this->input->post('cons_final') == 'S' ? true : false);
      $monto = $this->input->post('monto');

      $nit = $this->input->post('nit');
      $nombre = $this->input->post('nombre');

      /*if ($cons_final == true) {
        $nombre = 'C/F';
      }
      else {

      }*/

      // Valores cundo la factura esta anulada.
      if ($anulado == true) {
        $monto = 0.0;
        $nombre = 'anulado';
        $cons_final = false;
      }


      $registro = array(
        'serie' => $this->input->post('serie'),
        'numero' => $this->input->post('numero'),
        'fecha' => date('Y-m-d', strtotime($this->input->post('fecha'))),
        'nit' => $nit,
        'monto' => $monto,
        'nombre' => $nombre,
        'cons_final' => $cons_final,
        'anulado' => $anulado
        //'record_version' => date('Y-m-d H:i:s')
      );

      //var_dump($registro);
      if ( empty( $nit ) == true  ) {
        $this->factura_model->add_sin_nit($registro);
        //echo "ADD factura con nit...<br />";
      }
      else {
      	$this->factura_model->add($registro);
      //echo "ADD Factura sin nit...<br />";
        //var_dump($registro);
      }


      $msg = '<div class="success">Se agrego la factura ' . $registro['serie'] . ' ' . $registro['numero'] .  '.</div>';
      $this->add($msg);
      // set user message
      /*$data['message'] = '<div class="success">Se agrego la factura ' . $registro['serie'] . ' ' . $registro['numero'] .  '.</div>';
      $data['action'] = site_url('facturacion/add_buscar_nit');
      $data['link_otro'] = anchor('facturacion/add/', 'Ingresar otra factura.', array('class'=>'back'));
      $data['link_back'] = anchor('facturacion/index/', 'Regresar a listado.', array('class'=>'back'));
      $this->load->view('facturasAddResto', $data);*/

    }


  }

  function delete($serie, $numero){
    // delete person
    $this->factura_model->delete($serie, $numero);

    // redirect to person list page
    redirect('facturacion/index/','refresh');
  }


}
