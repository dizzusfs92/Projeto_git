<?php 
	require_once('database.php');
	
	$conn = open_database_login();

	$email = $_POST['inputEmail'];
	$senha = ($_POST['inputPassword']);

	$query = mysqli_query($conn,"SELECT * FROM login WHERE email = '$email' AND senha = '$senha'");
	$resultado = $query->fetch_assoc();
	$row = mysqli_num_rows($query);

	if ($row > 0){		
		if(isset($_POST["lembrarUsuarioHidden"])){
			$inputLembrar = $_POST["lembrarUsuarioHidden"];
			if($inputLembrar == "lembrarUsuario"){
				setcookie("email",$email);
				setcookie("senha", $senha);
			}
			elseif($inputLembrar == "naoLembrar"){
				 setcookie("email");
				 setcookie("senha");
			}
		}
	
		session_start();
		$_SESSION['email'] = $_POST['inputEmail'];
		$_SESSION['senha'] = $_POST['inputPassword'];
		//header('Location: MenuPrincipal.php');
		header('Location: Revendas/index.php');
	}else{
		header('Location: index.php?msg=1');
	}
?>