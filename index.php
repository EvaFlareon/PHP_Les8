<?php

session_start();

if (!empty($_SESSION['user'])) {
    header('Location: list.php');
}

$errors = [];

if (!empty($_POST)) {
    foreach (glob("userdata/*.json") as $userdata) {
        if ($userdata == 'userdata/'.$_POST['login'].'.json') {
            $content = file_get_contents($userdata);
            $result = json_decode($content, true);
        }
        if ($_POST['login'] == $result[0]['login'] && $_POST['password'] == $result[0]['password']) {
            $_SESSION['user'] = $result;
            header('Location: list.php');
        }
    }
    $errors[] = 'Неверный логин или пароль';

    if ($_POST['login'] === '' && $_POST['password'] === '' && $_POST['guest']) {
        $_SESSION['user'] = $_POST['guest'];
        header('Location: list.php');
    } 
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
    <h1>Авторизация</h1>
    <ul>
        <?php foreach ($errors as $error): ?>
        <li><?= $error ?></li>
        <? endforeach; ?>
    </ul>
    <form action="" method="POST">
        <label>Логин</label>
        <input type="text" name="login">
        <br>
        <label>Пароль</label>
        <input type="password"  name="password">
        <br>
        <label>Если у Вас нет логина и пароля, то введите свое имя в поле ниже, чтобы войти как гость</label>
        <br>
        <input type="text" name="guest">
        <br>
        <input type="submit" value="Войти">
    </form>
</body>
</html>