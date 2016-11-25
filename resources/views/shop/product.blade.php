@extends('layouts.master')

@section('sidemenu')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section>
        <h1>{{ $product->name }}</h1>
        <p>
            <b>Description:</b>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ac dapibus felis. Vestibulum mattis
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
            neque.
        </p>
        <p>
            <b>Price: </b> <i>$ 1,000.000</i>
        </p>
    </section>
@endsection
