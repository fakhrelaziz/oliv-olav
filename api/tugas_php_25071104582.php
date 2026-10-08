<?php
$tamuList = [
    1 => "Susilo Bambang Yudhoyono",
    2 => "Joko Widodo",
    3 => "Prabowo Subianto"
];

$index = isset($_GET['tamu']) ? intval($_GET['tamu']) : null;
$namaTamu = "Bapak/Ibu/Saudara/i";

if ($index !== null && isset($tamuList[$index])) {
    $namaTamu = $tamuList[$index];
}
?>
<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Olav & Oliv Wedding Invitation" />
  <title>Olav & Oliv — Wedding Invitation</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            moss: '#2C3424',
            mossDark: '#1a1f15',
            mossLight: '#4a573d',
            warmwhite: '#e8e6d9',
            ink: '#1A1A1A',
            cream: '#EFEFEA',
            cedar: '#4A3B32',
            aloe: '#8D9F87',
            paper: '#dcd8c8',
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['"Cormorant Garamond"', 'serif'],
            script: ['Caveat', 'cursive']
          },
          letterSpacing: {
            editorial: '0.32em'
          }
        }
      }
    }
  </script>
  
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/style.css" />
</head>
<body class="bg-moss text-warmwhite antialiased overflow-x-hidden">
  <main id="integrated-content">

    <!-- COVER UNDANGAN KELOMPOK -->
    <section id="home" class="relative min-h-[100svh] w-full overflow-hidden bg-moss">
      <!-- Background images -->
      <picture class="absolute inset-0 z-0">
        <source media="(min-width: 768px)" srcset="/aset/background_herosection(desktop).webp">
        <img src="/aset/background_herosection.webp" alt="Olav &amp; Oliv" class="h-full w-full object-cover object-center" />
      </picture>
      
      <!-- Overlay gradient -->
      <div class="absolute inset-0 z-0 bg-gradient-to-b from-moss/60 via-transparent to-moss/80"></div>

      <!-- Main content hero -->
      <div class="relative z-10 flex h-full min-h-[100svh] flex-col justify-center px-6 md:px-16">
        
        <div class="max-w-2xl mt-20">
          <div class="flex items-center gap-4 mb-4">
            <div class="h-px w-12 bg-warmwhite"></div>
            <p class="text-xs uppercase tracking-editorial text-warmwhite">THE WEDDING OF</p>
          </div>
          
          <h1 class="font-display text-7xl md:text-9xl leading-none text-warmwhite font-light">
            Olav<br>
            <span class="font-script text-4xl md:text-6xl italic lowercase relative -top-2 left-4">and</span><br>
            Oliv
          </h1>
          
          <p class="mt-8 text-sm uppercase tracking-[0.3em] text-warmwhite">
            25 OCTOBER 2026
          </p>
          
          <div class="mt-16 md:mt-24">
            <p class="text-lg md:text-xl font-display text-warmwhite">Kepada Yth,</p>
            <p class="text-2xl md:text-4xl font-display text-warmwhite mb-4 leading-tight">
              <?php echo htmlspecialchars($namaTamu, ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p class="text-sm md:text-base text-warmwhite/80 max-w-sm mb-6 leading-relaxed">
              Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir dalam hari bahagia kami.
            </p>
          </div>
        </div>
      </div>

      <!-- Right sidebar text (Desktop) -->
      <div class="absolute right-12 top-1/2 -translate-y-1/2 hidden md:flex flex-col items-end gap-2 text-right z-10">
        <p class="text-xs uppercase tracking-editorial text-warmwhite/80 leading-loose">
          SAME<br>PEOPLE<br>BRIGHTER<br>DAYS
        </p>
      </div>

      <!-- Right bottom script text -->
      <div class="absolute right-12 md:right-32 bottom-24 md:bottom-40 z-10 text-right rotate-[-10deg]">
        <p class="font-script text-4xl md:text-5xl text-warmwhite">A new<br>chapter<br>together</p>
      </div>

      <!-- Bottom decorations -->
      <picture class="absolute bottom-0 left-0 z-[5] pointer-events-none w-full md:w-[75%]">
        <source media="(min-width: 768px)" srcset="/aset/elemen_bagian_hero_section(desktop).webp">
        <img src="/aset/elemen_bagian_hero_section(mobile).webp" alt="Leaves decoration" class="w-full h-auto object-contain object-bottom-left" />
      </picture>

      <div class="absolute bottom-8 right-6 md:right-16 z-30 hidden md:block text-xs uppercase tracking-[0.3em] text-warmwhite/50">
        JAKARTA, INDONESIA
      </div>
    </section>
  </main>
</body>
</html>