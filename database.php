<?php

/** O nome do banco de dados*/
define('DB_NAME_LOGIN', 'AppOnePortaria');
/** Usuário do banco de dados MySQL */
define('DB_USER_LOGIN', 'root');
/** Senha do banco de dados MySQL */
define('DB_PASSWORD_LOGIN', 'MdpGdxfm');
/** nome do host do MySQL */
define('DB_HOST_LOGIN', 'localhost');

/** O nome do banco de dados*/
define('DB_NAME', 'AppOnePortaria');
/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');
/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'MdpGdxfm');
/** nome do host do MySQL */
define('DB_HOST', 'localhost');

session_start();
mysqli_report(MYSQLI_REPORT_STRICT);

function open_database() {
	try {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		mysqli_set_charset($conn,"utf8");
		return $conn;
	} catch (Exception $e) {
		echo $e->getMessage();
		return null;
	}
}
function open_database_login() {
	try {
		$conn = new mysqli(DB_HOST_LOGIN, DB_USER_LOGIN, DB_PASSWORD_LOGIN, DB_NAME_LOGIN);
		mysqli_set_charset($conn,"utf8");
		return $conn;
	} catch (Exception $e) {
		echo $e->getMessage();
		return null;
	}
}

function close_database($conn) {
	try {
		mysqli_close($conn);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function find_view($table,$id){
	//print_r($id);
	$database = open_database();
	$found = null;
	if($table == 'Movimentacao'){
		$sql = "SELECT Movimentacao.*,IF(Movimentacao.id_categoria is not NULL,(SELECT SubCategoria.nome From SubCategoria WHERE SubCategoria.id = Movimentacao.id_categoria),'') as nome_subpc,IF(Movimentacao.pessoa is not NULL,(SELECT Favorecido.nome From Favorecido WHERE Favorecido.codigo = Movimentacao.pessoa),'') as nome_pessoa, IF(Movimentacao.id_centro_custo is not NULL,(SELECT SubCentroCusto.nome From SubCentroCusto WHERE SubCentroCusto.id = Movimentacao.id_centro_custo),'') as nome_subcc,ContaCorrente.descricao as descricao_conta FROM Movimentacao,SubCategoria,ContaCorrente WHERE Movimentacao.id_conta = ContaCorrente.codigo AND Movimentacao.codigo =".$id;
		$result = $database->query($sql);
		if ($result->num_rows > 0) {
	      $found = $result->fetch_assoc();
	    }
	}
	elseif($table == 'ContasPagarReceber'){
		//$sql = "SELECT ContasPagarReceber.*, SubCategoria.nome as nome_subcat, SubCentroCusto.nome as nome_subcc, Favorecido.nome as nome_pessoa from ContasPagarReceber,SubCategoria,SubCentroCusto,Favorecido where (ContasPagarReceber.id_categoria = SubCategoria.id AND ContasPagarReceber.id_centro_custo = SubCentroCusto.id AND ContasPagarReceber.id_pessoa = Favorecido.codigo AND ContasPagarReceber.codigo = $id);";
		$sql = "SELECT DISTINCT ContasPagarReceber.*,IF(ContasPagarReceber.id_categoria is not NULL,(SELECT SubCategoria.nome From SubCategoria WHERE SubCategoria.id = ContasPagarReceber.id_categoria),'') as nome_subcat,IF(ContasPagarReceber.id_pessoa is not NULL,(SELECT Favorecido.nome From Favorecido WHERE Favorecido.codigo = ContasPagarReceber.id_pessoa),'') as nome_pessoa, IF(ContasPagarReceber.id_centro_custo is not NULL,(SELECT SubCentroCusto.nome From SubCentroCusto WHERE SubCentroCusto.id = ContasPagarReceber.id_centro_custo),'') as nome_subcc FROM ContasPagarReceber,SubCategoria,ContaCorrente WHERE ContasPagarReceber.codigo = $id";
		$result = $database->query($sql);
		//print_r($sql);
		if ($result->num_rows > 0) {
	      $found = $result->fetch_assoc();
	    }
	}
	//close_database();
	return $found;
}

function find_consulta($id = null){
	$database = open_database();
	$found = null;
	$sql = "SELECT Movimentacao.*, ContaCorrente.descricao as descricao_conta FROM Movimentacao,ContaCorrente WHERE Movimentacao.id_conta = ContaCorrente.codigo AND Movimentacao.codigo = ".$id;
	$result = $database->query($sql);
	if ($result->num_rows > 0) {
	    $found = $result->fetch_assoc();
	}
	close_database($database);
	return $found;
}

function find( $table = null, $id = null ) {
  
	$database = open_database();
	$found = null;
	try {
	  if ($id) {
		 if($table == 'empresa'){
			$sql = "SELECT * FROM " . $table . " WHERE codigo = " . $id;
			$result = $database->query($sql);
		}
		else{
			$sql = "SELECT * FROM " . $table . " WHERE id = " . $id;
			$result = $database->query($sql);
		}
	    
	    if ($result->num_rows > 0) {
	      $found = $result->fetch_assoc();
	    }
		
	    
	  } else {
	    if($table == 'empresa'){
			$sql = "SELECT * FROM " . $table . " order by nome";
			$result = $database->query($sql);
			
			if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
		  
			
			}
			
		}
		
		
		
		
		
		else{
	    $sql = "SELECT * FROM " . $table;
	    $result = $database->query($sql);
	    
	    if ($result->num_rows > 0) {
	      $found = $result->fetch_all(MYSQLI_ASSOC);
		  
		  /*$i = 0;
		  while($i < sizeof($found)){
			  $found[i]['data'] = date("d/m/Y", strtotime($found[i]['data']));
			  $i++;
		  }*/
		  
        
        /* Metodo alternativo
        $found = array();
        while ($row = $result->fetch_assoc()) {
          array_push($found, $row);
        } */
	    }
		}
	  }
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
  }
	
	close_database($database);
	return $found;
}

function find_all( $table ) {
  return find($table);
}
function get_movimentacoesPadrao( $id_conta ) {
	$database = open_database();
	$found = null;
	$sql = "SELECT DISTINCT Movimentacao.*,IF(Movimentacao.id_categoria is NULL,'',SubCategoria.nome) as nome_subcat from Movimentacao,SubCategoria where (Movimentacao.id_categoria = SubCategoria.id AND Movimentacao.id_conta =  " . $id_conta .") OR ( Movimentacao.id_categoria IS NULL  AND Movimentacao.id_conta =  " . $id_conta .")  order by data,codigo; ";
	$result = $database->query($sql);
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	close_database($database);
	return $found;
	
}

function get_contaPadrao() {
  
	$database = open_database();
	$found = null;
	$sql = "SELECT * FROM ContaCorrente where conta_padrao = 'S' ";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	
	close_database($database);
	return $found;
	
}

function find_all_pessoas($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT * FROM " . $table . " order by nome;";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_filiais($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT * FROM " . $table . " order by descricao;";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_centroCustos($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT * FROM " . $table . " order by nome;";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_subCentroCustos($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT SubCentroCusto.* from SubCentroCusto, CentroCusto where CentroCusto.codigo=SubCentroCusto.id_CentroCusto ORDER by CentroCusto.nome, SubCentroCusto.nome";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_planoContas($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT * FROM " . $table . " order by tipo,nome;";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_planoContas_Despesas($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT * FROM " . $table . " order by tipo desc,nome;";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_subPlanoContas($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT SubCategoria.* from SubCategoria, Categoria where Categoria.codigo=SubCategoria.id_categoria ORDER by Categoria.tipo,Categoria.nome, SubCategoria.nome";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function find_all_subPlanoContas_Despesas($table){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT SubCategoria.* from SubCategoria, Categoria where Categoria.codigo=SubCategoria.id_categoria ORDER by Categoria.tipo desc,Categoria.nome, SubCategoria.nome";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
}

function save($table = null, $data = null) {
  $database = open_database();
  $columns = null;
  $values = null;
  
  if($table == 'ContaCorrente'){
	  if (array_values($data)[4] == 'S'){
		  $zerar_conta = "UPDATE " . $table . " SET conta_padrao = 'N';";
		  try {
			$database->query($zerar_conta);
			//print_r($zerar_conta);
			//$_SESSION['message'] = 'Registro atualizado com sucesso.';
			//$_SESSION['type'] = 'success';
		  } catch (Exception $e) { 
			$_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
			$_SESSION['type'] = 'danger';
			} 
		  
	  }
	  
	  //Verificar se não tem contas cadastradas, se não tiver, setar automaticamente a conta como padrão
	  
	  $select_contas = "SELECT * FROM ContaCorrente;";
	  $resultado_select_contas = $database->query($select_contas);
	  if($resultado_select_contas->num_rows == 0){
		  $data["'conta_padrao'"] = 'S';
	  }
	  
  }
  if($table == "Movimentacao"){
	  //variáveis do procedure do saldo
	  $operacao = null;
	  $valor = null;
	  $datap = null;
	  $conta = null;
  }
  
	//ESCAPAR SÍMBOLOS
   if($table == "Movimentacao"){
	 $data["'descricao'"] =  mysqli_real_escape_string($database,$data["'descricao'"]);
   }
   
  
  foreach ($data as $key => $value) {
    $columns .= trim($key, "'") . ",";
	if($table == "Movimentacao"){
		if($key == "'valor'"){
			 $valor = $data[$key];
		}
		if($key == "'data'"){
			 $datap = $data[$key];
		}
		if($key == "'id_conta'"){
			 $conta = $data[$key];
		}
		if($key == "'operacao'"){
			 $operacao = $data[$key];
		}
		
	}
	if($data[$key] == ''){
		$values .= "NULL,";
	}
	else{
    $values .= "'$value',";
	}
  }
  // remove a ultima virgula
  $columns = rtrim($columns, ',');
  $values = rtrim($values, ',');
  
  $sql = "INSERT INTO " . $table . "($columns)" . " VALUES " . "($values);";
  //print_r($sql);
  try {
	if($table == 'Movimentacao'){
		if($database->query($sql)){
			if($operacao == "Entrada"){
				//positivo
			}
			else{
				$valor = -($valor);
			}
			$sql = "CALL Atualiza_Saldo_old(0,". $valor . ",'" . $datap . "'," . $conta . ");"; 
			//print_r($sql);
			$database->query($sql);
			$_SESSION['message'] = 'Registro cadastrado com sucesso.';
			$_SESSION['type'] = 'success';
			
		}
	}
	else{
	  
		$database->query($sql);
		
		$_SESSION['message'] = 'Registro cadastrado com sucesso.';
		$_SESSION['type'] = 'success';
	}
  
  } catch (Exception $e) { 
  
    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  } 
  print_r($sql);
  close_database($database);
}

function update($table = null, $id = 0, $data = null) {
  $database = open_database();
  $items = null;
  if($table == 'ContaCorrente'){
	  if (array_values($data)[4] == 'S'){
		  $zerar_conta = "UPDATE " . $table . " SET conta_padrao = 'N';";
		  try {
			$database->query($zerar_conta);
			//print_r($zerar_conta);
			$_SESSION['message'] = 'Registro atualizado com sucesso.';
			$_SESSION['type'] = 'success';
		  } catch (Exception $e) { 
			$_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
			$_SESSION['type'] = 'danger';
			} 
		  
	  }
  }
  if($table == 'Movimentacao'){
	  $data["'descricao'"] = mysqli_real_escape_string($database,$data["'descricao'"]);
	
	if($data["'transferencia'"] == 'S'){
		
		$columns2 = null;
		$values2 = null;
		$operacao2 = null;
		$valor2 = null;
		$datap2 = null;
		$conta2 = null;
		$codigo2 = $id;
		$data_antiga2 = null;
		$valor_antigo2 = null;
		$diferenca2 = null;
		
		$sql = "SELECT * FROM Movimentacao WHERE codigo =".$data["'id_transferencia'"];
		
		if($result2 = $database->query($sql)) {
			
			$select2 = $result2->fetch_all(MYSQLI_ASSOC);
			
			$valor_antigo2 = $select2[0]['valor'];
			$data_antiga2 = $select2[0]['data'];
			$conta2 = $select2[0]['id_conta'];
			$operacao2 = $select2[0]['operacao'];
			
			 
			
			 
			 
			 $valor2 = $data["'valor'"];
			 $datap2 = $data["'data'"];
			 $codigo2 = $select2[0]['codigo'];
			 
			 
			 if ($operacao2 == 'Entrada') {
				
			
				if ($valor2 >= $valor_antigo2){
					$diferenca2 = $valor2 - $valor_antigo2;
					
				}
			
				else{
					$diferenca2 = -($valor_antigo2 - $valor2);
				}
			}
			
			else{
				if ($valor2 >= $valor_antigo2){
					$diferenca2 = $valor_antigo2 - $valor2;
					
				}
				else{
					$diferenca2 = -($valor2 - $valor_antigo2);
				}
				
			}
			
			if($operacao2 == "Entrada"){
				//positivo
			}
			else{
				$valor2 = -($valor2);
			}
			
			
			
			if($datap2 != $data_antiga2){
				$sql = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME = 'Movimentacao'";
				$ultimo_codigo = $database->query($sql);
				$ultimo_codigo = $ultimo_codigo->fetch_all(MYSQLI_ASSOC);
				$ultimo_codigo = $ultimo_codigo[0]['AUTO_INCREMENT'];
				$transferencia2 = $ultimo_codigo + 1;
				$transferencia1 = $ultimo_codigo;
				$select2[0]['id_transferencia'] = $transferencia2;
				//remover e chamar o procedure da remoção				
				$sql = "DELETE FROM " . $table . " WHERE codigo = " . $codigo2;
				
				if($database->query($sql)){
					if($operacao2 == "Entrada"){
						$valor_antigo2 = -($valor_antigo2);
					}
					
					$sql = "CALL Atualiza_Saldo_Remocao(".$codigo2.",". $valor_antigo2 . ",'" . $data_antiga2 . "'," . $conta2 . ");";
					//print_r($sql);
					
					if($database->query($sql)){
				
				
					
						//Reinserir (com código maior que todos os outros) e chamar procedure de inserção
						$select2[0]['valor'] = $data["'valor'"];
						$select2[0]['data'] = $data["'data'"];
						
						foreach($select2[0] as $key => $value){
							if($key != 'data_antiga' AND $key != 'valor_antigo' AND $key != 'codigo' AND $key != 'id_transferencia'){
								$columns2 .= trim($key, "'") . ",";
								
								if($select2[0][$key] == ''){
									$values2 .= "NULL,";
								}
								else{
									$values2 .= "'$value',";
								}
							}
						}
						
						$columns2 .= trim('id_transferencia',"'") . ",";
						$values2 .= "'$transferencia2',";
						
						$columns2 = rtrim($columns2, ',');
						$values2 = rtrim($values2, ',');
					  
						$sql = "INSERT INTO " . $table . "($columns2)" . " VALUES " . "($values2);";
						print_r($sql);
						
						if($database->query($sql)){
							
							$sql = "CALL Atualiza_Saldo_old(0,". $valor2 . ",'" . $datap2 . "'," . $conta2 . ");";
							//print_r($sql);
							
							$database->query($sql);
							$_SESSION['message'] = 'Registro atualizado com sucesso.';
							$_SESSION['type'] = 'success';
								
						}
					}
				}
			}
			
			else{
				//Somente atualizar e chamar o procedure que atualiza os saldos
				$select2[0]['valor'] = $data["'valor'"];
				 foreach ($select2[0] as $key => $value) {
					 if($key != 'id_conta' AND $key != 'operacao' AND $key != 'data_antiga' AND $key != 'valor_antigo'){
						 if($select2[0][$key] == ''){
							 $items2 .= trim($key, "'") . "=NULL,";
						 }
						 else{
							$items2 .= trim($key, "'") . "='$value',";
						}
					 }
				}
				// remove a ultima virgula
				$items2 = rtrim($items2, ',');
				$sql  = "UPDATE " . $table;
				$sql .= " SET $items2";
				$sql .= " WHERE codigo=" . $codigo2 . ";";
				//print_r($sql);
				if($database->query($sql)){
					//chamar o procedure
					$sql = "CALL Atualiza_Saldo_old(".$codigo2.",". $diferenca2 . ",'" . $datap2 . "'," . $conta2 . ");"; 
					//print_r($sql);
					$database->query($sql);
					$_SESSION['message'] = 'Registro atualizado com sucesso.';
					$_SESSION['type'] = 'success';
					
				}
		
		
			}
			 
			
		}
		
	}
	
	$columns = null;
	$values = null;
	$operacao = null;
	$valor = null;
	$datap = null;
	$conta = null;
	$codigo = $id;
	$data_antiga = null;
	$valor_antigo = null;
	$diferenca = null;
	
	
	foreach($data as $key => $value){
		if($key == "'valor'"){
			$valor = $data[$key];
		}
		if($key == "'data'"){
			$datap = $data[$key];
		}
		if($key == "'id_conta'"){
			$conta = $data[$key];
		}
		if($key == "'operacao'"){
			$operacao = $data[$key];
		}
		if($key == "'valor_antigo'"){
			$valor_antigo = $data[$key];
		}
		if($key == "'data_antiga'"){
			$data_antiga = $data[$key];
		}
	}
	
	if ($operacao == 'Entrada') {
	
		if ($valor >= $valor_antigo){
			$diferenca = $valor - $valor_antigo;
			
		}
	
		else{
			$diferenca = -($valor_antigo - $valor);
		}
	}
	
	else{
		if ($valor >= $valor_antigo){
			$diferenca = $valor_antigo - $valor;
			
		}
		else{
			$diferenca = -($valor - $valor_antigo);
		}
		
	}
	
	if($operacao == "Entrada"){
		//positivo
	}
	else{
		$valor = -($valor);
	}
	
	
	if($datap != $data_antiga){
		$data["'id_transferencia'"] = $transferencia1;
	
		//remover e chamar o procedure da remoção
		
		$sql = "DELETE FROM " . $table . " WHERE codigo = " . $id;
		
		if($database->query($sql)){
			if($operacao == "Entrada"){
				$valor_antigo = -($valor_antigo);
			}
			
			$sql = "CALL Atualiza_Saldo_Remocao(".$codigo.",". $valor_antigo . ",'" . $data_antiga . "'," . $conta . ");";
			//print_r($sql);
			
			if($database->query($sql)){
		
		
			
				//Reinserir (com código maior que todos os outros) e chamar procedure de inserção
				  
				foreach($data as $key => $value){
					if($key != "'data_antiga'" AND $key != "'valor_antigo'" AND $key != "'id_transferencia'"){
						$columns .= trim($key, "'") . ",";
						
						if($data[$key] == ''){
							$values .= "NULL,";
						}
						else{
							$values .= "'$value',";
						}
					}
				}
				
				$columns .= trim('id_transferencia',"'") . ",";
				if($transferencia1 == ''){
					$values .= "NULL,";
				}
				else{
					$values .= "'$transferencia1',";
				}
				
				$columns = rtrim($columns, ',');
				$values = rtrim($values, ',');
			  
				$sql = "INSERT INTO " . $table . "($columns)" . " VALUES " . "($values);";
				print_r($sql);
				
				if($database->query($sql)){
					
					$sql = "CALL Atualiza_Saldo_old(0,". $valor . ",'" . $datap . "'," . $conta . ");";
					//print_r($sql);
					
					$database->query($sql);
					$_SESSION['message'] = 'Registro atualizado com sucesso.';
					$_SESSION['type'] = 'success';
						
				}
			}
		}
	}
	else{
		//Somente atualizar e chamar o procedure que atualiza os saldos
		 foreach ($data as $key => $value) {
			 if($key != "'id_conta'" AND $key != "'operacao'" AND $key != "'data_antiga'" AND $key != "'valor_antigo'"){
				 if($data[$key] == ''){
					 $items .= trim($key, "'") . "=NULL,";
				 }
				 else{
					$items .= trim($key, "'") . "='$value',";
				}
			 }
		}
		// remove a ultima virgula
		$items = rtrim($items, ',');
		$sql  = "UPDATE " . $table;
		$sql .= " SET $items";
		$sql .= " WHERE codigo=" . $id . ";";
		//print_r($sql);
		if($database->query($sql)){
			//chamar o procedure
			$sql = "CALL Atualiza_Saldo_old(".$codigo.",". $diferenca . ",'" . $datap . "'," . $conta . ");"; 
			//print_r($sql);
			$database->query($sql);
			$_SESSION['message'] = 'Registro atualizado com sucesso.';
			$_SESSION['type'] = 'success';
			
		}
		
		
	}
  }
  else{
	  
  
 
  
  foreach ($data as $key => $value) {
	  if($table == 'Categoria' AND $value == ''){
		  $items .= trim($key, "'") . "=NULL,";
	  }
	  elseif($table == 'ContasPagarReceber' AND ($key == "'id_contas_pagar_receber'" OR $key == "'transferencia'") ){
		  //ignorar, pois id_contas_pagar_receber e a transferencia não faz parte dos campos da tabela ContasPagarReceber
	  }
	  elseif($table == 'ContasPagarReceber' AND $value == ''){
		  $items .= trim($key, "'") . "=NULL,";
	  }
	  else{
		if($key != "'saldo_inicial_antigo'"){
			$items .= trim($key, "'") . "='$value',";
		}
	}
  }
  // remove a ultima virgula
  $items = rtrim($items, ',');
  $sql  = "UPDATE " . $table;
  $sql .= " SET $items";
  if($table == 'SubCategoria' OR $table == 'SubCentroCusto'){
	  $sql .= " WHERE id=" . $id . ";";
  }
  else{
	$sql .= " WHERE codigo=" . $id . ";";
  }
  print_r($sql);
  try {
    $database->query($sql);
    $_SESSION['message'] = 'Registro atualizado com sucesso.';
    $_SESSION['type'] = 'success';
	//print_r($database->error);
  } catch (Exception $e) { 
	//echo $e->getMessage();
    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  }
  if($table == "ContaCorrente"){
	//ATUALIZAR OS SALDOS DAS MOVIMENTAÇOES DESSA CONTA
	$saldo_inicial_antigo = null;
	$saldo = null;
	$diferenca = null;
	$codigo_conta = null;
	foreach($data as $key => $value){
		if($key == "'saldo_inicial_antigo'"){
			$saldo_inicial_antigo = $data[$key];
			$saldo_inicial_antigo = str_replace('.', '_', $saldo_inicial_antigo );
			$saldo_inicial_antigo  = str_replace(',', '.', $saldo_inicial_antigo );
			$saldo_inicial_antigo  = str_replace('_', '', $saldo_inicial_antigo );
		}
		if($key == "'saldo'"){
			$saldo = $data[$key];
		}
		
	}
	
	$diferenca = $saldo - $saldo_inicial_antigo;
			
	
	print_r($diferenca.'\n');
	print_r($saldo.'\n');
	print_r($saldo_inicial_antigo.'\n');
	
	
	$sql = "UPDATE Movimentacao set saldo = saldo + ".$diferenca." WHERE id_conta =".$id;
	$database->query($sql);
	
	  
  }
  }
  //print_r($sql);
  close_database($database);
}

function remove( $table = null, $id = null ) {
  $database = open_database();
	
  try {
    if ($id) {
	  if($table != 'SubCategoria' AND $table != 'SubCentroCusto'){
		$sql = "DELETE FROM " . $table . " WHERE codigo = " . $id;
		$result = $database->query($sql);
	  }
	  else{
		$sql = "DELETE FROM " . $table . " WHERE id = " . $id;
		$result = $database->query($sql); 
	  }
      if ($result = $database->query($sql)) {   	
        $_SESSION['message'] = "Registro Removido com Sucesso.";
        $_SESSION['type'] = 'success';
      }
    }
  } catch (Exception $e) { 
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);
}

function atualizaSaldoAposRemocao($mov_select, $id = null) {
	$database = open_database();
	$codigo = $mov_select['codigo'];
	$valor = $mov_select['valor'];
	$data = $mov_select['data'];
	$operacao = $mov_select['operacao'];
	$conta = $mov_select['id_conta'];
	
		
	try {
		if ($id) {
		  $sql = "DELETE FROM Movimentacao WHERE codigo = " . $id;
		  $result = $database->query($sql);
		  if ($result = $database->query($sql)) {   	
			$_SESSION['message'] = "Registro Removido com Sucesso.";
			$_SESSION['type'] = 'success';
			if ($operacao == 'Entrada') {
	
				$valor = -($valor);
			}
	
			else{
		
				//(valor sera positivo)
			}
	
			$sql = "CALL Atualiza_Saldo_Remocao(".$codigo.",". $valor . ",'" . $data . "'," . $conta . ");";
			$database->query($sql);
		  }
		}
	  } catch (Exception $e) { 
		$_SESSION['message'] = $e->GetMessage();
		$_SESSION['type'] = 'danger';
	  }
  close_database($database);
	
}

function save_transferencia($dados){
	$database = open_database();
	$sql = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME = 'Movimentacao'";
	$ultimo_codigo = $database->query($sql);
	$ultimo_codigo = $ultimo_codigo->fetch_all(MYSQLI_ASSOC);
	$ultimo_codigo = $ultimo_codigo[0]['AUTO_INCREMENT'];
	$codigo_link1 = $ultimo_codigo + 1;
	$codigo_link2 = $ultimo_codigo;
	//print_r($ultimo_codigo);
	
	$id_conta_destino = null;
	$id_conta_origem = null;
	$data = null;
	$valor = null;
	$columns = null;
	$values = null;
	
	//Cadastrar despesa na conta origem
	
	//PEGAR OS NOMES DAS CONTAS PARA COLOCAR NA DESCRICAO DA TRANSFERENCIA
	$selectContaOrigem = "SELECT * FROM ContaCorrente WHERE codigo=".$dados["'contaOrigem'"];
	$selectContaDestino = "SELECT * FROM ContaCorrente WHERE codigo=".$dados["'contaDestino'"];
	$resultContaOrigem = $database->query($selectContaOrigem);
	$resultContaDestino = $database->query($selectContaDestino);
	
	$found1 = $resultContaOrigem->fetch_all(MYSQLI_ASSOC);
	$found2 = $resultContaDestino->fetch_all(MYSQLI_ASSOC);
	
	$descricao2 = $dados["'descricao'"]." Transferência da conta ".$found1[0]['descricao'];
	$descricao1 = $dados["'descricao'"]." Transferência para a conta ".$found2[0]['descricao'];
	
	foreach ($dados as $key => $value) {
		
		
		if($key == "'valor'"){
			$valor = $dados[$key];
		}
		if($key == "'data'"){
		   $data = $dados[$key];
		}
		if($key == "'contaOrigem'"){
		   $id_conta_origem = $dados[$key];
		}
		if($key == "'contaDestino'"){
		   $id_conta_destino = $dados[$key];
		}
		
		if($key != "'contaOrigem'" AND $key != "'contaDestino'" AND $key != "'descricao'" ){
			$columns .= trim($key, "'") . ",";
			if($dados[$key] == ''){
				$values .= "NULL,";
			}
			else{
			$values .= "'$value',";
			}
		}
	}
	//adiciona a operação de saída nos registros  a serem gravados
  $columnsDespesa = $columns;
  $valuesDespesa = $values;
  
  $columnsReceita = $columns;
  $valuesReceita = $values;
  
  $columnsDespesa .= trim('operacao', "'") . ",";
  $valuesDespesa .= "'Saída',";
  $columnsDespesa .= trim('id_conta', "'") . ",";
  $valuesDespesa .= "'$id_conta_origem',";
  $columnsDespesa .= trim('descricao', "'") . ",";
  $valuesDespesa .= "'$descricao1',";
  //$columnsDespesa .= trim('aux', "'") . ",";
  //$valuesDespesa .= "'Origem',";
  
  $columnsDespesa .= trim('id_transferencia', "'") . ",";
  $valuesDespesa .= "'$codigo_link1',";
	
  // remove a ultima virgula
  $columnsDespesa = rtrim($columnsDespesa, ',');
  $valuesDespesa = rtrim($valuesDespesa, ',');
  
   $columnsReceita .= trim('operacao', "'") . ",";
  $valuesReceita .= "'Entrada',";
  $columnsReceita .= trim('id_conta', "'") . ",";
  $valuesReceita .= "'$id_conta_destino',";
  $columnsReceita .= trim('descricao', "'") . ",";
  $valuesReceita .= "'$descricao2',";
  //$columnsReceita .= trim('aux', "'") . ",";
  //$valuesReceita .= "'Destino',";
  $columnsReceita .= trim('id_transferencia', "'") . ",";
  $valuesReceita .= "'$codigo_link2',";
	
  // remove a ultima virgula
  $columnsReceita = rtrim($columnsReceita, ',');
  $valuesReceita = rtrim($valuesReceita, ',');
  
  $sql = "INSERT INTO Movimentacao (".$columnsDespesa.")  VALUES  (".$valuesDespesa.");";
  print_r($sql);
  
	if($database->query($sql)){
		$valorDespesa = -($valor);
		$sql = "CALL Atualiza_Saldo_old(0,". $valorDespesa . ",'" . $data . "'," . $id_conta_origem . ");"; 
		//print_r($sql);
		if($database->query($sql)){
			//Cadastrar receita na conta destino
	
	 
			$sql = "INSERT INTO Movimentacao (".$columnsReceita.")  VALUES  (".$valuesReceita.");";
			//print_r($sql);
			try {
				if($database->query($sql)){
					$valorReceita = $valor;
					$sql = "CALL Atualiza_Saldo_old(0,". $valorReceita . ",'" . $data . "'," . $id_conta_destino . ");"; 
					//print_r($sql);
					$database->query($sql);
					$_SESSION['message'] = 'Transferência cadastrada com sucesso.';
					$_SESSION['type'] = 'success';
					
				}
			
			
		  
			} catch (Exception $e) { 
		  
				$_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
				$_SESSION['type'] = 'danger';
			} 
			
			
		}
		
			
	}
	
	
	close_database($database);
	
}

function select_consulta($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	$plano_contas = $dados["'id_categoria'"];
	$centro_custos = $dados["'id_centro_custo'"];
	$valor_inicial = $dados["'valor_inicial'"];
	$valor_final = $dados["'valor_final'"];
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$descricao = $dados["'descricao'"];
	$pessoas = $dados["'pessoa'"];
	$contas = $dados["'id_conta'"];
	$filiais = $dados["'id_filial'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "where (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	if($valor_inicial != null AND $valor_final != null AND $iniciou == true){
		$where .= "AND (Movimentacao.valor between " . $valor_inicial . " AND " . $valor_final . ") ";
	}
	
	elseif($valor_inicial != null AND $valor_final != null AND $iniciou == false){
		$where .= "where (Movimentacao.valor between " . $valor_inicial . " AND " . $valor_final . ") ";
		$iniciou = true;			
	}
	
	if($plano_contas != null AND $centro_custos != null AND $iniciou == true){
		//$where .= "AND (Movimentacao.id_categoria = SubCategoria.id AND Movimentacao.id_centro_custo = SubCentroCusto.id) ";				
	}
	
	elseif($plano_contas != null AND $centro_custos != null AND $iniciou == false){
		//$where .= "where (Movimentacao.id_categoria = SubCategoria.id AND Movimentacao.id_centro_custo = SubCentroCusto.id) ";
		//$iniciou = true;
	}
	
	elseif($plano_contas != null AND $centro_custos == null AND $iniciou == true){
		//$where .= "AND (Movimentacao.id_categoria = SubCategoria.id) ";
	}
	
	elseif($plano_contas != null AND $centro_custos == null AND $iniciou == false){
		//$where .= "where (Movimentacao.id_categoria = SubCategoria.id) ";
		//$iniciou = true;
	}
	
	elseif($plano_contas == null AND $centro_custos != null AND $iniciou == true){
		//$where .= "AND (Movimentacao.id_centro_custo = SubCentroCusto.id) ";
	}
	
	elseif($plano_contas == null AND $centro_custos != null AND $iniciou == false){
		//$where .= "where (Movimentacao.id_centro_custo = SubCentroCusto.id) ";
		//$iniciou = true;
	}
	
	//INSERIR NA QUERY OS CÓDIGOS DOS PLANOS DE CONTAS INSERIDOS NO FILTRO
	if ($plano_contas != null) {
			
		for($i=0; $i < sizeof($plano_contas); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_categoria = ".$plano_contas[$i];
			}
			else{
				$where .= " OR Movimentacao.id_categoria = ".$plano_contas[$i];
			}
				
		}
				
		$where .= ") ";
			
	}
	
	if ($centro_custos != null){
			
		for($i=0; $i < sizeof($centro_custos); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_centro_custo = ".$centro_custos[$i];
			}
			else{
				$where .= " OR Movimentacao.id_centro_custo = ".$centro_custos[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	if ($contas != null){
			
		for($i=0; $i < sizeof($contas); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_conta = ".$contas[$i];
			}
			else{
				$where .= " OR Movimentacao.id_conta = ".$contas[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	if ($filiais != null){
			
		for($i=0; $i < sizeof($filiais); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_filial = ".$filiais[$i];
			}
			else{
				$where .= " OR Movimentacao.id_filial = ".$filiais[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	if ($pessoas != null){
			
		for($i=0; $i < sizeof($pessoas); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.pessoa = ".$pessoas[$i];
			}
			else{
				$where .= " OR Movimentacao.pessoa = ".$pessoas[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	if ($descricao != null AND $iniciou == true){
		$where .= "AND (descricao like '%". $descricao ."%') ";
	}
	
	else if ($descricao != null AND $iniciou == false){
		$where .= "where (descricao like '%". $descricao ."%') ";
		$iniciou = true;
	}
	
	//$where .= "order by data,descricao";
	$where .= "order by data,nome_conta";
	
	$sql = "SELECT DISTINCT Movimentacao.*,IF(Movimentacao.pessoa is NOT NULL AND Movimentacao.pessoa in (select codigo from Favorecido),(select nome from Favorecido where Movimentacao.pessoa = Favorecido.codigo),'') as nome_pessoa,IF(Movimentacao.id_filial is NOT NULL AND Movimentacao.id_filial in (select codigo from Filial),(select descricao from Filial where Movimentacao.id_filial = Filial.codigo),'') as nome_filial,IF(Movimentacao.id_conta is NOT NULL AND Movimentacao.id_conta in (select codigo from ContaCorrente),(select descricao from ContaCorrente where Movimentacao.id_conta = ContaCorrente.codigo),'') as nome_conta,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select nome from SubCategoria where Movimentacao.id_categoria = SubCategoria.id),'') as nome_pc,IF(Movimentacao.id_centro_custo is NOT NULL AND Movimentacao.id_centro_custo in (select id from SubCentroCusto where Movimentacao.id_centro_custo = SubCentroCusto.id),(select nome from SubCentroCusto where Movimentacao.id_centro_custo = SubCentroCusto.id),'') as nome_subcc from Movimentacao ". $where;
			
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
}


function find_all_categorias_subcategorias() {
	
	$database = open_database();
	$sql = "SELECT SubCategoria.id,SubCategoria.nome as nome_subcat,Categoria.* from SubCategoria,Categoria where Categoria.codigo=SubCategoria.id_categoria ORDER by Categoria.tipo, Categoria.nome,SubCategoria.nome;";
	$result = $database->query($sql);
	if ($result->num_rows > 0){
		$found = $result->fetch_all(MYSQLI_ASSOC);
	}
	
	close_database($database);
	
	return $found;
	
}

function db_ultimoSaldo($id_conta) {
	
	$database = open_database();
	$sql = "SELECT * FROM Movimentacao WHERE id_conta=".$id_conta." order by data desc,codigo desc LIMIT 1";
	$resultado = $database->query($sql);
	if($resultado->num_rows > 0){
		$resultado = $resultado->fetch_all(MYSQLI_ASSOC);
	}
	else{
		$sql = "SELECT * FROM ContaCorrente WHERE codigo=".$id_conta;
		$resultado = $database->query($sql);
		$resultado = $resultado->fetch_all(MYSQLI_ASSOC);
	}
	return $resultado;
	
}

function atualizaSaldoAposRemocaoTransferencia($mov_select, $mov_select2, $id) {
	
	$database = open_database();
	print_r($id);
	$codigo = $mov_select['codigo'];
	$valor = $mov_select['valor'];
	$data = $mov_select['data'];
	$operacao = $mov_select['operacao'];
	$conta = $mov_select['id_conta'];
	
	$codigo2 = $mov_select2['codigo'];
	$valor2 = $mov_select2['valor'];
	$data2 = $mov_select2['data'];
	$operacao2 = $mov_select2['operacao'];
	$conta2 = $mov_select2['id_conta'];
	
	
	
	try {
		if ($id) {
		  $sql = "DELETE FROM Movimentacao WHERE codigo = " . $id;
		  $result = $database->query($sql);
		  if ($result = $database->query($sql)) {   	
			$_SESSION['message'] = "Registro Removido com Sucesso.";
			$_SESSION['type'] = 'success';
			if ($operacao == 'Entrada') {
	
				$valor = -($valor);
			}
	
			else{
		
				//(valor sera positivo)
			}
	
			$sql = "CALL Atualiza_Saldo_Remocao(".$codigo.",". $valor . ",'" . $data . "'," . $conta . ");";
	
			if($database->query($sql)){
					
				  $sql = "DELETE FROM Movimentacao WHERE codigo = " . $codigo2;
				  $result = $database->query($sql);
				  if ($result = $database->query($sql)) {   	
					//$_SESSION['message'] = "Registro Removido com Sucesso.";
					//$_SESSION['type'] = 'success';
					if ($operacao2 == 'Entrada') {
			
						$valor2 = -($valor2);
					}
			
					else{
				
						//(valor sera positivo)
					}
			
					$sql = "CALL Atualiza_Saldo_Remocao(".$codigo2.",". $valor2 . ",'" . $data2 . "'," . $conta2 . ");";
					$database->query($sql);
					print_r($sql);
					
				  }
				
			}
		  }
		}
	  } catch (Exception $e) { 
		$_SESSION['message'] = $e->GetMessage();
		$_SESSION['type'] = 'danger';
	  }
  close_database($database);
	
}

function contarRegistros(){
	$database = open_database();
	$sql = "select count(*) as cc_numero, (SELECT count(*) from Categoria) as pc_numero, (SELECT count(*) from ContaCorrente) as contaCorrente_numero, (SELECT count(*) from Favorecido) as pessoas_numero FROM CentroCusto";
	$resultado = $database->query($sql);
	$resultado = $resultado->fetch_assoc();
	close_database($database);
	return $resultado;
}

function somaPagarVencidas(){
	$database = open_database();
	$sql = "select SUM(case when ContasPagarReceber.tipo = 'P' AND ContasPagarReceber.data_vencimento < DATE(NOW() ) AND ContasPagarReceber.status != 'Pago' then ContasPagarReceber.valor else 0 end) as soma from ContasPagarReceber";
	$resultado = $database->query($sql);
	$resultado = $resultado->fetch_assoc();
	close_database($database);
	return $resultado;
}

function somaPagarHoje(){
	$database = open_database();
	$sql = "select SUM(case when ContasPagarReceber.tipo = 'P' AND ContasPagarReceber.data_vencimento = DATE(NOW() ) AND ContasPagarReceber.status != 'Pago' then ContasPagarReceber.valor else 0 end) as soma from ContasPagarReceber";
	$resultado = $database->query($sql);
	$resultado = $resultado->fetch_assoc();
	close_database($database);
	return $resultado;
}

function somaReceberVencidas(){
	$database = open_database();
	$sql = "select SUM(case when ContasPagarReceber.tipo = 'R' AND ContasPagarReceber.data_vencimento < DATE(NOW() ) AND ContasPagarReceber.status != 'Pago' then ContasPagarReceber.valor else 0 end) as soma from ContasPagarReceber";
	$resultado = $database->query($sql);
	$resultado = $resultado->fetch_assoc();
	close_database($database);
	return $resultado;
}

function somaReceberHoje(){
	$database = open_database();
	$sql = "select SUM(case when ContasPagarReceber.tipo = 'R' AND ContasPagarReceber.data_vencimento = DATE(NOW() ) AND ContasPagarReceber.status != 'Pago' then ContasPagarReceber.valor else 0 end) as soma from ContasPagarReceber";
	$resultado = $database->query($sql);
	$resultado = $resultado->fetch_assoc();
	close_database($database);
	return $resultado;
}


function resgatar_consulta(){
	$database = open_database();
	$sql = $_SESSION['consulta_query'];
	$resultado = $database->query($sql);
	$resultado = $resultado->fetch_all(MYSQLI_ASSOC);
	close_database($database);
	return $resultado;
}

function verificarVinculoMovimentacao($table,$id){
	$database = open_database();
	if($table == 'Filial'){
		$sql = "SELECT * FROM Movimentacao WHERE id_filial = $id";
	}
	$query = $database->query($sql);
	if($query->num_rows > 0){
		return true;
	}
	else{
		return false;
	}
}

function select_relatorio($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	$centro_custos = $dados["'id_centro_custo'"];
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$filiais = $dados["'id_filial'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	
	if ($centro_custos != null){
			
		for($i=0; $i < sizeof($centro_custos); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_centro_custo = ".$centro_custos[$i];
			}
			else{
				$where .= " OR Movimentacao.id_centro_custo = ".$centro_custos[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	
	if ($filiais != null){
			
		for($i=0; $i < sizeof($filiais); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_filial = ".$filiais[$i];
			}
			else{
				$where .= " OR Movimentacao.id_filial = ".$filiais[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	
	
	$where .= "order by data,descricao";
	
	//$sql = "SELECT DISTINCT Movimentacao.*,IF(Movimentacao.id_filial is NOT NULL AND Movimentacao.id_filial in (select codigo from Filial),(select descricao from Filial where Movimentacao.id_filial = Filial.codigo),'') as nome_filial,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select nome from SubCategoria where Movimentacao.id_categoria = SubCategoria.id),'') as nome_pc,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select agrupamento_entrada from Categoria where codigo = (select id_categoria from SubCategoria where Movimentacao.id_categoria = SubCategoria.id)),'') as grupo_entrada,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select agrupamento_saida from Categoria where codigo = (select id_categoria from SubCategoria where Movimentacao.id_categoria = SubCategoria.id)),'') as grupo_saida,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select nome from Categoria where codigo = (select id_categoria from SubCategoria where Movimentacao.id_categoria = SubCategoria.id)),'') as grupo_nome from Movimentacao ". $where;
	$sql = "SELECT Categoria.*, (select SUM(case when Movimentacao.operacao = 'Saida' then -Movimentacao.valor else Movimentacao.valor end) as soma1 from Movimentacao, SubCategoria where Movimentacao.id_categoria=SubCategoria.id and SubCategoria.id_categoria=Categoria.codigo ".$where.") as soma FROM Categoria";	
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relOndeVai($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$classificacao = $dados["'classificacao'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	
	
	if($classificacao == 'SubGrupo'){
		$sql = "select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Saída' ".$where.")as soma from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL order by soma desc";
	}
	elseif($classificacao == 'Grupo'){
		//ORDENADO POR GRUPO (SEM SUBCATEGORIAS)
		$sql = "select * from (select Categoria.codigo, Categoria.nome as nome_categoria,(select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria IN (SELECT id FROM SubCategoria WHERE Categoria.codigo = SubCategoria.id_categoria) and Movimentacao.operacao='Saída' ".$where.")as soma from Categoria) a WHERE soma is not NULL order by soma desc";
	}
	else{
		//por valor
		$sql = "select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Saída' ".$where.")as soma from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL order by soma desc";
	}
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relOndeVem($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$classificacao = $dados["'classificacao'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	
	
	if($classificacao == 'SubGrupo'){
		$sql = "select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Entrada' ".$where.")as soma from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL order by soma desc";
	}
	elseif($classificacao == 'Grupo'){
		//ORDENADO POR GRUPO (SEM SUBCATEGORIAS)
		$sql = "select * from (select Categoria.codigo, Categoria.nome as nome_categoria,(select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria IN (SELECT id FROM SubCategoria WHERE Categoria.codigo = SubCategoria.id_categoria) and Movimentacao.operacao='Entrada' ".$where.")as soma from Categoria) a WHERE soma is not NULL order by soma desc";
	}
	else{
		//por pessoa
		$sql = "SELECT * FROM (select Favorecido.codigo,Favorecido.nome as nome_pessoa,(select SUM(valor) as soma from Movimentacao where Movimentacao.pessoa = Favorecido.codigo and Movimentacao.operacao='Entrada' ".$where.")as soma FROM Favorecido UNION SELECT -1 as codigo, 'Movimentações sem pessoas',(SELECT SUM(valor) as soma FROM Movimentacao where Movimentacao.pessoa is NULL AND Movimentacao.operacao = 'Entrada') as soma) a WHERE soma is not NULL order by nome_pessoa";
	}
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relQuemEstaRecebendo($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$classificacao = $dados["'classificacao'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	
	
	if($classificacao == 'Pessoa'){
		$sql = "SELECT * FROM (select Favorecido.codigo,Favorecido.nome as nome_pessoa,(select SUM(valor) as soma from Movimentacao where Movimentacao.pessoa = Favorecido.codigo and Movimentacao.operacao='Saída' ".$where.")as soma FROM Favorecido UNION SELECT -1 as codigo, 'Movimentações sem pessoas',(SELECT SUM(valor) as soma FROM Movimentacao where Movimentacao.pessoa is NULL AND Movimentacao.operacao = 'Saída') as soma) a WHERE soma is not NULL order by nome_pessoa";
	}
	else{
		//por valor
		$sql = "SELECT * FROM (select Favorecido.codigo,Favorecido.nome as nome_pessoa,(select SUM(valor) as soma from Movimentacao where Movimentacao.pessoa = Favorecido.codigo and Movimentacao.operacao='Saída' ".$where.")as soma FROM Favorecido UNION SELECT -1 as codigo, 'Movimentações sem pessoas',(SELECT SUM(valor) as soma FROM Movimentacao where Movimentacao.pessoa is NULL AND Movimentacao.operacao = 'Saída') as soma) a WHERE soma is not NULL order by soma desc";
	}
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relRendvsDespesas($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$classificacao = $dados["'classificacao'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	
	
	if($classificacao == 'SubGrupo'){
		$sql = "select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Entrada' ".$where.")as soma, 'Entrada' as operacao from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL UNION select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Saída' ".$where.")as soma, 'Saída' as operacao from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL order by operacao,soma desc";
	}
	elseif($classificacao == 'Grupo'){
		//ORDENADO POR GRUPO (SEM SUBCATEGORIAS)
		$sql = "select * from (select Categoria.codigo, Categoria.nome as nome_categoria,(select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria IN (SELECT id FROM SubCategoria WHERE Categoria.codigo = SubCategoria.id_categoria) and Movimentacao.operacao='Entrada' ".$where.")as soma,'Entrada' as operacao from Categoria) a WHERE soma is not NULL UNION select * from (select Categoria.codigo, Categoria.nome as nome_categoria,(select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria IN (SELECT id FROM SubCategoria WHERE Categoria.codigo = SubCategoria.id_categoria) and Movimentacao.operacao='Saída' ".$where.")as soma,'Saída' as operacao from Categoria) a WHERE soma is not NULL order by operacao,soma desc";
	}
	else{
		//por valor
		$sql = "select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Entrada' ".$where.")as soma, 'Entrada' as operacao from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL UNION select * FROM (select Categoria.codigo, Categoria.nome as nome_categoria, SubCategoria.nome as nome_subcategoria, (select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria=SubCategoria.id and Movimentacao.operacao='Saída' ".$where.")as soma, 'Saída' as operacao from Categoria, SubCategoria where Categoria.codigo=SubCategoria.id_categoria) as a WHERE soma is not NULL order by operacao,soma desc";
	}
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relOQueTenho($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	
	
	$data_final = $dados["'data_final'"];

	
	
	$sql = "select ContaCorrente.descricao, ContaCorrente.saldo as saldoInicial, (SELECT Movimentacao.saldo FROM Movimentacao WHERE Movimentacao.id_conta = ContaCorrente.codigo AND Movimentacao.data <= '".$data_final. "' ORDER BY data desc,codigo desc LIMIT 1) as saldo From ContaCorrente  order by descricao";
	
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_contas_relatorio($dados){
	$database = open_database();
	
	$iniciou = false;
	$where = "";
	$centro_custos = $dados["'id_centro_custo'"];
	$data_inicial = $dados["'data_inicial'"];
	$data_final = $dados["'data_final'"];
	$filiais = $dados["'id_filial'"];

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		$iniciou = true;	
	}
	
	
	if ($centro_custos != null){
			
		for($i=0; $i < sizeof($centro_custos); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_centro_custo = ".$centro_custos[$i];
			}
			else{
				$where .= " OR Movimentacao.id_centro_custo = ".$centro_custos[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	
	if ($filiais != null){
			
		for($i=0; $i < sizeof($filiais); $i++){
					
			if ($i==0){
				$where .= "AND (Movimentacao.id_filial = ".$filiais[$i];
			}
			else{
				$where .= " OR Movimentacao.id_filial = ".$filiais[$i];
			}
				
		}
				
		$where .= ") ";
	}
	
	
	
	$where .= "order by data,descricao";
	
	//$sql = "SELECT DISTINCT Movimentacao.*,IF(Movimentacao.id_filial is NOT NULL AND Movimentacao.id_filial in (select codigo from Filial),(select descricao from Filial where Movimentacao.id_filial = Filial.codigo),'') as nome_filial,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select nome from SubCategoria where Movimentacao.id_categoria = SubCategoria.id),'') as nome_pc,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select agrupamento_entrada from Categoria where codigo = (select id_categoria from SubCategoria where Movimentacao.id_categoria = SubCategoria.id)),'') as grupo_entrada,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select agrupamento_saida from Categoria where codigo = (select id_categoria from SubCategoria where Movimentacao.id_categoria = SubCategoria.id)),'') as grupo_saida,IF(Movimentacao.id_categoria is NOT NULL AND Movimentacao.id_categoria in (select id from SubCategoria),(select nome from Categoria where codigo = (select id_categoria from SubCategoria where Movimentacao.id_categoria = SubCategoria.id)),'') as grupo_nome from Movimentacao ". $where;
	$sql = "SELECT SubCategoria.*, (select SUM(case when Movimentacao.operacao = 'Saida' then -Movimentacao.valor else Movimentacao.valor end) as soma1 from Movimentacao where Movimentacao.id_categoria=SubCategoria.id ".$where.") as soma, (select Categoria.agrupamento_entrada from Categoria WHERE Categoria.codigo=SubCategoria.id_categoria) as agrupamento_entrada, (select Categoria.agrupamento_saida from Categoria WHERE Categoria.codigo=SubCategoria.id_categoria) as agrupamento_saida FROM SubCategoria";	
		
	$_SESSION['consulta_query']	= $sql;
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_nome_filiais($dados){
	//SELECT DOS NOMES DAS FILIAIS SELECIONADAS NO FILTRO DO RELATORIO
	$database = open_database();
	$codigos_filiais = '';
	foreach ($dados as $value){
		$codigos_filiais.= $value.',';
	}
	$codigos_filiais = rtrim($codigos_filiais, ',');

	//print_r($codigos_filiais);
	$sql = 'SELECT descricao FROM Filial WHERE codigo in ('.$codigos_filiais;
	$sql = $sql.') order by descricao';
	//print_r($sql);
	$result = $database->query($sql);
	if ($result->num_rows > 0) {
		$found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	close_database($database);
	
	return $found;	
	
}

function select_nome_centro_custos($dados){
	//SELECT DOS NOMES DAS FILIAIS SELECIONADAS NO FILTRO DO RELATORIO
	$database = open_database();
	$codigos_centro_custos = '';
	foreach ($dados as $value){
		$codigos_centro_custos.= $value.',';
	}
	$codigos_centro_custos = rtrim($codigos_centro_custos, ',');

	//print_r($codigos_filiais);
	$sql = 'SELECT nome FROM SubCentroCusto WHERE id in ('.$codigos_centro_custos;
	$sql = $sql.') order by nome';
	//print_r($sql);
	$result = $database->query($sql);
	if ($result->num_rows > 0) {
		$found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	close_database($database);
	
	return $found;	
	
}

function criacao_vetor_replicado(){
	
	$database = open_database();
	$sql = "SELECT*FROM((SELECT Categoria.*,SubCategoria.id,SubCategoria.nome as nome_subcat from Categoria,SubCategoria where Categoria.codigo=SubCategoria.id_categoria)UNION(SELECT Categoria.*,NULL as id,NULL as nome_subcat FROM Categoria,SubCategoria where Categoria.codigo NOT IN (SELECT DISTINCT id_categoria FROM SubCategoria)) UNION (SELECT Categoria.*,NULL as id,NULL as nome_subcat FROM Categoria where (SELECT DISTINCT count(*) FROM SubCategoria) = 0)) t1 ORDER BY t1.tipo,t1.nome,t1.nome_subcat;";
	$result = $database->query($sql);
	if ($result->num_rows > 0) {
		$found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	close_database($database);
	
	return $found;	
}

function configurar($dados){
	$database = open_database();
  // remove a ultima virgula
  $sql  = "UPDATE Configuracao";
  $sql .= " SET centro_custo_ativa = '".$dados["'centro_custo_ativa'"]."', filial_ativa = '".$dados["'filial_ativa'"]."'";
  $sql .= " WHERE codigo=1";

  //print_r($sql);
  try {
    $database->query($sql);
    $_SESSION['message'] = 'Configuração atualizada com sucesso.';
    $_SESSION['type'] = 'success';
	//print_r($database->error);
  } catch (Exception $e) { 
	//echo $e->getMessage();
    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  }

	close_database($database);

}

function buscarConfiguracoes(){
	$database = open_database();
	$sql = "SELECT * FROM Configuracao WHERE codigo = 1";
	$result = $database->query($sql);
	$result = $result->fetch_all(MYSQLI_ASSOC);
	close_database($database);
	
	return $result;
}

function select_relOndeVaiPadrao($data_inicial,$data_final){
	$database = open_database();
	
	
	$where = "";
	
	

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		
	}
		//ORDENADO POR GRUPO (SEM SUBCATEGORIAS)
		$sql = "select * from (select Categoria.codigo, Categoria.nome as nome_categoria,(select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria IN (SELECT id FROM SubCategoria WHERE Categoria.codigo = SubCategoria.id_categoria) and Movimentacao.operacao='Saída' ".$where.")as soma from Categoria) a WHERE soma is not NULL order by soma desc";
	
		
	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Não foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relOndeVemPadrao($data_inicial,$data_final){
	$database = open_database();
	
	
	$where = "";
	
	

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		
	}
		//ORDENADO POR GRUPO (SEM SUBCATEGORIAS)
		$sql = "select * from (select Categoria.codigo, Categoria.nome as nome_categoria,(select SUM(valor) as soma from Movimentacao where Movimentacao.id_categoria IN (SELECT id FROM SubCategoria WHERE Categoria.codigo = SubCategoria.id_categoria) and Movimentacao.operacao='Entrada' ".$where.")as soma from Categoria) a WHERE soma is not NULL order by soma desc";
		
	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relQuemEstaRecebendoPadrao($data_inicial,$data_final){
	$database = open_database();
	
	
	$where = "";
	
	

	if($data_inicial != null AND $data_final != null){
		$where .= "AND (Movimentacao.data between '" . $data_inicial . "' AND '" . $data_final . "') ";
		
	}
		//ORDENADO POR GRUPO (SEM SUBCATEGORIAS)
		$sql = "SELECT * FROM (select Favorecido.codigo,Favorecido.nome as nome_pessoa,(select SUM(valor) as soma from Movimentacao where Movimentacao.pessoa = Favorecido.codigo and Movimentacao.operacao='Saída' ".$where.")as soma FROM Favorecido UNION SELECT -1 as codigo, 'Movimentações sem pessoas',(SELECT SUM(valor) as soma FROM Movimentacao where Movimentacao.pessoa is NULL AND Movimentacao.operacao = 'Saída') as soma) a WHERE soma is not NULL order by nome_pessoa";
		
	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function select_relOQueTenhoPadrao(){
	$database = open_database();
	
	
	$where = "";
	
	
	$data_final = date ("Y-m-d");
	
	
	$sql = "select ContaCorrente.descricao, ContaCorrente.saldo as saldoInicial, (SELECT Movimentacao.saldo FROM Movimentacao WHERE Movimentacao.id_conta = ContaCorrente.codigo AND Movimentacao.data <= '".$data_final. "' ORDER BY data desc,codigo desc LIMIT 1) as saldo From ContaCorrente  order by descricao";
	
		
	$_SESSION['consulta_query']	= $sql;	
	//print_r($sql);
	
	try{
	
		$result = $database->query($sql);
		
		if ($result->num_rows > 0) {
			$found = $result->fetch_all(MYSQLI_ASSOC);
	
		}
		
		
	}
	catch(Exception $e){
		$_SESSION['message'] = 'Nao foi possivel realizar a consulta.';
		$_SESSION['type'] = 'danger';
		echo "ERRO";
		
	}
	
	close_database($database);
	
	return $found;	
	
	
	
}

function find_all_contas_pagar_receber(){
	$database = open_database();
	$found = null;
	
	try{
	$sql = "SELECT ContasPagarReceber.*, SubCategoria.nome as nome_subcat from ContasPagarReceber,SubCategoria where (ContasPagarReceber.id_categoria = SubCategoria.id) order by ContasPagarReceber.data_vencimento, ContasPagarReceber.codigo ;";
	$result = $database->query($sql);
	    
	if ($result->num_rows > 0) {
	   $found = $result->fetch_all(MYSQLI_ASSOC);
	
	}
	} catch (Exception $e) {
	  $_SESSION['message'] = $e->GetMessage();
	  $_SESSION['type'] = 'danger';
		
	}
	
	close_database($database);
	return $found;
	
}

function save_conta_a_pagar_receber($data){
	$database = open_database();
	$columns = null;
	$values = null;
	
	$data["'descricao'"] =  mysqli_real_escape_string($database,$data["'descricao'"]);
	
	foreach ($data as $key => $value) {
		$columns .= trim($key, "'") . ",";
		
		if($data[$key] == ''){
			$values .= "NULL,";
		}
		else{
		$values .= "'$value',";
		}
	}
  // remove a ultima virgula
  $columns = rtrim($columns, ',');
  $values = rtrim($values, ',');
  
  $sql = "INSERT INTO ContasPagarReceber ($columns)" . " VALUES " . "($values);";
  
  print_r($sql);
  $database->query($sql);
		
		$_SESSION['message'] = 'Registro cadastrado com sucesso.';
		$_SESSION['type'] = 'success';
}


function save_baixa($data){
	$database = open_database();
	$columns = null;
	$values = null;
	$data["'descricao'"] =  mysqli_real_escape_string($database,$data["'descricao'"]);
	
	foreach ($data as $key => $value) {
		
		
			if($key == "'valor_baixa'"){
				 $valor = $data[$key];
				 $columns .= trim('valor', "'") . ",";
			}
			elseif($key == "'data_baixa'"){
				 $datap = $data[$key];
				 $columns .= trim('data', "'") . ",";
			}
			elseif($key == "'id_pessoa'"){
				 
				 $columns .= trim('pessoa', "'") . ",";
			}
			elseif($key == "'id_conta'"){
				 $conta = $data[$key];
				 $columns .= trim($key, "'") . ",";
			}
			elseif($key == "'tipo'"){
				if($data["'tipo'"] == 'P'){
					$operacao = "Saída";
				}
				else{
					$operacao = "Entrada";
				}
				
				 $columns .= trim('operacao', "'") . ",";
			}
			elseif($key == "'status'"){
				
			}
			else{
				$columns .= trim($key, "'") . ",";
			}
			
		if($key != "'status'"){
			if($data[$key] == ''){
				$values .= "NULL,";
			}
			else{
			if($key == "'tipo'"){
				$values .= "'$operacao',";
			}
			else{
				$values .= "'$value',";
			}
			
			}
		}
	}
  // remove a ultima virgula
  $columns = rtrim($columns, ',');
  $values = rtrim($values, ',');
  
  $sql = "INSERT INTO Movimentacao ($columns)" . " VALUES " . "($values);";
  print_r($sql);
  
  try {
	
		if($database->query($sql)){
			if($operacao == "Entrada"){
				//positivo
			}
			else{
				$valor = -($valor);
			}
			$sql = "CALL Atualiza_Saldo_old(0,". $valor . ",'" . $datap . "'," . $conta . ");"; 
			print_r($sql);
			$database->query($sql);
			$_SESSION['message'] = 'Registro cadastrado com sucesso.';
			$_SESSION['type'] = 'success';
			
		}
	
	
  
  } catch (Exception $e) { 
  
    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  } 
  //print_r($sql);
  close_database($database);
	
	
}


?>