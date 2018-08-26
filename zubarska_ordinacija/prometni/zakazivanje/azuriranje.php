<?php
ob_start();
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);

?>
			<a href="pregled.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
	<?php
		if($_REQUEST['akcija'] == 'n'){
	
				$selectDoktor = "<option value='-1'> - Izaberite doktora - </option>";
			$sqlQuery = "SELECT K.*,K.id_vrsta_kor, V.naziv as vrsta
						FROM korisnik K
						LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
						WHERE K.id_vrsta_kor = 2";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectedDoktor = "";
				if(isset($_REQUEST['doktor'])){
					if($row['id'] == $_REQUEST['doktor'])
						$selectedDoktor = "selected";
				}
				$selectDoktor .= "<option value=${row['id']} $selectedDoktor> ${row['ime_prezime']} </option>";
			}
	
				$vreme = "<option value='-1'> - Izaberite vreme - </option>";
			$vremePocetka = 12;
			$krajDana = 20;
			$minuti = array("05", "10", "15","20","25","30","35","40","45","50","55");
			for($i=$vremePocetka; $i<($krajDana + 1); $i++){
				$sat = $i . ':00';
				$vreme .=  '<option value="' . $sat . '">' . $sat . '</option>';
				if($i != $krajDana){
					for($j=0; $j<sizeof($minuti); $j++){
						 $sat = $i . ':' . $minuti[$j];
					

				$vreme .=  '<option value="' . $sat . '">' . $sat . '</option>';				
					}
				}
			}
		$korisnik =$_SESSION['ime'];
		
	?>
		
	</header>
		<main>
			<form name="unos" action="" method="post">
								
					<div id="pacijent">
						<p>Dobro došli &nbsp
						<?=$korisnik?></p>
						<p>ZAKAŽITE PREGLED </p>
					</div>
					
				<table class="filterZ" width=70%>
					<tr>
						<td>Termin: 
						<input type="date" required name="termin" id="termin"  value=<?php echo (isset($_REQUEST['termin']))?$_REQUEST['termin']:"";?>></td>
					
						<td>Vreme početka:
						<select name="vreme" id="vreme"><?=$vreme?></select></td>
					
						<td>Doktor:
						<select name="doktor" id="doktor" onchange="ajaxDatum()"><?=$selectDoktor?></select></td>
					</tr>
				</table>
				<br>
			<div id="terminiZauzeti">
				<p>- Zauzeti termini za izabrani datum -</p>
				<div id="zauzeto"> 	</div>
			</div>
				<br> 
				<table class="zakazivanje" width=70%>
	<?php
				$sqlQuery = "SELECT U.*, V.naziv as vrsta
							FROM usluga U
							LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge = V.id
							ORDER BY V.id";
					$res = mysqli_query($conn, $sqlQuery);
					$pomocna = 0;
					while($row = mysqli_fetch_assoc($res)){	
						if($pomocna != $row['id_vrsta_usluge']){
	?>
					<th class="vrsta" colspan="3"><?=$row['vrsta']?></th>
	<?php
						}
	?>
					<tr>
						<td><input type="checkbox" name="usluge[]" value='<?=$row['id']?>' ><?=$row['naziv']?></td>
						<td align="right" ><?=$row['vreme']?> min.</td>
						<td align="right"><?=$row['cena']?> din.</td>
					</tr>
	<?php	
					$pomocna = $row['id_vrsta_usluge'];
					}
	?>
				</table>
					<br>
				<div id="submit">
					<tr>
						<td ><input id="snimi" type="submit" name="snimi" value="Snimi pregled"></td>
					</tr>
				</div>
					<input type="hidden" name="akcija" value="n">
					<input type="hidden" name="id" value="<?=$row['id']?>">
			
			</form>
		</main>	
	<?php
			if(isset($_REQUEST['snimi'])){
				
					if($_REQUEST['doktor']<=0 || !is_numeric($_REQUEST['doktor'])){
					greska("Niste izabrali doktora!");
					}
					else if($_REQUEST['vreme'] == ""){
						greska("Niste odabrali vreme!");
					}
					else if(@$_REQUEST['usluge'] == ""){
						greska("Niste odabrali uslugu!");
					}
					else{
						
					$pocetak = $_REQUEST['termin'] ." ". $_REQUEST['vreme']; 
					$usluge=	$_REQUEST['usluge'];
					
					$sqlQuery = "SELECT * FROM usluga ORDER BY naziv";
						$res = mysqli_query($conn, $sqlQuery);
						
						$ukupnoTrajanje=0;
						while($row = mysqli_fetch_assoc($res)){	
						
						foreach ($usluge as $usluga){  //prolazi se kroz foreach zbog niza u koji se smestaju usluge
										
						if($usluga == ($row['id'])){
						
						$trajanje = $row['vreme'];
						
						$ukupnoTrajanje += $trajanje;				//izracunavanje trajanja svih cekiranih usluga
						}
						}
					}
			  
				function vremeZakazivanja($start,$minuti){		//funkcija za preracunavanje vremena za kraj pregleda
					$brSekundapoMin=60;
					$pauza = 5;
					$vremePocetka = strtotime($start);
					$ukupnoTrajanje = ($minuti+$pauza)*$brSekundapoMin;
					$vremeKraj = ($vremePocetka + $ukupnoTrajanje);
					$kraj = date('Y-m-d H:i',$vremeKraj);
					return $kraj ;
				}
							
				$kraj = vremeZakazivanja($pocetak,$ukupnoTrajanje);
				
				$vreme_zavrsetka = date('H:i', strtotime($kraj));
				$krajDana = date('20:00');
				$pauzaStart = date('16:00');
				$pauzaKraj = date('16:30');
				
			
			
			if($_REQUEST['termin'] < date('Y-m-d')){
			greska("Niste izabrali dobar datum!");
			}
			else if($_REQUEST['termin'] == date('Y-m-d') && $_REQUEST['vreme'] < date('H:i')){
			greska("Niste izabrali ispravno vreme!");
			} 
			else if(strtotime($vreme_zavrsetka) > strtotime($krajDana)){
			greska("Vreme završetka pregleda je nakon radnog vremena. Izaberite raniji termin!");
			}
			else if(strtotime($_REQUEST['termin']) >= strtotime($pauzaStart) && strtotime($_REQUEST['termin']) <= strtotime($pauzaKraj)){
			greska("Termin se poklapa sa pauzom. Izaberite drugi termin.");
			}
			else if(strtotime($vreme_zavrsetka) >= strtotime($pauzaStart) && strtotime($vreme_zavrsetka) <= strtotime($pauzaKraj)){
			greska("Termin se poklapa sa pauzom. Izaberite drugi termin.");
			}
			else if(date('N', strtotime($_REQUEST['termin'])) >= 6){
			greska("Ne radimo vikendom. Izaberite drugi datum."); 
			}
			else if($_REQUEST['termin'] > date('Y-m-d', strtotime("+30 day"))){
			greska("Možete zakazati samo do 30 dana unapred."); 
			}
		else{															//provera da li se preklapa izabrani termin kod odredjenog doktora
				$sqlQuery = "SELECT * FROM zakazivanje WHERE doktor = ${_REQUEST['doktor']}";
				$resProvera = mysqli_query($conn, $sqlQuery);
				$greska = 0;
				while($rowProvera = mysqli_fetch_assoc($resProvera)){
				
				if(strtotime($pocetak) >= strtotime($rowProvera['vreme_start']) && 
				strtotime($pocetak) <= strtotime($rowProvera['vreme_kraj'])){
					greska("Zakazani termin se poklapa sa drugim terminom. Izaberite drugo vreme.");
					$greska++;
					
				}
				else if(strtotime($kraj) >= strtotime($rowProvera['vreme_start']) &&
				strtotime($kraj) <= strtotime($rowProvera['vreme_kraj'])){
					greska("Zakazani termin se poklapa sa drugim terminom. Izaberite drugo vreme.");
					$greska++;
				
				}
				else if((strtotime($pocetak) <= strtotime($rowProvera['vreme_start']) &&
				strtotime($rowProvera['vreme_start']) <= strtotime($kraj))
						|| (strtotime($pocetak) <= strtotime($rowProvera['vreme_kraj']) 
						&& strtotime($rowProvera['vreme_kraj']) <= strtotime($kraj))){
					greska("Zakazani termin se poklapa sa drugim terminom. Izaberite drugo vreme.");
					$greska++;
				
				}
				
			}
		if($greska == 0){
																//snima se zakazani pregled
				$sqlQuery = "INSERT INTO zakazivanje VALUES 
				(null,${_SESSION['id']},'$pocetak','$kraj',${_REQUEST['doktor']})";
			
				$res = mysqli_query($conn, $sqlQuery);
					if(!$res)
						greska("Pregled nije upisan u bazu! <br> opis: ".mysqli_error($conn));
					else{
						header("Location: pregled.php");
						$idPregleda = mysqli_insert_id($conn);
					}
					foreach($_REQUEST['usluge'] as $usluga){			//snimaju se izabrane usluge za odredjeni pregled
						$sqlQuery = "INSERT INTO zakazivanje_usluge VALUES
									('$idPregleda','$usluga')";
						$res = mysqli_query($conn, $sqlQuery);
							if(!$res)
								greska("Usluga nije upisan u bazu! <br> opis: ".mysqli_error($conn));
					}
		}
	}
		
	}
	}
}
		else if($_REQUEST['akcija'] == 'b'){
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				$sqlQuery = "DELETE FROM zakazivanje WHERE id=".$_REQUEST['id'];
				
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Pregled nije otkazan! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}
			else{
				greska("Nije korektan id pregleda!");
			}
			
		}
		else if($_REQUEST['akcija'] == 'i'){
			
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				
				$sqlQuery = "SELECT Z.*,Z.doktor , Z.vreme_start as vreme, K.ime_prezime as ime
								FROM zakazivanje Z
								LEFT JOIN korisnik K ON Z.doktor=K.id
								WHERE Z.id=${_REQUEST['id']}";
				$res = mysqli_query($conn, $sqlQuery);
				while($row = mysqli_fetch_assoc($res)){	
				
					$selectDoktor = "<option value='-1'> - Izaberite doktora - </option>";
				$sqlQuery = "SELECT K.*,K.id_vrsta_kor, V.naziv as vrsta
					FROM korisnik K
					LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
					WHERE K.id_vrsta_kor = 2";
				$resDr = mysqli_query($conn, $sqlQuery);
				while($rowDr = mysqli_fetch_assoc($resDr)){	
					$selectedDoktor="";
					if($row['doktor'] == $rowDr['id'])
						$selectedDoktor="selected";
					
					$selectDoktor .= "<option value=${rowDr['id']} $selectedDoktor > ${rowDr['ime_prezime']} </option>";
				}
				
					$vreme = "<option value='-1'> - Izaberite vreme - </option>";
				$vremePocetka = 12;
				$krajDana = 20;
				$minuti = array("05", "10", "15","20","25","30","35","40","45","50","55");
				for($i=$vremePocetka; $i<($krajDana + 1); $i++){
					$sat = $i . ':00';
					$vreme .=  '<option value="' . $sat . '">' . $sat . '</option>';
					if($i != $krajDana){
						for($j=0; $j<sizeof($minuti); $j++){
							 $sat = $i . ':' . $minuti[$j];
						$selectedVreme=""; 
						if($sat == date('H:i',strtotime($row['vreme'])))
							$selectedVreme="selected"; 
					$vreme .=  "<option value='$sat' $selectedVreme > $sat </option>";				
						}
					
					}
				}
						
					
	?>
			
	</header>
		<main>
				<form name="unos" action="" method="post">
								
					<div id="pacijent">
							<p>IZMENITE ZAKAZANI PREGLED </p>
					</div>
					
				<table class="filterZ">
					<tr>
						<td>Termin: 
						<input type="date" required name="termin" id="termin"  value=<?=date('Y-m-d',strtotime($row['vreme'])) ?>></td>
					
						<td>Vreme početka: 
						<select name="vreme" id="vreme"><?=$vreme?></select></td>
					
						<td>Doktor: 
						<select name="doktor" id="doktor" onchange="ajaxDatum()"><?=$selectDoktor?></select></td>
					</tr>
					
				</table>
				
				<div id="terminiZauzeti">
					<p>Zauzeti termini za izabrani datum:</p>
					<div id="zauzeto"></div>
				</div>
				<br> 
				<table class="zakazivanje">
	<?php
					$cekiraneUsluge = array();								//prikupiti u niz sve cekirane usluge po odredjenom id-u pregleda
					$sqlQuery = "SELECT * FROM zakazivanje_usluge WHERE id_zakazivanje = ${_REQUEST['id']}";
					$resCek = mysqli_query($conn, $sqlQuery);
					while($rowCek = mysqli_fetch_assoc($resCek)){
						
					$cekiraneUsluge[] .= $rowCek['id_usluga'];
					}	
					
				$sqlQueryUsl = "SELECT U.*,U.id as idUsl, V.naziv as vrsta,V.id as idVr
 						FROM usluga U
						LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge = V.id
						ORDER BY V.id";
					$resUsl = mysqli_query($conn, $sqlQueryUsl);
					$pomocna = 0;
					while($rowUsl = mysqli_fetch_assoc($resUsl)){
										
					if($pomocna != $rowUsl['idVr']){
					
	?>					
					<th class="vrsta" colspan="3"><?=$rowUsl['vrsta']?></th>	
	<?php
					} 
	?>
					<tr>
						<td><input type="checkbox" name="usluge[]" value='<?=$rowUsl['idUsl']?>'
	<?php						
							foreach($cekiraneUsluge as $id){
									$checked = '';
								if($rowUsl['idUsl']==$id){
									$checked = 'checked';
								}
								else{
									$checked = '';
								}
									echo $checked;		}			
	?>				
								> <?=$rowUsl['naziv']?></td>
							
						<td align="right" ><?=$rowUsl['vreme']?> min.</td>
						<td align="right"><?=$rowUsl['cena']?> din.</td>
					</tr>
	<?php	
					$pomocna = $rowUsl['idVr'];	
		}
					
	?>
				</table>
				<br>
					<div id="submit">
						<tr>
							<td ><input id="snimi" type="submit" name="izmeni" value="Izmeni pregled"></td>
						</tr>
					</div>
					<input type="hidden" name="akcija" value="i">
					<input type="hidden" name="id" value="<?=$row['id']?>">
			</form>
		</main>
		</body>
		</html>
	
	<?php
		}
			if(isset($_REQUEST['izmeni'])){
		
			$pocetak = $_REQUEST['termin'] ." ". $_REQUEST['vreme']; 
			$usluge=$_REQUEST['usluge'];
			
			$sqlQuery= "SELECT * FROM usluga";
				$resUs = mysqli_query($conn, $sqlQuery);
				
				$ukupnoTrajanje=0;
				while($rowUs = mysqli_fetch_assoc($resUs)){	
				
					foreach ($usluge as $usluga){
						if($usluga == ($rowUs['id'])){
						
						$trajanje = $rowUs['vreme'];
						$ukupnoTrajanje += $trajanje;				
						}
					}
				}
			  
				function vremeZakazivanja($start,$minuti){
					$brSekundapoMin=60;
					$pauza = 5;
					$vremePocetka = strtotime($start);
					$ukupnoTrajanje = ($minuti+$pauza)*$brSekundapoMin;
					$vremeKraj = ($vremePocetka + $ukupnoTrajanje);
					$kraj = date('Y-m-d H:i',$vremeKraj);
					return $kraj ;
				}
							
				$kraj = vremeZakazivanja($pocetak,$ukupnoTrajanje);
				
				$vreme_zavrsetka = date('H:i', strtotime($kraj));
				$krajDana = date('20:00');
				$pauzaStart = date('16:00');
				$pauzaKraj = date('16:30');
			
			if($_REQUEST['termin'] < date('Y-m-d')){
			greska("Niste izabrali dobar datum!");
			}
			else if($_REQUEST['vreme'] == ""){
				greska("Niste izabrali vreme!");
			}
			else if($_REQUEST['usluge'] == ""){
				greska("Niste izabrali uslugu!");
			}
			else if($_REQUEST['termin'] == date('Y-m-d') && $_REQUEST['vreme'] < date('H:i')){
			greska("Niste izabrali ispravno vreme!");
			} 
			else if(strtotime($vreme_zavrsetka) > strtotime($krajDana)){
			greska("Vreme završetka pregleda je nakon radnog vremena. Izaberite raniji termin!");
			}
			else if(strtotime($_REQUEST['termin']) >= strtotime($pauzaStart) && strtotime($_REQUEST['termin']) <= strtotime($pauzaKraj)){
			greska("Termin se poklapa sa pauzom. Izaberite drugi termin.");
			}
			else if(strtotime($vreme_zavrsetka) >= strtotime($pauzaStart) && strtotime($vreme_zavrsetka) <= strtotime($pauzaKraj)){
			greska("Termin se poklapa sa pauzom. Izaberite drugi termin.");
			}
			else if(date('N', strtotime($_REQUEST['termin'])) >= 6){
			greska("Ne radimo vikendom. Izaberite drugi datum."); 
			}
			else if($_REQUEST['termin'] > date('Y-m-d', strtotime("+30 day"))){
			greska("Možete zakazati samo do 30 dana unapred."); 
			}
			else{		
				$sqlQuery = "SELECT * FROM zakazivanje WHERE doktor = ${_REQUEST['doktor']}";
				$resProvera = mysqli_query($conn, $sqlQuery);
				$greska = 0;
				while($rowProvera = mysqli_fetch_assoc($resProvera)){
				
				if(strtotime($pocetak) >= strtotime($rowProvera['vreme_start']) && 
					strtotime($pocetak) <= strtotime($rowProvera['vreme_kraj']) && 
					$rowProvera['id'] != $_REQUEST['id']){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
					
				}
				else if(strtotime($kraj) >= strtotime($rowProvera['vreme_start']) && 
						strtotime($kraj) <= strtotime($rowProvera['vreme_kraj']) && 
						$rowProvera['id'] != $_REQUEST['id']){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
				
				}
				else if((strtotime($pocetak) <= strtotime($rowProvera['vreme_start']) && 
						((strtotime($rowProvera['vreme_start']) <= strtotime($kraj)) || 
						(strtotime($pocetak) <= strtotime($rowProvera['vreme_kraj']))) && 
						strtotime($rowProvera['vreme_kraj']) <= strtotime($kraj)) && 
						$rowProvera['id'] != $_REQUEST['id']){
					greska("Zakazani termin se poklapa sa drugim terminom. Odaberite drugo vreme.");
					$greska++;
				
				}
				
			}
			
			if($greska == 0){				
					$sqlQuery = "UPDATE zakazivanje SET vreme_start='$pocetak', vreme_kraj='$kraj',	doktor=${_REQUEST['doktor']} 
					WHERE id=".$_REQUEST['id'];		
							
					$res = mysqli_query($conn, $sqlQuery);
					if(!$res)
						greska("Pregled nije izmenjen! <br> opis: ".mysqli_error($conn));
					else{
						header("Location: pregled.php");
						}
						
					$sqlQuery = "DELETE FROM zakazivanje_usluge WHERE id_zakazivanje=".$_REQUEST['id'];
					$res = mysqli_query($conn, $sqlQuery);
				
					foreach($_REQUEST['usluge'] as $usluga){
					$sqlQuery = "INSERT INTO zakazivanje_usluge VALUES ('${_REQUEST['id']}', '$usluga')";
					$res = mysqli_query($conn, $sqlQuery);
						if(!$res){
						greska("Zakazana usluga nije upisana u bazu!".mysqli_error($conn));
						}
					}
					
			}
			}
	}
	}
	else{
		greska("Nije korektan id korisnika!");
	}
	
	}

else{
	greska("Ne prepoznajemo akciju");
}
ob_end_flush();
	?>
<script>
	
function ajaxDatum(){
	var xmlhttp = createRequest();
	
	xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("zauzeto").innerHTML = this.responseText;
		}
	}
	
	var termin = document.getElementById("termin").value;
	var doktor = document.getElementById("doktor").value;
	
	xmlhttp.open("GET", "ajaxProcesDatum.php?termin="+termin+"&doktor="+doktor, true);
	
	xmlhttp.send();
}
</script>
