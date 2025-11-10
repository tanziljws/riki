<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .gallery { display: flex; flex-wrap: wrap; gap: 20px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); padding: 10px; width: 200px; text-align: center; }
        img { max-width: 100%; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>Gallery</h1>

    @auth
        <form action="{{ route('gallery.add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="title" placeholder="Judul Foto" required>
            <input type="file" name="file" required>
            <button type="submit">Tambah Foto</button>
        </form>
    @endauth

    <div class="gallery">
        @foreach($photos as $photo)
            <div class="card">
                <img src="{{ asset('storage/'.$photo->file_path) }}" alt="{{ $photo->title }}">
                <p>{{ $photo->title }}</p>

                @auth
                    <form action="{{ route('gallery.delete', $photo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                @endauth
            </div>
        @endforeach
    </div>

    @guest
        <a href="{{ route('login') }}">Login Admin</a>
    @else
        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Logout</button></form>
    @endguest
</body>
</html>
