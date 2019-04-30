<?php session_start();
	$dir_atual = getcwd();
	
	if($_SESSION['email'] == null AND $dir_atual != '/var/www/html/MenuPrincipal.php'){
		header('Location: ../index.php');
	}
	elseif($_SESSION['email'] == null){
		header('Location: index.php');
	}?>

	<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>One POrtaria</title>
    <link rel="shortcut icon" href="<?php echo BASEURL; ?>icone.jpg">

    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASEURL; ?>css/bootstrapModificadoHR.css">
    <link href="<?php echo BASEURL; ?>css/build.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.js"></script>
    <script src="<?php echo BASEURL; ?>js/spin.min.js"></script>

    <link href="<?php echo BASEURL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/build.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    
    <link href="<?php echo BASEURL; ?>css/select2.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/select2-bootstrap.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/bootstrap-treeview.min.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/sb-admin.css" rel="stylesheet">
    <link href="<?php echo BASEURL; ?>css/star-rating.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <style type="text/css" id="treeEntrada-style">
        .treeview .list-group-item {
            cursor: pointer
        }

        .treeview span.indent {
            margin-left: 10px;
            margin-right: 10px
        }

        .treeview span.icon {
            width: 12px;
            margin-right: 5px
        }

        .treeview .node-disabled {
            color: silver;
            cursor: not-allowed
        }

        .node-treeEntrada {}

        .node-treeEntrada:not(.node-disabled):hover {
            background-color: #F5F5F5;
        }
    </style>
    <style type="text/css" id="treeSaida-style">
        .treeview .list-group-item {
            cursor: pointer
        }

        .treeview span.indent {
            margin-left: 10px;
            margin-right: 10px
        }

        .treeview span.icon {
            width: 12px;
            margin-right: 5px
        }

        .treeview .node-disabled {
            color: silver;
            cursor: not-allowed
        }

        .node-treeSaida {}

        .node-treeSaida:not(.node-disabled):hover {
            background-color: #F5F5F5;
        }
    </style>
</head>


<body style="font-family: Calibri;">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0; <?php if(($plano == 'Gratuito' AND $intervalo->days <= 7) OR $plano == 'Expirado' OR ($plano == 'Mensal' AND $intervalo->days <= 7) OR ($plano == 'Anual' AND $intervalo->days <= 7) OR $plano == 'ExpiradoAnual' OR $plano == 'ExpiradoMensal'): echo('top:40px;'); endif; ?>">




        <div class="navbar-header">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
   </button>
            
            <a class="navbar-brand" href="MenuPrincipal.php"><img src="<?php echo BASEURL; ?>logo.jpg" height="44" width="66" style="position:relative; top:-10px;"></a>
            
        </div>
        <ul class="nav navbar-top-links navbar-right">
            
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="perfilUsuario.php"><i class="fa fa-user fa-fw"></i>Perfil do Usuário</a>
                    </li>
                    <li><a href="<?php echo BASEURL; ?>Configuracoes"><i class="fa fa-gear fa-fw"></i>Configurações</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo BASEURLLOGIN; ?>?encerrarSessao=ok"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <div class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav in" id="side-menu">
                    <li><a href="<?php echo BASEURL; ?>Revendas"><i class="fa fa-dashboard"></i> Revendas</a></li>                    
                    <li><a href="<?php echo BASEURL; ?>index.php?encerrarSessao=ok"><i class="fa fa-sign-out"></i> Sair</a></li>
                </ul>
            </div>
        </div>


    </nav>
    <div id="page-wrapper">

        <br>

        

		
		
		
			

           




        

           