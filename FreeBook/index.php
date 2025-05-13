<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - FreeBooks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="images/book-icon.png" sizes="32x32">

    <!-- Rest of head content -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous">
    <style>
        body { background-color: #f8f9fa; }
        .container-custom { margin-top: 20px; padding-bottom: 20px; }
        .dashboard-title { font-size: 1.5rem; font-weight: bold; color: #007bff; display: flex; align-items: center; gap: 10px; }
        .dashboard-title i { font-size: 1.8rem; }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            min-width: 600px;
        }
        th, td {
            white-space: nowrap;
            padding: 8px;
            vertical-align: middle;
        }
        td:nth-child(4) {
            max-width: 150px;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons .btn {
            padding: 2px 6px;
            font-size: 0.9rem;
        }
        @media (max-width: 576px) {
            .dashboard-title { font-size: 1.2rem; }
            .dashboard-title i { font-size: 1.5rem; }
            .table {
                min-width: 0;
                display: block;
                overflow-x: auto;
            }
            th, td {
                display: block;
                text-align: left;
                padding: 6px;
            }
            th {
                background-color: #343a40;
                color: white;
            }
            td {
                border: none;
                border-bottom: 1px solid #dee2e6;
            }
            td:before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 10px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 2px;
            }
            .action-buttons .btn {
                width: 100%;
                margin: 2px 0;
            }
            #searchInput {
                font-size: 0.9rem;
                padding: 6px;
            }
            thead { display: none; }
            tr {
                display: block;
                margin-bottom: 10px;
                border: 1px solid #dee2e6;
                border-radius: 4px;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container container-custom">
    <div class="dashboard-title mb-3">
         Books List
    </div>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by title, author, or description...">
    </div>

    <?php
    require_once "connect.php";
    $sql = "SELECT * FROM books";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt;

    if ($result->rowCount() > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-hover">';
        echo "<thead class='thead-dark'><tr>";
        echo "<th data-label='ID'>ID</th>";
        echo "<th data-label='Title'>Title</th>";
        echo "<th data-label='Author'>Author</th>";
        echo "<th data-label='Description'>Description</th>";
        echo "<th data-label='File'>File</th>";
        echo "<th data-label='Upload Date'>Upload Date</th>";
        echo "<th class='text-center' data-label='Actions'>Actions</th>";
        echo "</tr></thead><tbody>";
        while ($row = $result->fetch()) {
            echo "<tr>";
            echo "<td data-label='ID'>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td data-label='Title'>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td data-label='Author'>" . htmlspecialchars($row['author']) . "</td>";
            echo "<td data-label='Description'>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td data-label='File'>" . htmlspecialchars($row['filename']) . "</td>";
            echo "<td data-label='Upload Date'>" . htmlspecialchars($row['uploaded_at']) . "</td>";
            echo "<td class='text-center action-buttons' data-label='Actions'>";
            echo '<a href="read.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-outline-info" title="View"><i class="fa fa-eye"></i></a>';
            echo '<a href="update.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fa fa-pencil"></i></a>';
            echo '<a href="delete.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Are you sure?\')" title="Delete"><i class="fa fa-trash"></i></a>';
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    } else {
        echo '<div class="alert alert-warning"><em>No books found.</em></div>';
    }
    unset($pdo);
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
</body>
</html>