<?php
/**
 * Settings Panel
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
$site_title = $contact_email = $contact_phone = $contact_address = "";
$facebook_url = $twitter_url = $linkedin_url = $instagram_url = "";
$success_message = $error_message = "";

// Get current settings
try {
    // Check if settings table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() == 0) {
        // Create settings table if it doesn't exist
        $sql = "CREATE TABLE settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) NOT NULL UNIQUE,
            setting_value TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
        
        // Insert default settings
        $default_settings = [
            ['site_title', 'Success Microfinance Institution S.C.'],
            ['contact_email', 'sumfihrsc@gmail.com'],
            ['contact_phone', '011-668-69-11'],
            ['contact_address', 'Addis Ababa, Ethiopia'],
            ['facebook_url', '#'],
            ['twitter_url', '#'],
            ['linkedin_url', '#'],
            ['instagram_url', '#']
        ];
        
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)";
        $stmt = $pdo->prepare($sql);
        
        foreach ($default_settings as $setting) {
            $stmt->bindParam(':key', $setting[0], PDO::PARAM_STR);
            $stmt->bindParam(':value', $setting[1], PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    
    // Get all settings
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Assign settings to variables
    $site_title = $settings['site_title'] ?? '';
    $contact_email = $settings['contact_email'] ?? '';
    $contact_phone = $settings['contact_phone'] ?? '';
    $contact_address = $settings['contact_address'] ?? '';
    $facebook_url = $settings['facebook_url'] ?? '';
    $twitter_url = $settings['twitter_url'] ?? '';
    $linkedin_url = $settings['linkedin_url'] ?? '';
    $instagram_url = $settings['instagram_url'] ?? '';
    
} catch(PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_settings'])) {
    // Get form data
    $site_title = trim($_POST['site_title']);
    $contact_email = trim($_POST['contact_email']);
    $contact_phone = trim($_POST['contact_phone']);
    $contact_address = trim($_POST['contact_address']);
    $facebook_url = trim($_POST['facebook_url']);
    $twitter_url = trim($_POST['twitter_url']);
    $linkedin_url = trim($_POST['linkedin_url']);
    $instagram_url = trim($_POST['instagram_url']);
    
    // Validate form data
    if (empty($site_title)) {
        $error_message = "Site title is required.";
    } elseif (empty($contact_email)) {
        $error_message = "Contact email is required.";
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (empty($contact_phone)) {
        $error_message = "Contact phone is required.";
    } elseif (empty($contact_address)) {
        $error_message = "Contact address is required.";
    } else {
        try {
            // Update settings
            $settings_to_update = [
                ['site_title', $site_title],
                ['contact_email', $contact_email],
                ['contact_phone', $contact_phone],
                ['contact_address', $contact_address],
                ['facebook_url', $facebook_url],
                ['twitter_url', $twitter_url],
                ['linkedin_url', $linkedin_url],
                ['instagram_url', $instagram_url]
            ];
            
            $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) 
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
            $stmt = $pdo->prepare($sql);
            
            foreach ($settings_to_update as $setting) {
                $stmt->bindParam(':key', $setting[0], PDO::PARAM_STR);
                $stmt->bindParam(':value', $setting[1], PDO::PARAM_STR);
                $stmt->execute();
            }
            
            $success_message = "Settings updated successfully.";
        } catch(PDOException $e) {
            $error_message = "Error updating settings: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Success Microfinance Institution S.C.</title>
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
                <a href="languages.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-language me-2"></i>Language Manager
                </a>
                <a href="media.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-images me-2"></i>Media Library
                </a>
                <a href="settings.php" class="list-group-item list-group-item-action bg-transparent second-text active">
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
                    <h2 class="fs-2 m-0">Settings</h2>
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
                <?php if(!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
                <?php endif; ?>

                <?php if(!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
                <?php endif; ?>

                <div class="row my-3">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Social Media</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="tab-content" id="settingsTabsContent">
                                        <!-- General Settings -->
                                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                            <div class="mb-3">
                                                <label for="site_title" class="form-label">Site Title</label>
                                                <input type="text" class="form-control" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" required>
                                                <div class="form-text">This will be displayed in the browser title bar and various places on the website.</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Contact Settings -->
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                            <div class="mb-3">
                                                <label for="contact_email" class="form-label">Contact Email</label>
                                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="contact_address" class="form-label">Contact Address</label>
                                                <textarea class="form-control" id="contact_address" name="contact_address" rows="3" required><?php echo htmlspecialchars($contact_address); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <!-- Social Media Settings -->
                                        <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                                            <div class="mb-3">
                                                <label for="facebook_url" class="form-label">Facebook URL</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                                    <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($facebook_url); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="twitter_url" class="form-label">Twitter URL</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                    <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($twitter_url); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($linkedin_url); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="instagram_url" class="form-label">Instagram URL</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                    <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($instagram_url); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button type="submit" name="update_settings" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Settings
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
