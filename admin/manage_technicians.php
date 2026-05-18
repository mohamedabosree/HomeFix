<?php


require_once '../backend/auth.php';
require_once '../backend/admin_db.php';


if (!isAdmin()) {
  
    header("Location: ../Frontend/auth.php"); 
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tech'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $specialty = trim($_POST['specialty']);
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);

    if (empty($name) || empty($phone) || empty($city)) {
        $error = "Name, Phone, and City are mandatory parameters.";
    } else {
        global $connection;
        $s_city = mysqli_real_escape_string($connection, $city);
        $s_street = mysqli_real_escape_string($connection, $street);
        mysqli_query($connection, "INSERT INTO locations (city, street_name) VALUES ('$s_city', '$s_street')");
        $loc_id = mysqli_insert_id($connection);

        $s_name = mysqli_real_escape_string($connection, $name);
        $s_phone = mysqli_real_escape_string($connection, $phone);
        $s_spec = mysqli_real_escape_string($connection, $specialty);
        
        $query = "INSERT INTO technicians (location_id, name, phone, specialty) VALUES ('$loc_id', '$s_name', '$s_phone', '$s_spec')";
        if (mysqli_query($connection, $query)) {
            $success = "Technician actively deployed.";
        } else {
            $error = "System error during deployment.";
        }
    }
}

if (isset($_GET['delete'])) {
    global $connection;
    $id = (int)$_GET['delete'];
    mysqli_query($connection, "DELETE FROM technicians WHERE tech_id = '$id'");
    $success = "Technician record purged.";
}

$technicians = getAllTechnicians();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Manage Technicians | HomeFix Admin</title>
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
                    <a class="nav-link text-dark mb-2" href="manage_services.php"><i class="bi bi-tools me-2"></i> Services</a>
                    <a class="nav-link active fw-bold mb-2" href="manage_technicians.php"><i class="bi bi-person-badge me-2"></i> Technicians</a>
                    <a class="nav-link text-dark mb-2" href="manage_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a>
                </div>
            </div>

            <main class="col-md-10 py-4 px-4">
                <h1 class="h3 fw-bold text-dark mb-4">Technician Roster</h1>

                <?php if ($success): ?><div class="alert alert-success border-0 shadow-sm"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger border-0 shadow-sm"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold py-3">Deploy New Technician</div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="add_tech" value="1">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Full Name" required></div>
                                <div class="col-md-3"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required></div>
                                <div class="col-md-2"><input type="text" name="specialty" class="form-control" placeholder="Specialty (e.g. Plumber)" required></div>
                                <div class="col-md-2"><input type="text" name="city" class="form-control" placeholder="City" required></div>
                                <div class="col-md-2"><input type="text" name="street" class="form-control" placeholder="Street" required></div>
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold">Deploy to Roster</button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th><th>Name</th><th>Specialty</th><th>Location</th><th>Status</th><th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($technicians as $tech): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?php echo $tech['tech_id']; ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($tech['name']); ?><br><small class="text-secondary"><?php echo htmlspecialchars($tech['phone']); ?></small></td>
                                    <td><?php echo htmlspecialchars($tech['specialty']); ?></td>
                                    <td><?php echo htmlspecialchars($tech['city'] . ', ' . $tech['street_name']); ?></td>
                                    <td><span class="badge bg-success"><?php echo htmlspecialchars($tech['availability_status']); ?></span></td>
                                    <td class="text-end pe-4"><a href="?delete=<?php echo $tech['tech_id']; ?>" class="btn btn-sm btn-outline-danger">Purge</a></td>
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
