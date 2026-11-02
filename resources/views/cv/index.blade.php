@extends('layouts.admin')
@section('judul', 'Daftar CV')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Daftar CV</h1>
            <p class="mb-0">Data portofolio yang sudah diisi. Klik "Lihat CV" untuk mencetak.</p>
        </div>
        <div>
            <a href="{{ route('cv.create') }}" class="btn btn-primary">+ Buat CV Baru</a>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Profesi</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataCv as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($item->foto && $item->tampilkan_foto)
                                    <img src="{{ route('cv.foto', $item->id_portofolio) }}" width="40" height="40"
                                         class="rounded-circle" style="object-fit:cover" alt="foto">
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->profesi }}</td>
                            <td>{{ $item->email ?: '-' }}</td>
                            <td>{{ $item->telepon ?: '-' }}</td>
                            <td>
                                <a href="{{ route('cv.show', $item->id_portofolio) }}" class="btn btn-sm btn-success">Lihat CV</a>
                                <a href="{{ route('cv.edit', $item->id_portofolio) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('cv.destroy', $item->id_portofolio) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus CV ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-gray-500">Belum ada data. Klik "Buat CV Baru".</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
