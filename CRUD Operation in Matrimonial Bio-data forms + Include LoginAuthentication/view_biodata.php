<?php
require 'connect.php';

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM biodata WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_biodata.php");
    exit();
}

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);

    // Collect and sanitize POST data (you can add all fields you want here)
    $fullName = $_POST['full_name'] ?? '';
    $age = intval($_POST['age'] ?? 0);
    $gender = $_POST['gender'] ?? '';
    $maritalStatus = $_POST['marital_status'] ?? '';
    $height = $_POST['height_cm'] ?? null;
    $weight = $_POST['weight_kg'] ?? null;
    $fatherName = $_POST['father_name'] ?? '';
    $fatherOccupation = $_POST['father_occupation'] ?? '';
    $motherName = $_POST['mother_name'] ?? '';
    $motherOccupation = $_POST['mother_occupation'] ?? '';
    $siblings = $_POST['siblings'] ?? null;
    $preferredAge = $_POST['preferred_age_range'] ?? '';
    $preferredHeight = $_POST['preferred_height'] ?? '';
    $preferredEducation = $_POST['preferred_education_level'] ?? '';
    $preferredProfession = $_POST['preferred_profession'] ?? '';
    $idealPartner = $_POST['ideal_partner_description'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $aboutYou = $_POST['description'] ?? '';
    $education = $_POST['education'] ?? '';
    $profession = $_POST['profession'] ?? '';
    $income = $_POST['annual_income'] ?? null;

    $sql = "UPDATE biodata SET 
        full_name=?, age=?, gender=?, marital_status=?, height_cm=?, weight_kg=?, 
        father_name=?, father_occupation=?, mother_name=?, mother_occupation=?, siblings=?, 
        preferred_age_range=?, preferred_height=?, preferred_education_level=?, preferred_profession=?, ideal_partner_description=?, 
        email=?, phone=?, address=?, city=?, description=?, education=?, profession=?, annual_income=? 
        WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sissddssssisssssssssssssi",
        $fullName, $age, $gender, $maritalStatus, $height, $weight,
        $fatherName, $fatherOccupation, $motherName, $motherOccupation, $siblings,
        $preferredAge, $preferredHeight, $preferredEducation, $preferredProfession, $idealPartner,
        $email, $phone, $address, $city, $aboutYou, $education, $profession, $income,
        $id
    );

    if ($stmt->execute()) {
        $msg = "Record updated successfully!";
    } else {
        $msg = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Get data for edit form if requested
$edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
$edit_data = null;
if ($edit_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM biodata WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all data
$sql = "SELECT * FROM biodata";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Biodata</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }
        th {
            background-color: #0066cc;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a {
            color: #0066cc;
            text-decoration: none;
            margin-right: 8px;
        }
        .form-update {
            background: #fff;
            padding: 15px;
            margin-top: 30px;
            border: 1px solid #ddd;
            max-width: 900px;
        }
        .form-update label {
            display: block;
            margin-top: 10px;
        }
        .form-update input, .form-update select, .form-update textarea {
            width: 100%;
            padding: 6px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        .message {
            color: green;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>All Biodata Records</h2>

<?php if (!empty($msg)): ?>
    <p class="message"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Marital Status</th>
            <th>Height</th>
            <th>Weight</th>
            <th>Father's Name</th>
            <th>Father's Occupation</th>
            <th>Mother's Name</th>
            <th>Mother's Occupation</th>
            <th>Siblings</th>
            <th>Preferred Age Range</th>
            <th>Preferred Height</th>
            <th>Preferred Education</th>
            <th>Preferred Profession</th>
            <th>Ideal Partner</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>About You</th>
            <th>Education</th>
            <th>Profession</th>
            <th>Income</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["full_name"]) ?></td>
            <td><?= $row["age"] ?></td>
            <td><?= htmlspecialchars($row["gender"]) ?></td>
            <td><?= htmlspecialchars($row["marital_status"]) ?></td>
            <td><?= htmlspecialchars($row["height_cm"]) ?></td>
            <td><?= htmlspecialchars($row["weight_kg"]) ?></td>
            <td><?= htmlspecialchars($row["father_name"]) ?></td>
            <td><?= htmlspecialchars($row["father_occupation"]) ?></td>
            <td><?= htmlspecialchars($row["mother_name"]) ?></td>
            <td><?= htmlspecialchars($row["mother_occupation"]) ?></td>
            <td><?= htmlspecialchars($row["siblings"]) ?></td>
            <td><?= htmlspecialchars($row["preferred_age_range"]) ?></td>
            <td><?= htmlspecialchars($row["preferred_height"]) ?></td>
            <td><?= htmlspecialchars($row["preferred_education_level"]) ?></td>
            <td><?= htmlspecialchars($row["preferred_profession"]) ?></td>
            <td><?= htmlspecialchars($row["ideal_partner_description"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= htmlspecialchars($row["phone"]) ?></td>
            <td><?= htmlspecialchars($row["address"]) ?></td>
            <td><?= htmlspecialchars($row["city"]) ?></td>
            <td><?= htmlspecialchars($row["description"]) ?></td>
            <td><?= htmlspecialchars($row["education"]) ?></td>
            <td><?= htmlspecialchars($row["profession"]) ?></td>
            <td><?= htmlspecialchars($row["annual_income"]) ?></td>
            <td>
                <a href="?edit_id=<?= $row['id'] ?>">Update</a>
                <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No biodata found in the database.</p>
<?php endif; ?>

<?php if ($edit_data): ?>
    <div class="form-update">
        <h2>Update Record ID: <?= $edit_data['id'] ?></h2>
        <form method="POST" action="view_biodata.php">
            <input type="hidden" name="update_id" value="<?= $edit_data['id'] ?>">

            <label>Full Name:
                <input type="text" name="full_name" value="<?= htmlspecialchars($edit_data['full_name']) ?>" required>
            </label>

            <label>Age:
                <input type="number" name="age" min="18" max="99" value="<?= htmlspecialchars($edit_data['age']) ?>" required>
            </label>

            <label>Gender:
                <select name="gender" required>
                    <option value="male" <?= $edit_data['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= $edit_data['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                    <option value="other" <?= $edit_data['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </label>

            <label>Marital Status:
                <input type="text" name="marital_status" value="<?= htmlspecialchars($edit_data['marital_status']) ?>">
            </label>

            <label>Height (cm):
                <input type="number" step="0.01" name="height_cm" value="<?= htmlspecialchars($edit_data['height_cm']) ?>">
            </label>

            <label>Weight (kg):
                <input type="number" step="0.01" name="weight_kg" value="<?= htmlspecialchars($edit_data['weight_kg']) ?>">
            </label>

            <label>Father's Name:
                <input type="text" name="father_name" value="<?= htmlspecialchars($edit_data['father_name']) ?>">
            </label>

            <label>Father's Occupation:
                <input type="text" name="father_occupation" value="<?= htmlspecialchars($edit_data['father_occupation']) ?>">
            </label>

            <label>Mother's Name:
                <input type="text" name="mother_name" value="<?= htmlspecialchars($edit_data['mother_name']) ?>">
            </label>

            <label>Mother's Occupation:
                <input type="text" name="mother_occupation" value="<?= htmlspecialchars($edit_data['mother_occupation']) ?>">
            </label>

            <label>Siblings:
                <input type="number" name="siblings" value="<?= htmlspecialchars($edit_data['siblings']) ?>">
            </label>

            <label>Preferred Age Range:
                <input type="text" name="preferred_age_range" value="<?= htmlspecialchars($edit_data['preferred_age_range']) ?>">
            </label>

            <label>Preferred Height:
                <input type="text" name="preferred_height" value="<?= htmlspecialchars($edit_data['preferred_height']) ?>">
            </label>

            <label>Preferred Education Level:
                <input type="text" name="preferred_education_level" value="<?= htmlspecialchars($edit_data['preferred_education_level']) ?>">
            </label>

            <label>Preferred Profession:
                <input type="text" name="preferred_profession" value="<?= htmlspecialchars($edit_data['preferred_profession']) ?>">
            </label>

            <label>Ideal Partner Description:
                <textarea name="ideal_partner_description" rows="3"><?= htmlspecialchars($edit_data['ideal_partner_description']) ?></textarea>
            </label>

            <label>Email:
                <input type="email" name="email" value="<?= htmlspecialchars($edit_data['email']) ?>" required>
            </label>

            <label>Phone:
                <input type="tel" name="phone" value="<?= htmlspecialchars($edit_data['phone']) ?>" required>
            </label>

            <label>Address:
                <input type="text" name="address" value="<?= htmlspecialchars($edit_data['address']) ?>">
            </label>

            <label>City:
                <input type="text" name="city" value="<?= htmlspecialchars($edit_data['city']) ?>">
            </label>

            <label>About You:
                <textarea name="description" rows="3"><?= htmlspecialchars($edit_data['description']) ?></textarea>
            </label>

            <label>Education:
                <input type="text" name="education" value="<?= htmlspecialchars($edit_data['education']) ?>">
            </label>

            <label>Profession:
                <input type="text" name="profession" value="<?= htmlspecialchars($edit_data['profession']) ?>">
            </label>

            <label>Annual Income:
                <input type="number" step="0.01" name="annual_income" value="<?= htmlspecialchars($edit_data['annual_income']) ?>">
            </label>

            <br><br>
            <button type="submit">Update Record</button>
            <a href="view_biodata.php" style="margin-left: 15px;">Cancel</a>
        </form>
    </div>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
