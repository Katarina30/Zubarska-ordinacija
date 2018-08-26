<?php
include_once "../../inc/header.php";
require '../../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);
?>
		<a href="pregled.php" class="nazad"><img src="../../img/back.jpg" width=30 height=30></a>
	<?php
		if($_REQUEST['akcija'] == 'n'){
				$selectVrsta = "<option value='-1'> - Izaberite vrstu - </option>";
			$sqlQuery = "SELECT * FROM vrsta_korisnika ORDER BY naziv";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$selectedVrsta = "";
				if(isset($_REQUEST['vrsta'])){
					if($row['id'] == $_REQUEST['vrsta'])
						$selectedVrsta = "selected";
				}
				$selectVrsta .= "<option value=${row['id']} $selectedVrsta> ${row['naziv']} </option>";
			}
			
				$selectAdresa = "<option value='-1'> - Izaberite adresu - </option>";
			$sqlQuery = "SELECT A.*, G.naziv AS grad, U.naziv AS ulica
						 FROM adresa A 
						 LEFT JOIN ulica U ON A.id_ulica=U.id	
						 LEFT JOIN grad G on A.id_grad=G.id
						 ORDER BY G.naziv, U.naziv";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
					$selectedAdresa = "";
				if(isset($_REQUEST['adresa'])){
					if($row['id'] == $_REQUEST['adresa'])
						$selectedAdresa = "selected";
				}
				$selectAdresa .= "<option value=${row['id']} $selectedAdresa> ${row['ulica']} ${row['broj']}, ${row['grad']} </option>";
			}
	
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
	?>
			
	</header>
		<main>
			<form name="unos" action="" method="post">
				<table class="tabelaUnos" align="center" border="1" >
					<caption>UNOS NOVOG KORISNIKA</caption>
					<tr>
						<td>Ime i prezime: </td>
						<td><input type="text" required name="ime" value=<?php echo (isset($_REQUEST['ime']))?$_REQUEST['ime']:"";?>></td>
					</tr>
					<tr>
						<td>Grad: </td> 
						<td><select name="grad" id="grad" onChange="ajaxAdrese();"><?=$selectGrad?></select></td>
					</tr>
					<tr>
						<td>Adresa: </td>
						<td width="70%"><select name="adresa" id="adresa"><?=$selectAdresa?></select></td>
					</tr>
					<tr>
						<td>Broj telefona: </td>
						<td><input type="text" required name="telefon" value=<?php echo (isset($_REQUEST['telefon']))?$_REQUEST['telefon']:"";?>></td>
					</tr>
					<tr>
						<td>Korisničko ime: </td>
						<td><input type="text" required name="korisnik" value=<?php echo (isset($_REQUEST['korisnik']))?$_REQUEST['korisnik']:"";?>></td>
					</tr>
					<tr>
						<td>Lozinka: </td>
						<td><input type="text" required name="lozinka" value=<?=(isset($_REQUEST['lozinka']))?$_REQUEST['lozinka']:""?>></td>
					</tr>
					<tr>
						<td>Vrsta korisnika: </td>
						<td><select name="vrsta"><?=$selectVrsta?></select></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="snimi" value="Snimi novog korisnika u bazu"></td>
					</tr>
				</table>
					<input type="hidden" name="akcija" value="n">
			</form>
		</main>	
	<?php
		if(isset($_REQUEST['snimi'])){
			
			if($_REQUEST['vrsta']<=0 || !is_numeric($_REQUEST['vrsta'])){
				greska("Niste izabrali vrstu korisnika!");
			}
			else if($_REQUEST['adresa']<=0 || !is_numeric($_REQUEST['adresa'])){
				greska("Niste izabrali adresu!");
			}
			else if($_REQUEST['grad']<=0 || !is_numeric($_REQUEST['grad'])){
				greska("Niste izabrali grad!");
			}
			
			else{
				$ime = validacijaPodatka($_REQUEST['ime']);
				$telefon = validacijaPodatka($_REQUEST['telefon']);
				$korisnik = validacijaPodatka($_REQUEST['korisnik']);
				$lozinka = validacijaPodatka($_REQUEST['lozinka']);
				
				$lozinka = password_hash($lozinka,PASSWORD_DEFAULT);
				$sqlQuery = "INSERT INTO korisnik VALUES 
							(null,'$ime',${_REQUEST['adresa']},'$telefon', '$korisnik', '$lozinka', ${_REQUEST['vrsta']})";
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Korisnik nije upisan u bazu! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");
			}	
		}
	}
		else if($_REQUEST['akcija'] == 'b'){
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				
				$sqlQuery = "DELETE FROM korisnik WHERE id=".$_REQUEST['id'];
				
				$res = mysqli_query($conn, $sqlQuery);
				if(!$res)
					greska("Korisnik nije obrisan! <br> opis: ".mysqli_error($conn));
				else
					header("Location: pregled.php");	
			}
			else{
				greska("Nije korektan id korisnika!");
			}
		
		}
		else if($_REQUEST['akcija'] == 'i'){
			if(is_numeric($_REQUEST['id']) && $_REQUEST['id']>0){
				
				$sqlQuery = "SELECT * FROM korisnik WHERE id=".$_REQUEST['id'];
				$res = mysqli_query($conn, $sqlQuery);
				while($row = mysqli_fetch_assoc($res)){	
				
						$selectVrsta = "";
					$sqlQuery = "SELECT * FROM vrsta_korisnika ORDER BY naziv";
					$resVr = mysqli_query($conn, $sqlQuery);
					while($rowVr = mysqli_fetch_assoc($resVr)){	
						$selectedVrsta = "";
						if($row['id_vrsta_kor'] == $rowVr['id'])
							$selectedVrsta = "selected";
						
						$selectVrsta .= "<option value=${rowVr['id']} $selectedVrsta> ${rowVr['naziv']} </option>";
					}	
					
						$selectAdresa = "";
					$sqlQuery = "SELECT A.*, G.naziv AS grad, U.naziv AS ulica
						 FROM adresa A 
						 LEFT JOIN ulica U ON A.id_ulica=U.id	
						 LEFT JOIN grad G ON A.id_grad=G.id
						 ORDER BY G.naziv, U.naziv";
					$resAdr = mysqli_query($conn, $sqlQuery);
					while($rowAdr = mysqli_fetch_assoc($resAdr)){	
						$selectedAdresa = "";
						
						if($row['id_adresa'] == $rowAdr['id'])
							$selectedAdresa = "selected";
						
						$selectAdresa .= "<option value=${rowAdr['id']} $selectedAdresa> ${rowAdr['ulica']} ${rowAdr['broj']}, ${rowAdr['grad']} </option>";
					}	
					
						$selectGrad = "<option value='-1'> - Izaberite grad - </option>";
					$sqlQuery = "SELECT * FROM grad ORDER BY naziv";
					$res = mysqli_query($conn, $sqlQuery);
					while($rowGr = mysqli_fetch_assoc($res)){	
						$selectedGrad = "";
						if(isset($_REQUEST['grad'])){
							if($rowGr['id'] == $_REQUEST['grad'])
								$selectedGrad = "selected";
						}
						$selectGrad .= "<option value=${rowGr['id']} $selectedGrad> ${rowGr['naziv']} </option>";
					}
		?>
		</header>
			<main>
				<form name="unos" action="" method="post">
					<table class="tabelaUnos" align="center" border="1" >
						<caption>IZMENA KORISNIKA</caption>
						<tr>
							<td>Ime i prezime: </td>
							<td><input type="text" required name="ime" value="<?=$row['ime_prezime'];?>"></td>
						</tr>
						<tr>
							<td>Grad: </td> 
							<td><select name="grad" id="grad" onChange="ajaxAdrese();"><?=$selectGrad?></select></td>
						</tr>
						<tr>
							<td>Adresa: </td>
							<td width="70%"><select name="adresa" id="adresa"><?=$selectAdresa?></select></td>
						</tr>
						<tr>
							<td>Broj telefona: </td>
							<td><input type="text" required name="telefon" value="<?=$row['br_tel'];?>"></td>
						</tr>
						<tr>
							<td>Korisnik: </td>
							<td><input type="text" required name="korisnik" value="<?=$row['username'];?>"></td>
						</tr>
						<tr>
							<td>Lozinka: </td>
							<td><input type="text" required name="lozinka" ></td>
						</tr>
						<tr>
							<td>Vrsta korisnika: </td>
							<td><select name="vrsta"><?=$selectVrsta?></select></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="snimi" value="Snimi izmenu korisnika u bazu"></td>
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
					$ime = validacijaPodatka($_REQUEST['ime']);
					$telefon = validacijaPodatka($_REQUEST['telefon']);
					$korisnik = validacijaPodatka($_REQUEST['korisnik']);
					$lozinka = validacijaPodatka($_REQUEST['lozinka']);
					
					$lozinka = password_hash($lozinka,PASSWORD_DEFAULT);
					
					$sqlQuery = "UPDATE korisnik SET ime_prezime = '$ime',id_adresa = ${_REQUEST['adresa']},br_tel ='$telefon',
						username='$korisnik', password='$lozinka', id_vrsta_kor=${_REQUEST['vrsta']} 
						WHERE id=".$_REQUEST['id'];
						
					$res = mysqli_query($conn, $sqlQuery);
					if(!$res)
						greska("Korisnik nije izmenjen! <br> opis: ".mysqli_error($conn));
					else
						header("Location: pregled.php");	
				}		
			}
			else{
				greska("Nije korektan id korisnika!");
			}
		}
		else{
			greska("Ne prepoznajemo akciju");
		}
	?>
<script>
	
function ajaxAdrese(){
	var xmlhttp = createRequest();
	
	xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("adresa").innerHTML = this.responseText;
		}
	}
	
	var grad = document.getElementById("grad").value;
		
	xmlhttp.open("GET", "ajaxProcesAdrese.php?idGrad="+grad, true);
	xmlhttp.send();
}
</script>