<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);
?>
			<a href="pregled.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
	<?php
		if($_REQUEST['akcija'] == 'n'){
			
				$selectUsluga = "<option value='-1'> - Izaberite vrstu usluge - </option>";
			$sqlQuery = "SELECT * FROM vrsta_usluge ORDER BY naziv";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectedUsluga = "";
				if(isset($_REQUEST['vrsta'])){
					if($row['id'] == $_REQUEST['vrsta'])
						$selectedUsluga = "selected";
				}
				$selectUsluga .= "<option value=${row['id']} $selectedUsluga> ${row['naziv']} </option>";
			}
	?>
		</header>
		<main>
			<form name="unos" action="" method="post">
				<table class="tabelaUnos" align="center" >
					<caption>UNOS NOVE USLUGE</caption>
					
					<tr>
						<td>Vrsta usluge: </td>
						<td><select name="vrsta" id="vrsta"><?=$selectUsluga?></select></td>
					</tr>
					<tr>
						<td>Usluga: </td> 
						<td><input type="text" required name="usluga" value=<?php echo (isset($_REQUEST['usluga']))?$_REQUEST['usluga']:"";?>></td>
					</tr>
					<tr>
						<td>Cena: </td>
						<td><input type="text" required name="cena" value=<?php echo (isset($_REQUEST['cena']))?$_REQUEST['cena']:"";?>></td>
					</tr>
					<tr>
						<td>Vreme trajanja: </td>
						<td><input type="text" required name="vreme" value=<?php echo (isset($_REQUEST['vreme']))?$_REQUEST['vreme']:"";?>></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="snimi" value="Snimi novu uslugu u bazu"></td>
					</tr>
				</table>
				<input type="hidden" name="akcija" value="n">
			</form>
		</main>	
	<?php
		if(isset($_REQUEST['snimi'])){
		
			if($_REQUEST['vrsta']<=0 || !is_numeric($_REQUEST['vrsta'])){
				greska("Niste izabrali vrstu usluge!");
			}
		
			else{
				$usluga = validacijaPodatka($_REQUEST['usluga']);
				$cena = validacijaPodatka($_REQUEST['cena']);
				$vreme = validacijaPodatka($_REQUEST['vreme']);
			
			
			$sqlQuery = "INSERT INTO usluga VALUES 
						(null, '$usluga','$cena', '$vreme', ${_REQUEST['vrsta']})";
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Usluga nije upisana u bazu! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
			}	
		}
	}
	else if($_REQUEST['akcija'] == 'b'){
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			
			$sqlQuery = "DELETE FROM usluga WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Usluga nije obrisana! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");	
		}
		else{
			greska("Nije korektan id usluge!");
		}
		
	}
	else if($_REQUEST['akcija'] == 'i'){
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			$sqlQuery = "SELECT * FROM usluga WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
			
				$selectUsluga = "";
				$sqlQuery = "SELECT * FROM vrsta_usluge ORDER BY naziv";
				$resUsl = mysqli_query($conn, $sqlQuery);
				while($rowUsl = mysqli_fetch_assoc($resUsl)){	
					$selectedUsluga = "";
					if($row['id_vrsta_usluge'] == $rowUsl['id'])
						$selectedUsluga = "selected";
					
					$selectUsluga .= "<option value=${rowUsl['id']} $selectedUsluga> ${rowUsl['naziv']} </option>";
				}
	?>
		</header>
		<main>
			<form name="unos" action="" method="post">
				<table  class="tabelaUnos" align="center" >
					<caption>IZMENA USLUGA</caption>
				<tr>
					<td>Vrsta usluge: </td>
					<td><select name="vrsta" id="vrsta"><?=$selectUsluga?></select></td>
				</tr>
				<tr>
					<td>Usluga: </td> 
					<td><input type="text" required name="usluga" value=<?=$row['naziv'] ?>></td>
				</tr>
				<tr>
					<td>Cena: </td>
					<td><input type="text" required name="cena" value=<?=$row['cena'] ?>></td>
				</tr>
				<tr>
					<td>Vreme trajanja: </td>
					<td><input type="text" required name="vreme" value=<?=$row['vreme'] ?>></td>
				</tr>
					<tr>
						<td colspan="2"><input type="submit" name="snimi" value="Snimi izmenu usluge u bazu"></td>
					</tr>
				</table>
				<input type="hidden" name="akcija" value="i">
				<input type="hidden" name="id" value="<?=$row['id'];?>">
			</form>
		</main>	
		</body>
		</html>
	
	<?php
		}
			if(isset($_REQUEST['snimi'])){
				if($_REQUEST['vrsta']<=0 || !is_numeric($_REQUEST['vrsta'])){
					greska("Niste izabrali vrstu usluge!");
				}
				
				else{
					$usluga = validacijaPodatka($_REQUEST['usluga']);
					$cena = validacijaPodatka($_REQUEST['cena']);
					$vreme = validacijaPodatka($_REQUEST['vreme']);
				
					$sqlQuery = "UPDATE usluga SET naziv='$usluga', cena='$cena', 
									vreme='$vreme', id_vrsta_usluge=${_REQUEST['vrsta']}
					 WHERE id=".$_REQUEST['id'];
					
					$res = mysqli_query($conn, $sqlQuery);
					if(!$res)
						greska("Usluga nije izmenjena! <br> opis: ".mysqli_error($conn));
					else
						header("Location: pregled.php");	
				}
			}
		}
		else{
			greska("Nije korektan id usluge!");
		}
	}
	else{
		greska("Ne prepoznajemo akciju");
	}
	?>