<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chùa Pháp Vân - Trang Chào Mừng</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f6f1e9;
            --card: #fffdf8;
            --text: #2b2b2b;
            --muted: #666;
            --primary: #8b5e34;
            --primary-dark: #6c4524;
            --border: #eadfce;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: radial-gradient(circle at top, #fffaf2, var(--bg));
            color: var(--text);
            line-height: 1.7;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero {
            padding: 56px 0 36px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #fff8ef;
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 14px;
        }

        h1 {
            margin-top: 16px;
            font-size: clamp(28px, 4vw, 52px);
            line-height: 1.2;
            color: var(--primary-dark);
        }

        .subtitle {
            max-width: 760px;
            margin: 18px auto 0;
            color: var(--muted);
            font-size: clamp(16px, 2.2vw, 20px);
        }

        .section {
            margin: 26px 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(106, 73, 41, 0.06);
        }

        .card h2 {
            color: var(--primary-dark);
            margin-bottom: 10px;
            font-size: 22px;
        }

        .card p,
        .card li {
            color: #444;
        }

        .contact {
            grid-column: span 5;
        }

        .about {
            grid-column: span 7;
        }

        .info {
            margin-top: 10px;
            display: grid;
            gap: 10px;
        }

        .info-row {
            padding: 10px 12px;
            background: #fffaf2;
            border-radius: 10px;
            border: 1px solid #f3e8d8;
        }

        .label {
            font-weight: 700;
            color: var(--primary-dark);
            display: block;
            margin-bottom: 4px;
        }

        .activities {
            grid-column: span 12;
        }

        .activities ul {
            margin-left: 20px;
            display: grid;
            gap: 8px;
        }

        .actions {
            margin-top: 18px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 10px 16px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid transparent;
            transition: .2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            border-color: var(--primary);
            color: var(--primary-dark);
            background: #fff;
        }

        .btn-outline:hover {
            background: #fff6ea;
        }

        footer {
            margin-top: 30px;
            padding: 22px 0 40px;
            text-align: center;
            color: #7b6b5a;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .contact,
            .about,
            .activities {
                grid-column: span 12;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <section class="hero">
            <span class="badge">Nam Mô Bổn Sư Thích Ca Mâu Ni Phật</span>
            <h1>Chào mừng đến với Chùa Pháp Vân</h1>
            <p class="subtitle">
                Không gian thanh tịnh để mọi người tìm về sự an yên, nuôi dưỡng lòng từ bi và thực hành đời sống hướng thiện.
            </p>
        </section>

        <section class="section grid">
            <article class="card contact">
                <h2>Thông tin liên hệ</h2>
                <div class="info">
                    <div class="info-row">
                        <span class="label">Địa chỉ</span>
                        <span>85/5 đường 97 ấp Giồng Sao, xã Tân Phú Trung, huyện Củ Chi, TP HCM, Việt Nam</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Bản đồ</span>
                        <a href="https://www.google.com/maps?q=85/5+duong+97+ap+Giong+Sao,+xa+Tan+Phu+Trung,+huyen+Cu+Chi,+TP+HCM,+Viet+Nam" target="_blank" rel="noopener noreferrer">
                            Mở Google Maps
                        </a>
                    </div>
                    <div class="info-row">
                        <span class="label">Điện thoại</span>
                        <a href="tel:0908373375">0908 373 375</a>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập hệ thống</a>
                    <a href="https://www.google.com/maps?q=85/5+duong+97+ap+Giong+Sao,+xa+Tan+Phu+Trung,+huyen+Cu+Chi,+TP+HCM,+Viet+Nam" class="btn btn-outline" target="_blank" rel="noopener noreferrer">Chỉ đường</a>
                </div>
            </article>

            <article class="card about">
                <h2>Về Chùa Pháp Vân</h2>
                <p>
                    Chùa Pháp Vân là nơi sinh hoạt tâm linh gần gũi cho Phật tử và bà con địa phương. Với tinh thần từ bi - trí tuệ,
                    chùa hướng đến việc lan tỏa những giá trị tốt đẹp trong đời sống thường nhật thông qua tu học, lắng nghe và phụng sự.
                </p>
                <p style="margin-top: 10px;">
                    Tại đây, mọi người có thể tham dự thời khóa tụng niệm, nghe pháp thoại, cùng nhau làm công quả và tham gia các hoạt động
                    thiện nguyện vì cộng đồng. Chùa luôn trân trọng sự hiện diện của quý thiện hữu gần xa trong tinh thần hòa hợp, chân thành.
                </p>
                <p style="margin-top: 10px;">
                    Chùa hiện do Hòa Thượng Thích Minh Thanh trụ trì, đồng thời tham gia công tác hoằng pháp và giảng dạy học thuật Phật học,
                    góp phần định hướng tu học cho Tăng Ni, Phật tử và cộng đồng địa phương.
                </p>
                <p style="margin-top: 10px; font-size: 14px; color: #6d6d6d;">
                    Nguồn tham khảo:
                    <a href="https://www.vbu.edu.vn/htts-thich-minh-thanh.html" target="_blank" rel="noopener noreferrer">Học viện Phật giáo Việt Nam tại TP.HCM</a>
                </p>
            </article>

            <article class="card activities">
                <h2>Thông tin chung</h2>
                <ul>
                    <li>Không gian chùa yên tĩnh, phù hợp cho việc lễ Phật, thiền hành và tịnh tâm cuối tuần.</li>
                    <li>Tổ chức các buổi chia sẻ giáo lý căn bản giúp người mới dễ tiếp cận Phật pháp.</li>
                    <li>Duy trì các chương trình hướng thiện, kết nối yêu thương và hỗ trợ người có hoàn cảnh khó khăn.</li>
                    <li>Khuyến khích nếp sống tỉnh thức, hiếu kính cha mẹ và xây dựng gia đình an lạc.</li>
                </ul>
            </article>
        </section>
    </main>

    <footer>
        © {{ date('Y') }} Chùa Pháp Vân - Kính chúc quý Phật tử và quý khách thân tâm thường an lạc.
    </footer>
</body>

</html>
