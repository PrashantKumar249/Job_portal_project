<?php
session_start();
include("../inc/db.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = 'CMP' . rand(1000, 9999);
    $admin_id = 'ADMIN' . rand(1000, 9999);

    $relativePath = ''; // ✅ Default path in case of failure

    // Company Inputs
    $company_name = $_POST['company_name'];

    // File Upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $file = $_FILES['logo'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 500 * 1024; // 500KB

        // Check file type & size
        $fileType = mime_content_type($file['tmp_name']);
        $fileSize = $file['size'];

        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>alert('❌ Only JPG and PNG files are allowed.'); window.history.back();</script>";
            exit;
        }

        if ($fileSize > $maxSize) {
            echo "<script>alert('❌ File size must be 500KB or less.'); window.history.back();</script>";
            exit;
        }

        // Prepare upload folder
        $uploadDir = __DIR__ . "/uploads/companies/"; // absolute path
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // create folder if not exists
        }

        // Clean filename and create unique one
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $cleanCompanyName = preg_replace("/[^a-zA-Z0-9]/", "", $company_name);
        $newFileName = 'logo_' . $cleanCompanyName . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $newFileName;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Save relative path for DB
            $relativePath = $newFileName;
        } else {
            echo "<script>alert('❌ Failed to upload the file.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('❌ No file uploaded or upload error.'); window.history.back();</script>";
        exit;
    }


    $company_description = $_POST['company_description'];
    $company_industry = $_POST['company_industry'];
    $company_website = $_POST['company_website'];
    $company_address = $_POST['company_address'];
    $company_contact_email = $_POST['company_contact_email'];
    $company_contact_phone = $_POST['company_contact_phone'];

    // Admin Inputs
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password']; // ✅ Hashed password

    // Insert company
    $company_sql = "INSERT INTO companies (company_id, name, logo, description, industry, website, address, contact_email, contact_phone)
                  VALUES ('$company_id', '$company_name', '$relativePath', '$company_description', '$company_industry', '$company_website', '$company_address', '$company_contact_email', '$company_contact_phone')";

    $isAdmin = "SELECT email FROM company_admins WHERE email = '$admin_email'";
    $result = mysqli_query($conn, $isAdmin);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('❌ Admin with this email already exists.'); window.history.back();</script>";
        exit;
    }

    if (mysqli_query($conn, $company_sql)) {
        $admin_sql = "INSERT INTO company_admins (company_admin_id, company_id, name, email, password)
                  VALUES ('$admin_id', '$company_id', '$admin_name', '$admin_email', '$admin_password')";

        if (mysqli_query($conn, $admin_sql)) {
            $_SESSION['company_admin_id'] = $admin_id;
            $_SESSION['company_admin_name'] = $admin_name;
            $_SESSION['company_id'] = $company_id;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Admin insert error: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ Company insert error: " . mysqli_error($conn);
    }
}
?>