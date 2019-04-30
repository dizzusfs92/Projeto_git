<?php
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
	print_r($id);
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
		$sql = "SELECT ContasPagarReceber.*, SubCategoria.nome as nome_subcat, SubCentroCusto.nome as nome_subcc, Favorecido.nome as nome_pessoa from ContasPagarReceber,SubCategoria,SubCentroCusto,Favorecido where (ContasPagarReceber.id_categoria = SubCategoria.id AND ContasPagarReceber.id_centro_custo = SubCentroCusto.id AND ContasPagarReceber.id_pessoa = Favorecido.codigo AND ContasPagarReceber.codigo = $id);";
		$result = $database->query($sql);
		if ($result->num_rows > 0) {
	      $found = $result->fetch_assoc();
	    }
	}
	//close_database();
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




function save($table = null, $data = null) {
  $database = open_database();
  $columns = null;
  $values = null;
  
  

  
	//ESCAPAR SÍMBOLOS
   if($table == "empresa"){
	 $data["'nome'"] =  mysqli_real_escape_string($database,$data["'nome'"]);
   }
   
  
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
  
  $sql = "INSERT INTO " . $table . "($columns)" . " VALUES " . "($values);";
  //print_r($sql);
  try {
	
	
	  
		$database->query($sql);
		
		$_SESSION['message'] = 'Registro cadastrado com sucesso.';
		$_SESSION['type'] = 'success';
	
  
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
  
 
 
  
  foreach ($data as $key => $value) {
	  if($table == 'empresa' AND $value == ''){
		  $items .= trim($key, "'") . "=NULL,";
	  }
	  elseif($table == 'revendas' AND $value == ''){
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
  if($table == 'revendas'){
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
  
  
  //print_r($sql);
  close_database($database);
}

function remove( $table = null, $id = null ) {
  $database = open_database();
	
  try {
    if ($id) {
	  if($table != 'revendas'){
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


































?>