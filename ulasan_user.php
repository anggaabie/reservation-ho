<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi diambil dari file koneksi.php

// Ambil data ulasan dari database
$sql = "SELECT id, nama, rating, komentar, balasan_admin FROM rating ORDER BY waktu_ulasan DESC";
$result = $conn->query($sql);
$current_page = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking Management</title>
    <style>
        /* Style umum untuk reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }


        form {
    margin: 20px; /* Jarak dari elemen lain */
    display: flex;
    justify-content: flex-start; /* Geser form ke kiri */
}

form input[type="text"] {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 300px; /* Atur lebar input */
    margin-right: 10px; /* Jarak antara input dan tombol */
}

form input[type="submit"] {
    padding: 8px 4px; /* Mengurangi padding */
    font-size: 14px; /* Mengurangi ukuran font */
    border: none;
    border-radius: 4px;
    background-color: #808588; /* Sesuaikan dengan warna tema */
    color: #fff;
    cursor: pointer;
}

form input[type="submit"]:hover {
    background-color: #666; /* Warna saat hover */
}
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            background-color: #808588;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
        }

        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            display: block;
        }

        .title {
            font-size: 24px;
            text-align: center; /* Memastikan teks berada di tengah */
            flex-grow: 1; /* Mengambil ruang yang tersedia agar teks bisa berada di tengah */
        }

        .sidebar {
            width: 200px;
            background-color: #808588;
            position: fixed;
            top: 0;
            left: -250px; /* Mulai sidebar dari luar layar */
            height: 100%;
            padding-top: 60px; /* Untuk memberi ruang di bawah header */
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: left 0.3s ease; /* Gunakan left untuk pergerakan sidebar */
            z-index: 999;
        }

        .sidebar.active {
            left: 0; /* Menampilkan sidebar */
        }

        .sidebar ul {
            list-style-type: none; /* Menghilangkan bullet point */
            padding: 0;
            width: 100%; /* Pastikan lebar sidebar penuh */
        }

        .sidebar ul li {
            width: 100%;
        }

        .sidebar ul li a {
            display: block; /* Membuat link memenuhi lebar sidebar */
            padding: 15px 20px; /* Memberikan ruang dalam link */
            color: #fff; /* Warna teks putih */
            text-decoration: none; /* Menghilangkan underline */
            width: 100%; /* Pastikan lebar penuh */
            box-sizing: border-box; /* Agar padding tidak melebihi lebar elemen */
            text-align: center; /* Teks di tengah */
        }

        .sidebar ul li a:hover {
            background-color: #444; /* Warna background saat di-hover */
        }

        main {
            padding: 20px;
            padding-top: 70px;
            padding-bottom: 20px; /* Memberi ruang di bawah untuk footer */
            flex: 1 0 auto; /* Agar konten utama dapat memperluas halaman */
            transition: margin-left 0.3s ease; /* Tambahkan transisi untuk efek mulus */
        }

        main.with-sidebar {
            margin-left: 250px; /* Memberi ruang untuk sidebar hanya jika aktif */
        }

        footer {
            background-color:#808588 ;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            width: 100%;
            position: relative;
            clear: both;
            margin-top: auto; /* Membuat footer berada di bawah */
        }

        .footer-content a {
            color: #fff;
            text-decoration: none;
            margin: 0 5px;
        }

        .footer-content a:hover {
            text-decoration: underline;
        }


        /* Responsif */
        @media (max-width: 768px) {
            main {
                margin-left: 0;
                padding-top: 60px;
            }

            .sidebar {
                width: 100%;
                top: -100%; /* Mulai dari atas layar */
                left: 0; /* Atur ulang untuk tampilan ponsel */
                transform: translateY(-100%);
                height: auto;
            }

            .sidebar.active {
                transform: translateY(0); /* Pindahkan ke layar */
            }
        }

     
        /* Style untuk menu item aktif */
.sidebar ul li a.active {
    background-color: #555; /* Warna latar belakang item aktif */
    font-weight: bold; /* Menebalkan teks item aktif */
    color: #fff; /* Warna teks item aktif */
}

.content {
    padding: 20px;
    background-color: #f9f9f9; /* Warna latar belakang yang lembut */
    border-radius: 8px; /* Sudut yang membulat */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Efek bayangan */
}

/* Gaya untuk tabel */
table {
    width: 100%; /* Membuat tabel lebar penuh */
    border-collapse: collapse; /* Menghilangkan jarak antar border sel */
    margin-top: 20px; /* Jarak atas tabel */
}

