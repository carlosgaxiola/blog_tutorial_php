<h1>Subir archivo</h1>
<?php 
	echo $form->create('Subida', array('type' => 'file'));
	echo $form->input('archivo',array('type'=>'file'));
	echo $form->end('Save');	
?>