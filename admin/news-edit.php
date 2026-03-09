<?php
/**
 * News Add/Edit Form
 * Success Microfinance Institution S.C. Website
 */

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include database configuration
require_once '../includes/config.php';

// Initialize variables
$id = $title_en = $title_am = $content_en = $content_am = $author = $image = $published_date = $status = "";
$title_en_err = $title_am_err = $content_en_err = $content_am_err = $author_err = $published_date_err = "";
$isEdit = false;

// Check if it's an edit operation
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id = trim($_GET['id']);
    $isEdit = true;
    
    // Prepare a select statement
    $sql = "SELECT * FROM news WHERE id = :id";
    
    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Fetch result row as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                $title_en = $row["title_en"];
                $title_am = $row["title_am"];
                $content_en = $row["content_en"];
                $content_am = $row["content_am"];
                $author = $row["author"];
                $image = $row["image"];
                $published_date = $row["published_date"];
                $status = $row["status"];
            } else {
                // URL doesn't contain valid id parameter
                header("location: news.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close statement
    unset($stmt);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title (English)
    if (empty(trim($_POST["title_en"]))) {
        $title_en_err = "Please enter a title in English.";
    } else {
        $title_en = trim($_POST["title_en"]);
    }
    
    // Validate title (Amharic)
    if (empty(trim($_POST["title_am"]))) {
        $title_am_err = "Please enter a title in Amharic.";
    } else {
        $title_am = trim($_POST["title_am"]);
    }
    
    // Validate content (English)
    if (empty(trim($_POST["content_en"]))) {
        $content_en_err = "Please enter content in English.";
    } else {
        $content_en = trim($_POST["content_en"]);
    }
    
    // Validate content (Amharic)
    if (empty(trim($_POST["content_am"]))) {
        $content_am_err = "Please enter content in Amharic.";
    } else {
        $content_am = trim($_POST["content_am"]);
    }
    
    // Validate author
    if (empty(trim($_POST["author"]))) {
        $author_err = "Please enter an author.";
    } else {
        $author = trim($_POST["author"]);
    }
    
    // Validate published date
    if (empty(trim($_POST["published_date"]))) {
        $published_date_err = "Please enter a published date.";
    } else {
        $published_date = trim($_POST["published_date"]);
    }
    
    // Get other form data
    $status = isset($_POST["status"]) ? 1 : 0;
    
    // Handle image upload
    $image = isset($_POST["current_image"]) ? $_POST["current_image"] : "";
    
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];
        
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $image_err = "Please select a valid file format.";
        }
        
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            $image_err = "File size is larger than the allowed limit.";
        }
        
        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            $newFilename = uniqid() . "." . $ext;
            $uploadDir = "../images/news/";
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $targetFile = $uploadDir . $newFilename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = "news/" . $newFilename;
            } else {
                $image_err = "There was an error uploading your file.";
            }
        } else {
            $image_err = "There was a problem with your upload.";
        }
    }
    
    // Check input errors before inserting into database
    if (empty($title_en_err) && empty($title_am_err) && empty($content_en_err) && empty($content_am_err) && empty($author_err) && empty($published_date_err)) {
        // Prepare an insert or update statement
        if ($isEdit) {
            $sql = "UPDATE news SET title_en = :title_en, title_am = :title_am, content_en = :content_en, content_am = :content_am, author = :author, image = :image, published_date = :published_date, status = :status WHERE id = :id";
        } else {
            $sql = "INSERT INTO news (title_en, title_am, content_en, content_am, author, image, published_date, status) VALUES (:title_en, :title_am, :content_en, :content_am, :author, :image, :published_date, :status)";
        }
        
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":title_en", $param_title_en, PDO::PARAM_STR);
            $stmt->bindParam(":title_am", $param_title_am, PDO::PARAM_STR);
            $stmt->bindParam(":content_en", $param_content_en, PDO::PARAM_STR);
            $stmt->bindParam(":content_am", $param_content_am, PDO::PARAM_STR);
            $stmt->bindParam(":author", $param_author, PDO::PARAM_STR);
            $stmt->bindParam(":image", $param_image, PDO::PARAM_STR);
            $stmt->bindParam(":published_date", $param_published_date, PDO::PARAM_STR);
            $stmt->bindParam(":status", $param_status, PDO::PARAM_INT);
            
            if ($isEdit) {
                $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
                $param_id = $id;
            }
            
            // Set parameters
            $param_title_en = $title_en;
            $param_title_am = $title_am;
            $param_content_en = $content_en;
            $param_content_am = $content_am;
            $param_author = $author;
            $param_image = $image;
            $param_published_date = $published_date;
            $param_status = $status;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Set success message
                $_SESSION['success_message'] = $isEdit ? "News article updated successfully." : "News article added successfully.";
                
                // Redirect to news page
                header("location: news.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? "Edit" : "Add"; ?> News - Success Microfinance Institution S.C.</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold border-bottom">
                <i class="fas fa-university me-2"></i>SMFI Admin
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="news.php" class="list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-newspaper me-2"></i>News Manager
                </a>
                <a href="products.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-money-bill-wave me-2"></i>Products Manager
                </a>
                <a href="content.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Content Editor
                </a>
                <a href="languages.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-language me-2"></i>Language Manager
                </a>
                <a href="media.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-images me-2"></i>Media Library
                </a>
                <a href="settings.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                    <i class="fas fa-power-off me-2"></i>Logout
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0"><?php echo $isEdit ? "Edit" : "Add"; ?> News Article</h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <div class="row my-3">
                    <div class="col">
                        <a href="news.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to News List
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($isEdit ? "?id=" . $id : "")); ?>" method="post" enctype="multipart/form-data">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab" aria-controls="english" aria-selected="true">English</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="amharic-tab" data-bs-toggle="tab" data-bs-target="#amharic" type="button" role="tab" aria-controls="amharic" aria-selected="false">Amharic</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="myTabContent">
                                        <!-- English Content -->
                                        <div class="tab-pane fade show active" id="english" role="tabpanel" aria-labelledby="english-tab">
                                            <div class="mb-3">
                                                <label for="title_en" class="form-label">Title (English)</label>
                                                <input type="text" name="title_en" class="form-control <?php echo (!empty($title_en_err)) ? 'is-invalid' : ''; ?>" id="title_en" value="<?php echo $title_en; ?>">
                                                <span class="invalid-feedback"><?php echo $title_en_err; ?></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="content_en" class="form-label">Content (English)</label>
                                                <textarea name="content_en" class="form-control <?php echo (!empty($content_en_err)) ? 'is-invalid' : ''; ?>" id="content_en" rows="10"><?php echo $content_en; ?></textarea>
                                                <span class="invalid-feedback"><?php echo $content_en_err; ?></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Amharic Content -->
                                        <div class="tab-pane fade" id="amharic" role="tabpanel" aria-labelledby="amharic-tab">
                                            <div class="mb-3">
                                                <label for="title_am" class="form-label">Title (Amharic)</label>
                                                <input type="text" name="title_am" class="form-control <?php echo (!empty($title_am_err)) ? 'is-invalid' : ''; ?>" id="title_am" value="<?php echo $title_am; ?>">
                                                <span class="invalid-feedback"><?php echo $title_am_err; ?></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="content_am" class="form-label">Content (Amharic)</label>
                                                <textarea name="content_am" class="form-control <?php echo (!empty($content_am_err)) ? 'is-invalid' : ''; ?>" id="content_am" rows="10"><?php echo $content_am; ?></textarea>
                                                <span class="invalid-feedback"><?php echo $content_am_err; ?></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Settings -->
                                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                            <div class="mb-3">
                                                <label for="author" class="form-label">Author</label>
                                                <input type="text" name="author" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" id="author" value="<?php echo $author; ?>">
                                                <span class="invalid-feedback"><?php echo $author_err; ?></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="published_date" class="form-label">Published Date</label>
                                                <input type="date" name="published_date" class="form-control <?php echo (!empty($published_date_err)) ? 'is-invalid' : ''; ?>" id="published_date" value="<?php echo $published_date; ?>">
                                                <span class="invalid-feedback"><?php echo $published_date_err; ?></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Featured Image</label>
                                                <?php if(!empty($image)): ?>
                                                    <div class="mb-2">
                                                        <img src="../images/<?php echo $image; ?>" alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                                        <input type="hidden" name="current_image" value="<?php echo $image; ?>">
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" name="image" class="form-control" id="image">
                                                <div class="form-text">Leave empty to keep current image (if editing).</div>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" name="status" class="form-check-input" id="status" value="1" <?php echo ($status == 1) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="status">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i><?php echo $isEdit ? "Update" : "Save"; ?> News Article
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
        });

        // Initialize TinyMCE
        tinymce.init({
            selector: '#content_en, #content_am',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 400
        });
    </script>
</body>
</html>
