<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Facturas</title>

<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class="content">
		<h1><?php echo $title; ?></h1>
		<div class="data">
		<table>
			<tr>
				<td width="30%">NIT</td>
				<td><?php echo $nit->nit; ?></td>
			</tr>
			<tr>
				<td valign="top">Nombre</td>
				<td><?php echo $nit->nombre; ?></td>
			</tr>
			<tr>
				<td valign="top">&Uacute;ltima Modificaci&oacute;n</td>
				<td><?php echo date('d-m-Y H:i:s', strtotime($nit->record_version)); ?></td>
			</tr>
		</table>
		</div>
		<br />
		<?php echo $link_back; ?>
	</div>
</body>
</html>