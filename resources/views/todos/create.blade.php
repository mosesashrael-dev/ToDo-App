<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat ToDo Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f4f6f9;">
    <div class="container py-5" style="max-width: 600px;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold">Buat ToDo Baru</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('todos.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold">Judul ToDo <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               placeholder="Contoh: Membuat laporan praktikum" value="{{ old('judul') }}" required autofocus>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  class="form-control" placeholder="Tuliskan detail tugas di sini (opsional)...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="is_completed" name="is_completed" value="1" {{ old('is_completed') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_completed">
                            Selesai (tanggal penyelesaian akan otomatis dicatat saat ini)
                        </label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('todos.index') }}" class="btn btn-light border">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan ToDo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>