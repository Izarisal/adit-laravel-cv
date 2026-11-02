@extends('layouts.admin')
@section('judul', $cv->exists ? 'Edit CV' : 'Buat CV')

@php
    $edit = $cv->exists;

    // Baris awal untuk bagian dinamis (old() dipakai bila validasi gagal)
    $rowPendidikan = old('pendidikan', $edit ? $cv->pendidikan->toArray() : [[]]);
    $rowPengalaman = old('pengalaman', $edit ? $cv->pengalaman->toArray() : [[]]);
    $rowKeahlian   = old('keahlian',   $edit ? $cv->keahlian->pluck('nama_keahlian')->all() : ['']);
    if (!$rowPendidikan) $rowPendidikan = [[]];
    if (!$rowPengalaman) $rowPengalaman = [[]];
    if (!$rowKeahlian)   $rowKeahlian   = [''];
@endphp

@section('content')
<div class="py-4">
    <h1 class="h4">{{ $edit ? 'Edit' : 'Buat' }} CV</h1>
    <p class="mb-0">Isi biodata di bawah ini, lalu CV akan dicetak dari data yang diinput.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ $edit ? route('cv.update', $cv->id_portofolio) : route('cv.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if ($edit) @method('PUT') @endif

    {{-- ========== BIODATA ========== --}}
    <div class="card border-0 shadow mb-4">
        <div class="card-header"><h2 class="h5 mb-0">Biodata</h2></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required
                           value="{{ old('nama_lengkap', $cv->nama_lengkap) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="profesi">Profesi</label>
                    <input type="text" class="form-control" id="profesi" name="profesi" required
                           placeholder="contoh: Professional Graphic Designer"
                           value="{{ old('profesi', $cv->profesi) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon"
                           value="{{ old('telepon', $cv->telepon) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email', $cv->email) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                           value="{{ old('alamat', $cv->alamat) }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="profil">Profil Singkat</label>
                    <textarea class="form-control" id="profil" name="profil" rows="4">{{ old('profil', $cv->profil) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="foto">Foto (maks 2 MB)</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    @if ($edit && $cv->foto)
                        <small class="text-gray-600">Sudah ada foto. Upload lagi hanya jika ingin mengganti.</small>
                    @endif
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="tampilkan_foto"
                               name="tampilkan_foto" value="1"
                               {{ old('tampilkan_foto', $cv->tampilkan_foto) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tampilkan_foto">Sertakan foto di CV</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== PENDIDIKAN ========== --}}
    <div class="card border-0 shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Pendidikan</h2>
            <button type="button" class="btn btn-sm btn-secondary" onclick="tambahBaris('pendidikan')">+ Tambah</button>
        </div>
        <div class="card-body" id="wrap-pendidikan">
            @foreach ($rowPendidikan as $i => $r)
                <div class="row baris border rounded p-2 mb-2">
                    <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pendidikan[{{ $i }}][tahun_mulai]" placeholder="Tahun mulai" maxlength="4" value="{{ $r['tahun_mulai'] ?? '' }}"></div>
                    <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pendidikan[{{ $i }}][tahun_selesai]" placeholder="Tahun selesai" maxlength="4" value="{{ $r['tahun_selesai'] ?? '' }}"></div>
                    <div class="col-md-3 mb-2"><input type="text" class="form-control" name="pendidikan[{{ $i }}][institusi]" placeholder="Institusi" value="{{ $r['institusi'] ?? '' }}"></div>
                    <div class="col-md-4 mb-2"><input type="text" class="form-control" name="pendidikan[{{ $i }}][jurusan]" placeholder="Jurusan / gelar" value="{{ $r['jurusan'] ?? '' }}"></div>
                    <div class="col-md-1 mb-2"><button type="button" class="btn btn-danger w-100" onclick="hapusBaris(this)">&times;</button></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ========== PENGALAMAN ========== --}}
    <div class="card border-0 shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Pengalaman Kerja</h2>
            <button type="button" class="btn btn-sm btn-secondary" onclick="tambahBaris('pengalaman')">+ Tambah</button>
        </div>
        <div class="card-body" id="wrap-pengalaman">
            @foreach ($rowPengalaman as $i => $r)
                <div class="row baris border rounded p-2 mb-2">
                    <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pengalaman[{{ $i }}][tahun_mulai]" placeholder="Tahun mulai" maxlength="4" value="{{ $r['tahun_mulai'] ?? '' }}"></div>
                    <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pengalaman[{{ $i }}][tahun_selesai]" placeholder="Tahun selesai" maxlength="4" value="{{ $r['tahun_selesai'] ?? '' }}"></div>
                    <div class="col-md-3 mb-2"><input type="text" class="form-control" name="pengalaman[{{ $i }}][jabatan]" placeholder="Jabatan" value="{{ $r['jabatan'] ?? '' }}"></div>
                    <div class="col-md-4 mb-2"><textarea class="form-control" rows="2" name="pengalaman[{{ $i }}][deskripsi]" placeholder="Deskripsi pekerjaan">{{ $r['deskripsi'] ?? '' }}</textarea></div>
                    <div class="col-md-1 mb-2"><button type="button" class="btn btn-danger w-100" onclick="hapusBaris(this)">&times;</button></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ========== KEAHLIAN ========== --}}
    <div class="card border-0 shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Keahlian</h2>
            <button type="button" class="btn btn-sm btn-secondary" onclick="tambahBaris('keahlian')">+ Tambah</button>
        </div>
        <div class="card-body" id="wrap-keahlian">
            @foreach ($rowKeahlian as $i => $nama)
                <div class="row baris mb-2">
                    <div class="col-md-5"><input type="text" class="form-control" name="keahlian[{{ $i }}]" placeholder="contoh: Illustrator" value="{{ $nama }}"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100" onclick="hapusBaris(this)">&times;</button></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-5">
        <button type="submit" class="btn btn-primary">Simpan &amp; Lihat CV</button>
        <a href="{{ route('cv.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Template baris baru; __i__ diganti dengan nomor urut unik
    const templat = {
        pendidikan: `<div class="row baris border rounded p-2 mb-2">
            <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pendidikan[__i__][tahun_mulai]" placeholder="Tahun mulai" maxlength="4"></div>
            <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pendidikan[__i__][tahun_selesai]" placeholder="Tahun selesai" maxlength="4"></div>
            <div class="col-md-3 mb-2"><input type="text" class="form-control" name="pendidikan[__i__][institusi]" placeholder="Institusi"></div>
            <div class="col-md-4 mb-2"><input type="text" class="form-control" name="pendidikan[__i__][jurusan]" placeholder="Jurusan / gelar"></div>
            <div class="col-md-1 mb-2"><button type="button" class="btn btn-danger w-100" onclick="hapusBaris(this)">&times;</button></div></div>`,
        pengalaman: `<div class="row baris border rounded p-2 mb-2">
            <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pengalaman[__i__][tahun_mulai]" placeholder="Tahun mulai" maxlength="4"></div>
            <div class="col-md-2 mb-2"><input type="text" class="form-control" name="pengalaman[__i__][tahun_selesai]" placeholder="Tahun selesai" maxlength="4"></div>
            <div class="col-md-3 mb-2"><input type="text" class="form-control" name="pengalaman[__i__][jabatan]" placeholder="Jabatan"></div>
            <div class="col-md-4 mb-2"><textarea class="form-control" rows="2" name="pengalaman[__i__][deskripsi]" placeholder="Deskripsi pekerjaan"></textarea></div>
            <div class="col-md-1 mb-2"><button type="button" class="btn btn-danger w-100" onclick="hapusBaris(this)">&times;</button></div></div>`,
        keahlian: `<div class="row baris mb-2">
            <div class="col-md-5"><input type="text" class="form-control" name="keahlian[__i__]" placeholder="contoh: Illustrator"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger w-100" onclick="hapusBaris(this)">&times;</button></div></div>`,
    };
    let counter = 1000;
    function tambahBaris(jenis) {
        document.getElementById('wrap-' + jenis)
            .insertAdjacentHTML('beforeend', templat[jenis].replaceAll('__i__', counter++));
    }
    function hapusBaris(btn) { btn.closest('.baris').remove(); }
</script>
@endpush
