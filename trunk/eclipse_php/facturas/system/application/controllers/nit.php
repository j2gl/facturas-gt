<?php

class Nit extends Controller {

  // num of records per page
  private $limit = 100;
  private $dateFormatDataBase = 'Y-m-d H:i:s';
  
  function Nit() {
    parent::Controller();
    
    // load library
    $this->load->library(array('table','validation'));
    
    // load helper
    $this->load->helper('url');
    
    // load model
    $this->load->model('nit_model', '', TRUE);
  }

//  function index($offset = 0) {
//    echo "Hello world!";
//  }
    
  function index($offset = 0) {
        // offset
    $uri_segment = 3;
    $offset = $this->uri->segment($uri_segment);
    
    // load data
    $nits = $this->nit_model->get_paged_list($this->limit, $offset)->result();
    
    // generate pagination
    $this->load->library('pagination');
    $config['base_url'] = site_url('nit/index/');
    $config['total_rows'] = $this->nit_model->count_all();
    $config['per_page'] = $this->limit;
    $config['uri_segment'] = $uri_segment;
    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();
    
    // generate table data
    $this->load->library('table');
    $this->table->set_empty("&nbsp;");
    $this->table->set_heading('No	', 'Nit', 'Nombre', 'Fecha del Registro', '');
    $i = 0 + $offset;
    
    foreach ($nits as $nit){
      $this->table->add_row(++$i, $nit->nit, $nit->nombre, date('d-m-Y',strtotime($nit->record_version)),
      	anchor('nit/view/'.$nit->nit,'view',array('class'=>'view')).' '.
      	anchor('nit/update/'.$nit->nit,'update',array('class'=>'update')).' '.
      	anchor('nit/delete/'.$nit->nit,'delete',array('class'=>'delete','onclick'=>"return confirm('Esta seguro de borrar este nit?')"))
      );
    }
    $data['table'] = $this->table->generate();
    
    // load view
    $this->load->view('nitList', $data);
  }

  function add() {
    // set validation properties
    $this->_set_fields();
    
    // set common properties
    $data['title'] = 'Agregar nuevo nit';
    $data['message'] = '';
    $data['action'] = site_url('nit/addNit');
    $data['link_back'] = anchor('nit/index/', 'Regresar a listado de nits', array('class'=>'back'));
    
    // load view
    $this->load->view('nitEdit', $data);
  }

  function addNit() {
    // set common properties
    $data['title'] = 'Agregar nuevo nit';
    $data['action'] = site_url('nit/addNit');
    $data['link_back'] = anchor('nit/index/', 'Regresar a listado de nits' , array('class'=>'back'));
    
    // set validation properties
    $this->_set_fields();
    $this->_set_rules();
    
    // run validation
    if ($this->validation->run() == FALSE) {
      $data['message'] = '';
    }
    else {
      // save data
      $registro = array(
              'nit' => $this->input->post('nit'),
      				'nombre' => $this->input->post('nombre'),
      				'record_version' => date($this->dateFormatDataBase)
      );
      
      $nit = $this->nit_model->add($registro);
      
      // set user message
      $data['message'] = '<div class="success">Se agrego nuevo nit.</div>';
    }
    
    // load view
    $this->load->view('nitEdit', $data);
  }

  function view($id){
    // set common properties
    $data['title'] = 'Nit';
    $data['link_back'] = anchor('nit/index/','Regresar a listado de nits',array('class'=>'back'));
    
    // get nit details
    $data['nit'] = $this->nit_model->get_by_id($id)->row();
    
    // load view
    $this->load->view('nitView', $data);
  }
  
  
  function update($id){
    // set validation properties
    $this->_set_fields();
    
    // prefill form values
    $registro = $this->nit_model->get_by_id($id)->row();
    if (!empty($registro)) {
      $this->validation->nit = $id;
      $this->validation->nombre = $registro->nombre;
      $this->validation->record_version = date($this->dateFormatDataBase, strtotime($registro->record_version));
      $data['message'] = '';
    }
    else {
      $this->validation->nit = '';
      $this->validation->nombre = '';
      $this->validation->record_version = '';
      $data['message'] = '<div class="error">No existe el nit' . $id . '</div><br/>';      
    }
    // set common properties
    $data['title'] = 'Actualizar Nit';
    
    $data['action'] = site_url('nit/updateNit');
    $data['link_back'] = anchor('nit/index/','Regresar a listado de nits',array('class'=>'back'));
    
    // load view
    $this->load->view('nitEdit', $data);
  }

  function updateNit(){
    // set common properties
    $data['title'] = 'Actualizar Nit';
    $data['action'] = site_url('nit/updateNit');
    $data['link_back'] = anchor('nit/index/','Regresar a listado de nits',array('class'=>'back'));
    
    // set validation properties
    $this->_set_fields();
    $this->_set_rules();
    
    // run validation
    if ($this->validation->run() == FALSE) {
  	  $data['message'] = '';
    }
    else {
      // save data
      $id = $this->input->post('nit');
      $registro = array('nombre' => $this->input->post('nombre'),
                        'record_version' => date($this->dateFormatDataBase, strtotime($this->input->post('record_version'))), 
						'nit' => $id);
      $this->nit_model->update($id,$registro);
      
      // set user message
      $data['message'] = '<div class="success">Actualizado con exito</div>';
    }
    
    // load view
    $this->load->view('nitEdit', $data);
  }

  
	function delete($id){
		// delete person
		$this->nit_model->delete($id);

		// redirect to person list page
		redirect('nit/index/','refresh');
	}

	// validation fields
	function _set_fields(){
		$fields['nit'] = 'nit';
		$fields['nombre'] = 'nombre';
		$fields['record_version'] = 'record_version';

		$this->validation->set_fields($fields);
	}

	// validation rules
	function _set_rules(){
		$rules['nit'] = 'trim|required';
		$rules['nombre'] = 'trim|required';
		$rules['record_version'] = 'trim|required|callback_valid_date';

		$this->validation->set_rules($rules);

		$this->validation->set_message('required', '* requerido');
		$this->validation->set_message('isset', '* requerido');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}
/*
	// date_validation callback
	function valid_date($str)
	{
		if(!ereg("^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$", $str))
		{
			$this->validation->set_message('valid_date', 'date format is not valid. dd-mm-yyyy');
			return false;
		}
		else
		{
			return true;
		}
	}*/

}
