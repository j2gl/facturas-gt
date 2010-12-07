<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Facturaci&oacute;n</title>
  <link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>script/jquery-1.4.2.js"></script>
</head>
<body onload="document.form1.fecha.focus();">
<div class="content">
<h1>Agregar Factura</h1>
<?php echo $message; ?>
<form name="form1" method="post" action="<?php echo $action; ?>">
<div class="data">
<table>
<tr>
  <td width="30%">Serie - N&uacute;mero<span style="color:red;">*</span></td>
  <td>
    <input type="text" name="serie" class="text" value="<?php echo $this->validation->serie; ?>"/>
    <?php echo $this->validation->serie_error; ?>
    <input type="text" name="numero" class="text" value="<?php echo $this->validation->numero; ?>"/>
    <?php echo $this->validation->numero_error; ?>
  </td>
</tr>
<tr>
  <td valign="top">Fecha (dd-mm-yyyy)<span style="color:red;">*</span></td>
  <td>
    <input type="text" name="fecha" onclick="displayDatePicker('dob');" class="text" value="<?php echo $this->validation->fecha; ?>"/>
    <a href="javascript:void(0);" onclick="displayDatePicker('fecha');">
      <img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0" />
    </a>
  <?php echo $this->validation->fecha_error; ?>
  </td>
</tr>
<tr>
  <td valign="top">C/F<span style="color:red;">*</span></td>
  <td>
  <?php
  /*
    <input type="radio" name="cons_final" value="S" <?php echo $this->validation->set_radio('cons_final', 'S'); ?>/> Si
    <input type="radio" name="cons_final" value="N" <?php echo $this->validation->set_radio('cons_final', 'N', true); ?>/> No
    */
  ?>
    <input type="radio" name="cons_final" value="S" /> Si
    <input type="radio" name="cons_final" value="N" checked="checked" /> No
    <?php echo $this->validation->cons_final_error; ?>
  </td>
</tr>
<tr>
  <td valign="top">Nit<span style="color:red;">*</span></td>
  <td><input type="text" id="nit" name="nit" class="text" value="<?php echo $this->validation->nit; ?>"/>
  <?php echo $this->validation->nit_error; ?></td>
</tr>
<tr>
  <td valign="top">Nombre<span style="color:red;"></span></td>
  <td><input type="text" id="nombre" name="nombre" class="text" value="<?php echo $this->validation->nombre; ?>"/>
  <?php echo $this->validation->nombre_error; ?></td>
</tr>
<tr>
  <td valign="top">Monto<span style="color:red;"></span></td>
  <td><input type="text" name="monto" class="text" value="<?php echo $this->validation->monto; ?>"/>
  <?php echo $this->validation->monto_error; ?></td>
</tr>
<tr>
  <td valign="top">Anulado<span style="color:red;"></span></td>
  <td>
  <?php
  /*
    <input type="radio" name="anulado" value="S" <?php echo $this->validation->set_radio('anulado', 'S'); ?>/> Si
    <input type="radio" name="anulado" value="N" <?php echo $this->validation->set_radio('anulado', 'N', true); ?>/> No
    */
  ?>
    <input type="radio" name="anulado" value="S" /> Si
    <input type="radio" name="anulado" value="N" checked="checked" /> No
    <?php echo $this->validation->anulado_error; ?>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><input type="submit" value="Agregar"/></td>
</tr>
</table>
</div>
</form>

<br />
<?php if (isset($link_back)) echo $link_back; ?><br />
<br />
<div class="data"><?php echo $table; ?></div>
<br />
<?php if (isset($link_back)) echo $link_back; ?>
<br />
<br />
</div>
<script>
jQuery(function(){
  $("#nit").blur( function() {
    if (document.form1.nit.value.length > 0) {
      if ( isValidNit(document.form1.nit) == true) {
        $.getJSON('<?php echo site_url("facturacion/buscar_nombre") ?>/' + $("#nit").val(),
            function(data) {
              $("#nombre").val(data.nombre)
            });
      }    
      else {
        document.form1.nombre.value = "";
        alert("El nit " + $("#nit").val() + " es invalido");
        document.form1.nit.focus();
      }
    }
  });
});


function isValidNit(obj) {
  if ( (obj.value == "") || (obj == null) )
    return true;
  if (obj.value*1 == 0)
    return false;
 

  var strNit = obj.value.toUpperCase();
  strNit = strNit.replace("-","");
 
  var nit = strNit.substring(0, strNit.length - 1);
  var suma = 0;
  var pos = nit.length + 1;
  for (var c=0; c < nit.length; c++)
  {
    suma += nit.charAt(c) * pos;
    pos -= 1;
  }
  if (isNaN(suma))
      return false;
 
  var verificador = 11 - (suma % 11);
  if (verificador == 10)
      verificador = "K"
  else if (verificador == 11)
      verificador= 0;
  
  nit = nit + verificador;

  if (strNit == nit)
      return true;
  else
      return false;
}

</script>

</body>
</html>