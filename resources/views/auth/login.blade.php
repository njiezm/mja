<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Administration MJA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mja: {
                            blue:     '#3DAEF5',
                            bluedark: '#1E93D6',
                            yellow:   '#F5A623',
                            red:      '#D0021B',
                            dark:     '#2048A4',
                            navy:     '#1A3D8A',
                        }
                    },
                    fontFamily: {
                        display: ['Montserrat', 'sans-serif'],
                        sans:    ['Open Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Open Sans', sans-serif; }
        .font-display { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #1A3D8A 0%, #2048A4 50%, #3262CC 100%);">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <div class="w-20 h-20 bg-white rounded-2xl shadow-xl flex items-center justify-center p-2">
                    <img src="/images/logo.jpg" alt="MJA" class="w-full h-full object-contain">
                </div>
            </div>
            <div class="font-display font-black text-3xl leading-none mb-1">
                <span style="color:#3DAEF5">M</span><span style="color:#F5A623">J</span><span style="color:#D0021B">A</span>
            </div>
            <div class="text-white/50 text-xs font-display font-semibold uppercase tracking-widest mt-1">Madin' Jeunes Ambition</div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Barre tricolore -->
            <div class="flex h-1.5">
                <div class="flex-1" style="background:#3DAEF5"></div>
                <div class="flex-1" style="background:#F5A623"></div>
                <div class="flex-1" style="background:#D0021B"></div>
            </div>
            <div class="p-8">
                <h1 class="font-display font-black text-xl text-gray-900 mb-1">Administration</h1>
                <p class="text-gray-500 text-sm mb-7">Connectez-vous pour gérer le contenu du site.</p>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm flex items-center gap-2 font-display font-semibold">
                    <i class="fas fa-exclamation-circle text-red-500"></i> {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-display font-bold text-gray-700 mb-1.5">
                            <i class="fas fa-envelope text-mja-blue mr-1"></i> Adresse email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors"
                               placeholder="admin@mja-martinique.com"
                               style="--tw-ring-color: #3DAEF5;">
                    </div>
                    <div>
                        <label class="block text-sm font-display font-bold text-gray-700 mb-1.5">
                            <i class="fas fa-lock text-mja-blue mr-1"></i> Mot de passe
                        </label>
                        <input type="password" name="password" required
                               class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors"
                               placeholder="••••••••">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember"
                               class="rounded border-gray-300 text-mja-blue">
                        <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
                    </div>
                    <button type="submit"
                            class="w-full text-white font-display font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm"
                            style="background:#3DAEF5;"
                            onmouseover="this.style.background='#1E93D6'" onmouseout="this.style.background='#3DAEF5'">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-white/50 hover:text-white text-sm transition-colors font-display font-semibold">
                <i class="fas fa-arrow-left mr-1"></i> Retour au site
            </a>
        </div>
    </div>
</body>
</html>
