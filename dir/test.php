<?php

if(isset($_POST['submit'])){
  $word = count($_POST['word']);
  for ($i=0; $i <= $word; $i++) {
    if(empty($_POST['word'][$i]) == false){
      echo $_POST['word'][$i].'<br>';
    }
  }
  echo $word;//$_POST['word'][3];
}

?>

<html>
  <body>
    <form method="POST" action="">
      <input type="text" name="word[1]">
      <input type="text" name="word[2]">
      <input type="submit" name="submit">
    </form>
  </body>
</html>