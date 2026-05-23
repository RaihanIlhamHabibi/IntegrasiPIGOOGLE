@extends('layouts.app')

@section('content')
<div class="py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-folder2 text-primary"></i>
            File Google Drive
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-cloud-upload"></i> Upload file
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2 px-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-2 px-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($files->count() > 0)

        <p class="text-uppercase text-muted small fw-semibold mb-3" style="letter-spacing:.05em">
            Tersimpan &mdash; {{ $files->total() }} file
        </p>

        <div class="d-flex flex-column gap-2">
            @foreach($files as $file)
            @php
                $mime = $file->mime_type ?? '';
                $iconClass = 'bi-file-earmark';
                $iconBg    = '#F1EFE8';
                $iconColor = '#5F5E5A';

                if (str_contains($mime, 'pdf')) {
                    $iconClass = 'bi-file-earmark-pdf';
                    $iconBg    = '#FCEBEB';
                    $iconColor = '#791F1F';
                } elseif (str_contains($mime, 'image')) {
                    $iconClass = 'bi-file-earmark-image';
                    $iconBg    = '#EAF3DE';
                    $iconColor = '#27500A';
                } elseif (str_contains($mime, 'word') || str_contains($mime, 'document')) {
                    $iconClass = 'bi-file-earmark-word';
                    $iconBg    = '#E6F1FB';
                    $iconColor = '#0C447C';
                } elseif (str_contains($mime, 'sheet') || str_contains($mime, 'excel')) {
                    $iconClass = 'bi-file-earmark-excel';
                    $iconBg    = '#EAF3DE';
                    $iconColor = '#27500A';
                } elseif (str_contains($mime, 'video')) {
                    $iconClass = 'bi-file-earmark-play';
                    $iconBg    = '#FAEEDA';
                    $iconColor = '#633806';
                } elseif (str_contains($mime, 'zip') || str_contains($mime, 'rar') || str_contains($mime, 'archive')) {
                    $iconClass = 'bi-file-earmark-zip';
                    $iconBg    = '#FBEAF0';
                    $iconColor = '#72243E';
                }
            @endphp

            <div class="card border shadow-none rounded-3">
                <div class="card-body py-3 px-4 d-flex align-items-center gap-3">

                    {{-- Icon --}}
                    <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                         style="width:42px;height:42px;background:{{ $iconBg }}">
                        <i class="bi {{ $iconClass }}" style="font-size:18px;color:{{ $iconColor }}"></i>
                    </div>

                    {{-- Info --}}
                    <div class="flex-grow-1" style="min-width:0">
                        <p class="mb-1 fw-semibold text-truncate" style="font-size:14px">
                            @if($file->web_view_link)
                                <a href="{{ $file->web_view_link }}" target="_blank"
                                   class="text-decoration-none text-dark">
                                    {{ $file->file_name }}
                                </a>
                            @else
                                {{ $file->file_name }}
                            @endif
                        </p>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="d-flex align-items-center gap-1 text-muted" style="font-size:12px">
                                <i class="bi bi-hdd"></i>
                                {{ formatBytes($file->file_size) }}
                            </span>
                            <span class="d-flex align-items-center gap-1 text-muted" style="font-size:12px">
                                <i class="bi bi-clock"></i>
                                {{ $file->created_at->format('d M Y, H:i') }}
                            </span>
                            <span class="badge rounded-2 text-truncate"
                                  style="max-width:180px;background:var(--bs-light);color:#5F5E5A;font-size:11px;font-weight:500">
                                {{ $file->mime_type }}
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-1 flex-shrink-0">
                        @if($file->web_view_link)
                        <a href="{{ $file->web_view_link }}" target="_blank"
                           class="btn btn-sm d-flex align-items-center justify-content-center rounded-2"
                           style="width:32px;height:32px;background:#E6F1FB;color:#0C447C;border:none"
                           title="Lihat file">
                            <i class="bi bi-box-arrow-up-right" style="font-size:13px"></i>
                        </a>
                        @endif
                        <button type="button"
                                class="btn btn-sm d-flex align-items-center justify-content-center rounded-2 delete-file-button"
                                style="width:32px;height:32px;background:#FCEBEB;color:#791F1F;border:none"
                                data-file-id="{{ $file->id }}"
                                title="Hapus">
                            <i class="bi bi-trash" style="font-size:13px"></i>
                        </button>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $files->links() }}
        </div>

    @else

        <div class="text-center py-5 rounded-3 border" style="background:var(--bs-light)">
            <i class="bi bi-inbox text-muted" style="font-size:2.5rem"></i>
            <p class="mt-3 text-muted mb-3">Belum ada file. Yuk upload file pertamamu!</p>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-cloud-upload"></i> Upload file
            </button>
        </div>

    @endif
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm rounded-3">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 p-2" style="background:#E6F1FB">
                        <i class="bi bi-cloud-upload text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Upload file ke Google Drive</h5>
                        <small class="text-muted">File akan otomatis dibagikan ke semua anggota</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">

                {{-- Broadcast notice --}}
                <div class="d-flex align-items-center gap-2 rounded-3 py-2 px-3 mb-3"
                     style="background:#E6F1FB;border:0.5px solid #B5D4F4">
                    <i class="bi bi-people-fill" style="color:#185FA5"></i>
                    <small style="color:#0C447C">File ini akan masuk ke Google Drive semua pengguna yang sudah terhubung.</small>
                </div>

                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Dropzone --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih file <span class="text-danger">*</span></label>
                        <div class="border rounded-3 text-center py-4 px-3 position-relative"
                             style="border-style:dashed !important;cursor:pointer;background:var(--bs-light)"
                             onclick="document.getElementById('file').click()">
                            <i class="bi bi-file-earmark-arrow-up text-muted" style="font-size:2rem"></i>
                            <p class="mb-1 mt-2 text-muted small">Klik untuk pilih file</p>
                            <small class="text-muted" style="font-size:11px">Semua format didukung &mdash; maks. 100 MB</small>
                            <p class="mb-0 mt-2 text-primary small fw-semibold" id="selectedFileName"></p>
                        </div>
                        <input type="file" class="d-none" id="file" name="file" required
                               onchange="document.getElementById('selectedFileName').textContent = this.files[0]?.name ?? ''">
                    </div>

                    <div class="mb-1">
                        <label for="description" class="form-label small fw-semibold">Deskripsi (opsional)</label>
                        <textarea class="form-control" id="description" name="description"
                                  rows="2" placeholder="Tambahkan catatan untuk file ini..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2"
                        onclick="submitUpload(event)">
                    <span class="spinner-border spinner-border-sm d-none" id="uploadSpinner" role="status" aria-hidden="true"></span>
                    <i class="bi bi-upload" id="uploadIcon"></i>
                    Upload
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function formatBytes(bytes, decimals = 2) {
    if (!bytes || bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function submitUpload(event) {
    const form      = document.getElementById('uploadForm');
    const formData  = new FormData(form);
    const uploadBtn = event.target.closest('button');
    const spinner   = document.getElementById('uploadSpinner');
    const icon      = document.getElementById('uploadIcon');

    spinner.classList.remove('d-none');
    icon.classList.add('d-none');
    uploadBtn.disabled = true;

    fetch('{{ route("google-drive.upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupload file.');
    })
    .finally(() => {
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
        uploadBtn.disabled = false;
    });
}

document.querySelectorAll('.delete-file-button').forEach(function(button) {
    button.addEventListener('click', function() {
        const fileId = this.dataset.fileId;
        if (!fileId) return;
        if (!confirm('Yakin ingin menghapus file ini?')) return;

        fetch(`/google-drive/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus file.');
        });
    });
});
</script>
@endsection