@extends('layouts.app')

@section('content')
<div class="py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-calendar-event text-primary"></i>
            Acara Google Calendar
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('google-calendar.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Buat acara
            </a>
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

    @if($events->count() > 0)

        <p class="text-uppercase text-muted small fw-semibold mb-3" style="letter-spacing:.05em">
            Mendatang &mdash; {{ $events->total() }} acara
        </p>

        <div class="d-flex flex-column gap-2">
            @foreach($events as $event)
            <div class="card border shadow-none rounded-3">
                <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between gap-3">

                    {{-- Info --}}
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold" style="font-size:14px">{{ $event->event_title }}</p>
                        <div class="d-flex align-items-center flex-wrap gap-2">

                            {{-- Waktu --}}
                            <span class="d-flex align-items-center gap-1 text-muted" style="font-size:12px">
                                <i class="bi bi-calendar3"></i>
                                {{ $event->event_start->format('d M Y, H:i') }}
                                &ndash;
                                {{ $event->event_start->isSameDay($event->event_end)
                                    ? $event->event_end->format('H:i')
                                    : $event->event_end->format('d M Y, H:i') }}
                            </span>

                            {{-- Lokasi --}}
                            @if($event->location)
                            <span class="badge d-flex align-items-center gap-1 rounded-2"
                                  style="background:#E6F1FB;color:#0C447C;font-size:11px;font-weight:500;padding:3px 8px">
                                <i class="bi bi-geo-alt" style="font-size:11px"></i>
                                {{ $event->location }}
                            </span>
                            @endif

                            {{-- Google Meet badge --}}
                            @if($event->hangout_link)
                            <a href="{{ $event->hangout_link }}" target="_blank"
                               class="badge d-flex align-items-center gap-1 rounded-2 text-decoration-none"
                               style="background:#EAF3DE;color:#27500A;font-size:11px;font-weight:500;padding:3px 8px">
                                <i class="bi bi-camera-video" style="font-size:11px"></i>
                                Google Meet
                            </a>
                            @endif

                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-1 flex-shrink-0">
                        <a href="{{ route('google-calendar.edit', $event->id) }}"
                           class="btn btn-sm d-flex align-items-center justify-content-center rounded-2"
                           style="width:32px;height:32px;background:#FAEEDA;color:#633806;border:none"
                           title="Edit">
                            <i class="bi bi-pencil" style="font-size:13px"></i>
                        </a>
                        <button type="button"
                                class="btn btn-sm d-flex align-items-center justify-content-center rounded-2 delete-event-button"
                                style="width:32px;height:32px;background:#FCEBEB;color:#791F1F;border:none"
                                data-event-id="{{ $event->id }}"
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
            {{ $events->links() }}
        </div>

    @else

        <div class="text-center py-5 rounded-3 border" style="background:var(--bs-light)">
            <i class="bi bi-calendar-x text-muted" style="font-size:2.5rem"></i>
            <p class="mt-3 text-muted mb-3">Belum ada acara. Yuk buat acara pertamamu!</p>
            <a href="{{ route('google-calendar.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Buat acara
            </a>
        </div>

    @endif

</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.delete-event-button').forEach(function(button) {
    button.addEventListener('click', function() {
        const eventId = this.dataset.eventId;
        if (!eventId) return;
        if (!confirm('Yakin ingin menghapus acara ini?')) return;

        fetch(`/google-calendar/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
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
            alert('Terjadi kesalahan saat menghapus acara.');
        });
    });
});
</script>
@endsection