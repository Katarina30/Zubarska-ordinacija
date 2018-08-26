<?php
require '../../inc/konekcija.php';

	$termin = $_GET['termin'];
	$doktor = $_GET['doktor'];

	$filter = " 1=1 ";


		if(is_numeric($termin) && $termin>0){
			$filter .= " AND doktor='$doktor' ORDER BY vreme_start";
		}


			$zauzeto = "";
		$sqlQuery = "SELECT * FROM zakazivanje WHERE $filter";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){	
		
			if($termin==date('Y-m-d',strtotime($row['vreme_start']))){
			$zauzeto .= "Od ".date('H:i',strtotime($row['vreme_start'])). " do " .date('H:i',strtotime($row['vreme_kraj']))."<br>";
			}
		}
		echo "$zauzeto ";
		
?>