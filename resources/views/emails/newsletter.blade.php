<!DOCTYPE html>
<html>
<head>
    <title>Newsletter from Valuta.kz</title>
</head>
<body>
<div class="">
    @forelse($posts as $post)
        <div>
            <h3>{{ $post->title }}</h3>
            <div class="">
                <p>{{ $post->body }}</p>
            </div>
        </div>
    @empty

    @endforelse
</div>
</body>
</html>
