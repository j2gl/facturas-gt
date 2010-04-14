<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Facturaci&oacute;n</title>
  <link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
</head>
<body>
<div class="content">
<h1>Agregar Factura</h1>
  
<form method="post" action="<?php echo $action; ?>">
<div class="data">
<table>
<tr>
  <td width="30%">Serie - N&uacute;mero<span style="color:red;">*</span></td>
  <td>
    <input type="text" name="serie" class="text" value="<?php echo $this->validation->serie; ?>"/>
    <input type="text" name="serie" class="text" value="<?php echo $this->validation->numero; ?>"/>
  </td>  
</tr>
<tr>
  <td valign="top">Fecha (dd-mm-yyyy)<span style="color:red;">*</span></td>
  <td>
    <input type="text" name="record_version" onclick="displayDatePicker('dob');" class="text" value="<?php echo $this->validation->fecha; ?>"/>
    <a href="javascript:void(0);" onclick="displayDatePicker('record_version');">
      <img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0" />
    </a>
  <?php echo $this->validation->fecha_error; ?>
  </td>
</tr>
<tr>
  <td valign="top">C/F<span style="color:red;">*</span></td>
  <td>
    <input type="radio" name="cons_final" value="true" <?php echo $this->validation->set_radio('cons_final', 'true'); ?>/> Si
    <input type="radio" name="cons_final" value="false" <?php echo $this->validation->set_radio('cons_final', 'false', true); ?>/> No     
    <?php echo $this->validation->cons_final_error; ?>
  </td>
</tr> 
<tr>
  <td valign="top">Nit<span style="color:red;">*</span></td>
  <td><input type="text" name="nit" class="text" value="<?php echo $this->validation->nit; ?>"/>
  <?php echo $this->validation->nit_error; ?></td>
</tr>
<tr>
  <td valign="top">Nombre<span style="color:red;">*</span></td>
  <td><input type="text" name="nombre" class="text" value="<?php echo $this->validation->nombre; ?>"/>
  <?php echo $this->validation->nombre_error; ?></td>
</tr>
<tr>
  <td valign="top">Monto<span style="color:red;">*</span></td>
  <td><input type="text" name="monto" class="text" value="<?php echo $this->validation->monto; ?>"/>
  <?php echo $this->validation->monto_error; ?></td>
</tr>
<tr>
  <td valign="top">Anulado<span style="color:red;">*</span></td>
  <td>
    <input type="radio" name="anulado" value="true" <?php echo $this->validation->set_radio('anulado', true); ?>/> Si
    <input type="radio" name="anulado" value="false" <?php echo $this->validation->set_radio('anulado', false); ?>/> No
    <?php echo $this->validation->anulado_error; ?>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><input type="submit" value="Grabar"/></td>
</tr>
</table>
</div>
</form>
  <div class="paging"><?php echo $pagination; ?></div>
  <div class="data"><?php echo $table; ?></div>
	<br />
  <?php echo anchor('nit/add/','agregar registro',array('class'=>'add')); ?>
		
</div>
</body>
</html>