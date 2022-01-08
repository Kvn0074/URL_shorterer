<?php
if(isset($_GET['q'])){
	$shortcut = htmlspecialchars($_GET['q']);

	$dataBase = new PDO('mysql:host=localhost;dbname=shobet;charset=utf8', 'root', 'root');

	$request = $dataBase->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
	$request->execute(array($shortcut));

	while($result = $request->fetch()){

		if ($result['x'] != 1){
			// $error = true; <= autre possibilité. (voir html)
			header('location:../?error=true&message=Unknow URL');
			exit();
		}
	}

	$request = $dataBase->prepare('SELECT * FROM links WHERE shortcut = ?');
	$request->execute(array($shortcut));

	while($result = $request->fetch()){

		header('location: '.$result['url']);
		exit();
	}
}

if(isset($_POST['url'])){
	$url = $_POST['url'];

	if(!filter_var($url, FILTER_VALIDATE_URL)){
		header('location: ../?error=true&message=Sorry invalid URL');
		exit();
	}

	$shortcut = crypt($url, rand());

	$dataBase = new PDO('mysql:host=localhost;dbname=shobet;charset=utf8', 'root', 'root');

	$request = $dataBase->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
	$request->execute(array($url));

	while ($result = $request->fetch()){
		
		if ($result['x'] != 0){
			header('location:../?error=true&message=Url already use');
			exit();
		}
	}
	
	$request = $dataBase->prepare('INSERT INTO links(url, shortcut) VALUES (?, ?)');
	$request->execute(array($url, $shortcut));

	header('location:../?short='.$shortcut);
	exit();
}

?>

<!DOCTYPE html>

<html lang="fr" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>SHO/BET</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/png" href="design/pictures/favicoRocket.png">
</head>

<body>

	<section id="hello">
		<div class="container">
			<header>
			<img id="logo" src="design/pictures/rocketWTE.png">
			</header>

			<h1>Sho/bet</h1>

			<h2>Because Shorter is Better</h2>

			<form method='post' action='index.php'>
				<input id="input" type='url' name='url' placeholder="put your URL" required/>
				<input type='submit' value='Transform'></button>
			</form>
						<!-- c'est ici qu'on peu reutiliser $error a la place du isset (attention pas si simple)-->
			<?php if(isset($_GET['error']) && isset($_GET['message'])){ ?>

				<div class="center">
					<div id="result">
						<b><?php echo htmlspecialchars($_GET['message']); ?></b>
					</div>
				</div>

			<?php } else if (isset($_GET['short'])) { ?>
				
				<div class="center">
					<div id="result">
						<b>SHORT URL :</b>
						<a id="shortLink" href="http://localhost:8888/?q=<?php echo htmlspecialchars($_GET['short']);?>" target="_blank">http://localhost:8888/?q=<?php echo htmlspecialchars($_GET['short']);?></a>
					</div>
				</div>

			<?php } ?>

		</div>	
	</section>

	<section id="brand">
		<div class="container">

			<h3>They have faith in us</h3>
			
			<img class="pictures" src="design/pictures/aplab.png">
			<img class="pictures" src="design/pictures/blame.png">
			<img class="pictures" src="design/pictures/cybe.png">
			<img class="pictures" src="design/pictures/umco.png">

		</div>
	</section>


	<section id="trademark">
		<div class="container">
			<footer>
			<h3> SHO/BET is powered by</h3>
			<h2>___ ROCKET GROUP<span id="r_logo">®</span> ___</h2>
			<p id="contact"><a href="#">Contact Us</a><span id="union"> - </span><a href="#">About Us</a></p>
			</footer>
		</div>
	</section>

<script src="script.js" type="text/javascript"></script>
</body>

</html>