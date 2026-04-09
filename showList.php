<?php
    require_once 'connection.php';

    $mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_error) {
        die("Не удалось установить подключение к базе данных: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8");

    $N = 3;

    $countResult = $mysqli->query("SELECT COUNT(*) as total FROM books");
    $countRow = $countResult->fetch_assoc();
    $totalBooks = $countRow['total'];
    $totalPages = ceil($totalBooks / $N);

    $page = 1;
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
        if ($page < 1) {
            $page = 1;
        }
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
    }

    $offset = ($page - 1) * $N;
    $result = $mysqli->query("SELECT * FROM books LIMIT $offset, $N");

    if (!$result) {
        die("Ошибка выполнения запроса: " . $mysqli->error);
    }

    if ($result->num_rows > 0) {
        echo "<h1>Список книг</h1>";

        while ($row = $result->fetch_assoc()) {
            echo "<h2>" . $row['title'] . "</h2>";            
            echo "<h3>" . ucfirst($row['genre']) . "</h3>";
            $formattedPrice = number_format($row['price'], 2, '.', ' ');
            echo "<p><strong>Цена:</strong> {$formattedPrice} руб</p>";
            echo "<p> Масса: " . $row['massa'] . "</p>";
            echo "<p> Страницы: " . $row['page'] . "</p>";
            echo "<p> Год выпуска: " . $row['yearReales'] . "</p>";
            
            echo "<hr>";
        }
    } 
    else 
    {
        echo "<p>В таблице 'books' ничего нету.</p>";
    }

    $result->free();
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список книг</title>
</head>
<body>
    <div class="sort-form">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="showList.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>