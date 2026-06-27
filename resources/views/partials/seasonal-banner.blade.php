@php
use Carbon\Carbon;

$today = Carbon::today();
$year  = $today->year;

// Easter (dimanche de Pâques)
$easter = Carbon::createFromTimestamp(easter_date($year));

// Variable dates dérivées de Pâques
$mardiGras        = $easter->copy()->subDays(47);
$mercrediCendres  = $easter->copy()->subDays(46);
$vendrediSaint    = $easter->copy()->subDays(2);
$lundiPaques      = $easter->copy()->addDay();
$ascension        = $easter->copy()->addDays(39);
$lundiPentecote   = $easter->copy()->addDays(50);

// Fête des Mères : dernier dimanche de mai (si ce jour = Pentecôte → 1er dimanche de juin)
$lastSundayMay = Carbon::create($year, 5, 31)->startOfMonth()->endOfMonth()->startOfWeek(Carbon::SUNDAY);
while ($lastSundayMay->month !== 5) $lastSundayMay->subWeek();
$feteMeres = $lastSundayMay->isSameDay($lundiPentecote->copy()->subDay())
    ? Carbon::create($year, 6, 1)->next(Carbon::SUNDAY)
    : $lastSundayMay;

// Fête des Pères : 3ème dimanche de juin
$fetePeres = Carbon::create($year, 6, 1)->next(Carbon::SUNDAY)->addWeeks(2);

// Carnaval : fenêtre du Dimanche Gras au Mercredi des Cendres
$dimancheGras = $mardiGras->copy()->subDays(2);

// Fonction helper: vrai si $today est dans [date - $avant, date + $apres]
$inWindow = function (Carbon $date, int $avant = 5, int $apres = 0) use ($today): bool {
    return $today->between($date->copy()->subDays($avant), $date->copy()->addDays($apres));
};

