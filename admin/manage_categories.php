<?php
/* HOMEFIX ADMIN - CATEGORY MANAGEMENT
 * Defines the high-level service taxonomy.
 */

require_once '../backend/auth.php';
require_once '../backend/admin_db.php';
require_once '../backend/db.php';

// Find this section at the top of your admin files:
if (!isAdmin()) {
    // Change this line:
    header("Location: ../Frontend/auth.php"); 
    exit;
}

$error = '';
$success = '';
$edit_id = null;

if (isset($_GET['delete'])) {
    global $connection;
    $id = (int)$_GET['delete'];
    if (mysqli_query($connection, "DELETE FROM categories WHERE category_id = '$id'")) {
        $success = "Category purged. Linked services are now uncategorized.";
    } else {
        $error = "System error during deletion.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $connection;
    $name = trim($_POST['category_name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $cat_id = $_POST['category_id'] ?? '';

    if (empty($name)) {
        $error = "Category Name is strictly required.";
    } else {
        $safe_name = mysqli_real_escape_string($connection, $name);
        $safe_desc = mysqli_real_escape_string($connection, $desc);

        if (empty($cat_id)) {
            $query = "INSERT INTO categories (category_name, description) VALUES ('$safe_name', '$safe_desc')";
            $success = "New category taxonomy initialized.";
        } else {
            $safe_id = (int)$cat_id;
            $query = "UPDATE categories SET category_name = '$safe_name', description = '$safe_desc' WHERE category_id = '$safe_id'";
            $success = "Category parameters updated.";
        }
        
        if (!mysqli_query($connection, $query)) {
            $error = "Database execution failed.";
            $success = '';
        }
    }
}

$categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories | HomeFix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4"><a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a></div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 shadow-sm py-4">
                <div class="nav flex-column nav-pills me-3">
                    <a class="nav-link text-dark mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_categories.php"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link text-dark mb-2" href="manage_technicians.php"><i class="bi bi-person-badge me-2"></i> Technicians</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link text-dark mb-2" href="manage_payments.php"><i class="bi bi-credit-card me-2"></i> Payments</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Service Taxonomy</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger border-0 shadow-sm"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3">Define Category</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><input type="text" name="category_name" class="form-control" placeholder="Category Name (e.g. HVAC)" required></div>
                                <div class="col-md-6"><input type="text" name="description" class="form-control" placeholder="Brief Description"></div>
                                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100 fw-bold">Commit</button></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">ID</th><th>Name</th><th>Description</th><th class="text-end pe-4">Action</th></tr></thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?php echo $cat['category_id']; ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($cat['description']); ?></td>
                                    <td class="text-end pe-4"><a href="?delete=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-outline-danger">Drop</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>