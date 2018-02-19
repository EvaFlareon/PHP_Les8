<?php

session_start();

if (is_string($_SESSION['user'])) {
	$username = $_SESSION['user'];
} else {
	$username = $_SESSION['user'][0]['username'];
}

$content = $_GET['filename'];

if (is_null($content)) {
	header('HTTP/1.1 404 Not Found');
	echo 'Тест не выбран'."<br>";
	echo "<a href='list.php'>Вернуться к выбору тестов</a>";
	exit(1);
}

if ($_GET['delete']) {
	unlink($content);
	header('Location: list.php');
}

foreach (glob("files/*.json") as $filename) {
	if ($content == $filename) {
		$test = file_get_contents($filename);
		$result = json_decode($test, true);
		break;
	} else {
		continue;
	}
}

if ($_POST['complete']) {
	
	$user_answer = [];
	$t = 0;
	$f = 0;
	$p = 0;

	for ($i = 1; $i < 6; $i ++) {
		$user_answer[$i] = $_POST["$i"];
		if ($user_answer[$i] === $result[$i]['answer']) {
			$t += 1;
			$f += 0;
			$p += 0;
		} else if ($_POST["$i"] == False) {
			$t += 0;
			$f += 0;
			$p += 1;
		} else {
			$t += 0;
			$f += 1;
			$p += 0;
		}
	}
	
	
	$text = "Сертефикат на имя:\n".$username."\nПравильных ответов: ".$t."\nНеправильных ответов: ".$f."\nПропущено вопросов: ".$p;

	$image = imagecreatetruecolor(768, 593);

	$backColor = imagecolorallocate($image, 255, 255, 255);
	$textColor = imagecolorallocate($image, 139, 69, 19);

	$boxFile = __DIR__.'/sert.png';

	if (!file_exists($boxFile)) {
		echo 'Файл с картинкой не найден';
		exit;
	}

	$imBox = imagecreatefrompng($boxFile);

	imagefill($image, 0, 0, $backColor);
	imagecopy($image, $imBox, 0, 0, 0, 0, 768, 593);

	$fontFile = __DIR__.'/font.ttf';

	if (!file_exists($fontFile)) {
		echo 'Файл со шрифтом не найден';
		exit;
	}

	imagettftext($image, 38, 0, 110, 200, $textColor, $fontFile, $text);

	header('Content-Type: image/png');

	imagepng($image);

	imagedestroy($image);
	exit;
}

?>

<form action="" method="post">
	Пройдите тест <?= $result[0]['name'] ?>
	<?php for ($i = 1; $i < 6; $i++) { ?>
	<ol><?= $result[$i]['question'];
		for ($j = 0; $j < 3; $j++) { ?>
		<li>
			<input type="radio" name="<?= $i ?>" value="<?= $result[$i]['answers'][$j]; ?>">
			<?= $result[$i]['answers'][$j]; ?>
		</li>
		<?php } ?>
	</ol>
	<?php } ?>
	<br>
	<input type="submit" name="complete" value="Проверить">
</form>