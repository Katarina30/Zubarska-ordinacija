<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);
?>
		<a href="pregled.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
	<?php
		if($_REQUEST['akcija'] == 'n'){
			
			$selectGrad = "<option value='-1'> - Izaberite grad - </option>";
		$sqlQuery = "SELECT * FROM grad ORDER BY naziv";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){	
			$selectedGrad = "";
		if(isset($_REQUEST['grad'])){
			if($row['id'] == $_REQUEST['grad'])
			$selectedGrad = "selected";
		}
			$selectGrad .= "<option value=${row['id']} $selectedGrad> ${row['naziv']} </option>";
		}
	
			$selectUlica = "<option value='-1'> - Izaberite ulicu - </option>";
		$sqlQuery = "SELECT * FROM ulica ORDER BY naziv";
		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){	
			$selectedUlica = "";
		if(isset($_REQUEST['ulica'])){
			if($row['id'] == $_REQUEST['ulica'])
			$selectedUlica = "selected";
		}
			$selectUlica .= "<option value=${row['id']} $selectedUlica> ${row['naziv']} </option>";
		}
	
	?>
	
	</header>
		<main>
			<form name="unos" action="" method="post">
				<table class="tabelaUnos" align="center" border="1">
					<caption>UNOS NOVE ADRESE</caption>
					<tr>
						<td>Grad: </td> 
						<td><select name="grad"><?=$selectGrad?></select></td>
					</tr>
					<tr>
						<td>Ulica: </td>
						<td><select name="ulica"><?=$selectUlica?></select></td>
					</tr>
					<tr>
						<td>Broj: </td>
						<td><input type="text" required name="broj" value=<?php echo (isset($_REQUEST['broj']))?$_REQUEST['broj']:"";?>></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="snimi" value="Snimi novu adresu u bazu"></td>
					</tr>
				</table>
					<input type="hidden" name="akcija" value="n">
			</form>
		</main>	
	<?php
		if(isset($_REQUEST['snimi'])){
		
			if($_REQUEST['grad']<=0 || !is_numeric($_REQUEST['grad'])){
				greska("Niste izabrali grad!");
			}
			else if($_REQUEST['ulica']<=0 || !is_numeric($_REQUEST['ulica'])){
				greska("Niste izabrali ulicu!");
			}
		else{
			$broj = validacijaPodatka($_REQUEST['broj']);
			
			$sqlQuery = "INSERT INTO adresa VALUES 
						(null, ${_REQUEST['grad']}, ${_REQUEST['ulica']}, '$broj')";
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Adresa nije upisana u bazu! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");
			}	
		}
	}
		else if($_REQUEST['akcija'] == 'b'){
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				
			$sqlQuery = "DELETE FROM adresa WHERE id=".$_REQUEST['id'];
		
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Adresa nije obrisana! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");	
		}
	else{
		greska("Nije korektan id adrese!");
	}
	
}
		else if($_REQUEST['akcija'] == 'i'){
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				
			$sqlQuery = "SELECT * FROM adresa WHERE id=".$_REQUEST['id'];
				$res = mysqli_query($conn, $sqlQuery);
				while($row = mysqli_fetch_assoc($res)){	
		
				$selectGrad = "";
			$sqlQuery = "SELECT * FROM grad ORDER BY naziv";
			$resGr = mysqli_query($conn, $sqlQuery);
			while($rowGr = mysqli_fetch_assoc($resGr)){	
				$selectedGrad = "";
				if($row['id_grad'] == $rowGr['id'])
					$selectedGrad = "selected";
				
				$selectGrad .= "<option value=${rowGr['id']} $selectedGrad> ${rowGr['naziv']} </option>";
			}
			
				$selectUlica = "";
			$sqlQuery = "SELECT * FROM ulica ORDER BY naziv";
			$resUl = mysqli_query($conn, $sqlQuery);
			while($rowUl = mysqli_fetch_assoc($resUl)){	
				$selectedUlica = "";
				if($row['id_ulica'] == $rowUl['id'])
					$selectedUlica = "selected";
				
				$selectUlica .= "<option value=${rowUl['id']} $selectedUlica> ${rowUl['naziv']} </option>";
			}
	?>
		</header>
			<main>
				<form name="unos" action="" method="post">
					<table class="tabelaUnos" align="center" border="1">
						<caption>UNOS NOVE ADRESE</caption>
						<tr>
							<td>Grad: </td> 
							<td><select name="grad"><?=$selectGrad?></select></td>
						</tr>
						<tr>
							<td>Ulica: </td>
							<td><select name="ulica"><?=$selectUlica?></select></td>
						</tr>
						<tr>
							<td>Broj: </td>
							<td><input type="text" required name="broj" value="<?=$row['broj'];?>"></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="snimi" value="Izmeni adresu u bazi"></td>
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
			$broj = validacijaPodatka($_REQUEST['broj']);
			
			$sqlQuery = "UPDATE adresa SET id_grad=${_REQUEST['grad']}, id_ulica=${_REQUEST['ulica']},
				broj='$broj' WHERE id=".$_REQUEST['id'];
				
			$res = mysqli_query($conn, $sqlQuery);
			if(!$res)
				greska("Adresa nije izmenjena! <br> opis: ".mysqli_error($conn));
			else
				header("Location: pregled.php");	
		}		
	}
	else{
		greska("Nije korektan id adrese!");
	}
}
else{
	greska("Ne prepoznajemo akciju");
}
?>