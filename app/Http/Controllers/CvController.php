<?php

namespace App\Http\Controllers;

use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    /** Daftar CV yang sudah dibuat */
    public function index()
    {
        $data['dataCv'] = Portofolio::latest('id_portofolio')->get();
        return view('cv.index', $data);
    }

    /** Form isi biodata */
    public function create()
    {
        return view('cv.form', ['cv' => new Portofolio(['tampilkan_foto' => true])]);
    }

    public function store(Request $request)
    {
        $input = $this->validasi($request);

        DB::transaction(function () use ($request, $input, &$cv) {
            $cv = Portofolio::create($this->dataUtama($request, $input));
            $this->simpanRelasi($cv, $input);
        });

        return redirect()->route('cv.show', $cv->id_portofolio)
            ->with('success', 'Data CV berhasil disimpan!');
    }

    /** Output akhir: CV siap cetak */
    public function show(string $id)
    {
        $data['cv'] = Portofolio::with(['pendidikan', 'pengalaman', 'keahlian'])->findOrFail($id);
        return view('cv.cetak', $data);
    }

    /** Tampilkan foto langsung dari storage (tidak perlu `storage:link`) */
    public function foto(string $id)
    {
        $cv = Portofolio::findOrFail($id);
        abort_unless($cv->foto && Storage::disk('public')->exists($cv->foto), 404);

        return response()->file(Storage::disk('public')->path($cv->foto));
    }

    public function edit(string $id)
    {
        $cv = Portofolio::with(['pendidikan', 'pengalaman', 'keahlian'])->findOrFail($id);
        return view('cv.form', compact('cv'));
    }

    public function update(Request $request, string $id)
    {
        $cv    = Portofolio::findOrFail($id);
        $input = $this->validasi($request);

        DB::transaction(function () use ($request, $input, $cv) {
            $cv->update($this->dataUtama($request, $input, $cv));
            $cv->pendidikan()->delete();
            $cv->pengalaman()->delete();
            $cv->keahlian()->delete();
            $this->simpanRelasi($cv, $input);
        });

        return redirect()->route('cv.show', $cv->id_portofolio)
            ->with('success', 'Data CV berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $cv = Portofolio::findOrFail($id);
        if ($cv->foto) {
            Storage::disk('public')->delete($cv->foto);
        }
        $cv->delete(); // tabel anak ikut terhapus (cascade)

        return redirect()->route('cv.index')->with('success', 'Data CV berhasil dihapus');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama_lengkap'  => 'required|string|max:100',
            'profesi'       => 'required|string|max:100',
            'profil'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
            'alamat'        => 'nullable|string|max:255',
            'foto'          => 'nullable|image|max:2048',

            'pendidikan.*.tahun_mulai'   => 'nullable|digits:4',
            'pendidikan.*.tahun_selesai' => 'nullable|digits:4',
            'pendidikan.*.institusi'     => 'nullable|string|max:150',
            'pendidikan.*.jurusan'       => 'nullable|string|max:150',

            'pengalaman.*.tahun_mulai'   => 'nullable|digits:4',
            'pengalaman.*.tahun_selesai' => 'nullable|digits:4',
            'pengalaman.*.jabatan'       => 'nullable|string|max:100',
            'pengalaman.*.deskripsi'     => 'nullable|string',

            'keahlian.*' => 'nullable|string|max:100',
        ], [
            'required' => ':attribute wajib diisi.',
            'email'    => 'Format email tidak valid.',
            'image'    => 'File harus berupa gambar.',
            'max'      => ':attribute terlalu panjang / besar.',
            'digits'   => ':attribute harus 4 digit (contoh 2020).',
        ]);
    }

    private function dataUtama(Request $request, array $input, ?Portofolio $cv = null): array
    {
        $data = [
            'nama_lengkap'   => $input['nama_lengkap'],
            'profesi'        => $input['profesi'],
            'profil'         => $input['profil'] ?? null,
            'telepon'        => $input['telepon'] ?? null,
            'email'          => $input['email'] ?? null,
            'alamat'         => $input['alamat'] ?? null,
            // checkbox: include / exclude gambar di CV
            'tampilkan_foto' => $request->boolean('tampilkan_foto'),
        ];

        if ($request->hasFile('foto')) {
            if ($cv && $cv->foto) {
                Storage::disk('public')->delete($cv->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-cv', 'public');
        }

        return $data;
    }

    private function simpanRelasi(Portofolio $cv, array $input): void
    {
        foreach ($input['pendidikan'] ?? [] as $row) {
            if (!empty($row['institusi'])) {
                $cv->pendidikan()->create($row);
            }
        }
        foreach ($input['pengalaman'] ?? [] as $row) {
            if (!empty($row['jabatan'])) {
                $cv->pengalaman()->create($row);
            }
        }
        foreach ($input['keahlian'] ?? [] as $nama) {
            if (!empty(trim((string) $nama))) {
                $cv->keahlian()->create(['nama_keahlian' => trim($nama)]);
            }
        }
    }
}
