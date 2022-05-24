<?php
function dbconnect(){
  $user = 'u47597';
  $pass = '4080021';
  $db = new PDO('mysql:host=localhost;dbname=u47597', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  return $db;
}
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
$db = dbconnect();

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO project4 (name, email, birth, pol, konechnosti, biography, date) VALUES (:name, :email, :birth, :pol, :konechnosti, :biography, :date)");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':birth', $birth);
  $stmt->bindParam(':pol', $pol);
  $stmt->bindParam(':konechnosti', $konechnosti);
  $stmt->bindParam(':biography', $biography);
  $stmt->bindParam(':date', $date);
  $name = $_POST['name'];
  $email = $_POST['email'];
  $birth = $_POST['birth'];
  $pol =$_POST['pol'];
  $konechnosti = $_POST['konechnosti'];
  $biography = $_POST['biography'];
  $date = date('Y-m-d');;
  $stmt->execute();

  $stmt = $db->prepare("SELECT id FROM project4 WHERE name = :name AND email = :email AND birth = :birth AND pol = :pol AND konechnosti = :konechnosti AND biography = :biography AND date = :date");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':birth', $birth);
  $stmt->bindParam(':pol', $pol);
  $stmt->bindParam(':konechnosti', $konechnosti);
  $stmt->bindParam(':biography', $biography);
  $stmt->bindParam(':date', $date);
  $stmt->execute();
  $personID=$stmt->fetchColumn();
foreach($_POST['powers'] as $power){
    if($power=='1')
      $power_name="immortal";
    if($power=='2')
      $power_name="passing through walls";
    if($power=='3')
      $power_name="levitation";
    $stmt = $db->prepare("INSERT INTO project4_powers (personID, power) VALUES (:personID, :power)");
    $stmt->bindParam(':personID', $personID);
    $stmt->bindParam(':power', $power_name);
    $stmt->execute();
  }
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
