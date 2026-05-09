<?php
/* HOMEFIX - SERVICE MANAGEMENT
 * Handles Create, Read, Update, and Delete logic for the service catalog.
 */

require_once '../backend/auth.php';
require_once '../backend/admin_db.php';

if (!isAdmin()) {
    header("Location: ../auth.php");
    exit;
}

$error = '';
$success = '';
$edit_id = null;
$edit_service = null;

$services = getAllServicesAdmin();

// Process Deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if (deleteService($_GET['delete'])) {
        $success = "Service entry removed from the database.";
        $services = getAllServicesAdmin();
    } else {
        $error = "System error: Failed to execute deletion command.";
    }
}

// Process Edit State
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    foreach ($services as $service) {
        if ($service['id'] == $edit_id) {
            $edit_service = $service;
            break;
        }
    }
}

// Process Form Submission (Add/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $image_icon = trim($_POST['image_icon'] ?? 'bi-tools');
    $service_id = $_POST['service_id'] ?? '';

    if (empty($name) || empty($price)) {
        $error = "Service Name and Price metrics are strictly required.";
    } elseif (!is_numeric($price)) {
        $error = "Pricing must contain valid numerical data.";
    } else {
        if (empty($service_id)) {
            if (addService($name, $description, $price, $image_icon)) {
                $success = "New service successfully injected into the catalog.";
                $services = getAllServicesAdmin();
            } else {
                $error = "System error: Failed to commit new service.";
            }
        } else {
            if (updateService($service_id, $name, $description, $price, $image_icon)) {
                $success = "Service parameters updated successfully.";
                $edit_id = null;
                $edit_service = null;
                $services = getAllServicesAdmin();
            } else {
                $error = "System error: Failed to execute parameter update.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | HomeFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="index.php">HomeFix Admin</a>
            <div class="d-flex ms-auto gap-3">
                <a href="../index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> View Live Site</a>
                <a href="../backend/logout.php" class="btn btn-danger btn-sm"><i class="bi bi-power me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 shadow-sm py-4">
                <div class="nav flex-column nav-pills me-3">
                    <a class="nav-link text-dark mb-2" href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                    <a class="nav-link text-dark mb-2" href="manage_users.php"><i class="bi bi-people me-2"></i> Users</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Service Catalog Management</h1>

                <?php if ($success): ?>
                    <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3">
                        <?php echo $edit_id ? 'Update Existing Service' : 'Initialize New Service'; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($edit_id): ?>
                                <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($edit_id); ?>">
                            <?php endif; ?>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Service Nomenclature</label>
                                    <input type="text" name="name" class="form-control" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['name']) : ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Base Price (EGP)</label>
                                    <input type="number" name="price" step="0.01" class="form-control" required value="<?php echo $edit_service ? htmlspecialchars($edit_service['price']) : ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Bootstrap Icon Class</label>
                                    <input type="text" name="image_icon" class="form-control" placeholder="e.g., bi-tools" value="<?php echo $edit_service ? htmlspecialchars($edit_service['image_icon']) : 'bi-tools'; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Operational Description</label>
                                <textarea name="description" class="form-control" rows="2"><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary fw-bold"><?php echo $edit_id ? 'Execute Update' : 'Commit to Database'; ?></button>
                            <?php if ($edit_id): ?>
                                <a href="manage_services.php" class="btn btn-light border ms-2">Abort</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Icon</th>
                                        <th>Service Name</th>
                                        <th>Price (EGP)</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-secondary">#<?php echo htmlspecialchars($service['id']); ?></td>
                                            <td><i class="bi <?php echo htmlspecialchars($service['image_icon']); ?> fs-5 text-primary"></i></td>
                                            <td class="fw-bold"><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td><?php echo htmlspecialchars($service['price']); ?> EGP</td>
                                            <td class="text-end pe-4">
                                                <a href="?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary me-2">Configure</a>
                                                <a href="?delete=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirm deletion of this service from the platform database.')">Drop</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>