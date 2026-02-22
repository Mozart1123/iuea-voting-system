<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Vérification OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #660000;
        }
        * { font-family: 'Inter', sans-serif; }
        .gradient-left { background: linear-gradient(145deg, #660000 0%, #8B0000 100%); }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased">
    <!-- LEFT PANEL (Shared style) -->
    <div class="md:w-1/2 gradient-left text-white flex flex-col justify-between p-8 md:p-12 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-12">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-20 h-20 object-contain brightness-0 invert">
                <span class="font-bold text-2xl tracking-tight">IUEA <span class="font-extrabold">GuildVote</span></span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-6">Sécurisez votre compte</h1>
            <p class="text-white/80 text-lg">Un code de vérification a été envoyé à votre compte Gmail.</p>
        </div>
        <div class="relative z-10 text-white/60 text-sm mt-12">© 2025 IUEA – Secure Voting</div>
    </div>

    <!-- RIGHT PANEL (OTP Form) -->
    <div class="md:w-1/2 bg-white flex items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-md text-center">
            <div class="mb-8">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Vérification OTP</h2>
                <p class="text-gray-500">Entrez le code à 6 chiffres reçu par email.</p>
            </div>

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-xl text-sm italic">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('otp.verify') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="text" name="otp" maxlength="6" placeholder="000000" 
                           class="w-full text-center text-4xl tracking-widest font-bold py-4 border-2 border-gray-100 rounded-2xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('otp') border-red-500 @enderror" required>
                    @error('otp')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-red-50 text-red-800 border-2 border-primary font-bold py-3 px-4 rounded-xl shadow-md hover:bg-red-100 transition duration-200">
                    Vérifier le code
                </button>
            </form>

            <form action="{{ route('otp.resend') }}" method="POST" class="mt-8">
                @csrf
                <p class="text-gray-600 text-sm">
                    Vous n'avez pas reçu le code ? 
                    <button type="submit" class="font-semibold text-primary hover:underline transition">Renvoyer par email</button>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
