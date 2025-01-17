<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
require_once 'db_config.php';

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $id = intval($_POST['id']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $price = floatval($_POST['price']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        $sql = "UPDATE products SET title = ?, price = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sdsi", $title, $price, $description, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Handle image update if a new image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = "images/products/";
                $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = "product_" . $id . "_" . uniqid() . "." . $file_extension;
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_sql = "UPDATE products SET image_path = ? WHERE id = ?";
                    $image_stmt = mysqli_prepare($conn, $image_sql);
                    mysqli_stmt_bind_param($image_stmt, "si", $target_file, $id);
                    mysqli_stmt_execute($image_stmt);
                }
            }

            $response['status'] = 'success';
            $response['message'] = 'Product updated successfully';
        } else {
            throw new Exception("Error updating product: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
