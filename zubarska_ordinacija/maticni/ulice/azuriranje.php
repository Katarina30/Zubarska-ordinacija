<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);
?>
		<a href="pregled.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
	<?php
		if($_REQUEST['akcija'] == 'n'){
	?>
		</header>
		<main>
			<form name="unos" action="" method="post">
				<table class="tabelaUnos" align="center" >
					<caption>UNOS NOVE ULICE</caption>
					<tr>
						<td>Ulica: </td>
						<td><input type="text" required name="naziv" value=<?php echo (isset($_REQUEST['naziv']))?$_REQUEST['naziv']:"";?>></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="snimi" value="Snimi novu ulicu u bazu"></td>
					</tr>
				</table>
					<input type="hidden" name="akcija" value="n">
			</form>
		</main>	
	<?php
		if(isset($_REQUEST['snimi'])){
			$naziv = validacijaPodatka($_REQUEST['naziv']);
			$sqlQuery = "INSERT INTO `ulica`(`id`, `naziv`) VALUES (null, '$naziv')";
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Ulica nije upisana u bazu! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");	
		}
	}
		else if($_REQUEST['akcija'] == 'b'){
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				
			$sqlQuery = "DELETE FROM ulica WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Ulica nije obrisan! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");	
		}
		else{
			greska("Nije korektan id ulice!");
		}
		
	}
	else if($_REQUEST['akcija'] == 'i'){
		if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
			$sqlQuery = "SELECT * FROM ulica WHERE id=".$_REQUEST['id'];
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
			?>
			</header>
			<main>
				<form name="unos" action="" method="post">
					<table  class="tabelaUnos" align="center" border="1" >
						<caption>IZMENA ULICE</caption>
						<tr>
							<td>Ulica: </td>
							<td><input type="text" required name="naziv" value="<?=$row['naziv'];?>"></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="snimi" value="Snimi izmenu ulice u bazu"></td>
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
				$naziv = validacijaPodatka($_REQUEST['naziv']);
			
				$sqlQuery = "UPDATE ulica SET naziv='$naziv' WHERE id=".$_REQUEST['id'];
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Ulica nije izmenjen! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}		
		}
		else{
			greska("Nije korektan id ulice!");
		}
	}
	else{
		greska("Ne prepoznajemo akciju");
	}
?>