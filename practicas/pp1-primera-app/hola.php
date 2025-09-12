<?php

function sayHello($name) {
  echo "Hello $name !";
}
?>

<html>
  <head>
    <title>Visual Studio Remote :: PHP</title>
  </head>
  <body>
    <?php

      sayHello('remote world');

      phpinfo();

    ?>
  </body>
</html>
