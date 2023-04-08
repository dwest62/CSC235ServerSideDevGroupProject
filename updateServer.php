
<?php
    $output = shell_exec('git checkout master');
    echo "$output";
    $output = shell_exec('git pull');
    echo "$output";
    $output = shell_exec('git checkout prod');
    echo "$output";
    $output = shell_exec('git merge master');
    echo "$output";
    $output = shell_exec('git push');
    echo "$output";
?>
