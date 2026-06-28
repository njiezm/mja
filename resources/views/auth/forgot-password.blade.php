<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — Administration MJA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { mja: { blue:'#3DAEF5', bluedark:'#1E93D6', yellow:'#F5A623', red:'#D0021B', dark:'#2048A4', navy:'#1A3D8A' } },
                    fontFamily: { display:['Montserrat','sans-serif'], sans:['Open Sans','sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #1A3D8A 0%, #2048A4 50%, #3262CC 100%);">
    <div class="w-full max-w-md">
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

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex h-1.5">
                <div class="flex-1" style="background:#3DAEF5"></div>
                <div class="flex-1" style="background:#F5A623"></div>
                <div class="flex-1" style="background:#D0021B"></div>
            </div>
            <div class="p-8">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 bg-mja-blue/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-lock-open text-mja-blue"></i>
                    </div>
                    <h1 class="font-display font-black text-xl text-gray-900">Mot de passe oublié</h1>
                </div>
                <p class="text-gray-500 text-sm mb-7 ml-13">Entrez votre adresse email, nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm flex items-start gap-2 font-display font-semibold">
                    <i class="fas fa-check-circle mt-0.5 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->has('email'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm flex items-center gap-2 font-display font-semibold">
                    <i class="fas fa-exclamation-circle text-red-500 shrink-0"></i> {{ $errors->first('email') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-display font-bold text-gray-700 mb-1.5">
                            <i class="fas fa-envelope text-mja-blue mr-1"></i> Adresse email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border-2 border-gray-100 focus:border-mja-blue rounded-xl px-4 py-3 text-sm outline-none transition-colors"
                               placeholder="votre@email.fr">
                    </div>
                    <button type="submit"
                            class="w-full text-white font-display font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm"
                            style="background:#3DAEF5;"
                            onmouseover="this.style.background='#1E93D6'" onmouseout="this.style.background='#3DAEF5'">
                        <i class="fas fa-paper-plane"></i> Envoyer le lien
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-white/50 hover:text-white text-sm transition-colors font-display font-semibold">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la connexion
            </a>
        </div>
    </div>
</body>
</html>