th, td {
    padding: 12px; /* Jarak dalam sel */
    text-align: left; /* Teks rata kiri */
    border-bottom: 1px solid #ddd; /* Garis bawah sel */
}

/* Gaya untuk header tabel */
th {
    background-color: #808588; /* Warna latar belakang header */
    color: white; /* Warna teks header */
}

/* Gaya untuk baris tabel saat dihover */
tr:hover {
    background-color: #f1f1f1; /* Warna saat mouse hover */
}

/* Gaya untuk tombol */
button {
    background-color: #808588; /* Warna tombol */
    color: white; /* Warna teks tombol */
    padding: 10px 15px; /* Jarak dalam tombol */
    border: none; /* Menghilangkan border */
    border-radius: 4px; /* Sudut yang membulat pada tombol */
    cursor: pointer; /* Menunjukkan kursor pointer saat hover */
    transition: background-color 0.3s; /* Transisi halus untuk perubahan warna */
}

button:hover {
    background-color: #6e7577; /* Warna saat hover pada tombol (lebih gelap) */
}

/* Gaya untuk textarea */
textarea {
    width: 100%; /* Lebar penuh */
    padding: 10px; /* Jarak dalam textarea */
    border: 1px solid #ccc; /* Border textarea */
    border-radius: 4px; /* Sudut yang membulat pada textarea */
    resize: none; /* Menonaktifkan resize textarea */
}

/* Gaya untuk input teks */
input[type="text"], input[type="number"] {
    width: 100%; /* Lebar penuh */
    padding: 10px; /* Jarak dalam input */
    border: 1px solid #ccc; /* Border input */
    border-radius: 4px; /* Sudut yang membulat pada input */
    box-sizing: border-box; /* Mengatur padding dan border dalam lebar total */
}


    </style>
 <script defer>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('main');

        sidebar.classList.toggle('active');
        mainContent.classList.toggle('with-sidebar'); 
    }
</script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="menu-icon" onclick="toggleSidebar()">
                &#9776; <!-- Simbol untuk tiga garis (ikon hamburger) -->
            </div>
            <div class="title">Hotel Alexander</div>
        </div>
    </header>

    <div class="sidebar">
        <ul>
        <li><a href="admin.php" class="<?php echo $current_page == 'admin.php' ? 'active' : ''; ?>">Home</a></li>
        <li><a href="user_list.php" class="<?php echo $current_page == 'user_list.php' ? 'active' : ''; ?>">User</a></li>
        <li><a href="daftar_kamar.php" class="<?php echo $current_page == 'daftar_kamar.php' ? 'active' : ''; ?>">Daftar kamar</a></li>
        <li><a href="komentar.php" class="<?php echo $current_page == 'komentar.php' ? 'active' : ''; ?>">Help user</a></li>
        <li><a href="ulasan_user.php" class="<?php echo $current_page == 'ulasan_user.php' ? 'active' : ''; ?>">Ulasan user</a></li>
        <li><a href="logout.php?logout=true" class="<?php echo $current_page == 'logout.php' ? 'active' : ''; ?>">Logout</a></li>
        </ul>
    </div>

    <main>
    <div class="content">
        <h2>Ulasan Pengguna</h2>
        <br>
        <br>
        <br>


        <?php if ($result->num_rows > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Balasan Admin</th>
                <th>Balas</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td>
            <?php 
            // Debugging: Cek apakah rating ada
            if (isset($row['rating'])) {
                echo $row['rating']; // Tampilkan rating untuk debugging
                for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?php echo $i <= $row['rating'] ? '' : '-o'; ?>"></i>
                <?php endfor; 
            } else {
                echo 'Rating tidak tersedia';
            }
            ?>
        </td>
        <td><?php echo htmlspecialchars($row['komentar'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td>
            <?php echo !empty($row['balasan_admin']) ? htmlspecialchars($row['balasan_admin'] ?? '', ENT_QUOTES, 'UTF-8') : 'Belum ada balasan'; ?>
        </td>
        <td>
            <form action="proses_balasan.php" method="POST">
                <input type="hidden" name="rating_id" value="<?php echo $row['id']; ?>">
                <textarea name="balasan_admin" placeholder="Tulis balasan..." required><?php echo htmlspecialchars($row['balasan_admin'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea><br>
                <button type="submit">Kirim Balasan</button>
            </form>
        </td>
    </tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
    <p>Belum ada ulasan dari pengguna.</p>
<?php endif; ?>

    </div>

    </main>

    <!-- <footer>
        <div class="footer-content">
            <p>Halaman Admin-</p>
        </div>
    </footer> -->
</body>
</html>
