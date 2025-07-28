<?php
require 'connect.php';

$fullName = $_POST['fullName'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$maritalStatus = $_POST['maritalStatus'];
$height = $_POST['height'];
$weight = $_POST['weight'];

$fatherName = $_POST['fatherName'];
$fatherOccupation = $_POST['fatherOccupation'];
$motherName = $_POST['motherName'];
$motherOccupation = $_POST['motherOccupation'];
$siblings = $_POST['siblings'];

$preferredAge = $_POST['preferredAge'];
$preferredHeight = $_POST['preferredHeight'];
$preferredEducation = $_POST['preferredEducation'];
$preferredProfession = $_POST['preferredProfession'];
$aboutPartner = $_POST['aboutPartner'];

$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];

$aboutYou = $_POST['aboutYou'];
$education = $_POST['education'];
$profession = $_POST['profession'];
$income = $_POST['income'];

$sql = "INSERT INTO biodata (
    full_name, age, gender, marital_status, height_cm, weight_kg,
    father_name, father_occupation, mother_name, mother_occupation, siblings,
    preferred_age_range, preferred_height, preferred_education_level, preferred_profession, ideal_partner_description,
    email, phone, address, city,
    description, education, profession, annual_income
) VALUES (
    '$fullName', '$age', '$gender', '$maritalStatus', '$height', '$weight',
    '$fatherName', '$fatherOccupation', '$motherName', '$motherOccupation', '$siblings',
    '$preferredAge', '$preferredHeight', '$preferredEducation', '$preferredProfession', '$aboutPartner',
    '$email', '$phone', '$address', '$city',
    '$aboutYou', '$education', '$profession', '$income'
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Data submitted successfully! <br><a href='index.html'>Go back to form</a>";
} else {
    echo "❌ Error inserting data: " . $conn->error;
}

$conn->close();
?>
