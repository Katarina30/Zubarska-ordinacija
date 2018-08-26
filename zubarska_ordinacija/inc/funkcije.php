<?php

function validacijaPodatka($podatak) {		//funkcija za validaciju podataka prikupljenih formom
  $podatak = trim($podatak);				//uklanja beline sa obe strane podatka
  $podatak = stripslashes($podatak);		// postavlja \ ispred nesigurnih karaktera
  $podatak = htmlspecialchars($podatak);	//specijalne karaktere pretvara u kod kako se ne bi izvrsili npr < i > u &lt; i &gt;
  return $podatak;
}


function greska($tekst){
    echo"<script type='text/javascript'>alert('" .$tekst."')</script>";	//funkcija koja javlja gresku u vidu alerta
}

function autentifikacija($sesija){			//funkcija za autentifikaciju po svim nivoima aplikacije
    
	$pojavljivanje = substr_count($_SERVER['PHP_SELF'], "/");
	
	if($pojavljivanje == 2){
		$fajl = "logout.php";
	}
	else if($pojavljivanje == 3){
		$fajl = "../logout.php";
	}
	else if($pojavljivanje == 4){
		$fajl = "../../logout.php";
	}
	
	if(!isset($sesija['korisnik'])){
        header("Location: $fajl");
		die();
    }
}
	
function proveraAdmin($sesija){
	if($sesija['vrsta_korisnika'] == 1)
		return true;
	else
		return false;
}

?>