<?php
//http://localhost/dashboard/test/6/index.php

function dbconnect(){
  $user = 'u47597';
  $pass = '4080021';
  $db = new PDO('mysql:host=localhost;dbname=u47597', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  return $db;
}

$is_admin = false;
if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])){
  $login = $_SERVER['PHP_AUTH_USER'];
  $pass = md5($_SERVER['PHP_AUTH_PW']);
  $db=dbconnect();
  try {
    $stmt = $db->prepare("SELECT adminID FROM project4_admins where login = :login AND pass = :pass");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':pass', $pass);
    $stmt->execute();
    $adminID=$stmt->fetchColumn();
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  if(empty(!$adminID)){
    $is_admin = true;
  }
}




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
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';

    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('<br/>Вы можете войти с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['name'] = empty($_COOKIE['name_error']) ? '0' : $_COOKIE['name_error'];
  $errors['email'] = empty($_COOKIE['email_error']) ? '0' : $_COOKIE['email_error'];
  $errors['birth'] = !empty($_COOKIE['birth_error']);
  $errors['pol'] = !empty($_COOKIE['pol_error']);
  $errors['konechnosti'] = !empty($_COOKIE['konechnosti_error']);
  $errors['powers'] = !empty($_COOKIE['powers_error']);
  $errors['biography'] = empty($_COOKIE['biography_error']) ? '0' : $_COOKIE['biography_error'];
  $errors['check'] = !empty($_COOKIE['check_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['name']=='1') {
    setcookie('name_error', '', 100);
    $messages[] = '<div class="error">Укажите имя.</div>';
  }
  if ($errors['name']=='2') {
    setcookie('name_error', '', 100);
    $messages[] = '<div class="error">Неверный формат имени.<br />';
    $messages[] = 'Допустимые символы: A-Z, a-z, А-Я, а-я, пробельные символы и "-"<br />';
    $messages[] = 'Например: Александр</div>';
  }
  if ($errors['email']=='1') {
    setcookie('email_error', '', 100);
    $messages[] = '<div class="error">Укажите email.</div>';
  }  
  if ($errors['email']=='2') {
    setcookie('email_error', '', 100);
    $messages[] = '<div class="error">Неверный формат почты.<br />';
        $messages[] = 'Например: example@mail.com</div>';
  }  
  if ($errors['birth']) {
    setcookie('birth_error', '', 100);
    $messages[] = '<div class="error">Укажите год рождения.</div>';
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
   if ($errors['biography']=='1') {
    setcookie('biography_error', '', 100);
    $messages[] = '<div class="error">Напишите биографию.</div>';
  }
  if ($errors['biography']=='2') {
    setcookie('biography_error', '', 100);
    $messages[] = '<div class="error">Неверный формат биографии.';
    $messages[] = 'Допустимые символы: A-Z, a-z, А-Я, а-я, пробельные символы и "-", ",", "."</div>';
  }
   if ($errors['check']) {
    setcookie('check_error', '', 100);
    $messages[] = '<div class="error">Отметьте чекбокс.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['birth'] = empty($_COOKIE['birth_value']) ? '' : strip_tags($_COOKIE['birth_value']);
  $values['pol'] = empty($_COOKIE['pol_value']) ? '' : strip_tags($_COOKIE['pol_value']);
  $values['konechnosti'] = empty($_COOKIE['konechnosti_value']) ? '' : strip_tags($_COOKIE['konechnosti_value']);
  $values['powers'] = empty($_COOKIE['powers_value']) ? '' : unserialize($_COOKIE['powers_value']);
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : strip_tags($_COOKIE['biography_value']);
  $values['check'] = empty($_COOKIE['check_value']) ? '' : strip_tags($_COOKIE['check_value']);

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.

  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])||$is_admin) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    if(!$is_admin){
      printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
      print '<br/><a href="login.php">Выйти</a>';
    }
    else{
      print '<form action="" method="POST">
               <input name="cancel" type="submit" value="Отмена">
             </form>';
    }
    $db = dbconnect();
    try {
      $personID=$is_admin ? $_COOKIE['uid'] : $_SESSION['uid'];
      $stmt = $db->prepare("SELECT name, email, birth, pol, konechnosti, biography FROM project4 where ID = :personID");
      $stmt->bindParam(':personID', $personID);
      $stmt->execute();
      $val=array();
      $val = $stmt->fetch();
      $values['name'] = strip_tags($val['name']);
      $values['email'] = strip_tags($val['email']);
      $values['birth'] = strip_tags($val['birth']);
      $values['pol'] = strip_tags($val['pol']);
      $values['konechnosti'] = strip_tags($val['konechnosti']);
      $values['biography'] = strip_tags($val['biography']);
      $values['check'] = 'on';

      $stmt = $db->prepare("SELECT power FROM project4_powers where personID = :personID");
      $stmt->bindParam(':personID', $personID);
      $stmt->execute();
      $values['powers'] = array(0,0,0);
      for($power=$stmt->fetchColumn(); $power!=false; $power=$stmt->fetchColumn()){
        if ($power=='immortal')
          $values['powers'][0]=1;
        if ($power=='passing through walls')
          $values['powers'][1]=1;
        if ($power=='levitation')
          $values['powers'][2]=1;
      }
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
  else{
    print '<a href="login.php">Войти</a>';
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else{
if(!empty($_POST['cancel'])){
  header('Location: admin.php');
  exit();
}
// Проверяем ошибки.
$bioreg="/^\s*[\w\s\.йцукенгшщзхъфывапролджэячсмитьбюёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ,-]*$/";
$reg="/^([a-zA-ZйцукенгшщзхъфывапролджэячсмитьбюёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ\s-])+$/";
$mailreg="/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/";

$errors = FALSE;
$cookie_error_time= time() + 24 * 60 * 60;
$cookie_value_time= time() + 365 * 24 * 60 * 60;
if (empty($_POST['name'])) {
    setcookie('name_error', '1', $cookie_error_time);
    $errors = TRUE;
    setcookie('name_value','', 100);
}
else{
    if(!preg_match($reg,$_POST['name'])){
      setcookie('name_error', '2', $cookie_error_time);
      $errors = TRUE;
    }
  setcookie('name_value', $_POST['name'], $cookie_value_time);
}
if (empty($_POST['email'])) {
    setcookie('email_error', '1', $cookie_error_time);
    $errors = TRUE;
    setcookie('email_value','', 100);
}
else{
    if(!preg_match($mailreg,$_POST['email'])){
      setcookie('email_error', '2', $cookie_error_time);
      $errors = TRUE;
    }
  setcookie('email_value', $_POST['email'], $cookie_value_time);
}
if (empty($_POST['birth'])||!is_numeric($_POST['birth'])) {
    setcookie('birth_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
  setcookie('birth_value', $_POST['birth'], $cookie_value_time);
}
if (empty($_POST['pol'])||!preg_match('/^[mw]$/',$_POST['pol'])) {
    setcookie('pol_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
  setcookie('pol_value', $_POST['pol'], $cookie_value_time);
}
if (empty($_POST['konechnosti'])||!preg_match('/^[1-4]$/',$_POST['konechnosti'])) {
    setcookie('konechnosti_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
  setcookie('konechnosti_value', $_POST['konechnosti'], $cookie_value_time);
}
if (empty($_POST['powers'])||!is_array($_POST['powers'])) {
    setcookie('powers_error', '1', $cookie_error_time);
    $errors = TRUE;
}
else{
  $powers = array(0, 0, 0);
  foreach($_POST['powers'] as $power){
    if ($power=='1')
      $powers[0]=1;
    if ($power=='2')
      $powers[1]=1;
    if ($power=='3')
      $powers[2]=1;
  }
  setcookie('powers_value', serialize($powers), $cookie_value_time);
}
if (empty($_POST['biography'])) {
    setcookie('biography_error', '1', $cookie_error_time);
    $errors = TRUE;
    setcookie('email_value','', 100);
}
else{
  if (!preg_match($bioreg,$_POST['biography'])){
    setcookie('biography_error', '2', $cookie_error_time);
    $errors = TRUE;
  }
  setcookie('biography_value', $_POST['biography'], $cookie_value_time);
}
if (empty($_POST['check'])) {
    setcookie('check_error', '1', $cookie_error_time);
    $errors = TRUE;
    setcookie('check_value', '', $cookie_value_time);
}
else{
  setcookie('check_value', $_POST['check'], $cookie_value_time);
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


// Проверяем меняются ли ранее сохраненные данные или отправляются новые.
if (!empty($_COOKIE[session_name()]) &&
    session_start() && !empty($_SESSION['login'])||$is_admin) {
  // TODO: перезаписать данные в БД новыми данными,
  // кроме логина и пароля.
  $db = dbconnect();

  try {
    $stmt = $db->prepare("UPDATE project4 SET name = :name, email = :email, birth = :birth, pol = :pol, konechnosti = :konechnosti, biography = :biography, date = :date WHERE ID = :personID");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':birth', $birth);
    $stmt->bindParam(':pol', $pol);
    $stmt->bindParam(':konechnosti', $konechnosti);
    $stmt->bindParam(':biography', $biography);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':personID', $personID);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birth = $_POST['birth'];
    $pol =$_POST['pol'];
    $konechnosti = $_POST['konechnosti'];
    $biography = $_POST['biography'];
    $date = date('Y-m-d');
    $personID=$is_admin ? $_COOKIE['uid'] : $_SESSION['uid'];
    $stmt->execute();

    $stmt = $db->prepare("DELETE FROM project4_powers where personID = :personID");
    $stmt->bindParam(':personID', $personID);
    $stmt->execute();

  
    foreach($_POST['powers'] as $power){
      if(!empty($power)){
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
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  if($is_admin){
   setcookie('save_admin', '1');
   setcookie('uid', '', 100);
   header('Location: admin.php');
   exit();
  }
}
else {
  // Генерируем уникальный логин и пароль.
  // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
  $login = uniqid();
  $pass = rand(1000,9999);
  // Сохраняем в Cookies.
  setcookie('login', $login);
  setcookie('pass', $pass);



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
    $date = date('Y-m-d');
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
    for($test=$stmt->fetchColumn();$test!=false;$test=$stmt->fetchColumn()){
      $personID=$test;
    }
  

    foreach($_POST['powers'] as $power){
      if(!empty($power)){
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

    $stmt = $db->prepare("INSERT INTO project4_users (login, pass, personID) VALUES (:login, :pass, :personID)");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':pass', $pass);
    $stmt->bindParam(':personID', $personID);
    $pass=md5($pass);
    $stmt->execute();
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
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