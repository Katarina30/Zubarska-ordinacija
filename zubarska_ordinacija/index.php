<?php 
include_once "inc/header.php"; ?>
		
                
	</header>
	<main>
        <br><br><br><br><br>
	<div class="login">
        <form action=<?=htmlspecialchars($_SERVER['PHP_SELF'])?> method="post">
			<label for="korisnik"> Korisnik: </label>
			<br><input type="text" maxlength="30" name="korisnik" placeholder="Unesite korisničko ime..." class="forma" value="<?=(isset($_REQUEST['korisnik']))?$_REQUEST['korisnik']:""?>">
			<br><label for="lozinka"> Lozinka: </label>
			<br><input type="password"  maxlength="25" name="lozinka" placeholder="Unesite lozinku..."class="forma" value="<?=(isset($_REQUEST['lozinka']))?$_REQUEST['lozinka']:""?>">
			<br><input type="submit" class="button" name="logovanje" value="Uloguj se">
        </form>
	</div>
	</main>
	
	</body>
</html>	
	<?php
	
	if(isset($_REQUEST['logovanje'])){
		require 'inc/konekcija.php';
		
		$korisnik = validacijaPodatka($_POST['korisnik']);
		$lozinka = validacijaPodatka($_POST['lozinka']);
		
	
		
		$pass="";
		$sqlQuery = "SELECT * FROM korisnik WHERE username='$korisnik'";

		$res = mysqli_query($conn, $sqlQuery);
		while($row = mysqli_fetch_assoc($res)){				
			$password = $row['password'];
		
			if(password_verify($lozinka,$password)){
				$pass .=$password;
			}
		}
		
		$sqlQuery = "SELECT * FROM korisnik WHERE username='$korisnik' AND password='$pass'";

		$res = mysqli_query($conn, $sqlQuery);
		if($row = mysqli_fetch_assoc($res)){
			session_start();
			$_SESSION['id'] = $row['id'];
			$_SESSION['ime'] = $row['ime_prezime'];
			$_SESSION['korisnik'] = $row['username'];
			$_SESSION['vrsta_korisnika'] = $row['id_vrsta_kor'];
			$_SESSION['osvezena'] = time();
			header("Location: pocetna.php");
		}
		else{
			greska("Uneli ste pogrešnu lozinku i nemate pravo pristupa!");
		}
	}
	
	?>
    
	

