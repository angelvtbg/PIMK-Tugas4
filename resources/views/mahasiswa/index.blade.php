@extends('layouts.app')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold">Daftar Mahasiswa</h1>
            <button onclick="openAddModal()" class="px-4 py-2 bg-green-600 text-white rounded-[10px] hover:bg-green-700">
                + Tambah
            </button>
        </div>
        <form method="GET" action="{{ route('mahasiswa.index') }}" class="flex mt-4 md:mt-0 items-center space-x-2">
            <input type="text" name="search" placeholder="Cari Nama / NIM / Prodi" value="{{ request('search') }}"
                    class="px-4 py-2 border rounded-[10px] focus:outline-none focus:ring focus:ring-blue-300">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded-[10px] hover:bg-blue-700">
                <i class='bx bx-search'></i>
            </button>
        </form>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">NIM</th>
                    <th class="px-4 py-2 text-left">Program Studi</th>
                    <th class="px-4 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            
            <tbody>
                @forelse ($mahasiswas as $mhs)
                <tr class="border-t hover:bg-gray-50 text-sm">
                    <td class="px-4 py-2">{{ $mhs->name }}</td>
                    <td class="px-4 py-2">{{ $mhs->nim }}</td>
                    <td class="px-4 py-2">{{ $mhs->prodi }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <button onclick="openEditModal({{ $mhs }})"
                        class="p-2 bg-yellow-400 text-white rounded-[10px] hover:bg-yellow-500"><i class='bx bx-pencil'></i></button>
                        <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="p-2 bg-red-500 text-white rounded-[10px] hover:bg-red-600">
                                    <i class='bx bx-trash'></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div id="mahasiswaModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-[10px] w-full max-w-md">
            <h2 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Mahasiswa</h2>
            <form id="mahasiswaForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="mhs_id">

                <div class="mb-4">
                    <label class="block mb-1">Nama</label>
                    <input type="text" name="name" id="name" required class="w-full border px-3 py-2 rounded-[10px]">
                </div>
                <div class="mb-4">
                    <label class="block mb-1">NIM</label>
                    <input type="text" name="nim" id="nim" required pattern="^\d{9}$" title="NIM harus 9 digit angka"
                            class="w-full border px-3 py-2 rounded-[10px]">
                </div>
                <div class="mb-4">
                    <label class="block mb-1">Program Studi</label>
                    <input type="text" name="prodi" id="prodi" required class="w-full border px-3 py-2 rounded-[10px]">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 rounded-[10px] hover:bg-gray-400">Batal</button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-[10px] hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endsection

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Mahasiswa';
        document.getElementById('mahasiswaForm').action = "{{ route('mahasiswa.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('name').value = '';
        document.getElementById('nim').value = '';
        document.getElementById('prodi').value = '';
        document.getElementById('mahasiswaModal').classList.remove('hidden');
    }

    function openEditModal(mhs) {
        document.getElementById('modalTitle').innerText = 'Edit Mahasiswa';
        document.getElementById('mahasiswaForm').action = '/mahasiswa/' + mhs.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('name').value = mhs.name;
        document.getElementById('nim').value = mhs.nim;
        document.getElementById('prodi').value = mhs.prodi;
        document.getElementById('mahasiswaModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('mahasiswaModal').classList.add('hidden');
    }
</script>
@endpush
