<?php
// Include config file
require_once "connect.php";

// Define variables and initialize
$title = $author = $description = "";
$title_err = $author_err = $description_err = $file_err = "";

// Process form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        }
    } else {
        $file_err = "Please choose a file to upload.";
    }

    // Insert into DB
    if (empty($title_err) && empty($author_err) && empty($description_err) && empty($file_err)) {
        $sql = "INSERT INTO books (title, author, description, filename) VALUES (:title, :author, :description, :filename)";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":filename", $filename);

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
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Book Record</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h2 class="mb-4">Add a New Book</h2>
        <p>Please fill this form to add a book to the library.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($title); ?>">
        <div class="invalid-feedback"><?php echo $title_err; ?></div>
    </div>

    <div class="form-group">
        <label>Author</label>
        <input type="text" name="author" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($author); ?>">
        <div class="invalid-feedback"><?php echo $author_err; ?></div>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($description); ?></textarea>
        <div class="invalid-feedback"><?php echo $description_err; ?></div>
    </div>

    <div class="form-group">
        <label>Upload File</label>
        <input type="file" name="file" class="form-control-file <?php echo (!empty($file_err)) ? 'is-invalid' : ''; ?>">
        <div class="invalid-feedback d-block"><?php echo $file_err; ?></div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
</form>
    </div>
</body>
</html>
