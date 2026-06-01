<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$user     = htmlspecialchars($_SESSION['user'] ?? 'User');
$role     = htmlspecialchars($_SESSION['role'] ?? '-');
$initials = strtoupper(substr($user, 0, 2));

$bulan = ['', 'JAN','FEB','MAR','APR','MEI','JUN','JUL','AGS','SEP','OKT','NOV','DES'];
$hari  = (int) date('d');
$bln   = $bulan[(int) date('n')];
$thn   = date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard – SIM Gizi</title>

<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*, *::before, *::after{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

:root{
    --indigo:#4f46e5;
    --indigo-dark:#3730a3;
    --indigo-soft:#6366f1;
    --indigo-pale:#ede9fe;
    --indigo-mist:#eef2ff;
    --indigo-text:#1e1b4b;
    --indigo-mid:#4338ca;
    --muted:#a5b4fc;
    --border:#eef0f8;
    --white:#ffffff;
    --bg:#fafbff;
}

body{
    font-family:'Nunito',sans-serif;
    background:var(--bg);
    color:var(--indigo-text);
    min-height:100vh;
}

/* TOPBAR */
.topbar{
    background:var(--white);
    border-bottom:1px solid var(--border);
    height:58px;
    padding:0 32px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:sticky;
    top:0;
    z-index:100;
}

.brand{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
}

.brand-icon{
    width:32px;
    height:32px;
    background:var(--indigo);
    border-radius:9px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.brand-name{
    font-size:16px;
    font-weight:800;
    color:var(--indigo-text);
    line-height:1.1;
}

.brand-name span{
    display:block;
}

.tb-right{
    display:flex;
    align-items:center;
    gap:14px;
}

.tb-greeting{
    font-size:13px;
    color:var(--muted);
    text-align:right;
}

.tb-greeting strong{
    display:block;
    color:var(--indigo);
    font-weight:700;
}

.tb-avatar{
    width:34px;
    height:34px;
    border-radius:50%;
    background:var(--indigo-pale);
    border:2px solid var(--muted);
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--indigo);
    font-weight:800;
    font-size:12px;
}

.tb-logout{
    display:flex;
    align-items:center;
    gap:6px;
    border:1px solid var(--border);
    border-radius:8px;
    padding:6px 14px;
    font-size:12px;
    font-weight:700;
    color:var(--indigo-text);
    text-decoration:none;
    background:var(--white);
    transition:0.2s;
}

.tb-logout:hover{
    background:var(--indigo-mist);
}

/* CONTAINER */
.container{
    max-width:1100px;
    margin:0 auto;
    padding:32px 24px 48px;
}

/* HERO */
.hero-row{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    margin-bottom:28px;
    gap:16px;
}

.hero-text h2{
    font-size:32px;
    font-weight:800;
    margin-bottom:8px;
    line-height:1.2;
}

.hero-text p{
    font-size:14px;
    color:var(--muted);
    font-weight:600;
}

.hero-date{
    background:var(--indigo-pale);
    border-radius:14px;
    padding:12px 20px;
    text-align:center;
}

.hd-day{
    font-size:32px;
    font-weight:800;
    color:var(--indigo);
}

.hd-month{
    font-size:11px;
    color:var(--muted);
    font-weight:700;
}

/* LABEL */
.section-lbl{
    font-size:10px;
    font-weight:800;
    color:#c7d2fe;
    letter-spacing:1.5px;
    text-transform:uppercase;
    margin-bottom:16px;
}

/* MENU */
.menu-hero{
    width:100%;
}

.main-card{
    width:100%;
    background:var(--indigo);
    border-radius:18px;
    padding:28px;
    min-height:230px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    text-decoration:none;
    transition:0.2s;
}

.main-card:hover{
    transform:translateY(-4px);
    background:var(--indigo-mid);
}

.mc-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.mc-icon{
    width:60px;
    height:60px;
    border-radius:16px;
    background:rgba(255,255,255,.15);
    display:flex;
    align-items:center;
    justify-content:center;
}

.mc-badge{
    background:rgba(255,255,255,.2);
    color:#dbeafe;
    font-size:11px;
    font-weight:800;
    padding:6px 12px;
    border-radius:20px;
}

.mc-title{
    color:#fff;
    font-size:28px;
    font-weight:800;
    margin-bottom:8px;
}

.mc-sub{
    color:#c7d2fe;
    font-size:14px;
}

.mc-link{
    display:inline-flex;
    align-items:center;
    gap:6px;
    color:#fff;
    font-weight:700;
    margin-top:18px;
}

@media(max-width:768px){

    .topbar{
        padding:0 16px;
    }

    .container{
        padding:20px 14px;
    }

    .hero-row{
        flex-direction:column;
    }

    .hero-text h2{
        font-size:24px;
    }

    .tb-greeting{
        display:none;
    }
}
</style>
</head>
<body>

<header class="topbar">
    <a href="index.php" class="brand">
        <div class="brand-icon">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                <path d="M12 6v6l4 2"/>
            </svg>
        </div>

        <div class="brand-name">
            <span>SIM</span>
            <span>Gizi</span>
        </div>
    </a>

    <div class="tb-right">
        <div class="tb-greeting">
            Halo,
            <strong><?= $user ?></strong>
        </div>

        <div class="tb-avatar">
            <?= $initials ?>
        </div>

        <a href="auth/logout.php" class="tb-logout">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Keluar
        </a>
    </div>
</header>

<div class="container">

    <div class="hero-row">
        <div class="hero-text">
            <h2>Selamat Datang<br>Kembali!</h2>
            <p>Role: <?= ucfirst(strtolower($role)) ?></p>
        </div>

        <div class="hero-date">
            <div class="hd-day"><?= $hari ?></div>
            <div class="hd-month"><?= $bln ?> <?= $thn ?></div>
        </div>
    </div>

    <div class="section-lbl">Menu Utama</div>

    <div class="menu-hero">
        <a href="sekolah/index.php" class="main-card">

            <div class="mc-top">
                <div class="mc-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff"
                    stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>

                <span class="mc-badge">AKTIF</span>
            </div>

            <div class="mc-bottom">
                <div class="mc-title">Data Sekolah</div>
                <div class="mc-sub">
                    Kelola seluruh data sekolah terdaftar
                </div>

                <div class="mc-link">
                    Buka Modul →
                </div>
            </div>

        </a>
    </div>

</div>

</body>
</html>