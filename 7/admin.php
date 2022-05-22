<?php
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

if(!$is_admin){
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
function print_table_line($values,$columns,$token){
  print'<tr>';
  $PK=$columns[0];
  foreach ($columns as $col) {
    print'<td>'.$values[$col].'</td>';
  }
  print '<form action="" method="POST">
           <input name="PK" value="'.$values[$PK].'" hidden>
           <input name="csrf_token" value="'.$token.'" hidden>
           <td><input type="submit" name="change" value="изменить"></td>';
  print '  <td class="delete"><input type="submit" name="delete" value="удалить"></td>
        </form>';
  print'<tr>';
}
function print_table_head($values,$token){
  print'<tr>';
  foreach ($values as $value) {
    print'<th>'.strip_tags($value).'</th>';
  }
  print'</tr>';
}


setcookie('name_value', '', 100000);
setcookie('email_value', '', 100000);
setcookie('birth_value', '', 100000);
setcookie('pol_value', '', 100000);
setcookie('konechnosti_value', '', 100000);
setcookie('powers_value', '', 100000);
setcookie('biography_value', '', 100000);
setcookie('check_value', '', 100000);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  session_start();
  $token= uniqid();
  $_SESSION['csrf_token'] = $token;
  if(!empty($_COOKIE['change_user'])){
    $login = empty($_COOKIE['login']) ? '' : $_COOKIE['login'];
    print '<form action="" method="POST">
      <input name="login" value="'.$login.'">
      <input name="pass">
      <input type="submit">
    </form><br/>
    <form action="" method="POST">
      <input name="cancel" type="submit" value="Отмена">
    </form>';
    exit();
  }
  $error = empty($_COOKIE['table_value']);
  $table = $error? "" : $_COOKIE['table_value'];
  print '
<!DOCTYPE html>

<html lang="ru" >
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <title></title>
</head>
<body>';
  if (!empty($_COOKIE['save_admin'])) {
    setcookie('save_admin', '', 100000);
    print 'Изменения сохранены.';
    }
  $power = empty($_COOKIE['powers_counter_value']) ? '' : $_COOKIE['powers_counter_value'];
  print'<form action="" method="POST" class="radio_tables">
    <div>
      <label><input type="radio" name="table" value="project4"';if (!$error&&$table=='project4') print 'checked="checked"';print'>
        project4</label>
      <label><input type="radio" name="table" value="project4_powers"';if (!$error&&$table=='project4_powers') print 'checked="checked"';print'>
        project4_powers</label>      
      <label><input type="radio" name="table" value="project4_users"';if (!$error&&$table=='project4_users') print 'checked="checked"';print'>
        project4_users</label><br />
    </div>  
    <input name="csrf_token" value="'.$token.'" hidden>
    <input type="submit" value="Открыть" />
  </form>
  <div class="powers_counter">
    <form action="" method="POST">
      <select name="powers_counter_value">
        <option value="immortal" ';if($power=='immortal')print 'selected="selected"'; print '>Бессмертие</option>
        <option value="passing through walls" ';if($power=='passing through walls')print 'selected="selected"'; print '>Прохождение сквозь стены </option>
        <option value="levitation" ';if($power=='levitation')print 'selected="selected"'; print '>Левитация </option>
        <option value="" ';if(empty($power))print 'selected="selected"'; print 'hidden></option>
      </select>
      <input name="csrf_token" value="'.$token.'" hidden>
      <input type="submit" name="powers_counter" value="посчитать">
    </form>';
    if(!empty($_COOKIE['count']))print $_COOKIE['count'];
  print '</div>';
  if(!$error){
    $db = dbconnect();
    try {
      if($table=='project4')
        $stmt = $db->prepare("DESC project4");
      if($table=='project4_powers')
        $stmt = $db->prepare("DESC project4_powers");
      if($table=='project4_users')
        $stmt = $db->prepare("DESC project4_users");
      $stmt->execute();
      $columns=array();
      $column=$stmt->fetchColumn();
      for($i=0;$column!=false;$i++){
        $columns[$i]=$column;
        $column=$stmt->fetchColumn();
      }
      print '  <table border="1">
   <caption>'.$table.'</caption>';
      print_table_head($columns,$token);
      if($table=='project4')
        $stmt = $db->prepare("SELECT * FROM project4");
      if($table=='project4_powers')
        $stmt = $db->prepare("SELECT * FROM project4_powers");
      if($table=='project4_users')
        $stmt = $db->prepare("SELECT * FROM project4_users");
      $stmt->execute();
      $line=array();
      for($line=$stmt->fetch();!empty($line);$line=$stmt->fetch()){
        print_table_line($line,$columns,$token);
      }
      print '</table>';

    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }

}
//если метод POST == $_POST['csrf_token']
else{
  if(!empty($_COOKIE[session_name()]) &&
      session_start() && $_SESSION['csrf_token']){
    if(!empty($_POST['cancel'])){
      setcookie('uid', '', 100);
      setcookie('change_user', '', 100);
      setcookie('login', '', 100);
      header('Location: admin.php');
      exit();
    }
    if((!empty($_POST['powers_counter_value']) || !empty($_COOKIE['powers_counter_value']))){
      $power = empty($_POST['powers_counter_value'])? $_COOKIE['powers_counter_value'] : $_POST['powers_counter_value'];
      if(!empty($_POST['powers_counter_value']))
        setcookie('powers_counter_value', $_POST['powers_counter_value'], time()+3600*24);
      $db = dbconnect();
      try{
        $stmt = $db->prepare("SELECT count(power) FROM project4_powers WHERE power = :power");
        $stmt->bindParam(':power', $power);
        $stmt->execute();
        setcookie('count', $stmt->fetchColumn(), time()+3600*24);
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
      }
    }
    if(!empty($_COOKIE['change_user'])){
      setcookie('login', $_POST['login'], time()+3600*24);
      if (!empty($_POST['login'])&&!empty($_POST['pass'])){
        try{
          $stmt = $db->prepare("UPDATE project4_users SET login = :login, pass = :pass WHERE personID = :personID");
          $stmt->bindParam(':login', $login);
          $stmt->bindParam(':pass', $pass);
          $stmt->bindParam(':personID', $personID);
          $login = $_POST['login'];
          $pass = md5($_POST['pass']);
          $personID = $_COOKIE['uid'];
          $stmt->execute();
          setcookie('uid', '', 100);
          setcookie('change_user', '', 100);
          setcookie('login', '', 100);
          setcookie('save_admin', '1');
        }
        catch(PDOException $e){
          print('Error : ' . $e->getMessage());
          exit();
        }
      }
    }
    $errors=false;
    if (empty($_POST['table']) && empty($_COOKIE['table_value'])) {
      setcookie('table_value', '', time()+3600*24);
      $errors = TRUE;
    }
    if (!empty($_POST['table'])){ 
      setcookie('table_value', $_POST['table'], time()+3600*24);
    }

    if(!empty($_POST['change'])){
      $table = $_COOKIE['table_value'];
      $PK = $_POST['PK'];
      if($table=='project4'||$table=='project4_powers'){
        if($table=='project4_powers'){ 
          $stmt = $db->prepare("SELECT personID FROM project4_powers WHERE powerID = :PK");
          $stmt->bindParam(':PK', $PK);
          $stmt->execute();
          $PK = $stmt->fetchColumn();
        }
        setcookie('uid', $PK, time()+3600*24);
        header('Location: ./');
        exit();
      }
      if($table=='project4_users'){
        $stmt = $db->prepare("SELECT personID FROM project4_users WHERE login = :PK");
        $stmt->bindParam(':PK', $PK);
        $stmt->execute();
        $personID = $stmt->fetchColumn();
        setcookie('uid', $personID, time()+3600*24);
        setcookie('login', $PK, time()+3600*24);
        setcookie('change_user', '1', time()+3600*24);
      }
    }

    if(!empty($_POST['delete'])){
      $table = $_COOKIE['table_value'];
      $PK = $_POST['PK'];
      $db = dbconnect();
      try{
        if($table=='project4')
          $stmt = $db->prepare("DELETE FROM project4 WHERE ID = :PK");
        if($table=='project4_powers')
          $stmt = $db->prepare("DELETE FROM project4_powers WHERE powerID = :PK");
        if($table=='project4_users')
          $stmt = $db->prepare("DELETE FROM project4_users WHERE login = :PK");
        $stmt->bindParam(':PK', $PK);
        $stmt->execute();
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
      }
      setcookie('save_admin', '1');
    }


    header('Location: admin.php');
  }
}
?>