<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Facturaci&oacute;n</title>

<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class="content">
		<h1>Facturas</h1>
    <?php echo anchor('nit/','Nits',array('class'=>'link')); ?> -
    <?php echo anchor('facturacion/show_summary/','Ver Resumen',array('class'=>'link')); ?><br /><br />
		<?php echo anchor('facturacion/add/','agregar registro',array('class'=>'add')); ?>
		<br />
		<br />
		<div class="paging"><?php echo $pagination; ?></div>
		<div class="data"><?php echo $table; ?></div>
		<br />
		<?php echo anchor('facturacion/add/','agregar registro',array('class'=>'add')); ?>
	</div>
</body>
</html>