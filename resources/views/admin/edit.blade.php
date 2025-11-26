@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h4 class="mb-0 fw-bold">Edit Produk</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="Sembako" {{ $product->category == 'Sembako' ? 'selected' : '' }}>Sembako</option>
                                    <option value="Barang" {{ $product->category == 'Barang' ? 'selected' : '' }}>Barang</option>
                                    <option value="Makanan" {{ $product->category == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                    </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ganti Gambar (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if($product->image)
                                <div class="mt-2">
                                    <small>Gambar Saat Ini:</small><br>
                                    <img src="{{ asset('storage/' . $product->image) }}" width="100" class="rounded border">
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Update Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection