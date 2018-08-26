<?php
include_once "../inc/header.php";
require '../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);


if(!proveraAdmin($_SESSION)){
	header("Location:../logout.php");
	
}
?>
<a href="../pocetna.php" class="nazad"><img src="../img/back.jpg" width=30 height=30> </a>
			
	</header>

		<main>
	<?php
				$doktor = "<option value='-1'> - Izaberite doktora - </option>";
			$sqlQuery = "SELECT K.*, V.naziv as vrsta, V.id as id
						FROM korisnik K
						LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
						WHERE id_vrsta_kor=2";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$doktor .= "<option value=${row['id']}> ${row['ime_prezime']} </option>";
			}
			
				$usluga = "<option value='-1'> - Izaberite uslugu - </option>";
			$sqlQuery = "SELECT * FROM usluga";
			$res = mysqli_query($conn, $sqlQuery);
			while($row = mysqli_fetch_assoc($res)){	
				$usluga .= "<option value=${row['id']}> ${row['naziv']} </option>";
			}
	?>
		
		<div class="filter">
			<form action="" name="filtriranje" >
				Pacijent: <input type="text" size="15" name="fltIme" value=<?php echo (isset($_REQUEST['fltIme']))?$_REQUEST['fltIme']:"";?>>
				<select name="fltDr" id="fltDr"><?=$doktor?></select>
				<select name="fltUsl" id="fltUsl"><?=$usluga?></select>
				<input type="submit" name="filtriraj" value="Primeni filtere">
				<input type="submit" name="reset" value="Resetuj">
			</form>
		</div>
		<br>
			<table class="zakazivanje" align="center">
			
	<?php
			$sqlUslovi = " 1=1 ";
						
			if(isset($_REQUEST['filtriraj'])){
				if($_REQUEST['fltIme']!=""){
					$ime = validacijaPodatka($_REQUEST['fltIme']);
					$sqlUslovi .= " AND K.ime_prezime LIKE '%$ime%'";
				}
				
				if(@$_REQUEST['fltDr']>0){
					$sqlUslovi .= " AND Z.doktor=${_REQUEST['fltDr']}";
				}
				
				if(@$_REQUEST['fltUsl']>0){
					$sqlUslovi .= " AND P.id_usluga=${_REQUEST['fltUsl']}";
				}
			}
			
			$sqlQuery = "SELECT P.*,Z.*,U.*,U.naziv as usluga, K.*,V.*
						FROM zakazivanje_usluge P
						LEFT JOIN zakazivanje Z ON P.id_zakazivanje=Z.id
						LEFT JOIN usluga U ON P.id_usluga=U.id
						LEFT JOIN korisnik K ON Z.id_korisnik=K.id
						LEFT JOIN vrsta_korisnika V ON K.id_vrsta_kor=V.id
						WHERE $sqlUslovi";
						
			
						
			$res = mysqli_query($conn, $sqlQuery);
							
			$pomocna = 0;
			$zarada=0;
			while($row = mysqli_fetch_assoc($res)){

			if(strtotime(date('Y-m-d H:i')) > strtotime($row['vreme_start'])){
				
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
						<td>PACIJENT</td>
						<td>DATUM I VREME</td>
						<td>DOKTOR</td>
					</tr>
					
					<tr align="center">
						<td><?=$row['ime_prezime']?></td>
						<td><?=date('d.m.Y H:i',strtotime($row['vreme_start']))?></td>
						<td><?=$doktor?></td>
					</tr>
										
					<tr>
						<td class="vrsta" colspan="4" >USLUGE</td>
					</tr>
	<?php
			}
	?>
					<tr>
						<td align="left"  ><?=$row['usluga']?></td>
						<td align="right"><?=number_format($row['cena'], 2, ",", ".")?> din</td>
					</tr>
				
	<?php
			
					$zarada +=$row['cena'];
					
					$pomocna = $row['id_zakazivanje'];		
					
				}	
			}			
			?>	
		</table>
		
			<br>
			
		<table class="filterZ">
			<tr align="center">
				<td colspan="4" align="right" ><i>Ukupno:</i></td>
				<td ><i><?=number_format($zarada, 2, ",", ".")?></i></td>
			</tr>
			
		</table>
				
		</main>
		
	<script>
	zapamtiSelektovano(document.filtriranje, 'fltDr', <?=isset($_REQUEST['fltDr'])?$_REQUEST['fltDr']:""?>);   //zapamti selektovane filtere
	zapamtiSelektovano(document.filtriranje, 'fltUsl', <?=isset($_REQUEST['fltUsl'])?$_REQUEST['fltUsl']:""?>);
</script>
	</body>
	</html>