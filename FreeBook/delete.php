<?php
// Process delete operation after confirmation
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    require_once "connect.php";

   $sql_fetch = "SELECT filename FROM books WHERE id = :id";
    $stmt_fetch = $pdo->prepare($sql_fetch);
    $stmt_fetch->bindParam(":id", $param_id);
    $param_id = trim($_POST["id"]);

    if ($stmt_fetch->execute() && $stmt_fetch->rowCount() == 1) {
        $row = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        $filename = $row["filename"];
        $file_path = "uploads/" . $filename;

        // Delete the file from the server if it exists
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    unset($stmt_fetch);

    //  Delete from database
    $sql = "DELETE FROM books WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $param_id);
        if ($stmt->execute()) {
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    unset($stmt);
    unset($pdo);
} elseif (empty(trim($_GET["id"]))) {
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            max-width: 500px;
            margin: 50px auto;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="wrapper shadow">
    <h2 class="text-danger mb-4">Delete Confirmation</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars(trim($_GET["id"])); ?>">
        <div class="alert alert-danger">
            <p><strong>Are you sure you want to delete this book?</strong> This action cannot be undone.</p>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
