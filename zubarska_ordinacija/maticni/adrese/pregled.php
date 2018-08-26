<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);
?>
		<a href="../../pocetna.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30> </a>
	</header>

		<main>
	
		
	<?php
				$selectGrad = "<option value='-1'> - Izaberite grad - </option>";
			$sqlQuery = "SELECT * FROM grad ";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectGrad .= "<option value=${row['id']}> ${row['naziv']} </option>";
			}
	?>
	
		<div class="filter">
			<form action="" name="filtriranje" >
				Ulica: <input type="text" size="15" name="fltUl" value=<?php echo (isset($_REQUEST['fltUl']))?$_REQUEST['fltUl']:"";?>>
				<select name="fltGrad" id="fltGrad"><?=$selectGrad?></select>
				<input type="submit" name="filtriraj" value="Primeni filtere">
		
			&nbsp; <a href="azuriranje.php?akcija=n" class="unos"> > > > UNOS NOVE ADRESE < < < </a>
			</form>
				
		</div>
		
		<br>
		
		<table class="tabela">
			<tr align="center">
				<td>ID</td>
				<td>GRAD</td>
				<td>ULICA</td>
				<td>BROJ</td>
				<td>IZMENA</td>
				<td>BRISANJE</td>
			</tr>
	<?php
			$sqlUslovi = " 1=1 ";
			
			if(isset($_REQUEST['filtriraj'])){
				if($_REQUEST['fltUl']!=""){
					$ulica = validacijaPodatka($_REQUEST['fltUl']);
					$sqlUslovi .= " AND U.naziv LIKE '%$ulica%'";
				}
				
				if(@$_REQUEST['fltGrad']>0){
					$sqlUslovi .= " AND G.id=${_REQUEST['fltGrad']}";
				}
			}
			
			$sqlQuery = "SELECT A.id AS id, G.naziv AS grad, U.naziv AS ulica, A.broj 
						 FROM adresa A 
						 LEFT JOIN ulica U ON A.id_ulica=U.id	
						 LEFT JOIN grad G on A.id_grad=G.id
						 WHERE $sqlUslovi
						 ORDER BY G.naziv, U.naziv";
						 
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$adresa = $row['ulica'] ." ". $row['broj'].", ". $row['grad'] ;
	?>
			<tr align="center">
				<td><?=$row['id']?></td>
				<td><?=$row['grad']?></td>
				<td><?=$row['ulica']?></td>
				<td><?=$row['broj']?></td>
				<td><a href="azuriranje.php?akcija=i&id=<?=$row['id']?>"><img src="../../img/edit.jpg" width=20 height=20></a></td>
				<td><a href="azuriranje.php?akcija=b&id=<?=$row['id']?>" onClick="return potvrdiAkciju('Da li želite da obrišete adresu \n <?=$adresa?>?');"><img src="../../img/remove.png" width=20 height=20></a></td>
				
			</tr>
	<?php
		}
	?>
			</table>
		</main>
	</body>
	</html>