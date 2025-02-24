@extends('layouts.backend.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Form Input -->
        <div class="col-md-8">
            <h2 class="mb-4">Tambahkan Caption</h2>

            {{-- Form Postingan --}}
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Input Caption -->
                <div class="mb-3">
                    <label for="caption" class="form-label">Caption</label>
                    <textarea class="form-control" id="caption" name="caption" rows="2" placeholder="Tambahkan caption ke videomu" maxlength="150"></textarea>
                </div>

                <!-- Input Foto Sampul -->
                <div class="mb-3">
                    <label class="form-label">Foto Sampul</label>
                    <div class="d-flex">
                        <img id="previewImage" src="https://via.placeholder.com/100" class="rounded" width="100" height="100" alt="Foto Sampul">
                        <input type="file" class="form-control ms-3" id="thumbnail" name="thumbnail" accept="image/*" onchange="previewThumbnail(event)">
                    </div>
                </div>

                <!-- Tambah Produk -->
                <div class="mb-3">
                    <label class="form-label">Produk</label>
                    <button type="button" class="btn btn-outline-danger">+ Tambahkan Produk (0/6)</button>
                </div>

                <!-- Jadwal Posting -->
                <div class="mb-3">
                    <label class="form-label">Jadwal Posting</label>
                    <div>
                        <input type="radio" id="post_now" name="schedule" value="now" checked>
                        <label for="post_now">Post Sekarang</label>

                        <input type="radio" id="schedule_post" name="schedule" value="schedule" class="ms-3">
                        <label for="schedule_post">Atur Jadwal Posting</label>
                    </div>
                </div>

                <!-- Izin Duet & Stitch -->
                <div class="mb-3">
                    <label class="form-label">Pengaturan Video</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="duet" name="duet" checked>
                        <label class="form-check-label" for="duet">Izinkan Duet</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="stitch" name="stitch" checked>
                        <label class="form-check-label" for="stitch">Izinkan Stitch</label>
                    </div>
                </div>

                <!-- Tombol Kirim -->
                <button type="submit" class="btn btn-primary">Posting</button>
            </form>
        </div>

        <!-- Preview Video -->
        <div class="col-md-4">
            <h5 class="mb-3">Preview</h5>
            <div class="border p-3 rounded">
                <video id="videoPreview" width="100%" controls>
                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                    Browser Anda tidak mendukung pemutar video.
                </video>
                <p class="mt-2 text-muted">@username</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview gambar sampul
    function previewThumbnail(event) {
        const image = document.getElementById("previewImage");
        image.src = URL.createObjectURL(event.target.files[0]);
    }
</script>

@endsection
