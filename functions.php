<?php
session_start();
// Для того чтобы выводить все ошибки и предупреждения
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * Получение списка пользователей
 * @return array|mixed
 */
function getUsers()
{
    $fileData = file_get_contents(__DIR__ . '/users.json');
    $users = json_decode($fileData, true);
    if (!$users) {
        return [];
    }
    return $users;
}
/**
 * Перенаправление на другую страницу
 * @param $page
 */
function redirect($page) {
    header("Location: $page.php");
    die;
}
/**
 * Проверка, является ли пользователь просто аутоидентифицированным
 * @return bool
 */
function isAuthorized()
{
    return !empty($_SESSION['user']);
}
/**
 * Проверка, является ли пользователь Админом
 * @return bool
 */
function isAdmin()
{
    return isAuthorized() && $_SESSION['user']['is_admin'] == 1;
}