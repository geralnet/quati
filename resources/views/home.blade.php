<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quati</title>
</head>

<body>
@foreach($categories as $category)
    <section>
        <h1>{{ $category->name }}</h1>
        <b>Products:</b>
        <ul>
            @foreach($category->products as $product)
                <li>{{ $product->name }}</li>
            @endforeach
        </ul>
    </section>
@endforeach
</body>

</html>
