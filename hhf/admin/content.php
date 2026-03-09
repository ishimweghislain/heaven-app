<?php
/**
 * Content Editor
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

// Get all content sections
try {
    $stmt = $pdo->query("SELECT id, section, `key`, value_en FROM content ORDER BY section, `key`");
    $contents = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Group content by section
$contentSections = [];
if (isset($contents) && count($contents) > 0) {
    foreach ($contents as $content) {
        $contentSections[$content['section']][] = $content;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Editor - Success Microfinance Institution S.C.</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                <a href="content.php" class="list-group-item list-group-item-action bg-transparent second-text active">
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
                    <h2 class="fs-2 m-0">Content Editor</h2>
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
                        <a href="content-edit.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Add New Content
                        </a>
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
                        <div class="accordion" id="contentAccordion">
                            <?php if(isset($contentSections) && count($contentSections) > 0): ?>
                                <?php foreach($contentSections as $section => $items): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo ucfirst($section); ?>">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo ucfirst($section); ?>" aria-expanded="true" aria-controls="collapse<?php echo ucfirst($section); ?>">
                                                <?php echo ucfirst($section); ?> Section
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo ucfirst($section); ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?php echo ucfirst($section); ?>" data-bs-parent="#contentAccordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Key</th>
                                                                <th>Value (English)</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($items as $item): ?>
                                                                <tr>
                                                                    <td><?php echo htmlspecialchars($item['key']); ?></td>
                                                                    <td><?php echo htmlspecialchars(substr($item['value_en'], 0, 100)) . (strlen($item['value_en']) > 100 ? '...' : ''); ?></td>
                                                                    <td>
                                                                        <a href="content-edit.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                                                                            <i class="fas fa-edit"></i> Edit
                                                                        </a>
                                                                        <a href="content-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this content?');">
                                                                            <i class="fas fa-trash"></i> Delete
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info">No content found. Please add some content.</div>
                            <?php endif; ?>
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
    </script>
</body>
</html>
