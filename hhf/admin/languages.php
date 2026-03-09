<?php
/**
 * Language Manager
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
require_once '../includes/language.php';

// Get translations
$translations = getTranslations();

// Handle form submission for updating translations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_translations'])) {
    $updatedTranslations = $_POST['translations'];
    
    // Convert to JSON
    $jsonTranslations = json_encode($updatedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Write to file
    $translationsFile = __DIR__ . '/../languages/translations.json';
    
    if (file_put_contents($translationsFile, $jsonTranslations)) {
        $_SESSION['success_message'] = "Translations updated successfully.";
        
        // Refresh translations
        $translations = getTranslations();
    } else {
        $error = "Failed to update translations. Please check file permissions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Manager - Success Microfinance Institution S.C.</title>
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
                <a href="content.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Content Editor
                </a>
                <a href="languages.php" class="list-group-item list-group-item-action bg-transparent second-text active">
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
                    <h2 class="fs-2 m-0">Language Manager</h2>
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
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="languageTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab" aria-controls="english" aria-selected="true">English</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="amharic-tab" data-bs-toggle="tab" data-bs-target="#amharic" type="button" role="tab" aria-controls="amharic" aria-selected="false">Amharic</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="tab-content" id="languageTabsContent">
                                        <!-- English Tab -->
                                        <div class="tab-pane fade show active" id="english" role="tabpanel" aria-labelledby="english-tab">
                                            <?php if(isset($translations['en'])): ?>
                                                <?php foreach($translations['en'] as $section => $items): ?>
                                                    <div class="mb-4">
                                                        <h4><?php echo ucfirst($section); ?></h4>
                                                        <hr>
                                                        <?php foreach($items as $key => $value): ?>
                                                            <div class="mb-3">
                                                                <label for="en_<?php echo $section; ?>_<?php echo $key; ?>" class="form-label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></label>
                                                                <?php if(strlen($value) > 100): ?>
                                                                    <textarea class="form-control" id="en_<?php echo $section; ?>_<?php echo $key; ?>" name="translations[en][<?php echo $section; ?>][<?php echo $key; ?>]" rows="3"><?php echo htmlspecialchars($value); ?></textarea>
                                                                <?php else: ?>
                                                                    <input type="text" class="form-control" id="en_<?php echo $section; ?>_<?php echo $key; ?>" name="translations[en][<?php echo $section; ?>][<?php echo $key; ?>]" value="<?php echo htmlspecialchars($value); ?>">
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="alert alert-warning">No English translations found.</div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Amharic Tab -->
                                        <div class="tab-pane fade" id="amharic" role="tabpanel" aria-labelledby="amharic-tab">
                                            <?php if(isset($translations['am'])): ?>
                                                <?php foreach($translations['am'] as $section => $items): ?>
                                                    <div class="mb-4">
                                                        <h4><?php echo ucfirst($section); ?></h4>
                                                        <hr>
                                                        <?php foreach($items as $key => $value): ?>
                                                            <div class="mb-3">
                                                                <label for="am_<?php echo $section; ?>_<?php echo $key; ?>" class="form-label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></label>
                                                                <?php if(strlen($value) > 100): ?>
                                                                    <textarea class="form-control" id="am_<?php echo $section; ?>_<?php echo $key; ?>" name="translations[am][<?php echo $section; ?>][<?php echo $key; ?>]" rows="3"><?php echo htmlspecialchars($value); ?></textarea>
                                                                <?php else: ?>
                                                                    <input type="text" class="form-control" id="am_<?php echo $section; ?>_<?php echo $key; ?>" name="translations[am][<?php echo $section; ?>][<?php echo $key; ?>]" value="<?php echo htmlspecialchars($value); ?>">
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="alert alert-warning">No Amharic translations found.</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button type="submit" name="update_translations" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Translations
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
    </script>
</body>
</html>
