<?php
/**
 * Media Library
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

// Get all media files
try {
    $stmt = $pdo->query("SELECT * FROM media ORDER BY created_at DESC");
    $mediaFiles = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $allowed = [
            "jpg" => "image/jpg",
            "jpeg" => "image/jpeg",
            "gif" => "image/gif",
            "png" => "image/png",
            "pdf" => "application/pdf",
            "doc" => "application/msword",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ];
        
        $filename = $_FILES["file"]["name"];
        $filetype = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];
        
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $error = "Please select a valid file format.";
        }
        
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            $error = "File size is larger than the allowed limit.";
        }
        
        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            $newFilename = uniqid() . "." . $ext;
            $uploadDir = "../uploads/";
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $targetFile = $uploadDir . $newFilename;
            
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                // Insert file info into database
                $sql = "INSERT INTO media (filename, path, type, size, uploaded_by) VALUES (:filename, :path, :type, :size, :uploaded_by)";
                
                if ($stmt = $pdo->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":filename", $param_filename, PDO::PARAM_STR);
                    $stmt->bindParam(":path", $param_path, PDO::PARAM_STR);
                    $stmt->bindParam(":type", $param_type, PDO::PARAM_STR);
                    $stmt->bindParam(":size", $param_size, PDO::PARAM_INT);
                    $stmt->bindParam(":uploaded_by", $param_uploaded_by, PDO::PARAM_INT);
                    
                    // Set parameters
                    $param_filename = $filename;
                    $param_path = "uploads/" . $newFilename;
                    $param_type = $filetype;
                    $param_size = $filesize;
                    $param_uploaded_by = $_SESSION['admin_id'];
                    
                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        $_SESSION['success_message'] = "File uploaded successfully.";
                        header("location: media.php");
                        exit();
                    } else {
                        $error = "Oops! Something went wrong. Please try again later.";
                    }
                }
            } else {
                $error = "There was an error uploading your file.";
            }
        } else {
            $error = "There was a problem with your upload.";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}

// Handle file deletion
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Get file path
    $sql = "SELECT path FROM media WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
        $param_id = $id;
        
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                $filePath = "../" . $row['path'];
                
                // Delete file from server
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // Delete from database
                $sql = "DELETE FROM media WHERE id = :id";
                if ($stmt = $pdo->prepare($sql)) {
                    $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
                    $param_id = $id;
                    
                    if ($stmt->execute()) {
                        $_SESSION['success_message'] = "File deleted successfully.";
                        header("location: media.php");
                        exit();
                    } else {
                        $error = "Oops! Something went wrong. Please try again later.";
                    }
                }
            } else {
                $error = "File not found.";
            }
        } else {
            $error = "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library - Success Microfinance Institution S.C.</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                <a href="news.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
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
                <a href="media.php" class="list-group-item list-group-item-action bg-transparent second-text active">
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
                    <h2 class="fs-2 m-0">Media Library</h2>
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-2"></i>Upload New File
                        </button>
                    </div>
                </div>

                <?php if(isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                    ?>
                </div>
                <?php endif; ?>

                <div class="row my-3">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <?php if(isset($mediaFiles) && count($mediaFiles) > 0): ?>
                                        <?php foreach($mediaFiles as $file): ?>
                                            <div class="col-md-3 mb-4">
                                                <div class="card h-100">
                                                    <?php
                                                    $fileExt = pathinfo($file['filename'], PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif']);
                                                    ?>
                                                    
                                                    <?php if($isImage): ?>
                                                        <img src="../<?php echo $file['path']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($file['filename']); ?>" style="height: 200px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                            <?php if(strtolower($fileExt) == 'pdf'): ?>
                                                                <i class="fas fa-file-pdf fa-5x text-danger"></i>
                                                            <?php elseif(in_array(strtolower($fileExt), ['doc', 'docx'])): ?>
                                                                <i class="fas fa-file-word fa-5x text-primary"></i>
                                                            <?php else: ?>
                                                                <i class="fas fa-file fa-5x text-secondary"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="card-body">
                                                        <h5 class="card-title text-truncate"><?php echo htmlspecialchars($file['filename']); ?></h5>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                Type: <?php echo htmlspecialchars($fileExt); ?><br>
                                                                Size: <?php echo round($file['size'] / 1024, 2); ?> KB<br>
                                                                Uploaded: <?php echo date('M d, Y', strtotime($file['created_at'])); ?>
                                                            </small>
                                                        </p>
                                                        <div class="d-flex justify-content-between">
                                                            <a href="../<?php echo $file['path']; ?>" class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            <a href="media.php?delete=<?php echo $file['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?');">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <div class="alert alert-info">No media files found. Please upload some files.</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload New File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Select File</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                            <div class="form-text">
                                Allowed file types: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX<br>
                                Maximum file size: 5MB
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    </script>
</body>
</html>
