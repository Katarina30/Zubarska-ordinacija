<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);

?>
		<a href="../../pocetna.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
			
	</header>

		<main>
		
		<div class="filter">
	<?php
			if($_SESSION['vrsta_korisnika'] == PACIJENT){
	?>
				&nbsp; <a href="azuriranje.php?akcija=n" class="unos" > > > > ZAKAZIVANJE < < < </a>
	<?php
			}
	?>
		</div>
				
			<table class="zakazivanje" align="center">
			
	<?php
			$sqlUslovi = "1=1";
			
			if($_SESSION['vrsta_korisnika'] == PACIJENT){	
																// ako je pacijent prikazuje se samo za njegov id, ako je admin prikazuje se sve
				$sqlUslovi .= " AND Z.id_korisnik=${_SESSION['id']} ";
			}
					
			$sqlQuery = "SELECT P.*,Z.*, U.naziv as usluga, U.cena as cena, Z.id as idPregled, K.ime_prezime as ime, K.id_vrsta_kor
						FROM zakazivanje_usluge P
						LEFT JOIN zakazivanje Z ON P.id_zakazivanje=Z.id
						LEFT JOIN usluga U ON P.id_usluga=U.id
						LEFT JOIN korisnik K ON Z.id_korisnik=K.id
						LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
						WHERE $sqlUslovi";
						
			$res = mysqli_query($conn, $sqlQuery);
							
			$pomocna = 0;
			while($row = mysqli_fetch_assoc($res)){	
				
					$doktor = "";
				$sqlQuery = "SELECT K.*,K.id_vrsta_kor, V.naziv as vrsta
				FROM korisnik K
				LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
				WHERE K.id_vrsta_kor = 2";
				$resDr = mysqli_query($conn, $sqlQuery);
				while($rowDr = mysqli_fetch_assoc($resDr)){	
					if($row['doktor'] == $rowDr['id'])
											
					$doktor .= $rowDr['ime_prezime'];
				}
				
			if($pomocna != $row['id_zakazivanje']){
				
	?>	
				<tr class="vrsta" >
					<td>DATUM I VREME</td>
					<td>DOKTOR</td>
	<?php
			if($_SESSION['vrsta_korisnika'] == ADMIN){
	?>	
					<td>PACIJENT</td>
	<?php
			}
			if($_SESSION['vrsta_korisnika'] != ADMIN){
	?>		
					<td >IZMENI</td>
	<?php
			}		
	?>
					<td >OTKAŽI</td>
				</tr>
				<tr align="center">
					<td><?=date('d.m.Y H:i',strtotime($row['vreme_start']))?></td>
					<td><?=$doktor?></td>
	<?php
			if($_SESSION['vrsta_korisnika'] == ADMIN){
	?>	
					<td><?=$row['ime']?></td>
	<?php
			}
			if($_SESSION['vrsta_korisnika'] != ADMIN){
	?>		
					<td><a href="azuriranje.php?akcija=i&id=<?=$row['idPregled']?>">Izmeni</a></td>
	<?php
			}		
			if(strtotime(date('Y-m-d H:i')) < strtotime($row['vreme_start'])){
	?>	
					<td><a href="azuriranje.php?akcija=b&id=<?=$row['idPregled']?>" onClick="return potvrdiAkciju('Da li želite da otkažete zakazani pregled?');">Otkaži</a></td>
	<?php
			}		
	?>
				</tr>
				<tr>
					<td class="vrsta" colspan="4">USLUGE</td>
				</tr>
	<?php
			}
	?>
				<tr>
					<td align="center" colspan="4" ><?=$row['usluga']?></td>
				</tr>
						
	<?php
				$pomocna = $row['id_zakazivanje'];		
					
			}		
	?>	
				</table>
			</main>
		</body>
		</html>