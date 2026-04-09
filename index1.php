<?php
    echo 123;
    if (@$db = mysqli_connect("localhost", 
    "root", "root", 
    "market")) 
    {
        echo "Вы зашли";
    }
    else 
    {
        echo "Не удалось установить подключение к базе данных";
    }
?>