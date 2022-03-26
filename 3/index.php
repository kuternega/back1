<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['name'])) {
  print('Укажите имя.<br/>');
  $errors = TRUE;
}
if (empty($_POST['email'])) {
  print('Укажите email.<br/>');
  $errors = TRUE;
}
if (empty($_POST['date'])) {
  print('Укажите дату рождения.<br/>');
  $errors = TRUE;
}
if (empty($_POST['check'])) {
  print('Чекбокс:)<br/>');
  $errors = TRUE;
}
if ($errors) {
  include('form.php');
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u47597';
$pass = '4080021';
$db = new PDO('mysql:host=localhost;dbname=u47597', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO project3 (name, email, birth, pol, konechnosti, powers, biography) VALUES (:name, :email, :birth, :pol, :konechnosti, :powers, :biography)");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':birth', $birth);
  $stmt->bindParam(':pol', $pol);
  $stmt->bindParam(':konechnosti', $konechnosti);
  $stmt->bindParam(':powers', $powers);
  $stmt->bindParam(':biography', $biography);
  $name = $_POST['name'];
  $email = $_POST['email'];
  $birth = $_POST['date'];
  $pol =$_POST['radio1'];
  $konechnosti = $_POST['radio2'];
  $powers = $_POST['powers'];
  $biography = $_POST['biography'];
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
header('Location: ?save=1');
