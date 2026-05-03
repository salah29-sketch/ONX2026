<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>دخول العمال — ONX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', sans-serif; min-height: 100vh; background: #0a0a0a; color: #fff; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .box { width: 100%; max-width: 400px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 20px; padding: 2rem; }
        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; }
        .sub { font-size: 0.875rem; color: rgba(255,255,255,.5); margin-bottom: 1.5rem; }
        .alert-danger { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); color: #fca5a5; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.875rem; margin-bottom: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,.7); margin-bottom: 0.4rem; }
        .form-group input { width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,.15); background: rgba(255,255,255,.06); color: #fff; font-size: 1rem; }
        .form-group input:focus { outline: none; border-color: #f97316; }
        .btn { width: 100%; padding: 0.875rem; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; border: none; background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; margin-top: 0.5rem; }
        .btn:hover { opacity: 0.95; }
        .back { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: rgba(255,255,255,.4); text-decoration: none; margin-bottom: 1rem; }
        .back:hover { color: rgba(255,255,255,.8); }
    </style>
</head>
<body>
    <div class="box">
        <a href="{{ url('/') }}" class="back">← الرئيسية</a>
        <h1>دخول العمال</h1>
        <p class="sub">أدخل بريدك وكلمة المرور</p>

        @if($errors->any())
            <div class="alert-danger">
                @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('worker.login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="remember"> تذكرني</label>
            </div>
            <button type="submit" class="btn">دخول</button>
        </form>
    </div>
</body>
</html>
