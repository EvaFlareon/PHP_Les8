<?php

session_start();

if (empty($_SESSION['user'])) {
	header('Location: index.php');
}

if ($_POST['exit']) {
    session_destroy();
    header('Location: index.php');
}

if (is_string($_SESSION['user'])) {
	$username = $_SESSION['user'];
	$vis = 'display: none;';
} else {
	$username = $_SESSION['user'][0]['username'];
	$vis = '';
}

?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<title>Список тестов</title>
	</head>
	<body>
		<h1>Приветствую, <?= $username; ?></h1>
		<h2>Список доступных тестов:</h2>
		<a href="admin.php" style="<?= $vis; ?>">Добавить тест</a>
		<form action="test.php" method="get">
			<ol>
				<?php foreach (glob("files/*.json") as $filename) { ?>
				<li>
					<input type="radio" name="filename" value="<?= $filename ?>">
					<?php 
						$content = file_get_contents($filename);
						$result = json_decode($content, true);
						echo $result[0]['name']."<br>";
					?>

				</li>
				<?php } ?>
			</ol>
			<input type="submit" name="start" value="Выполнить">
			<input type="submit" name="delete" value="Удалить тест" style="<?= $vis; ?>">
		</form>
		<form action="" method="post">
			<input type="submit" name="exit" value="Выход">
		</form>
	</body>
</html>