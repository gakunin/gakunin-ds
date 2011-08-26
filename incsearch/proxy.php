<?php
	// Website url to open
	if(isset($_POST['jsonURL'])) {  
		$daurl = $_POST['jsonURL'];  
		$html = file_get_contents ($daurl);
		echo $html;
	}
?>
