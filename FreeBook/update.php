<?php
require_once "connect.php";

$title = $author = $description = $filename = "";
$title_err = $author_err = $description_err = $file_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Validate title
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter a title.";
    } else {
        $title = $input_title;
    }

    // Validate author
    $input_author = trim($_POST["author"]);
    if (empty($input_author)) {
        $author_err = "Please enter an author.";
    } else {
        $author = $input_author;
    }

    // Validate description
    $input_description = trim($_POST["description"]);
    if (empty($input_description)) {
        $description_err = "Please enter a description.";
    } else {
        $description = $input_description;
    }

    // Handle file upload
    $new_file_uploaded = false;
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $allowed_ext = ["pdf", "docx", "txt"];
        $filename = basename($_FILES["file"]["name"]);
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $upload_dir = "uploads/";
        $target_path = $upload_dir . $filename;

        if (!in_array(strtolower($filetype), $allowed_ext)) {
            $file_err = "Only PDF, DOCX, and TXT files are allowed.";
        } elseif (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_path)) {
            $file_err = "Failed to upload file.";
        } else {
            $new_file_uploaded = true;
        }
    } else {
        $filename = $_POST["existing_file"]; // keep existing file
    }

    if (empty($title_err) && empty($author_err) && empty($description_err) && empty($file_err)) {
        $sql = "UPDATE books SET title=:title, author=:author, description=:description, filename=:filename WHERE id=:id";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":filename", $filename);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong.";
            }
        }
        unset($stmt);
    }
    unset($pdo);
} elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    $sql = "SELECT * FROM books WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $title = $row["title"];
                $author = $row["author"];
                $description = $row["description"];
                $filename = $row["filename"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong.";
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
    <title>Update Book</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="wrapper shadow">
    <h2 class="mb-4">Update Book</h2>
    <p>Please update the book details below.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title"
                   class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo htmlspecialchars($title); ?>">
            <div class="invalid-feedback"><?php echo $title_err; ?></div>
        </div>

        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author"
                   class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo htmlspecialchars($author); ?>">
            <div class="invalid-feedback"><?php echo $author_err; ?></div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description"
                      class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($description); ?></textarea>
            <div class="invalid-feedback"><?php echo $description_err; ?></div>
        </div>

        <div class="form-group">
            <label>Upload New File (leave empty to keep current)</label>
            <input type="file" name="file" class="form-control-file <?php echo (!empty($file_err)) ? 'is-invalid' : ''; ?>">
            <div class="invalid-feedback d-block"><?php echo $file_err; ?></div>
            <?php if ($filename): ?>
                <small class="form-text text-muted">Current File: <?php echo htmlspecialchars($filename); ?></small>
            <?php endif; ?>
            <input type="hidden" name="existing_file" value="<?php echo htmlspecialchars($filename); ?>">
        </div>

        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
    </form>
</div>
</body>
</html>
