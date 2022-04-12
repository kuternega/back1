<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['birth'] = !empty($_COOKIE['birth_error']);
  $errors['pol'] = !empty($_COOKIE['pol_error']);
  $errors['konechnosti'] = !empty($_COOKIE['konechnosti_error']);
  $errors['powers'] = !empty($_COOKIE['powers_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['check'] = !empty($_COOKIE['check_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['name']) {
    setcookie('name_error', '', 100);
    $messages[] = '<div class="error">Укажите имя.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100);
    $messages[] = '<div class="error">Укажите email.</div>';
  }  
  if ($errors['birth']) {
    setcookie('birth_error', '', 100);
    $messages[] = '<div class="error">Укажите дату рождения.</div>';
  }
   if ($errors['pol']) {
    setcookie('pol_error', '', 100);
    $messages[] = '<div class="error">Укажите пол.</div>';
  }
   if ($errors['konechnosti']) {
    setcookie('konechnosti_error', '', 100);
    $messages[] = '<div class="error">Укажите количество конечностей.</div>';
  }
   if ($errors['powers']) {
    setcookie('powers_error', '', 100);
    $messages[] = '<div class="error">Выберите суперспособности.</div>';
  }
   if ($errors['biography']) {
    setcookie('biography_error', '', 100);
    $messages[] = '<div class="error">Напишите биографию.</div>';
  }
   if ($errors['check']) {
    setcookie('check_error', '', 100);
    $messages[] = '<div class="error">Отметьте чекбокс.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['birth'] = empty($_COOKIE['birth_value']) ? '' : $_COOKIE['birth_value'];
  $values['pol'] = empty($_COOKIE['pol_value']) ? '' : $_COOKIE['pol_value'];
  $values['konechnosti'] = empty($_COOKIE['konechnosti_value']) ? '' : $_COOKIE['konechnosti_value'];
  $values['powers'] = empty($_COOKIE['powers_value']) ? '' : $_COOKIE['powers_value'];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['check'] = empty($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else{
// Проверяем ошибки.
$errors = FALSE;
$cookie_error_time= time() + 24 * 60 * 60;
$cookie_value_time= time() + 365 * 24 * 60 * 60;
if (empty($_POST['name'])) {
    setcookie('name_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('name_value', $_POST['name'], $cookie_value_time);
}
if (empty($_POST['email'])) {
    setcookie('email_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('email_value', $_POST['email'], $cookie_value_time);
}
if (empty($_POST['birth'])) {
    setcookie('birth_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('birth_value', $_POST['birth'], $cookie_value_time);
}
if (empty($_POST['pol'])) {
    setcookie('pol_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('pol_value', $_POST['pol'], $cookie_value_time);
}
if (empty($_POST['konechnosti'])) {
    setcookie('konechnosti_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('konechnosti_value', $_POST['konechnosti'], $cookie_value_time);
}
if (empty($_POST['powers'])) {
    setcookie('powers_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('powers_value', $_POST['powers'], $cookie_value_time);
}
if (empty($_POST['biography'])) {
    setcookie('biography_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('biography_value', $_POST['biography'], $cookie_value_time);
}
if (empty($_POST['check'])) {
    setcookie('check_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
	setcookie('name_value', $_POST['name'], $cookie_value_time);
}
if ($errors) {
    header('Location: index.php');
    exit();
}
else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
	setcookie('email_error', '', 100000);
    setcookie('birth_error', '', 100000);
    setcookie('pol_error', '', 100000);
    setcookie('konechnosti_error', '', 100000);
    setcookie('powers_error', '', 100000);
    setcookie('biography_error', '', 100000);
    setcookie('check_error', '', 100000);
  }


// Сохранение в базу данных.

$user = 'u47597';
$pass = '4080021';
$db = new PDO('mysql:host=localhost;dbname=u47597', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO project3 (name, email, birth, pol, konechnosti, powers, biography) VALUES (:name, :email, :birth, :pol, :konechnosti, :powers, :biography, :check)");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':birth', $birth);
  $stmt->bindParam(':pol', $pol);
  $stmt->bindParam(':konechnosti', $konechnosti);
  $stmt->bindParam(':powers', $powers);
  $stmt->bindParam(':biography', $biography);
  $stmt->bindParam(':check', $check);
  $name = $_POST['name'];
  $email = $_POST['email'];
  $birth = $_POST['birth'];
  $pol =$_POST['pol'];
  $konechnosti = $_POST['konechnosti'];
  $powers = $_POST['powers'];
  $biography = $_POST['biography'];
  $check = time();
  $stmt->execute();
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(array('label'=>'perfect', 'color'=>'green'));
 
//Еще вариант
/*
$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/
//Еще вариант
/*
  $stmt = $db->prepare("INSERT INTO application (name) SET name = ?");
  $stmt -> execute(array('fio'));
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
  setcookie('save', '1');
  header('Location: index.php');
}










// *************
// TODO: тут необходимо проверить правильность заполнения всех остальных полей.
// Сохранить в Cookie признаки ошибок и значения полей.
// *************
