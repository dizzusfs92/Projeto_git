

<?php

/** O nome do banco de dados*/
define('DB_NAME', 'AppOnePortaria');
/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');
/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'MdpGdxfm');
/** nome do host do MySQL */
define('DB_HOST', 'localhost');
	
	
define('ABSPATH',  '/var/www/html/');
	
/** caminho no server para o sistema **/
/** if ( !defined('BASEURL') )*/

define('BASEURL', '../');
define('BASEURLLOGIN','http://201.73.1.105/index.php');
	
/** caminho do arquivo de banco de dados 
if ( !defined('DBAPI') )**/
	define('DBAPI','../inc/database.php');

/** caminhos dos templates de header e footer **/
define('HEADER_TEMPLATE', '../inc/header.php');
define('FOOTER_TEMPLATE', '../inc/footer.php');

	
?>
