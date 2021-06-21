<?php 
session_start();

if( isset($_POST['submit']) ) {
	if( $_POST['captcha_code'] == $_SESSION['captcha_code'] ) {
		$_SESSION['status'] = [
			'success'	=> true,
			'message'	=> 'Right Code',
		];
	} else {
		$_SESSION['status'] = [
			'success'	=> false,
			'message'	=> 'Wrong Captcha Code',
		];
	}

	header("Location: ./index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>PHP Verification Captcha</title>
	<link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>

	<div class="container">
		<div class="box">
			<h1>PHP Verification Captcha</h1>

			<?php if( isset($_SESSION['status']) ) { ?>
				<?php if( $_SESSION['status']['success'] ) { ?>
					<p><b>Success </b> <?= $_SESSION['status']['message'] ?></p>
				<?php } else { ?>
					<p><b>Error! </b> <?= $_SESSION['status']['message'] ?></p>
				<?php } ?>
			<?php } ?>

			<form method="POST" action="./">
				<img src="./Captcha.php">
				<div class="form-group">
					<input type="text" name="captcha_code" autocomplete="off" placeholder="Masukan Kode Captcha">
					<button type="submit" name="submit">SUBMIT</button>
				</div>
			</form>
		</div>
	</div>

</body>
</html>
