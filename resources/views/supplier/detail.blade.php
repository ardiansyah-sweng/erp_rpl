<?php
$conn = mysqli_connect("localhost", "root", "", "erp_rpllll");

$supplier_id = $_GET['id'];

$query = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Supplier</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      color: #333;
    }

    input {
      width: 50%;
      padding: 8px;
      margin: 6px 0;
      box-sizing: border-box;
      border: 1px solid #999;
      font-size: 14px;
    }

    label {
      display: block;
      margin-top: 10px;
      font-size: 14px;
    }

    button {
      padding: 10px 20px;
      margin-top: 10px;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-update {
      background-color: #007BFF;
      color: white;
    }

    .btn-kembali {
      background-color: #d3d3d3;
      color: black;
    }
  </style>
</head>
<body>

  <h2>Detail Supplier</h2>

  <form method="POST">
    <label>ID Supplier</label><br>
    <input type="text" name="supplier_id" value="<?= $row['supplier_id']; ?>" readonly><br>

    <label>Nama Supplier</label><br>
    <input type="text" name="company_name" value="<?= $row['company_name']; ?>"><br>

    <label>Alamat Supplier</label><br>
    <input type="text" name="address" value="<?= $row['address']; ?>"><br>

    <label>Telephone</label><br>
    <input type="text" name="phone_number" value="<?= $row['phone_number']; ?>"><br>

    <label>Rekening Bank</label><br>
    <input type="text" name="bank_account" value="<?= $row['bank_account']; ?>"><br>

    <button type="submit" class="btn-update">Update</button>
    <button type="button" class="btn-kembali">Kembali</button>
  </form>

</body>
</html>
