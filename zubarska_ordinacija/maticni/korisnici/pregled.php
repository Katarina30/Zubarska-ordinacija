<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);
?>
		<a href="../../pocetna.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
				
		</header>

		<main>
		
	<?php
				$selectV = "<option value='-1'> - Izaberite vrstu korisnika - </option>";
			$sqlQuery = "SELECT V.naziv as vrsta, V.*
						FROM vrsta_korisnika V";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectV .= "<option value=${row['id']}> ${row['vrsta']} </option>";
			}
	?>
		
		<div class="filter">
			<form action="" name="filtriranje" >
			Ime i prezime: <input type="text" size="15" name="fltIme" value=<?php echo (isset($_REQUEST['fltIme']))?$_REQUEST['fltIme']:"";?>>
			<select name="fltVrsta" id="fltVrsta"><?=$selectV?></select>
			<input type="submit" name="filtriraj" value="Primeni filtere">
		
			&nbsp; <a href="azuriranje.php?akcija=n" class="unos">> > > UNOS NOVOG KORISNIKA < < < </a>
			</form>
		</div>
		<br>
		
		<table class="tabela" align="center" border="1">
		
			<tr align="center">
				<td>ID</td>
				<td>IME I PREZIME</td>
				<td>ADRESA</td>
				<td>BROJ TELEFONA</td>
				<td>KORISNIČKO IME</td>
				<td>VRSTA KORISNIKA</td>
				<td>IZMENA</td>
				<td>BRISANJE</td>
				
			</tr>
	<?php
			
			$sqlUslovi = " 1=1 ";
			
			if(isset($_REQUEST['filtriraj'])){
				if($_REQUEST['fltIme']!=""){
					$ime = validacijaPodatka($_REQUEST['fltIme']);
					$sqlUslovi .= " AND K.ime_prezime LIKE '%$ime%'";
				}
				
				if(@$_REQUEST['fltVrsta']>0){
					$sqlUslovi .= " AND V.id=${_REQUEST['fltVrsta']}";
				}
			}
			$sqlQuery = "SELECT K.*,V.naziv as vrsta, G.naziv AS grad, U.naziv AS ulica, A.broj 
						FROM korisnik K
						LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
						LEFT JOIN adresa A ON K.id_adresa=A.id
						LEFT JOIN ulica U ON A.id_ulica=U.id	
						LEFT JOIN grad G on A.id_grad=G.id
						WHERE $sqlUslovi
						ORDER BY username";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
			
			$adresa = $row['ulica'] ." ". $row['broj'].", ". $row['grad'] ; 
			
	?>
			<tr align="center">
				<td><?=$row['id']?></td>
				<td><?=$row['ime_prezime']?></td>
				<td><?=$adresa?></td>
				<td><?=$row['br_tel']?></td>
				<td><?=$row['username']?></td>
				<td><?=$row['vrsta']?></td>
				<td><a href="azuriranje.php?akcija=i&id=<?=$row['id']?>"><img src="../../img/edit.jpg" width=20 height=20></a></td>
				<td><a href="azuriranje.php?akcija=b&id=<?=$row['id']?>" onClick="return potvrdiAkciju('Da li želite da obrišete korisnika <?=$row['ime_prezime']?>?');"><img src="../../img/remove.png" width=20 height=20></a></td>
			</tr>
	<?php
		}
	?>
		</table>
	</main>
	</body>
	</table>