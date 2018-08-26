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
			$selectV = "<option value='-1'> - Izaberite vrstu usluge - </option>";
		$sqlQuery = "SELECT V.*,V.naziv as vrsta
					FROM vrsta_usluge V ";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){	
			$selectV .= "<option value=${row['id']}> ${row['vrsta']} </option>";
		}
	?>
		
		<div class="filter">
			<form action="" name="filtriranje" >
				Usluga: <input type="text" size="15" name="fltUsl" value=<?php echo (isset($_REQUEST['fltUsl']))?$_REQUEST['fltUsl']:"";?>>
				<select name="fltVrsta" id="fltVrsta"><?=$selectV?></select>
				<input type="submit" name="filtriraj" value="Primeni filtere">
			
			&nbsp; <a href="azuriranje.php?akcija=n" class="unos" > > > > UNOS NOVE USLUGE < < < </a><br><br>
			</form>
				
		</div>
			
		<br>
			<table class="tabela" >
				<tr align="center">
					<td>ID</td>
					<td>VRSTA USLUGE</td>
					<td>USLUGA</td>
					<td>CENA USLUGE</td>
					<td>VREME TRAJANJA</td>
					<td>IZMENA</td>
					<td>BRISANJE</td>
				</tr>
	<?php
			$sqlUslovi = " 1=1 ";
			
			if(isset($_REQUEST['filtriraj'])){
				if($_REQUEST['fltUsl']!=""){
					$ime = validacijaPodatka($_REQUEST['fltUsl']);
					$sqlUslovi .= " AND U.naziv LIKE '%$ime%'";
				}
				
				if(@$_REQUEST['fltVrsta']>0){
					$sqlUslovi .= " AND V.id=${_REQUEST['fltVrsta']}";
				}
			}
				
			
			$sqlQuery = "SELECT U.*, V.naziv AS vrsta
						FROM usluga U
						LEFT JOIN vrsta_usluge V ON U.id_vrsta_usluge=V.id
						WHERE $sqlUslovi						
						 ORDER BY V.naziv, U.naziv";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	

	?>
				<tr align="center">
					<td><?=$row['id']?></td>
					<td><?=$row['vrsta']?></td>
					<td><?=$row['naziv']?></td>
					<td><?=$row['cena']?></td>
					<td><?=$row['vreme']?></td>
					<td><a href="azuriranje.php?akcija=i&id=<?=$row['id']?>"><img src="../../img/edit.jpg" width=20 height=20></a></td>
					<td><a href="azuriranje.php?akcija=b&id=<?=$row['id']?>" onClick="return potvrdiAkciju('Da li želite da obrišete uslugu \n <?=$row['naziv']?>?');"><img src="../../img/remove.png" width=20 height=20></a></td>
					
				</tr>
	<?php
		}
	?>
			</table>
		</main>
	</body>
	</html>
