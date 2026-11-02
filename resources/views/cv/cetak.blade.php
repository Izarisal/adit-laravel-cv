<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV - {{ $cv->nama_lengkap }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #e5e7eb; font-family: 'Poppins', 'Segoe UI', Arial, sans-serif; color: #111; }

        .toolbar { max-width: 210mm; margin: 16px auto; display: flex; gap: 8px; flex-wrap: wrap; padding: 0 8px; }
        .toolbar a, .toolbar button {
            font: 500 14px 'Poppins', Arial, sans-serif; padding: 8px 16px; border-radius: 6px;
            border: 1px solid #1f2937; background: #fff; color: #1f2937; text-decoration: none; cursor: pointer;
        }
        .toolbar .utama { background: #1f2937; color: #fff; }
        .sukses { max-width: 210mm; margin: 0 auto 8px; padding: 8px 12px; background: #d1fae5; border-radius: 6px; font-size: 14px; }

        .halaman {
            width: 210mm; min-height: 297mm; margin: 0 auto 24px; background: #fff;
            padding: 14mm 14mm 0; position: relative; display: flex; flex-direction: column;
        }
        .kepala { display: grid; grid-template-columns: 62mm 1fr; column-gap: 8mm; align-items: center; margin-bottom: 12mm; }
        .foto { width: 46mm; height: 46mm; border-radius: 50%; object-fit: cover; display: block; }
        .nama { font-size: 26pt; font-weight: 700; line-height: 1.1; margin: 0 0 4mm; }
        .profesi { font-size: 9pt; letter-spacing: .35em; margin: 0; }

        .isi { display: grid; grid-template-columns: 62mm 1fr; column-gap: 8mm; flex: 1; }
        h2 { font-size: 13pt; font-weight: 700; margin: 0 0 5mm; }
        section { margin-bottom: 9mm; }
        .kolom-kiri { font-size: 9.5pt; }
        .kolom-kanan { font-size: 9.5pt; line-height: 1.6; }

        .kontak p { margin: 0 0 4mm; display: flex; gap: 3mm; align-items: flex-start; }
        .kontak svg { width: 4mm; height: 4mm; flex: none; margin-top: 1mm; }

        .item { margin-bottom: 6mm; }
        .tahun { margin: 0; }
        .judul { font-weight: 600; color: #4b5563; margin: 0; }
        .item p.ket { margin: 0; white-space: pre-line; }
        .profil { text-align: justify; margin: 0; white-space: pre-line; }
        ul.skill { margin: 0; padding-left: 5mm; }
        ul.skill li { margin-bottom: 2mm; }

        .bar { background: #000; height: 14mm; margin: 0 -14mm; }

        @media print {
            body { background: #fff; }
            .toolbar, .sukses { display: none !important; }
            .halaman { margin: 0; box-shadow: none; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <a href="{{ route('cv.edit', $cv->id_portofolio) }}">Edit Data</a>
    <a href="{{ route('cv.index') }}">Daftar CV</a>
</div>
@if (session('success'))
    <div class="sukses">{{ session('success') }}</div>
@endif

@php
    $adaFoto = $cv->tampilkan_foto && $cv->foto;
    $rentang = fn ($a, $b) => trim(($a ?: '') . ($b ? ' - ' . $b : ''));
@endphp

<div class="halaman">
    <header class="kepala">
        <div>
            @if ($adaFoto)
                <img class="foto" src="{{ route('cv.foto', $cv->id_portofolio) }}" alt="Foto {{ $cv->nama_lengkap }}">
            @endif
        </div>
        <div>
            <h1 class="nama">{{ $cv->nama_lengkap }}</h1>
            <p class="profesi">{{ $cv->profesi }}</p>
        </div>
    </header>

    <div class="isi">
        <aside class="kolom-kiri">
            @if ($cv->telepon || $cv->email || $cv->alamat)
                <section class="kontak">
                    <h2>Kontak</h2>
                    @if ($cv->telepon)
                        <p><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z"/></svg><span>{{ $cv->telepon }}</span></p>
                    @endif
                    @if ($cv->email)
                        <p><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5z"/></svg><span>{{ $cv->email }}</span></p>
                    @endif
                    @if ($cv->alamat)
                        <p><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg><span>{{ $cv->alamat }}</span></p>
                    @endif
                </section>
            @endif

            @if ($cv->pendidikan->count())
                <section>
                    <h2>Pendidikan</h2>
                    @foreach ($cv->pendidikan as $p)
                        <div class="item">
                            <p class="tahun">{{ $rentang($p->tahun_mulai, $p->tahun_selesai) }}</p>
                            <p class="judul">{{ $p->institusi }}</p>
                            @if ($p->jurusan)<p class="ket">{{ $p->jurusan }}</p>@endif
                        </div>
                    @endforeach
                </section>
            @endif

            @if ($cv->keahlian->count())
                <section>
                    <h2>Skill</h2>
                    <ul class="skill">
                        @foreach ($cv->keahlian as $k)
                            <li>{{ $k->nama_keahlian }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </aside>

        <main class="kolom-kanan">
            @if ($cv->profil)
                <section>
                    <h2>Profil</h2>
                    <p class="profil">{{ $cv->profil }}</p>
                </section>
            @endif

            @if ($cv->pengalaman->count())
                <section>
                    <h2>Pengalaman Kerja</h2>
                    @foreach ($cv->pengalaman as $e)
                        <div class="item">
                            <p class="tahun">{{ $rentang($e->tahun_mulai, $e->tahun_selesai) }}</p>
                            <p class="judul">{{ $e->jabatan }}</p>
                            @if ($e->deskripsi)<p class="ket">{{ $e->deskripsi }}</p>@endif
                        </div>
                    @endforeach
                </section>
            @endif
        </main>
    </div>

    <div class="bar"></div>
</div>

</body>
</html>
