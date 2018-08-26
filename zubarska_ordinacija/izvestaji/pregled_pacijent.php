<?php
include_once "../inc/header.php";
require '../inc/konekcija.php';
session_start();
autentifikacija($_SESSION);

?>
<a href="../pocetna.php" class="nazad"><img src="../img/back.jpg" width=30 height=30></a>
			
	</header>

	<main>
			<table class="izvestaj" align="center">
			
	<?php
										
			$sqlQuery = "SELECT P.*,Z.*, U.*, U.naziv as usluga, U.cena as cena, K.ime_prezime as ime, K.id_vrsta_kor
						FROM zakazivanje_usluge P
						LEFT JOIN zakazivanje Z ON P.id_zakazivanje=Z.id
						LEFT JOIN usluga U ON P.id_usluga=U.id
						LEFT JOIN korisnik K ON Z.doktor=K.id
						WHERE Z.id_korisnik=${_SESSION['id']} AND ${_SESSION['vrsta_korisnika']}=3 ";
						
			
						
			$res = mysqli_query($conn, $sqlQuery);
							
			$pomocna = 0;
			while($row = mysqli_fetch_assoc($res)){	
			
			if(strtotime(date('Y-m-d H:i')) > strtotime($row['vreme_start'])){
				
				if($pomocna != $row['id_zakazivanje']){
				
	?>						
					<tr class="vrsta" >
						<td>DATUM I VREME</td>
						<td>DOKTOR</td>
					</tr>
					
					<tr align="center">
						<td><?=date('d.m.Y H:i',strtotime($row['vreme_start']))?></td>
						<td><?=$row['ime']?></td>
					</tr>
										
					<tr>
						<td class="vrsta" colspan="4" >USLUGE</td>
					</tr>
	<?php
		}
	?>
					<tr>
						<td align="left"  ><?=$row['usluga']?></td>
						<td align="right"><?=$row['cena']?> din</td>
					</tr>
					
	<?php
					$pomocna = $row['id_zakazivanje'];		
					
				}
			}
			
	?>	
								
			</table>
			
		</main>
	</body>
	</html>