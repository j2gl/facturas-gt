<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Facturas</title>
  <link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
</head>
<body>
	<div class="content">
		<h1><?php echo $title; ?></h1>
		<?php echo $message; ?>
		<form method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="record_version" value="<?php echo date('Y-m-d H:i:s', strtotime($this->validation->record_version)); ?>" />
		<div class="data">
		<table>
			<tr>
				<td width="30%">Nit<span style="color:red;">*</span></td>
				<td>
				  <input type="text" name="nit" class="text" value="<?php echo $this->validation->nit; ?>"/>				  
				</td>
				  
			</tr>
			<tr>
				<td valign="top">Nombre<span style="color:red;">*</span></td>
				<td><input type="text" name="nombre" class="text" value="<?php echo $this->validation->nombre; ?>"/>
				<?php echo $this->validation->nombre_error; ?></td>
			</tr>
<?php
/* 			
			<tr>
				<td valign="top">Fecha (dd-mm-yyyy)<span style="color:red;">*</span></td>
				<td>
					<input type="text" name="record_version" onclick="displayDatePicker('dob');" class="text" value="<?php echo $this->validation->record_version; ?>"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('record_version');">
						<img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0" />
					</a>
				    <?php echo $this->validation->record_version_error; ?>
				</td>
			</tr>
*/			
?>			
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Grabar"/></td>
			</tr>
		</table>
		</div>
		</form>
		<br />
		<?php echo $link_back; ?>
	</div>
</body>
</html>