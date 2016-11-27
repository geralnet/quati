@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section>
        <h1>Description</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ac dapibus felis. Vestibulum mattis
            ante ac
            felis posuere sodales. Nulla facilisi. In egestas, risus sed viverra fringilla, dolor massa pharetra
            risus,
            ut posuere mi urna eget sapien. Sed tempor enim sed imperdiet imperdiet. Fusce ipsum turpis, egestas
            eu
            ligula nec, fermentum sagittis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
            posuere
            cubilia Curae; Ut a urna est. Praesent interdum dui sed tempor gravida. Nullam at metus elementum,
            sollicitudin ante pretium, dignissim augue. Integer convallis condimentum felis ac lobortis.
            Praesent rutrum
            pharetra elit et pretium. Duis a turpis turpis. Praesent id quam in ipsum consequat feugiat non a
            nibh.
            Praesent vestibulum scelerisque purus et commodo. Ut mauris enim, tincidunt ac mollis non, egestas
            non
            neque.</p>
    </section>
    <section class="subcategories">
        @foreach($show_categories as $category)
            <section class="product-category">
                <h1>
                    <a href="{{ $category->getKeywordPath() }}">
                        {{ $category->name }}
                    </a>
                </h1>
                <b>Products:</b>
                <ul>
                    @foreach($category->products as $product)
                        <li>
                            <a href="{{ $product->getKeywordPath() }}">
                                {{ $product->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </section>
    <section>
        <h1>Products</h1>
        <ul>
            @foreach($current_category->products as $product)
                <li>
                    <a href="{{ $product->getKeywordPath() }}">
                        {{ $product->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endsection
