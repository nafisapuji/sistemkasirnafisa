<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

include "../koneksi.php";

/* =========================
   TRANSAKSI TERBARU
========================= */

$query = mysqli_query($koneksi, "
    SELECT 
        p.id_penjualan,
        p.tanggal_penjualan,
        p.total_harga,
        pl.nama_pelanggan
    FROM penjualan p
    LEFT JOIN pelanggan pl
        ON p.id_pelanggan = pl.id_pelanggan
    ORDER BY p.id_penjualan DESC
    LIMIT 5
");


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Kasir - Beranda</title>
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <div class="logo-area">

                <img src="../assets/img/logo.jpg" class="logo-icon">

                <div>
                    <h2>K-Mart</h2>
                    <span>Management System</span>
                </div>

            </div>
        </div>

        <div class="menu-title">
            Menu Utama
        </div>

        <nav class="menu">

            <a href="index.php" class="active">
                <span class="menu-icon">⌂</span>
                <span>Beranda</span>
            </a>

            <a href="produk.php">
                <span class="menu-icon">▣</span>
                <span>Produk</span>
            </a>

            <a href="pelanggan.php">
                <span class="menu-icon">♙</span>
                <span>Pelanggan</span>
            </a>

            <a href="penjualan.php">
                <span class="menu-icon">▤</span>
                <span>Penjualan</span>
            </a>

            <a href="laporan.php">
                <span class="menu-icon">◷</span>
                <span>Laporan</span>
            </a>

            <a href="user.php">
                <span class="menu-icon">♙</span>
                <span>Users</span>
            </a>
            
        </nav>

    </aside>


<!-- MAIN -->
<main class="main">

    <!-- TOPBAR -->

    <header class="topbar">

        <div class="topbar-left">

            <h3>Beranda</h3>

            <span>Sistem Pengelolaan Kasir</span>

        </div>


        <div class="topbar-right">


            <!-- PROFIL USER -->

            <div class="admin-profile">

                <div class="admin-avatar">

                    <?= strtoupper(
                        substr($_SESSION['nama'], 0, 1)
                    ); ?>

                </div>


                <div class="admin-info">

                    <strong>
                        <?= htmlspecialchars($_SESSION['nama']); ?>
                    </strong>

                    <small>
                        @<?= htmlspecialchars($_SESSION['username']); ?>
                    </small>

                </div>

            </div>


            <!-- LOGOUT -->

            <a
                href="../logout.php"
                class="btn-logout"
                onclick="return confirm('Yakin ingin logout?')"
            >
                Logout
            </a>


        </div>

    </header>

    <!-- =========================
         CONTENT
    ========================= -->

    <section class="content">


        <!-- WELCOME -->

        <div class="welcome">

            <h1>
                Selamat Datang,
                <?= htmlspecialchars(
                    $_SESSION['nama']
                ); ?>
            </h1>

            <p>
                Silakan pilih menu yang ingin digunakan.
            </p>

        </div>



        <!-- =========================
             MENU CARDS
        ========================= -->

        <div class="menu-grid">


            <!-- PRODUK -->

            <a
                href="produk.php"
                class="menu-card"
            >

                <div class="menu-card-icon">
                    ▣
                </div>

                <h3>
                    Produk
                </h3>

                <p>
                    Lihat data produk, harga,
                    dan stok barang.
                </p>

            </a>



            <!-- PELANGGAN -->

            <a
                href="pelanggan.php"
                class="menu-card"
            >

                <div class="menu-card-icon">
                    ♙
                </div>

                <h3>
                    Pelanggan
                </h3>

                <p>
                    Lihat dan kelola data pelanggan.
                </p>

            </a>



            <!-- PENJUALAN -->

            <a
                href="penjualan.php"
                class="menu-card"
            >

                <div class="menu-card-icon">
                    ▤
                </div>

                <h3>
                    Penjualan
                </h3>

                <p>
                    Catat dan lihat transaksi penjualan.
                </p>

            </a>

            <a href="detail_penjualan.php" class="menu-card">

                <div class="menu-card-icon">
                    ☷
                </div>

                <h3>Detail Penjualan</h3>

                <p>
                    Lihat rincian produk dalam setiap transaksi.
                </p>

            </a>




        </div>



        <!-- =========================
             TRANSAKSI TERBARU
        ========================= -->

        <div class="section-title">


            <h2>
                Transaksi Terbaru
            </h2>


            <a href="penjualan.php">
                Lihat Semua
            </a>


        </div>



        <div class="transaction-box">


            <table>


                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Pelanggan
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (mysqli_num_rows($query) > 0):

                    while (
                        $data = mysqli_fetch_assoc($query)
                    ):

                ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            #<?= $data['id_penjualan']; ?>

                        </td>


                        <!-- PELANGGAN -->

                        <td>

                            <?= !empty(
                                $data['nama_pelanggan']
                            )
                                ? htmlspecialchars(
                                    $data['nama_pelanggan']
                                )
                                : 'Pelanggan Umum'; ?>

                        </td>


                        <!-- TANGGAL -->

                        <td>

                            <?= date(
                                'd-m-Y H:i',
                                strtotime(
                                    $data['tanggal_penjualan']
                                )
                            ); ?>

                        </td>


                        <!-- TOTAL -->

                        <td>

                            Rp

                            <?= number_format(
                                $data['total_harga'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>


                    </tr>


                <?php

                    endwhile;

                else:

                ?>


                    <tr>

                        <td
                            colspan="4"
                            class="empty"
                        >
                            Belum ada transaksi.
                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>


    </section>


</main>

</body>
</html>