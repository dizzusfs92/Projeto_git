<html><head>
<style>
body, html {
  height: 100%;
  margin: 0;
  font: 400 15px/1.8 "Lato", sans-serif;
  color: #777;
}

.bgimg-1, .bgimg-2, .bgimg-3 {
  position: relative;
  opacity: 1;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;

}
.bgimg-1 {
  background-image: url("background.jpg");
  height: 100%;
}

</style>


</body></html>

<?php require_once 'database.php'; ?>


<?php
	if(isset($_GET['msg'])){
		$msg = $_GET['msg'];
	}
	
	if(isset($_GET['encerrarSessao'])){
	
		unset($_SESSION['email']);
		
	}
 ?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Meu Sistema Teste</title>
	<link rel="shortcut icon" href="icone.jpg">	
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrapModificadoHR.css">
	<link href="css/build.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css" />
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.js"></script>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')</script>
	<script src="js/star-rating.js"></script>

    <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/bootstrap-datepicker.pt-BR.min.js"></script>
	<script src="js/bootstrap-treeview.min.js"></script>

   
	<script src="js/select2.js"></script>
	<script src="js/jquery.maskMoney.min.js"></script>
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/jspdf.min.js"></script>
	<script src="js/jspdf.plugin.autotable.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
	<script src="js/jquery.metisMenu.js"></script>
	<script src="js/sb-admin.js"></script>
	
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
	
</head>

<body style=" background: white; background-size: contain; font-family: Calibri;">


<main>

	<link rel="stylesheet" href="css/login-fundoPrata.css">
	
	<br>
	<div class="bgimg-1">
	<div id="login-dialog">
		<br>
		<div id="login" class="login box" aria-hidden="false">
			<form action="autenticar.php" method="post" class="form-login">
				<center><img src="logo1.png" height="77px" width="154px" ></center>
				<!-- <div class="container" style=" position:relative;margin-left: auto; margin-right: auto;"> -->
				<br>		
				<!-- <h3 class="form-login-heading" style="text-align: center ">Entre com o usuário</h3> -->
				<label for="inputEmail" class="sr-only">E-mail</label>
				<input type="email" id="inputEmail" style=" position:relative;margin-left: auto; margin-right: auto;" name="inputEmail" class="form-control" placeholder="Email" value="<?php if(isset($_COOKIE['email'])){ echo $_COOKIE['email']; }; ?>" required autofocus>
				<label for="inputPassword" class="sr-only">Senha</label>
				<input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Senha" value="<?php if(isset($_COOKIE['senha'])){ echo $_COOKIE['senha']; }; ?>" required>
				<div class="checkbox checkbox-primary" style="text-align: center;">
					<input id="lembrarUsuario" name="lembrarUsuario" type="checkbox" value=<?php if(isset($_COOKIE['senha'])){ echo "lembrarUsuario"; } else{ echo "naoLembrar";}; ?> <?php if(isset($_COOKIE['senha'])){ echo 'checked'; }; ?>><label for="lembrarUsuario">Lembrar Usuário</label>
					<input id='lembrarUsuarioHidden' type='hidden' value=<?php if(isset($_COOKIE['senha'])){ echo "lembrarUsuario"; } else{ echo "naoLembrar";}; ?> name='lembrarUsuarioHidden'>
				</div>
				<hr/>
				<div style="text-align: center"><a href="esqueceuLogin.php" class="btn btn-sm btn-warning btn-block" style="color:#fff;">Esqueceu a senha? </a>  </div>
				<!--<hr class="divider">-->
				<br>
				<button type="submit" class="btn btn-lg btn-danger btn-block">Entrar</button>
				

			</form>
			<center>Ainda não tem uma conta? <a  href="criarConta.php">Crie sua conta</a></center>
		</div>
	</div>
	</div>	
	
	</main> <!-- /container -->
</body>
</html>

<script type="text/javascript">

$('#lembrarUsuario').click(function() {
	
		if ($( 'input[id="lembrarUsuario"]:checked' ).val()){
			document.getElementById('lembrarUsuario').value = 'lembrarUsuario';
			document.getElementById('lembrarUsuarioHidden').value = 'lembrarUsuario';
			
			
			console.log(document.getElementById('lembrarUsuario').value);
			console.log(document.getElementById('lembrarUsuarioHidden').value);
		}
		else{
			document.getElementById('lembrarUsuario').value = 'naoLembrar';
			document.getElementById('lembrarUsuarioHidden').value = 'naoLembrar';
			
			console.log(document.getElementById('lembrarUsuario').value);
			console.log(document.getElementById('lembrarUsuarioHidden').value);
		}
		
})

var mensagem = <?php echo json_encode($msg); ?>;
if(mensagem == 1){
	swal({
  title: 'Usuário ou Senha Incorreto',
  text: 'Tente Novamente!',
  type: 'error',
  confirmButtonText: 'Ok'
});
	
	
}




</script>