<?php

session_start();

if (empty($_SESSION['user'])) {
	header('Location: index.php');
}

if (is_string($_SESSION['user'])) {
	header('HTTP/1.1 403 Forbidden');
	echo 'Доступ запрещен';
	die;
}

$uploadfile = 'files/';

if (isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])) {
	if ($_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name']) && strpos($_FILES['userfile']['name'], '.json')) {
		$test = file_get_contents($_FILES['userfile']['tmp_name']);
		$result = json_decode($test, true);
		if (count($result) < 2) {
			echo 'Чего-то в этом файле не хватает'."<br>";
			echo "<a href='admin.php'>Вернуться к загрузке</a>";
			exit;
		}
		if (!is_string($result[0]['name'])) {
			echo 'У этого теста нет названия'."<br>";
			echo "<a href='admin.php'>Вернуться к загрузке</a>";
			exit;
		}
		for ($i = 1; $i < count($result); $i++) {
			if (count($result[$i]) < 3) {
				echo 'Чего-то в этом файле не хватает'."<br>";
				echo "<a href='admin.php'>Вернуться к загрузке</a>";
				exit;
			}
			if (!is_string($result[$i]['question'])) {
				echo 'У этого теста нет вопроса'."<br>";
				echo "<a href='admin.php'>Вернуться к загрузке</a>";
				exit;
			}
			if (!is_array($result[$i]['answers'])) {
				echo 'У этого теста нет перечня ответов'."<br>";
				echo "<a href='admin.php'>Вернуться к загрузке</a>";
				exit;
			}
			if (!is_string($result[$i]['answer'])) {
				echo 'У этого теста нет ответа для проверки'."<br>";
				echo "<a href='admin.php'>Вернуться к загрузке</a>";
				exit;
			}
		}
		move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile.date('YmdHis').$_FILES['userfile']['name']);
		header('Location: list.php');
		exit;
	} else {
		echo "<p style='color:red'>Ошибка: Файл ".$_FILES['userfile']['name']." с тестами не загружен</p>";
	}
}

?>
<form enctype="multipart/form-data" action="" method="POST">
	Формат файла для загрузки: *.json.<br>
	Максимально разрешенный размер файла: 3 МБ.<br>
	<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
	Файл теста: <input name="userfile" type="file"><br>
	<input type="submit" value="Отправить">
</form>
