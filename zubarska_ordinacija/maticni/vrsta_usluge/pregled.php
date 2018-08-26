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
			&nbsp; <a href="azuriranje.php?akcija=n" class="unos"> > > > UNOS NOVE VRSTE USLUGA < < < </a>
			</div>
			
			<table class="tabela">
				<tr align="center">
					<td>ID</td>
					<td>USLUGA</td>
					<td>IZMENA</td>
					<td>BRISANJE</td>
				</tr>
	<?php
				
				$sqlQuery = "SELECT * FROM vrsta_usluge ORDER BY naziv";
				$res = mysqli_query($conn, $sqlQuery);
				while($row = mysqli_fetch_assoc($res)){	
	?>
				<tr align="center">
					<td><?=$row['id']?></td>
					<td><?=$row['naziv']?></td>
					<td><a href="azuriranje.php?akcija=i&id=<?=$row['id']?>"><img src="../../img/edit.jpg" width=20 height=20></a></td>
					<td><a href="azuriranje.php?akcija=b&id=<?=$row['id']?>" onClick="return potvrdiAkciju('Da li zelite da obrišete vrstu <?=$row['naziv']?>?');"><img src="../../img/remove.png" width=20 height=20></a></td>
				</tr>
	<?php
		}
	?>
			</table>
		</main>
	</body>
	</html>