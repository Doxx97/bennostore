@extends('layout')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i> Tambah Produk Baru</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Minyak Goreng 2L" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category" class="form-select" required>
                                <option value="" selected disabled>Pilih Kategori...</option>
                                <option value="Sembako">Sembako</option>
                                <option value="Barang">Barang</option>
                                <option value="Pulsa & Kuota">Pulsa & Kuota</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Kebersihan">Kebersihan</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" placeholder="Contoh: 15000" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stok Awal</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" placeholder="Contoh: 100" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-save"></i> Simpan Produk
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection