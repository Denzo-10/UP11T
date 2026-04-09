<?php
   require_once 'connection.php';

   $mysqli = new mysqli($host, $user, $password, $database);

   if ($mysqli->connect_error) {
       die("Не удалось установить подключение к базе данных: " . $mysqli->connect_error);
   }

   echo "<h2>Подключение к БД успешно выполнено</h2>";

   $mysqli->set_charset("utf8");

   $sortBy = 'title';

   if (isset($_GET['sortBy'])) {
       $sortBy = $_GET['sortBy'];
   }

    if ($sortBy == 'idBooks') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY idBooks");
    } 
    else if ($sortBy == 'idAuthor') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY idAuthor");
    } 

    else if ($sortBy == 'title') {
       $result = $mysqli->query("SELECT * FROM books ORDER BY title");
    } 

    else if ($sortBy == 'genre') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY genre");
    } 

    else if ($sortBy == 'price') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY price");
    } 

    else if ($sortBy == 'massa') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY massa");
    } 

    else if ($sortBy == 'page') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY 'page'");
    } 

    else if ($sortBy == 'yearReales') {
        $result = $mysqli->query("SELECT * FROM books ORDER BY yearReales");
    } 
    
    else {
       $result = $mysqli->query("SELECT * FROM books ORDER BY idBooks");
    }

    if (!$result) {
       die("Ошибка выполнения запроса: " . $mysqli->error);
    }

    $textTitle = $_GET['title'] ?? '';
    $textGenre = "";
    $textPrice = "";

    $price = (int)$textPrice;

    if($textTitle != ""){
        if(isset($_GET['title']) && isset($_GET['genre']) && isset($_GET['price'])){
            $textTitle = $_GET['title'];
            $textGenre = $_GET['genre'];
            $price = $_GET['price'];
            $result = $mysqli->query("SELECT * FROM books WHERE title Like '%$title%' AND genre = '$textGenre' AND price = $price");
        }
    }
    else{
        if(isset($_GET['genre']) && isset($_GET['price'])){
            $textGenre = $_GET['genre'];
            $price = $_GET['price'];
            $result = $mysqli->query("SELECT * FROM books WHERE genre = '$textGenre' AND price = $price");
        }
    }
    
   
    
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список книг</title>
</head>
<body> 
    <div class="sort-form">
        <form method="GET" action="">
                <h3>Фильтровать по:</h3>
                <label>Название: </label>
                <input type="text" name="title" id="textTitle"/><br/>
                <label>Жанр: </label>
                <input type="text" name="genre" id="textGenre"/><br/>   
                <label>Цена: </label>
                <input type="text" name="price" id="textPrice"/><br/>        

                <button type="submit">Фильтровать</button>
            </form>
                
            <p class="active-price">
                Текущий фильтр: <?php echo $price ?>
            </p>

        <form method="GET" action="">
            <h3>Сортировать по:</h3>
            
            <input type="radio" name="sortBy" id="sortByTitle" value="title" 
                    <?php echo ($sortBy == 'title') ? 'checked' : ''; ?> />
            <label for="sortByTitle">Названию</label>
            
            <br/>
            
            <input type="radio" name="sortBy" id="sortByPrice" value="price" 
                    <?php echo ($sortBy == 'price') ? 'checked' : ''; ?> />
            <label for="sortByPrice">Цене</label>
            
            <br />
            
            <button type="submit">Сортировать</button>
        </form>
            
        <p class="active-sort">
            Текущая сортировка: 
            <?php echo ($sortBy == 'title') ? 'по названию' : 'по цене'; ?>
        </p>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th><a href="showTable.php?sortBy=idBooks">idBooks</a></th>
                <th><a href="showTable.php?sortBy=idAuthor">idAuthor</a></th>
                <th><a href="showTable.php?sortBy=title">title</a></th>
                <th><a href="showTable.php?sortBy=genre">genre</a></th>
                <th><a href="showTable.php?sortBy=price">price</a></th>
                <th><a href="showTable.php?sortBy=massa">massa</a></th>
                <th><a href="showTable.php?sortBy=page">page</a></th>
                <th><a href="showTable.php?sortBy=yearReales">yearReales</a></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>

                    <td><?php echo htmlspecialchars($row['idBooks']); ?></td>
                    <td><?php echo htmlspecialchars($row['idAuthor']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['genre']); ?></td>
                    <td><?php echo number_format($row['price'], 2, ',', ' ') . " руб."; ?></td>
                    <td><?php echo htmlspecialchars($row['massa']); ?></td>
                    <td><?php echo htmlspecialchars($row['page']); ?></td>
                    <td><?php echo htmlspecialchars($row['yearReales']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
    $result->free();
    $mysqli->close();
?>
