<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';
include('admin-nav.php');

// Add category
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $query = "INSERT INTO category (category) VALUES('$name')";
    mysqli_query($conn, $query);
    echo "<script>alert('Category Added Successfully');</script>";
}

// Edit category
if (isset($_POST["edit"])) {
    $editCategoryId = $_POST["edit_id"];
    $editCategoryName = mysqli_real_escape_string($conn, $_POST["edit_name"]);
    $editQuery = "UPDATE category SET category = '$editCategoryName' WHERE id = $editCategoryId";
    mysqli_query($conn, $editQuery);
    echo "<script>alert('Category Updated Successfully');</script>";
}

// Delete selected
if (isset($_POST["delete_selected"])) {
    if (!empty($_POST["selected_categories"])) {
        foreach ($_POST["selected_categories"] as $id) {
            mysqli_query($conn, "DELETE FROM category WHERE id = $id");
        }
        echo "<script>alert('Selected categories deleted');</script>";
    } else {
        echo "<script>alert('No categories selected');</script>";
    }
}

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$result = mysqli_query($conn, "SELECT * FROM category WHERE category LIKE '%$searchTerm%'");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/logo/logo2.png"/>
    <title>CATEGORY MANAGEMENT</title>
    <link rel="stylesheet" href="category-management.css">
</head>
<body>
    <h1 class="text1">CATEGORY MANAGEMENT</h1>
    <div class="all">
        <div class="add">
            <form action="" method="post" autocomplete="off">
                <label for="name">Category Name:</label>
                <input type="text" name="name" id="name" required placeholder="Enter category name"> <br><br>
                <button type="submit" name="submit" class="btnSubmit">Submit</button>
            </form>
        </div>

        <div class="view">
            <h1 class="text4">CATEGORY LIST</h1>

            <form action="" method="get">
                <label for="search" class="text5">Search Category:</label>
                <input type="text" name="search" class="searchtxt" id="search" placeholder="Enter category name" required />
                <button type="submit" class="btnSearch">Search</button>
            </form><br>

            <form action="" method="post">
                <table border="1" cellspacing="0" cellpadding="10" class="viewTable">
                    <tr class="thView">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                    <input type="text" name="edit_name" value="<?php echo $row['category']; ?>" required>
                                    <button type="submit" name="edit" class="editbtn">Edit</button>
                                </form>
                            </td>
                            <td>
                                <input type="checkbox" name="selected_categories[]" value="<?php echo $row['id']; ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <button type="submit" name="delete_selected" class="deletebtn">Delete Selected</button>
            </form>
        </div>
    </div>
</body>
</html>