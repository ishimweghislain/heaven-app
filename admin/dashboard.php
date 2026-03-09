<?php
/**
 * Admin Dashboard
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

// Get user information
$username = $_SESSION['admin_username'];
$role = $_SESSION['admin_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Success Microfinance Institution S.C.</title>
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
                <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent second-text active">
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
                    <h2 class="fs-2 m-0">Dashboard</h2>
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
                                <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($username); ?>
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
                <div class="row g-3 my-2">
                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">3</h3>
                                <p class="fs-5">News Articles</p>
                            </div>
                            <i class="fas fa-newspaper fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">5</h3>
                                <p class="fs-5">Products</p>
                            </div>
                            <i class="fas fa-money-bill-wave fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">4</h3>
                                <p class="fs-5">Partners</p>
                            </div>
                            <i class="fas fa-handshake fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">2</h3>
                                <p class="fs-5">Languages</p>
                            </div>
                            <i class="fas fa-language fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                </div>

                <div class="row my-5">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="fs-4 mb-0">Recent News</h3>
                            </div>
                            <div class="card-body">
                                <table class="table bg-white rounded shadow-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="50">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>New Branch Opening in Addis Ababa</td>
                                            <td>May 15, 2025</td>
                                            <td><a href="news-edit.php?id=1" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Financial Literacy Workshop Series</td>
                                            <td>April 28, 2025</td>
                                            <td><a href="news-edit.php?id=2" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>New Agricultural Loan Program</td>
                                            <td>April 10, 2025</td>
                                            <td><a href="news-edit.php?id=3" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="fs-4 mb-0">Recent Products</h3>
                            </div>
                            <div class="card-body">
                                <table class="table bg-white rounded shadow-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="50">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Regular Saving</td>
                                            <td>Savings</td>
                                            <td><a href="products-edit.php?id=1" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Group Saving</td>
                                            <td>Savings</td>
                                            <td><a href="products-edit.php?id=2" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Home Construction Loan</td>
                                            <td>Loans</td>
                                            <td><a href="products-edit.php?id=3" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="fs-4 mb-0">Quick Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <a href="news-add.php" class="btn btn-primary w-100 py-3">
                                            <i class="fas fa-plus-circle me-2"></i>Add News
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="products-add.php" class="btn btn-success w-100 py-3">
                                            <i class="fas fa-plus-circle me-2"></i>Add Product
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="media-upload.php" class="btn btn-info w-100 py-3 text-white">
                                            <i class="fas fa-upload me-2"></i>Upload Media
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <a href="../index.php" target="_blank" class="btn btn-secondary w-100 py-3">
                                            <i class="fas fa-eye me-2"></i>View Website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

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
