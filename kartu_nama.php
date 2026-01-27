<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>15 Ide Desain Kartu Nama Bisnis</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600;700&family=Roboto:wght@300;400;500&family=Poppins:wght@300;400;600&family=Raleway:wght@300;400;600&family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 1.1rem;
            margin-bottom: 50px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 40px;
            padding: 20px;
        }

        .card-wrapper {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-wrapper:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            color: #e2e8f0;
            font-size: 1.2rem;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .card-desc {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .business-card {
            width: 350px;
            height: 200px;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
        }

        /* Design 1: Minimalist Elegant */
        .design-1 {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px;
        }
        .design-1 .name {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: #1a1a1a;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .design-1 .title {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 5px;
        }
        .design-1 .contact {
            margin-top: 25px;
            font-size: 0.7rem;
            color: #888;
            line-height: 1.8;
        }
        .design-1 .accent {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #c9a227, #d4af37);
        }

        /* Design 2: Bold Corporate */
        .design-2 {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: flex;
        }
        .design-2 .left {
            width: 35%;
            background: linear-gradient(180deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .design-2 .logo-circle {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Oswald', sans-serif;
            font-size: 1.8rem;
            color: #fff;
            font-weight: 600;
        }
        .design-2 .right {
            flex: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .design-2 .name {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.3rem;
            color: #fff;
            font-weight: 700;
        }
        .design-2 .title {
            font-size: 0.7rem;
            color: #60a5fa;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 3px;
        }
        .design-2 .contact {
            margin-top: 20px;
            font-size: 0.65rem;
            color: #94a3b8;
            line-height: 1.9;
        }

        /* Design 3: Creative Gradient */
        .design-3 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .design-3 .top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .design-3 .name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #fff;
            font-weight: 600;
        }
        .design-3 .title {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            margin-top: 3px;
        }
        .design-3 .logo {
            font-family: 'Oswald', sans-serif;
            font-size: 1.5rem;
            color: #fff;
            font-weight: 600;
        }
        .design-3 .contact {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.9);
            line-height: 1.8;
        }

        /* Design 4: Nature Inspired */
        .design-4 {
            background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);
            padding: 25px;
            position: relative;
        }
        .design-4::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 0 0 0 100%;
        }
        .design-4 .name {
            font-family: 'Raleway', sans-serif;
            font-size: 1.4rem;
            color: #fff;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }
        .design-4 .title {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            margin-top: 5px;
            letter-spacing: 2px;
        }
        .design-4 .contact {
            position: absolute;
            bottom: 25px;
            font-size: 0.65rem;
            color: rgba(255,255,255,0.9);
            line-height: 1.8;
        }

        /* Design 5: Luxury Gold */
        .design-5 {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 30px;
            border: 2px solid #c9a227;
        }
        .design-5 .name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #c9a227;
            font-weight: 700;
        }
        .design-5 .title {
            font-size: 0.7rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 5px;
        }
        .design-5 .divider {
            width: 50px;
            height: 1px;
            background: #c9a227;
            margin: 20px 0;
        }
        .design-5 .contact {
            font-size: 0.65rem;
            color: #aaa;
            line-height: 1.9;
        }

        /* Design 6: Tech Modern */
        .design-6 {
            background: #0a0a0a;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        .design-6::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #00d4ff, #090979);
            border-radius: 50%;
            opacity: 0.3;
        }
        .design-6 .name {
            font-family: 'Roboto', sans-serif;
            font-size: 1.4rem;
            color: #fff;
            font-weight: 500;
            position: relative;
        }
        .design-6 .title {
            font-size: 0.7rem;
            color: #00d4ff;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        .design-6 .contact {
            position: absolute;
            bottom: 25px;
            font-size: 0.65rem;
            color: #666;
            line-height: 1.8;
        }

        /* Design 7: Geometric Pattern */
        .design-7 {
            background: #fff;
            display: flex;
        }
        .design-7 .pattern {
            width: 30%;
            background: repeating-linear-gradient(
                45deg,
                #e74c3c,
                #e74c3c 10px,
                #c0392b 10px,
                #c0392b 20px
            );
        }
        .design-7 .content {
            flex: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .design-7 .name {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.3rem;
            color: #2c3e50;
            font-weight: 700;
        }
        .design-7 .title {
            font-size: 0.7rem;
            color: #e74c3c;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 3px;
        }
        .design-7 .contact {
            margin-top: 18px;
            font-size: 0.65rem;
            color: #7f8c8d;
            line-height: 1.8;
        }

        /* Design 8: Pastel Soft */
        .design-8 {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            padding: 30px;
        }
        .design-8 .name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #5d4e37;
            font-weight: 600;
        }
        .design-8 .title {
            font-size: 0.7rem;
            color: #8b7355;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        .design-8 .contact {
            margin-top: 25px;
            font-size: 0.65rem;
            color: #6d5d4b;
            line-height: 1.9;
        }
        .design-8 .dots {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 5px;
        }
        .design-8 .dot {
            width: 8px;
            height: 8px;
            background: rgba(93, 78, 55, 0.3);
            border-radius: 50%;
        }

        /* Design 9: Split Design */
        .design-9 {
            display: flex;
        }
        .design-9 .left {
            width: 50%;
            background: #2c3e50;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .design-9 .right {
            width: 50%;
            background: #ecf0f1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .design-9 .name {
            font-family: 'Raleway', sans-serif;
            font-size: 1.3rem;
            color: #fff;
            font-weight: 600;
        }
        .design-9 .title {
            font-size: 0.65rem;
            color: #bdc3c7;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        .design-9 .contact {
            font-size: 0.6rem;
            color: #7f8c8d;
            line-height: 2;
        }

        /* Design 10: Artistic Brush */
        .design-10 {
            background: #fff;
            padding: 25px;
            position: relative;
        }
        .design-10::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent 60%, #ff6b6b 60%, #ff6b6b 65%, transparent 65%);
        }
        .design-10 .name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #2d3436;
            font-weight: 700;
            position: relative;
        }
        .design-10 .title {
            font-size: 0.7rem;
            color: #636e72;
            margin-top: 5px;
            position: relative;
        }
        .design-10 .contact {
            margin-top: 25px;
            font-size: 0.65rem;
            color: #636e72;
            line-height: 1.8;
            position: relative;
        }

        /* Design 11: Neon Glow */
        .design-11 {
            background: #0d0d0d;
            padding: 30px;
            position: relative;
        }
        .design-11 .name {
            font-family: 'Oswald', sans-serif;
            font-size: 1.6rem;
            color: #fff;
            font-weight: 600;
            text-shadow: 0 0 10px #ff00ff, 0 0 20px #ff00ff;
        }
        .design-11 .title {
            font-size: 0.7rem;
            color: #00ffff;
            margin-top: 5px;
            letter-spacing: 3px;
            text-shadow: 0 0 5px #00ffff;
        }
        .design-11 .contact {
            margin-top: 25px;
            font-size: 0.65rem;
            color: #666;
            line-height: 1.9;
        }
        .design-11 .border-glow {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid rgba(255, 0, 255, 0.3);
            border-radius: 8px;
        }

        /* Design 12: Vintage Classic */
        .design-12 {
            background: #f5f0e8;
            padding: 25px;
            border: 3px double #8b7355;
        }
        .design-12 .ornament {
            text-align: center;
            color: #8b7355;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .design-12 .name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: #3d3d3d;
            font-weight: 700;
            text-align: center;
        }
        .design-12 .title {
            font-size: 0.65rem;
            color: #8b7355;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 5px;
        }
        .design-12 .contact {
            margin-top: 20px;
            font-size: 0.6rem;
            color: #666;
            line-height: 1.8;
            text-align: center;
        }

        /* Design 13: Ocean Wave */
        .design-13 {
            background: linear-gradient(180deg, #0077b6 0%, #00b4d8 50%, #90e0ef 100%);
            padding: 25px;
            position: relative;
        }
        .design-13::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.3' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom;
            background-size: cover;
        }
        .design-13 .name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            color: #fff;
            font-weight: 600;
        }
        .design-13 .title {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            margin-top: 5px;
        }
        .design-13 .contact {
            margin-top: 20px;
            font-size: 0.65rem;
            color: rgba(255,255,255,0.9);
            line-height: 1.8;
        }

        /* Design 14: Monochrome Pro */
        .design-14 {
            background: #fff;
            padding: 30px;
            position: relative;
        }
        .design-14 .corner {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #1a1a1a;
        }
        .design-14 .corner-tl {
            top: 15px;
            left: 15px;
            border-right: none;
            border-bottom: none;
        }
        .design-14 .corner-br {
            bottom: 15px;
            right: 15px;
            border-left: none;
            border-top: none;
        }
        .design-14 .name {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.4rem;
            color: #1a1a1a;
            font-weight: 700;
            letter-spacing: 2px;
        }
        .design-14 .title {
            font-size: 0.65rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 5px;
        }
        .design-14 .contact {
            margin-top: 25px;
            font-size: 0.65rem;
            color: #888;
            line-height: 1.9;
        }

        /* Design 15: Sunset Vibes */
        .design-15 {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            padding: 25px;
            position: relative;
        }
        .design-15::before {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            border-radius: 100% 0 0 0;
            opacity: 0.5;
        }
        .design-15 .name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #5d4e6d;
            font-weight: 600;
            position: relative;
        }
        .design-15 .title {
            font-size: 0.7rem;
            color: #8b7a9e;
            margin-top: 5px;
            position: relative;
        }
        .design-15 .contact {
            margin-top: 25px;
            font-size: 0.65rem;
            color: #6d5d7e;
            line-height: 1.9;
            position: relative;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .business-card {
                width: 100%;
                max-width: 350px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>15 Ide Desain Kartu Nama Bisnis</h1>
        <p class="subtitle">Koleksi desain kartu nama profesional untuk inspirasi bisnis Anda</p>

        <div class="cards-grid">
            <?php
            $designs = [
                [
                    'title' => '1. Minimalist Elegant',
                    'desc' => 'Desain bersih dengan sentuhan emas yang elegan. Cocok untuk konsultan, pengacara, atau profesional.',
                    'class' => 'design-1',
                    'content' => '
                        <div class="name">Alexandra Chen</div>
                        <div class="title">Business Consultant</div>
                        <div class="contact">
                            +62 812 3456 7890<br>
                            alexandra@company.com<br>
                            www.alexandrachen.com
                        </div>
                        <div class="accent"></div>
                    '
                ],
                [
                    'title' => '2. Bold Corporate',
                    'desc' => 'Tampilan korporat yang kuat dengan aksen biru. Ideal untuk perusahaan teknologi atau startup.',
                    'class' => 'design-2',
                    'content' => '
                        <div class="left">
                            <div class="logo-circle">TC</div>
                        </div>
                        <div class="right">
                            <div class="name">Michael Torres</div>
                            <div class="title">Chief Technology Officer</div>
                            <div class="contact">
                                +62 813 9876 5432<br>
                                michael@techcorp.io<br>
                                www.techcorp.io
                            </div>
                        </div>
                    '
                ],
                [
                    'title' => '3. Creative Gradient',
                    'desc' => 'Gradien warna-warni yang eye-catching. Sempurna untuk agensi kreatif atau desainer.',
                    'class' => 'design-3',
                    'content' => '
                        <div class="top">
                            <div>
                                <div class="name">Sarah Williams</div>
                                <div class="title">Creative Director</div>
                            </div>
                            <div class="logo">CW</div>
                        </div>
                        <div class="contact">
                            +62 821 5678 1234 | sarah@creativeworks.co<br>
                            www.creativeworks.co
                        </div>
                    '
                ],
                [
                    'title' => '4. Nature Inspired',
                    'desc' => 'Nuansa hijau alami yang menenangkan. Cocok untuk bisnis eco-friendly atau wellness.',
                    'class' => 'design-4',
                    'content' => '
                        <div class="name">David Green</div>
                        <div class="title">Sustainability Consultant</div>
                        <div class="contact">
                            +62 856 7890 1234<br>
                            david@ecolife.id<br>
                            www.ecolife.id
                        </div>
                    '
                ],
                [
                    'title' => '5. Luxury Gold',
                    'desc' => 'Hitam dan emas yang mewah. Ideal untuk brand premium, perhiasan, atau real estate.',
                    'class' => 'design-5',
                    'content' => '
                        <div class="name">Victoria Laurent</div>
                        <div class="title">Luxury Real Estate Agent</div>
                        <div class="divider"></div>
                        <div class="contact">
                            +62 811 2233 4455<br>
                            victoria@luxuryestates.com<br>
                            www.luxuryestates.com
                        </div>
                    '
                ],
                [
                    'title' => '6. Tech Modern',
                    'desc' => 'Desain futuristik dengan aksen cyan. Sempurna untuk developer atau perusahaan IT.',
                    'class' => 'design-6',
                    'content' => '
                        <div class="name">Ryan Nakamura</div>
                        <div class="title">Full Stack Developer</div>
                        <div class="contact">
                            +62 878 9012 3456<br>
                            ryan@devstudio.tech<br>
                            github.com/ryannakamura
                        </div>
                    '
                ],
                [
                    'title' => '7. Geometric Pattern',
                    'desc' => 'Pola geometris yang dinamis. Cocok untuk arsitek, interior designer, atau konstruksi.',
                    'class' => 'design-7',
                    'content' => '
                        <div class="pattern"></div>
                        <div class="content">
                            <div class="name">Emma Rodriguez</div>
                            <div class="title">Interior Architect</div>
                            <div class="contact">
                                +62 812 4567 8901<br>
                                emma@designstudio.co.id<br>
                                www.designstudio.co.id
                            </div>
                        </div>
                    '
                ],
                [
                    'title' => '8. Pastel Soft',
                    'desc' => 'Warna pastel yang lembut dan hangat. Ideal untuk bakery, salon, atau wedding planner.',
                    'class' => 'design-8',
                    'content' => '
                        <div class="name">Olivia Baker</div>
                        <div class="title">Pastry Chef & Owner</div>
                        <div class="contact">
                            +62 857 1234 5678<br>
                            olivia@sweetdelights.id<br>
                            @sweetdelights.id
                        </div>
                        <div class="dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    '
                ],
                [
                    'title' => '9. Split Design',
                    'desc' => 'Pembagian dua warna yang kontras. Cocok untuk berbagai jenis bisnis profesional.',
                    'class' => 'design-9',
                    'content' => '
                        <div class="left">
                            <div class="name">James Wilson</div>
                            <div class="title">Marketing Director</div>
                        </div>
                        <div class="right">
                            <div class="contact">
                                +62 813 5678 9012<br>
                                james@brandagency.com<br>
                                www.brandagency.com<br>
                                Jakarta, Indonesia
                            </div>
                        </div>
                    '
                ],
                [
                    'title' => '10. Artistic Brush',
                    'desc' => 'Sentuhan artistik dengan diagonal merah. Sempurna untuk seniman atau fotografer.',
                    'class' => 'design-10',
                    'content' => '
                        <div class="name">Isabella Martinez</div>
                        <div class="title">Professional Photographer</div>
                        <div class="contact">
                            +62 821 9876 5432<br>
                            isabella@artlens.co<br>
                            www.artlens.co
                        </div>
                    '
                ],
                [
                    'title' => '11. Neon Glow',
                    'desc' => 'Efek neon yang mencolok. Ideal untuk DJ, event organizer, atau nightclub.',
                    'class' => 'design-11',
                    'content' => '
                        <div class="border-glow"></div>
                        <div class="name">DJ NEXUS</div>
                        <div class="title">Music Producer & DJ</div>
                        <div class="contact">
                            +62 878 5432 1098<br>
                            booking@djnexus.id<br>
                            @djnexus.official
                        </div>
                    '
                ],
                [
                    'title' => '12. Vintage Classic',
                    'desc' => 'Gaya vintage yang timeless. Cocok untuk antique shop, kafe klasik, atau notaris.',
                    'class' => 'design-12',
                    'content' => '
                        <div class="ornament">❧</div>
                        <div class="name">William Hartford</div>
                        <div class="title">Antique Dealer</div>
                        <div class="contact">
                            +62 811 7654 3210<br>
                            william@heritagecollection.id<br>
                            Est. 1985
                        </div>
                    '
                ],
                [
                    'title' => '13. Ocean Wave',
                    'desc' => 'Gradien biru laut yang segar. Ideal untuk travel agency, resort, atau marine business.',
                    'class' => 'design-13',
                    'content' => '
                        <div class="name">Marina Santoso</div>
                        <div class="title">Travel Consultant</div>
                        <div class="contact">
                            +62 856 2345 6789<br>
                            marina@oceantravel.co.id<br>
                            www.oceantravel.co.id
                        </div>
                    '
                ],
                [
                    'title' => '14. Monochrome Pro',
                    'desc' => 'Hitam putih yang sophisticated. Sempurna untuk lawyer, akuntan, atau konsultan.',
                    'class' => 'design-14',
                    'content' => '
                        <div class="corner corner-tl"></div>
                        <div class="corner corner-br"></div>
                        <div class="name">ROBERT CHANG</div>
                        <div class="title">Attorney at Law</div>
                        <div class="contact">
                            +62 812 8765 4321<br>
                            robert@changlawfirm.com<br>
                            www.changlawfirm.com
                        </div>
                    '
                ],
                [
                    'title' => '15. Sunset Vibes',
                    'desc' => 'Gradien pink yang feminin dan modern. Cocok untuk beauty salon, fashion, atau florist.',
                    'class' => 'design-15',
                    'content' => '
                        <div class="name">Sophie Anderson</div>
                        <div class="title">Beauty & Wellness Expert</div>
                        <div class="contact">
                            +62 857 6543 2109<br>
                            sophie@bloombeauty.id<br>
                            @bloombeauty.id
                        </div>
                    '
                ]
            ];

            foreach ($designs as $design) {
                echo '<div class="card-wrapper">';
                echo '<h3 class="card-title">' . htmlspecialchars($design['title']) . '</h3>';
                echo '<p class="card-desc">' . htmlspecialchars($design['desc']) . '</p>';
                echo '<div class="business-card ' . htmlspecialchars($design['class']) . '">';
                echo $design['content'];
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <footer style="text-align: center; padding: 40px 20px; color: #64748b; font-size: 0.85rem;">
        <p>&copy; <?php echo date('Y'); ?> - Koleksi Desain Kartu Nama Bisnis</p>
        <p style="margin-top: 10px; color: #475569;">Semua desain dapat dikustomisasi sesuai kebutuhan bisnis Anda</p>
    </footer>
</body>
</html>
