<?php include_once "inc/header.php";
include_once "inc/funkcije.php";
include_once "inc/konstante.php";
session_start();
autentifikacija($_SESSION);
		
 ?>
 			
			<div id="navigacija">
               <nav>
					<ul id="meni">
	<?php				
						if($_SESSION['vrsta_korisnika'] == ADMIN){
	?>
                        <li id="podmeni"> <a href="#">Matični podaci</a>
			
							<ul>
								<li><a href="maticni/korisnici/pregled.php">Korisnici</a></li>
								<li ><a href="maticni/vrste_korisnika/pregled.php">Vrste korisnika</a></li>
								<li ><a href="maticni/usluge/pregled.php">Usluge</a></li>
								<li ><a href="maticni/vrsta_usluge/pregled.php">Vrste usluga</a></li>
								<li ><a href="maticni/adrese/pregled.php">Adrese</a></li>
								<li><a href="maticni/gradovi/pregled.php">Gradovi</a></li>
								<li ><a href="maticni/ulice/pregled.php">Ulice</a></li>
																					
							</ul>
							</li>	
	<?php				
						}
						if($_SESSION['vrsta_korisnika'] != DOKTOR){
	?>			
							<li ><a href="prometni/zakazivanje/pregled.php">Zakazivanje</a></li>
	<?php
						}
	?>
							<li id="podmeni"><a href="#">Izveštaji</a>
								<ul>
	<?php				
						if($_SESSION['vrsta_korisnika'] == PACIJENT){
	?>
								<li><a href="izvestaji/pregled_pacijent.php">Izveštaj</a></li>
	<?php				
						}
						if($_SESSION['vrsta_korisnika'] == DOKTOR){
	?>					
								<li ><a href="izvestaji/raspored_doktor.php">Raspored</a></li>
								<li ><a href="izvestaji/pregled_doktor.php">Izveštaj</a></li>
	<?php				
						}
						if($_SESSION['vrsta_korisnika'] == ADMIN){
	?>
								<li ><a href="izvestaji/pregled_zarade.php">Izveštaj</a></li>
	<?php
						}
	?>
								</ul>
							</li>
							<li ><a href="logout.php" onClick="return potvrdiAkciju('Zelite li da se odjavite?');">Odjava</a></li>
					</ul>
        		</nav>
			</div>
			
			    	
	</header>
		<br><br><br>
			<div id="animacija">
				<p> > > > DOBRO DOŠLI < < < </p>
			</div>
		<br><br><br>
			
			<img src="img/dete.jpg" id="deca" alt="slika deteta">
			
			<img src="img/popravka.jpg" id="popravka" alt="slika kod zubara">
			
			<img src="img/osmeh.jpg" id="osmeh" alt="nasmejana devojka">
		<div id="cistac"></div>	
		<br><br><br>
		<footer>
			<address>
			<p id="kontakt">Stomatološka ordinacija SMILE <br>
			Milivoja Rakića 10, Beograd <br>
			063/222-11-22  011/2222-111 <br>
			smile@gmail.com</p>
			</address>
			<h3 class="footer"><i> &copy; Copyright 2018 - Design by Katarina Stanisavljević  </i></h3>
	    </footer>
       <script>
	var meni = document.getElementById("meni");
	var sveStavke = meni.getElementsByTagName("li");
	
	for(var i=0; i<sveStavke.length; i++){
		if(sveStavke[i].id == "podmeni"){
			
			var podstavke = sveStavke[i].getElementsByTagName("ul")[0];
			podstavke.style.display = "none";
			
			sveStavke[i].onmouseover = function(){
				podstavke = this.getElementsByTagName("ul")[0];
				podstavke.style.display = "block";
			}
			
			sveStavke[i].onmouseout = function(){
				podstavke = this.getElementsByTagName("ul")[0];
				podstavke.style.display = "none";
			}
		}
	}
	</script>
	</body>
	</html>
