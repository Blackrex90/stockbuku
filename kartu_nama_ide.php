<?php
// Array berisi 15 ide desain kartu nama untuk bisnis
$ide_desain = [
    [
        'judul' => 'Minimalis Elegan',
        'deskripsi' => 'Desain kartu nama dengan gaya minimalis yang elegan, menggunakan warna netral dan tipografi sederhana.',
        'fitur' => ['Warna hitam putih', 'Font sans-serif modern', 'Layout simetris', 'Logo kecil di kanan atas']
    ],
    [
        'judul' => 'Kreatif dengan Ilustrasi',
        'deskripsi' => 'Kartu nama yang menarik dengan ilustrasi unik yang mewakili bidang bisnis Anda.',
        'fitur' => ['Ilustrasi custom', 'Palet warna cerah', 'Layout asimetris', 'QR code terintegrasi']
    ],
    [
        'judul' => 'Klasik Profesional',
        'deskripsi' => 'Desain klasik dengan sentuhan modern untuk bisnis formal seperti konsultan atau pengacara.',
        'fitur' => ['Warna navy dan emas', 'Font serif tradisional', 'Border halus', 'Informasi lengkap']
    ],
    [
        'judul' => 'Modern dengan Gradien',
        'deskripsi' => 'Kartu nama dengan efek gradien yang memberikan kesan modern dan dinamis.',
        'fitur' => ['Gradien warna', 'Font geometris', 'Elemen abstrak', 'Layout vertikal']
    ],
    [
        'judul' => 'Vintage Retro',
        'deskripsi' => 'Desain kartu nama dengan gaya vintage yang cocok untuk bisnis kreatif atau kafe.',
        'fitur' => ['Warna pastel', 'Texture kertas tua', 'Font retro', 'Ilustrasi vintage']
    ],
    [
        'judul' => 'Teknologi Futuristik',
        'deskripsi' => 'Kartu nama dengan elemen teknologi seperti hologram atau pola geometris.',
        'fitur' => ['Pola geometris', 'Warna neon', 'Font futuristik', 'Efek metallic']
    ],
    [
        'judul' => 'Alam dan Hijau',
        'deskripsi' => 'Desain yang menggabungkan elemen alam untuk bisnis ramah lingkungan.',
        'fitur' => ['Motif daun', 'Warna hijau alami', 'Font organik', 'Kertas daur ulang']
    ],
    [
        'judul' => 'Luxury Premium',
        'deskripsi' => 'Kartu nama mewah dengan material premium dan desain eksklusif.',
        'fitur' => ['Embossing', 'Warna emas', 'Font premium', 'Material karton tebal']
    ],
    [
        'judul' => 'Pop Art Berwarna',
        'deskripsi' => 'Desain kartu nama dengan gaya pop art yang mencolok dan energik.',
        'fitur' => ['Warna-warni cerah', 'Pola pop art', 'Font bold', 'Bentuk unik']
    ],
    [
        'judul' => 'Minimalis dengan Akcent',
        'deskripsi' => 'Desain minimalis dengan satu elemen aksen yang menonjol.',
        'fitur' => ['Background putih', 'Aksen warna tunggal', 'Font clean', 'Logo besar']
    ],
    [
        'judul' => 'Bisnis Korporat',
        'deskripsi' => 'Kartu nama standar untuk perusahaan besar dengan branding yang kuat.',
        'fitur' => ['Logo perusahaan', 'Warna korporat', 'Informasi jabatan', 'Barcode']
    ],
    [
        'judul' => 'Kreatif dengan Fotografi',
        'deskripsi' => 'Menggunakan foto produk atau layanan sebagai elemen utama desain.',
        'fitur' => ['Foto background', 'Text overlay', 'Filter artistic', 'Layout kreatif']
    ],
    [
        'judul' => 'Geometric Pattern',
        'deskripsi' => 'Desain dengan pola geometris yang memberikan kesan modern dan terstruktur.',
        'fitur' => ['Pola geometris', 'Warna kontras', 'Font angular', 'Layout grid']
    ],
    [
        'judul' => 'Watercolor Effect',
        'deskripsi' => 'Kartu nama dengan efek cat air yang memberikan kesan artistik dan kreatif.',
        'fitur' => ['Efek watercolor', 'Warna lembut', 'Font handwritten', 'Texture unik']
    ],
    [
        'judul' => 'Digital Tech',
        'deskripsi' => 'Desain untuk bisnis teknologi dengan elemen digital dan coding.',
        'fitur' => ['Pola binary', 'Warna tech', 'Font monospace', 'Icon teknologi']
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>15 Ide Desain Kartu Nama untuk Bisnis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 2.5em;
        }
        .header p {
            color: #666;
            font-size: 1.2em;
            margin-top: 10px;
        }
        .ideas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }
        .idea-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .idea-card:hover {
            transform: translateY(-5px);
        }
        .idea-title {
            color: #2c3e50;
            font-size: 1.5em;
            margin-bottom: 10px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        .idea-description {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .idea-features {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .idea-features h4 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .feature-list li {
            color: #666;
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
        }
        .feature-list li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .footer p {
            color: #666;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>15 Ide Desain Kartu Nama untuk Bisnis</h1>
        <p>Inspirasi desain kartu nama yang dapat disesuaikan dengan jenis bisnis Anda</p>
    </div>

    <div class="ideas-grid">
        <?php foreach ($ide_desain as $index => $ide): ?>
        <div class="idea-card">
            <h2 class="idea-title"><?php echo ($index + 1) . '. ' . htmlspecialchars($ide['judul']); ?></h2>
            <p class="idea-description"><?php echo htmlspecialchars($ide['deskripsi']); ?></p>
            <div class="idea-features">
                <h4>Fitur Utama:</h4>
                <ul class="feature-list">
                    <?php foreach ($ide['fitur'] as $fitur): ?>
                    <li><?php echo htmlspecialchars($fitur); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        <p>Ide desain ini dapat dikustomisasi sesuai dengan branding dan kebutuhan bisnis Anda.</p>
    </div>
</body>
</html>