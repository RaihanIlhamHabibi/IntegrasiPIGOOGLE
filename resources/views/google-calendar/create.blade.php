@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- Header --}}
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="rounded-3 p-2 bg-primary bg-opacity-10">
                            <i class="bi bi-calendar-plus fs-5 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-500">Buat acara baru</h5>
                            <small class="text-muted">Acara akan otomatis dibagikan ke semua anggota terdaftar</small>
                        </div>
                    </div>

                    {{-- Broadcast notice --}}
                    <div class="alert alert-info d-flex align-items-center gap-2 py-2 px-3 mb-4" role="alert">
                        <i class="bi bi-people-fill"></i>
                        <small>Acara ini akan masuk ke Google Calendar semua pengguna yang sudah terhubung ke Google.</small>
                    </div>

                    <form action="{{ route('google-calendar.store') }}" method="POST">
                        @csrf

                        {{-- Informasi acara --}}
                        <p class="text-uppercase text-muted small fw-semibold mb-3" style="letter-spacing:.05em">Informasi acara</p>

                        <div class="mb-3">
                            <label for="title" class="form-label small fw-semibold">Judul acara <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent"><i class="bi bi-fonts"></i></span>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Rapat mingguan tim" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label small fw-semibold">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Tambahkan detail atau agenda acara...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr class="my-4">

                        {{-- Waktu & Lokasi --}}
                        <p class="text-uppercase text-muted small fw-semibold mb-3" style="letter-spacing:.05em">Waktu & lokasi</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label small fw-semibold">Waktu mulai <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bi bi-clock"></i></span>
                                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                    @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label small fw-semibold">Waktu selesai <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bi bi-clock-history"></i></span>
                                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                    @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label small fw-semibold">Lokasi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Ruang rapat A, Gedung HQ">
                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Google Meet --}}
                        <p class="text-uppercase text-muted small fw-semibold mb-3" style="letter-spacing:.05em">Link meeting</p>

                        <div class="card border-0 bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://fonts.gstatic.com/s/i/productlogos/meet_2020q4/v1/web-64dp/logo_meet_2020q4_color_2x_web_64dp.png"
                                         alt="Google Meet" width="24" height="24">
                                    <div>
                                        <p class="mb-0 fw-semibold small">Google Meet</p>
                                        <small class="text-muted">Link generate otomatis saat acara dibuat</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="add_meet" name="add_meet" value="1" {{ old('add_meet', '1') ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('google-calendar.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calendar-check"></i> Buat acara
                            </button>
                        </div>
{{-- taruh sebelum div action button --}}
<div class="card border-0 bg-light rounded-3 p-3 mb-4">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <img src="https://fonts.gstatic.com/s/i/productlogos/meet_2020q4/v1/web-64dp/logo_meet_2020q4_color_2x_web_64dp.png"
                 alt="Google Meet" width="24" height="24">
            <div>
                <p class="mb-0 fw-semibold small">Google Meet</p>
                <small class="text-muted">Link generate otomatis saat acara dibuat</small>
            </div>
        </div>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" role="switch"
                   id="add_meet" name="add_meet" value="1"
                   {{ old('add_meet', '1') ? 'checked' : '' }}>
        </div>
    </div>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection