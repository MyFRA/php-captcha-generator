<?php 

session_start();

class Captcha {

	private $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	private $captcha = '';

	private $captchaAmount = 4;

	private $randomLinesAmount;

	private $noisePointsAmount;

	private $background;

	private $backgroundWidth = 100;

	private $backgroundHeight = 100;

	public function __construct($parrams)
	{
		$this->randomLinesAmount = $parrams['random_lines'];
		$this->noisePointsAmount = $parrams['noise_points'];

		$this->init();
	}

	private function init()
	{
		$this->createBackground();
		$this->generateCaptcha();
		$this->addCaptchaToBackground();
		$this->addRandomLines();
		$this->addNoisePoints();
	}

	private function addRandomLines()
	{
		$color = imagecolorallocate($this->background, 100, 100, 100);

		for( $i = 0; $i < $this->randomLinesAmount; $i++ ) {
			$xStartPosition = rand(0, $this->backgroundWidth);
			$xEndPosition = rand(0, $this->backgroundWidth);

			$yStartPosition = rand(0, $this->backgroundHeight);
			$yEndPosition = rand(0, $this->backgroundHeight);

			imageline($this->background, $xStartPosition, $yStartPosition, $xEndPosition, $yEndPosition, $color);
		}
	}

	private function addNoisePoints()
	{
		$color = imagecolorallocate($this->background, 0, 0, 0);

		for( $i = 0; $i < $this->noisePointsAmount; $i++ ) {
			$xPosition = rand(0, $this->backgroundWidth);
			$yPosition = rand(0, $this->backgroundHeight);

			imageline($this->background, $xPosition, $yPosition, $xPosition, $yPosition, $color);
		}
	}

	private function createBackground()
	{
		$this->background = imagecreatetruecolor($this->backgroundWidth, $this->backgroundHeight);
		$backgroundColor  = imagecolorallocate($this->background, 200, 200, 200);
		imagefill($this->background, 0, 0, $backgroundColor);
	}

	private function generateCaptcha()
	{
		for( $i = 0; $i < $this->captchaAmount; $i++ ) {
			$this->captcha .= $this->charset[rand(0, strlen($this->charset) - 1)];
		}
	}

	private function addCaptchaToBackground()
	{
		$captcha 			= [];
		$xPosition 			= 10;
		$yArrayPosition 	= [];
		$yUsedPosition 		= [];
		$yPos 				= 7;

		for( $i = 0; $i < $this->captchaAmount; $i++ ) {
			$yArrayPosition[] = $yPos;

			$yPos += 23;
		}

		for( $i = 0; $i < $this->captchaAmount; $i++ ) {

			$yPosition = $yArrayPosition[array_rand($yArrayPosition)];
			while ( in_array($yPosition, $yUsedPosition) ) {
				$yPosition = $yArrayPosition[array_rand($yArrayPosition)];
			}
			$yUsedPosition[] = $yPosition;


			$degreeRotate 		= rand(-20, 20);
			while( $degreeRotate > -10 && $degreeRotate < 10 ) {
				$degreeRotate = rand(-30, 30);
			}


			$captcha[$i] 				= imagecreatetruecolor(30, 30);
			$captchaBackground 			= imagecolorallocate($captcha[$i], 200, 200, 200);
			imagefill($captcha[$i], 0, 0, $captchaBackground);

			$captcha[$i] 				= imagerotate($captcha[$i], $degreeRotate, $captchaBackground);
			$textColor   				= imagecolorallocate($captcha[$i], 0, 0, 0);
			imagestring($captcha[$i], 5, 0, 0, ' ' . $this->captcha[$i] . ' ', $textColor);

			$captcha[$i] 				= imagerotate($captcha[$i], $degreeRotate, $captchaBackground);

			imagecopymerge($this->background, $captcha[$i], $xPosition, $yPosition, 0, 0, 30, 30, 100);
			$xPosition += 20;
		}

	}

	public function displayCaptcha()
	{
		header('Content-Type: image/png');
		imagepng($this->background);
		imagedestroy($this->background);
	}

	public function getCaptchaText()
	{
		return $this->captcha;
	}
}

$captcha = new Captcha([
	'random_lines'	=> 3,
	'noise_points'	=> 40,
]);

$captcha->displayCaptcha();
$_SESSION['captcha_code'] = $captcha->getCaptchaText();

?>