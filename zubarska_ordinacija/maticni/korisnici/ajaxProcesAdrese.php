<?php
require '../../inc/konekcija.php';

	$idGrad = $_GET['idGrad'];


	$filter = " 1=1 ";

	if(is_numeric($idGrad) && $idGrad>0){
		$filter .= " AND A.id_grad=$idGrad ";
	}


	$selectAdresa = "<option value='-1'> - Izaberite adresu - </option>";
		$sqlQuery = "SELECT A.*, G.naziv AS grad, U.naziv AS ulica
					 FROM adresa A 
						LEFT JOIN ulica U ON A.id_ulica=U.id	
						LEFT JOIN grad G ON A.id_grad=G.id
					 WHERE $filter
					 ORDER BY G.naziv, U.naziv";
		$res = mysqli_query($conn, $sqlQuery);
		
		while($row = mysqli_fetch_assoc($res)){	
			$selectAdresa .= "<option value=${row['id']}> ${row['ulica']} ${row['broj']}, ${row['grad']} </option>";
		}
		
		echo $selectAdresa;
	
?>