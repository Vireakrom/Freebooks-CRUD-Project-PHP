<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - FreeBooks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/book-icon.png">

    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container-custom {
            margin-top: 30px;
            padding-bottom: 40px;
        }

        .dashboard-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .dashboard-title i {
            font-size: 1.8rem;
        }

        .action-buttons .btn {
            margin: 2px 0;
            width: 100%;
        }

        @media (min-width: 576px) {
            .action-buttons .btn {
                width: auto;
                margin: 0 5px;
            }
        }

        th, td {
            min-width: 100px;
        }

        td:nth-child(4), th:nth-child(4) {
            max-width: 200px;
            white-space: normal;
            word-break: break-word;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 0.95rem;
        }

        .table thead th {
            white-space: nowrap;
        }

        .search-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container container-custom">
    <div class="dashboard-title mb-4">
        <span>Books List</span>
    </div>

    <!-- Search Form -->
    <form method="GET" class="search-form form-inline">
        <input type="text" name="search" class="form-control mr-2 flex-fill" placeholder="Search by title, author, or description"
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
    </form>

    <?php
    require_once "connect.php";

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sql = "SELECT * FROM books";
    if ($search !== '') {
        $sql .= " WHERE title LIKE :search OR author LIKE :search OR description LIKE :search";
    }

    $stmt = $pdo->prepare($sql);
    if ($search !== '') {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {

            // Table view (desktop)
            echo '<div class="d-none d-md-block table-responsive">';
            echo '<table class="table table-bordered table-hover">';
            echo "<thead class='thead-dark'>";
            echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Description</th><th>File</th><th>Upload Date</th><th class='text-center'>Actions</th></tr>";
            echo "</thead><tbody>";
            $allRows = $stmt->fetchAll();
            foreach ($allRows as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>" . htmlspecialchars($row['filename']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
                echo "<td class='text-center'>";
                echo '<div class="d-flex flex-column flex-sm-row justify-content-center action-buttons">';
                echo '<a href="read.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-info mb-1"><i class="fa fa-eye"></i></a>';
                echo '<a href="update.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-warning mb-1"><i class="fa fa-pencil"></i></a>';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>';
                echo '</div>';
                echo "</td></tr>";
            }
            echo "</tbody></table></div>";

            // Card view (mobile)
            echo '<div class="d-md-none">';
            foreach ($allRows as $row) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['author']) . '</h6>';
                echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                echo '<p><strong>File:</strong> ' . htmlspecialchars($row['filename']) . '</p>';
                echo '<p><strong>Uploaded:</strong> ' . htmlspecialchars($row['uploaded_at']) . '</p>';
                echo '<div class="d-flex justify-content-between">';
                echo '<a href="Read.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>';
                echo '<a href="update.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-warning"><i class="fa fa-pencil"></i></a>';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>';
                echo '</div>';
                echo '</div></div>';
            }
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning"><em>No books found.</em></div>';
        }
    } else {
        echo '<div class="alert alert-danger">Error fetching data. Please try again later.</div>';
    }

    unset($pdo);
    ?>
</div>

</body>
</html>
<?php