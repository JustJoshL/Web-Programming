<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[2473018]-Joshua Lewi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            background-color: #ffffff;
            width: 400px;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-update {
            background-color: #4CAF50;
        }

        .btn-kembali {
            background-color: #2196F3;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Data Siswa</h2>

        <?php
        /** @var mysqli $conn */
        include 'koneksi.php';
        $id = $_GET['id']; 

        $sql = "SELECT * FROM siswa WHERE id='$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        ?>

        <form action="proses_edit.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label>Kelas:</label>
                <input type="text" name="kelas" value="<?php echo $row['kelas']; ?>" required>
            </div>

            <input type="submit" value="Update" class="btn btn-update">
            <a href="index.php" class="btn btn-kembali">Kembali</a>
        </form>
</body>

</html>