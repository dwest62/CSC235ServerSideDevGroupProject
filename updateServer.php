
<?php
    $output = shell_exec('git checkout master 2>&1');
    echo "$output<br/>";
    $output = shell_exec('git pull');
    // echo "$output<br/>";
    // $output = shell_exec('git checkout prod');
    // echo "$output<br/>";
    // $output = shell_exec('git merge master');
    echo "$output<br/>";
?>
