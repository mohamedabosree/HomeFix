<?php


require_once '../backend/auth.php';
require_once '../backend/db.php'; 


if (!isAdmin()) {

    header("Location: ../Frontend/auth.php"); 
    exit;
}

global $connection;
$error = ''; $success = ''; $edit_id = null; $edit_service = null;


if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (mysqli_query($connection, "DELETE FROM services WHERE id = $id")) {
        $success = "Service entry purged from catalog.";
    } else {
        $error = "Database constraint: Cannot delete service with active bookings.";
    }
}


if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = mysqli_query($connection, "SELECT * FROM services WHERE id = $edit_id");
    $edit_service = mysqli_fetch_assoc($edit_query);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = (int)$_POST['category_id'];
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $desc = mysqli_real_escape_string($connection, $_POST['description']);
    $price = (float)$_POST['price'];
    $icon = mysqli_real_escape_string($connection, $_POST['image_icon']);
    $service_id = $_POST['service_id'] ?? '';

    if (empty($service_id)) {
        
        $query = "INSERT INTO services (category_id, name, description, price, image_icon) VALUES ($cat_id, '$name', '$desc', $price, '$icon')";
        $success_msg = "New service successfully injected.";
    } else {
       
        $id = (int)$service_id;
        $query = "UPDATE services SET category_id=$cat_id, name='$name', description='$desc', price=$price, image_icon='$icon' WHERE id=$id";
        $success_msg = "Service parameters updated.";
    }

    if (mysqli_query($connection, $query)) {
        $success = $success_msg;
        $edit_id = null; $edit_service = null;
    } else {
        $error = "System error: " . mysqli_error($connection);
    }
}


$services = mysqli_query($connection, "SELECT s.*, c.category_name as cat_name 
      FROM services s 
    JOIN categories c ON s.category_id = c.category_id 
     ORDER BY c.category_name ASC");
$categories = mysqli_query($connection, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Services | HomeFix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a>
            <div class="ms-auto"><a href="../Frontend/index.php" class="btn btn-outline-light btn-sm">View Live Site</a></div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 shadow-sm py-4">
                <div class="nav flex-column nav-pills me-3">
                    <a class="nav-link text-dark mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link text-dark mb-2" href="manage_users.php"><i class="bi bi-people me-2"></i> Users</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Service Catalog CRUD</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo $success; ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger border-0 shadow-sm"><?php echo $error; ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3"><?php echo $edit_id ? 'Update Service' : 'Add New Service'; ?></div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="service_id" value="<?php echo $edit_id; ?>">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                            <option value="<?php echo $cat['category_id']; ?>" <?php echo ($edit_service && $edit_service['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['category_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Name</label>
                                    <input type="text" name="name" class="form-control" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['name']) : ''; ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Price (EGP)</label>
                                    <input type="number" name="price" class="form-control" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['price']) : ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Icon (Bootstrap Icon Class)</label>
                                    <input type="text" name="image_icon" class="form-control" value="<?php echo $edit_service ? htmlspecialchars($edit_service['image_icon']) : 'bi-tools'; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="2"><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold"><?php echo $edit_id ? 'Save Changes' : 'Create Service'; ?></button>
                            <?php if ($edit_id): ?><a href="manage_services.php" class="btn btn-light border ms-2">Cancel</a><?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="ps-4">Category</th><th>Name</th><th>Price</th><th class="text-end pe-4">Actions</th></tr></thead>
                        <tbody>
                            <?php while($s = mysqli_fetch_assoc($services)): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary"><?php echo htmlspecialchars($s['cat_name']); ?></td>
                                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                                    <td><?php echo number_format($s['price']); ?> EGP</td>
                                    <td class="text-end pe-4">
                                        <a href="?edit=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Purge service?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
