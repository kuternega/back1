<!DOCTYPE html>

<html lang="ru" >
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <title></title>
</head>
<body>
  <div>
    <?php 
      foreach($messages as $message){
        print $message;
    }?>
  </div>
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
        Год рождения:<br />
        <?php
        print '<select name="birth" ';
        if ($errors['birth']) {print 'class="error"';};
        print '>';
        for($i=1920;$i<=date('Y');$i++){
          print '<option ';
          if(!empty($values['birth'])&&$values['birth']==$i)
            print 'selected="selected"';
          print 'value="'.$i.'" />'.$i.'</option>';
        }
        print '<option value="" ';
        if(empty($values['birth']))
          print 'selected="selected"';
        print ' hidden></option>';
        print '</select>';
        ?>
      </label><br />
     Пол:<br />
   <div <?php if ($errors['pol']) {print 'class="error"';} ?>>
      <label><input type="radio" name="pol" value="m" <?php if($values['pol']=='m') {print 'checked="checked"';} ?> />
        М</label>
      <label><input type="radio" name="pol" value="w" <?php if($values['pol']=='w') {print 'checked="checked"';} ?> />
        Ж</label>
    </div><br />
        Количество конечностей:<br />
    <div <?php if ($errors['konechnosti']) {print 'class="error"';} ?>>
      <label><input type="radio" name="konechnosti" value="1" <?php if($values['konechnosti']==1) {print 'checked="checked"';} ?> />
        1</label>
      <label><input type="radio" name="konechnosti" value="2"  <?php if($values['konechnosti']==2) {print 'checked="checked"';} ?> />
        2</label><br />
      <label><input type="radio" name="konechnosti" value="3" <?php if($values['konechnosti']==3) {print 'checked="checked"';} ?> />
        3</label>
      <label><input type="radio" name="konechnosti" value="4" <?php if($values['konechnosti']==4) {print 'checked="checked"';} ?> />
        4</label><br /> 
      </div>    
      <label>
        Сверхспособности:
        <br />
        <select name="powers[]" multiple="multiple" <?php if ($errors['powers']) {print 'class="error"';} ?>>
          <option value="1" <?php if(!empty($values['powers']) && $values['powers'][0]) {print 'selected="selected"';} ?> >Бессмертие</option>
          <option value="2" <?php if(!empty($values['powers']) && $values['powers'][1]) {print 'selected="selected"';} ?> >Прохождение сквозь стены </option>
          <option value="3" <?php if(!empty($values['powers']) && $values['powers'][2]) {print 'selected="selected"';} ?> >Левитация </option>
        </select>
      </label><br />
      <label>
        Биография:<br />
        <textarea name="biography" <?php if ($errors['biography']) {print 'class="error"';} ?> ><?php print $values['biography']; ?></textarea>
      </label><br />
      <label <?php if ($errors['check']) {print 'class="error"';} ?>><input type="checkbox" name="check" <?php if(!empty($values['check'])) {print 'checked="checked"';} ?> />
      С контрактом ознакомлен (а)</label><br />
      <input type="submit" value="Отправить" />
    </form>
  </div>
</body>
</html>
