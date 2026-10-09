<?php require "db.php";

$insert = false;
$update = false;
$delete = false;

if (isset($_GET['delete'])) {
    $sno = (int) $_GET['delete'];

    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_affected_rows($conn) > 0) {
        $delete = true;
    }
}
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $decription = $_POST['descriptionEdit'];

        $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$decription' WHERE `notes`.`sno`= '$sno'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $update = true;
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $title = $_POST['title'];
        $decription = $_POST['description'];

        $sql = "INSERT INTO `notes`(`title`, `description`) VALUES('$title','$decription')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $insert = true;
        } else {
            echo mysqli_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNotes - Notes taking made easy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/dt/dt-3.1.3/datatables.min.css" rel="stylesheet">
</head>

<body>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit this Note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/crud/index.php" method="POST">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="titleEdit" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="descriptionEdit" class="form-label">Note Decription</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Note</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>

                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <?php
    if ($insert) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note  has been Inserted Successfully
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    if ($update) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note  has been Updated Successfully
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    if ($delete) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note  has been Deleted Successfully
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    ?>
    <div class="container my-4">
        <h2>Add a Notes</h2>
        <form action="/crud/index.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Note Decription</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>
    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">SNo</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                $sno = 0;

                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    echo "<tr>
                    <td>" . $sno . "</td>
                     <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td>
                    <button class='edit btn btn-sm btn-primary' id='" . $row['sno'] . "'>Edit</button>
                    <button class='delete btn btn-sm btn-danger' id='d" . $row['sno'] . "'>Delete</button>
                     </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <script>
            let edits = document.getElementsByClassName('edit');
            Array.from(edits).forEach((element) => {
                element.addEventListener("click", (e) => {
                    let tr = e.target.parentNode.parentNode;
                    let title = tr.getElementsByTagName("td")[1].innerText;
                    let description = tr.getElementsByTagName("td")[2].innerText;
                    console.log(title, description);
                    document.getElementById("titleEdit").value = title;
                    document.getElementById("descriptionEdit").value = description;
                    document.getElementById("snoEdit").value = e.target.id;

                    let editModal = new bootstrap.Modal(
                        document.getElementById('editModal')
                    );
                    editModal.show();
                });
            });

            let deletes = document.getElementsByClassName('delete');
            Array.from(deletes).forEach((element) => {
                element.addEventListener("click", (e) => {

                    let sno = e.target.id.substring(1);

                    if (confirm("Are you sure you want to delete this note?")) {
                        window.location = `/crud/index.php?delete=${sno}`;
                    }

                });
            });
        </script>
    </div>
    <hr>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
</body>

</html>