// ────────────────────────────────��───────────────────────────────���
// Catalogue des bandeaux — premier match actif s'affiche
// ─────────────────────────────────────────────────────────────────
$banners = [

    // ── NOËL / NOUVEL AN ─────────────────────────────────
    [
        'key'     => "noel-$year",
        'actif'   => $today->month === 12 && $today->day >= 20,
        'emoji'   => '🎄',
        'message' => 'Joyeux Noël ! Toute l\'équipe MJA vous souhaite de belles fêtes de fin d\'année.',
        'bg'      => 'bg-gradient-to-r from-red-700 via-green-800 to-red-700',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],
    [
        'key'     => "nouvelan-$year",
        'actif'   => ($today->month === 12 && $today->day >= 29) || ($today->month === 1 && $today->day <= 4),
        'emoji'   => '🎉',
        'message' => 'Bonne année ! MJA vous souhaite une nouvelle année pleine de projets et de réussites.',
        'bg'      => 'bg-gradient-to-r from-mja-blue via-mja-dark to-mja-blue',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── ÉPIPHANIE ────────────────────────────���────────────
    [
        'key'     => "epiphanie-$year",
        'actif'   => $inWindow(Carbon::create($year, 1, 6), 1, 0),
        'emoji'   => '👑',
        'message' => 'Bonne Épiphanie ! Qui aura la fève ce soir ?',
        'bg'      => 'bg-gradient-to-r from-yellow-500 to-yellow-600',
        'text'    => 'text-yellow-900',
        'accent'  => 'bg-yellow-900/20',
    ],

    // ── CHANDELEUR ────────────────────────────────────────
    [
        'key'     => "chandeleur-$year",
        'actif'   => $inWindow(Carbon::create($year, 2, 2), 2, 0),
        'emoji'   => '🥞',
        'message' => 'Bonne Chandeleur ! On espère que vous avez fait sauter des crêpes sans les faire tomber 😄',
        'bg'      => 'bg-gradient-to-r from-amber-400 to-orange-400',
        'text'    => 'text-amber-900',
        'accent'  => 'bg-amber-900/20',
    ],

    // ── SAINT-VALENTIN ────────────────────────────────────
    [
        'key'     => "stvalentin-$year",
        'actif'   => $inWindow(Carbon::create($year, 2, 14), 4, 0),
        'emoji'   => '❤️',
        'message' => 'Bonne Saint-Valentin ! L\'équipe MJA vous souhaite une journée pleine d`\'amour et de douceur.',
        'bg'      => 'bg-gradient-to-r from-pink-500 to-rose-500',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── JOURNÉE DES FEMMES ────────────────────────────────
    [
        'key'     => "femmes-$year",
        'actif'   => $inWindow(Carbon::create($year, 3, 8), 1, 0),
        'emoji'   => '💜',
        'message' => 'Journée Internationale des Femmes — MJA célèbre et honore toutes les femmes !',
        'bg'      => 'bg-gradient-to-r from-purple-600 to-violet-600',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── CARNAVAL ──────────────────────────────────────────
    [
        'key'     => "carnaval-$year",
        'actif'   => $today->between($dimancheGras, $mercrediCendres),
        'emoji'   => '🎭',
        'message' => 'C\'est la saison du Carnaval ! Vive Vaval, vive la fête martiniquaise !',
        'bg'      => 'bg-gradient-to-r from-orange-500 via-pink-500 to-purple-500',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── PÂQUES ────────────────────────────────────────────
    [
        'key'     => "paques-$year",
        'actif'   => $today->between($vendrediSaint, $lundiPaques),
        'emoji'   => '🐣',
        'message' => 'Joyeuses Pâques ! Bonne chasse aux œufs à tous 🍫',
        'bg'      => 'bg-gradient-to-r from-yellow-400 to-green-500',
        'text'    => 'text-yellow-900',
        'accent'  => 'bg-yellow-900/20',
    ],

    // ── FÊTE DU TRAVAIL ───────────────────────────────────
    [
        'key'     => "travail-$year",
        'actif'   => $inWindow(Carbon::create($year, 5, 1), 1, 0),
        'emoji'   => '✊',
        'message' => 'Bonne Fête du Travail ! MJA s\'engage pour l\'emploi et l\'avenir des jeunes.',
        'bg'      => 'bg-gradient-to-r from-red-600 to-red-700',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── VICTOIRE 1945 ─────────────────────────────────────
    [
        'key'     => "victoire-$year",
        'actif'   => $inWindow(Carbon::create($year, 5, 8), 1, 0),
        'emoji'   => '🕊️',
        'message' => '8 Mai 1945 — En mémoire de ceux qui ont lutté pour notre liberté.',
        'bg'      => 'bg-gradient-to-r from-blue-700 to-blue-900',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── ABOLITION ESCLAVAGE MARTINIQUE ────────────────────
    [
        'key'     => "abolition-mq-$year",
        'actif'   => $inWindow(Carbon::create($year, 5, 22), 3, 0),
        'emoji'   => '✊🏾',
        'message' => '22 Mai — Commémoration de l\'abolition de l\'esclavage en Martinique. N\'oublions jamais.',
        'bg'      => 'bg-gradient-to-r from-red-700 via-black to-green-800',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── ABOLITION ESCLAVAGE GUADELOUPE ────────────────────
    [
        'key'     => "abolition-gp-$year",
        'actif'   => $inWindow(Carbon::create($year, 5, 27), 2, 0),
        'emoji'   => '✊🏾',
        'message' => '27 Mai — Commémoration de l\'abolition de l\'esclavage en Guadeloupe.',
        'bg'      => 'bg-gradient-to-r from-red-700 via-black to-green-800',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── FÊTE DES MÈRES ────────────────────────────────────
    [
        'key'     => "meres-$year",
        'actif'   => $inWindow($feteMeres, 3, 0),
        'emoji'   => '💐',
        'message' => 'Bonne Fête des Mamans ! MJA rend hommage à toutes les mamans de Martinique et d\'ailleurs 💕',
        'bg'      => 'bg-gradient-to-r from-pink-400 to-rose-400',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── ABOLITION ESCLAVAGE GUYANE ────────────────────────
    [
        'key'     => "abolition-gy-$year",
        'actif'   => $inWindow(Carbon::create($year, 6, 10), 2, 0),
        'emoji'   => '✊🏾',
        'message' => '10 Juin — Commémoration de l\'abolition de l\'esclavage en Guyane.',
        'bg'      => 'bg-gradient-to-r from-red-700 via-black to-green-800',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── FÊTE DES PÈRES ────────────────────────────────────
    [
        'key'     => "peres-$year",
        'actif'   => $inWindow($fetePeres, 3, 0),
        'emoji'   => '👔',
        'message' => 'Bonne Fête des Papas ! MJA célèbre tous les pères qui inspirent nos jeunes 🙌',
        'bg'      => 'bg-gradient-to-r from-blue-500 to-cyan-600',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── FÊTE DE LA MUSIQUE ────────────────────────────────
    [
        'key'     => "musique-$year",
        'actif'   => $inWindow(Carbon::create($year, 6, 21), 2, 0),
        'emoji'   => '🎵',
        'message' => 'Bonne Fête de la Musique ! La musique unit les peuples — vive la culture !',
        'bg'      => 'bg-gradient-to-r from-purple-500 via-pink-500 to-orange-400',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── FÊTE NATIONALE ────────────────────────────────────
    [
        'key'     => "bastille-$year",
        'actif'   => $inWindow(Carbon::create($year, 7, 14), 2, 0),
        'emoji'   => '🇫🇷',
        'message' => 'Bonne Fête Nationale ! Vive la République, vive la France, vive la Martinique 🎆',
        'bg'      => 'bg-gradient-to-r from-blue-700 via-white to-red-600',
        'text'    => 'text-blue-900',
        'accent'  => 'bg-blue-900/10',
    ],

    // ── HALLOWEEN ─────────────────────────────────────────
    [
        'key'     => "halloween-$year",
        'actif'   => $inWindow(Carbon::create($year, 10, 31), 4, 0),
        'emoji'   => '🎃',
        'message' => 'Halloween approche ! Préparez vos déguisements 👻',
        'bg'      => 'bg-gradient-to-r from-orange-600 to-orange-800',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── TOUSSAINT ─────────────────────────────────────────
    [
        'key'     => "toussaint-$year",
        'actif'   => $inWindow(Carbon::create($year, 11, 1), 1, 1),
        'emoji'   => '🕯️',
        'message' => 'Toussaint — En ce jour de recueillement, MJA a une pensée pour tous ceux qui nous ont quittés.',
        'bg'      => 'bg-gradient-to-r from-gray-700 to-gray-800',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],

    // ── ARMISTICE ─────────────────────────────────────────
    [
        'key'     => "armistice-$year",
        'actif'   => $inWindow(Carbon::create($year, 11, 11), 1, 0),
        'emoji'   => '🌹',
        'message' => '11 Novembre — Armistice. Mémoire et respect pour tous ceux qui ont sacrifié leur vie.',
        'bg'      => 'bg-gradient-to-r from-slate-700 to-slate-800',
        'text'    => 'text-white',
        'accent'  => 'bg-white/20',
    ],
];

$activeBanner = collect($banners)->first(fn($b) => $b['actif']);
@endphp

@if($activeBanner)
<div id="seasonal-banner"
     data-key="{{ $activeBanner['key'] }}"
     class="{{ $activeBanner['bg'] }} {{ $activeBanner['text'] }} relative overflow-hidden"
     style="display:none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <span class="text-xl shrink-0">{{ $activeBanner['emoji'] }}</span>
            <p class="text-sm font-display font-semibold truncate sm:whitespace-normal">
                {{ $activeBanner['message'] }}
            </p>
        </div>
        <button onclick="dismissSeasonalBanner()"
                aria-label="Fermer"
                class="shrink-0 w-7 h-7 rounded-lg {{ $activeBanner['accent'] }} hover:opacity-80 flex items-center justify-center transition-opacity">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
</div>

<script>
(function () {
    const banner = document.getElementById('seasonal-banner');
    if (!banner) return;
    const key = 'sbanner_' + banner.dataset.key;
    if (!localStorage.getItem(key)) {
        banner.style.display = '';
    }
})();

function dismissSeasonalBanner() {
    const banner = document.getElementById('seasonal-banner');
    if (!banner) return;
    localStorage.setItem('sbanner_' + banner.dataset.key, '1');
    banner.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
    banner.style.maxHeight  = banner.offsetHeight + 'px';
    banner.style.overflow   = 'hidden';
    requestAnimationFrame(() => {
        banner.style.maxHeight = '0';
        banner.style.opacity   = '0';
    });
    setTimeout(() => banner.remove(), 320);
}
</script>
@endif
