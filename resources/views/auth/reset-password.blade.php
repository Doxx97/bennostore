<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru - Benno Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         /* Copy style body, overlay, card-box, btn-custom dari file di atas */
         body { background: #234C6A; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
         .card-box { background: white; padding: 40px; border-radius: 20px; width: 90%; max-width: 450px; }
         .btn-custom { background: #234C6A; color: white; width: 100%; padding: 10px; border-radius: 10px; border:none;}
    </style>
</head>
<body>

<div class="card-box">
    <h3 class="fw-bold text-center mb-4" style="color: #234C6A;">Buat Password Baru</h3>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus>
            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-custom">Ubah Password</button>
    </form>
</div>

</body>
</html>