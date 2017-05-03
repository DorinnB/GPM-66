<?php

//creation de User si le cookie est présent et correct
if (isset($_COOKIE['id_user']) AND isset($_COOKIE['password']))	{
		$user->shortlogin($_COOKIE['id_user'],$_COOKIE['password'],'true');
}


// Affichage de la page html
include 'views/navbar-view.php';


//affichage du user si le cookie etait BadFunctionCallException
if($user->is_loggedin()){
	echo '<script>	document.getElementById("user").innerHTML = "'.$user->getTechnicien().'";	</script>';
}
