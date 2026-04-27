<!DOCTYPE html>
<html>
<head><title>Richiesta Revisore</title></head>
<body>
    <h2>Nuova richiesta di diventare revisore</h2>
    <p><strong>Nome:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Registrato il:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
    <p>
        <a href="{{ route('make.revisor', $user->email) }}"
           style="background:#dc3545;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
            Rendi Revisore
        </a>
    </p>
</body>
</html>
