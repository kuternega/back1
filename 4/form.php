<!DOCTYPE html>

<html lang="ru" >
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <title></title>
</head>
<body>
  <div id="form">
    <form action=""
      method="POST">
       <label>
        Имя:<br />
        <input name="name" <?php if ($errors['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?>" />
      </label><br />
       <label>
        Email:<br />
        <input name="email" type="email" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>" />
      </label><br />
       <label>
        Дата рождения:<br />
        <input name="birth" <?php if ($errors['birth']) {print 'class="error"';} ?> value="<?php print $values['birth']; ?>" type="date" />
      </label><br />
     Пол:<br />
	 <div <?php if ($errors['pol']) {print 'class="error"';} ?>>
      <label><input type="radio" name="pol" value="m" <?php if($values[pol]=='m') {print 'checked="checked"';} ?> />
        М</label>
      <label><input type="radio" name="pol" value="w" <?php if($values[pol]=='w') {print 'checked="checked"';} ?> />
        Ж</label>
	  </div><br />
        Количество конечностей:<br />
	  <div <?php if ($errors['konechnosti']) {print 'class="error"';} ?>>
      <label><input type="radio" name="konechnosti" value="1" <?php if($values[konechnosti]==1) {print 'checked="checked"';} ?> />
        1</label>
      <label><input type="radio" name="konechnosti" value="2"  <?php if($values[konechnosti]==2) {print 'checked="checked"';} ?> />
        2</label><br />
      <label><input type="radio" name="konechnosti" value="3" <?php if($values[konechnosti]==3) {print 'checked="checked"';} ?> />
        3</label>
      <label><input type="radio" name="konechnosti" value="4" <?php if($values[konechnosti]==4) {print 'checked="checked"';} ?> />
        4</label><br /> 
      </div>		
      <label>
        Сверхспособности:
        <br />
        <select name="powers" multiple="multiple" <?php if ($errors['powers']) {print 'class="error"';} ?>>
          <option value="1" selected="selected">Бессмертие</option>
          <option value="2">Прохождение сквозь стены </option>
          <option value="3">Левитация </option>
        </select>
      </label><br />
      <label>
        Биография:<br />
        <textarea name="biography" <?php if ($errors['biography']) {print 'class="error"';} ?> value="<?php print $values['biography']; ?>"></textarea>
      </label><br />
      <label><input type="checkbox" name="check" <?php if ($errors['check']) {print 'class="error"';} if(!empty($values[check])) {print 'checked="checked"';} ?> />
      С контрактом ознакомлен (а)</label><br />
      <input type="submit" value="Отправить" />
    </form>
  </div>
</body>
</html>