    <?php
    try{
        $pdo = new PDO("mysql:host=localhost;dbname=book_db;", "root", "qebfix-fiqgy4-kabGim");
        //Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // echo "Connected successfully" . "<br>";
        $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    } catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    } 
    ?>