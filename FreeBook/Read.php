<?php
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "connect.php";

    $sql = "SELECT * FROM books WHERE id = :id";

    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $param_id);
        $param_id = trim($_GET["id"]);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $title = $row["title"];
                $author = $row["author"];
                $description = $row["description"];
                $filename = $row["filename"];
                $uploaded_at = $row["uploaded_at"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    unset($stmt);
    unset($pdo);
} else {
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Book</title>
        <link rel="icon" type="image/png" href="assets/icons8-book-32.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .container {
            max-width: 700px;
            margin-top: 50px;
        }
        .card {
            padding: 20px;
        }
        .label-text {
            font-weight: 600;
            color: #555;
        }
        .value-text {
            color: #212529;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="card shadow">
            <h3 class="mb-4">Book Details</h3>

            <div class="mb-3">
                <span class="label-text">Title:</span>
                <div class="value-text"><?php echo htmlspecialchars($title); ?></div>
            </div>

            <div class="mb-3">
                <span class="label-text">Author:</span>
                <div class="value-text"><?php echo htmlspecialchars($author); ?></div>
            </div>

            <div class="mb-3">
                <span class="label-text">Description:</span>
                <div class="value-text"><?php echo nl2br(htmlspecialchars($description)); ?></div>
            </div>

            <div class="mb-3">
                <span class="label-text">Filename:</span>
                <div class="value-text"><?php echo htmlspecialchars($filename); ?></div>
            </div>

            <div class="mb-3">
                <span class="label-text">Uploaded At:</span>
                <div class="value-text"><?php echo htmlspecialchars($uploaded_at); ?></div>
            </div>


<div class="mb-3">
    <span class="label-text">Download File:</span><br>
    <?php
        $file_path = 'uploads/' . $filename;
        if (file_exists($file_path)) {
            echo '<a href="' . $file_path . '" download class="btn btn-success">Download Book</a>';
        } else {
            echo '<div class="text-danger">File not found for download.</div>';
        }
    ?>
</div>


            <a href="index.php" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</body>
</html>
