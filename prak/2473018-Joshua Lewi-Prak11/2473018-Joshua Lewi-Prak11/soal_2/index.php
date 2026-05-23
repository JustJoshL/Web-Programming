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
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            background-color: #ffffff;
            width: 800px;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: bold;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
            display: inline-block;
        }

        .btn-tambah {
            background-color: #4CAF50; /* Hijau */
            margin-bottom: 10px;
        }

        .btn-edit {
            background-color: #2196F3; /* Biru */
        }

        .btn-hapus {
            background-color: #f44336; /* Merah */
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container">
    <h2 style="text-align: center;">Data Siswa</h2> 
    <a href="tambah.php" class="btn btn-tambah">Tambah Data</a>
    <br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>
        <?php
        /** @var mysqli $conn */
        include 'koneksi.php';
        $sql = "SELECT * FROM siswa";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nama"] . "</td>";
                echo "<td>" . $row["kelas"] . "</td>";
                echo "<td>
                        <a href='edit.php?id=" . $row["id"] . "'><button class='btn btn-edit'>Edit</button></a> 
                        <a href='hapus.php?id=" . $row["id"] . "'><button class='btn btn-hapus'>Hapus</button></a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Belum ada data siswa.</td></tr>";
        }
        ?>
    </table>
    </div>
</body>

</html>