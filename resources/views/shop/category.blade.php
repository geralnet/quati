@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')

    <section class="site-block">
        <h1 class="site-block-header">{{ $category->name }}</h1>
        <div>{!! (
            $category->description
            ? $category->description
            : $category->name.' Category'
        ) !!}</div>
    </section>

    @if ($category->hasSubcategories())
        <section class="site-block">
            <h3 class="site-block-header">Subcategories</h3>
            <div>
                <ul class="subcategories">
                    @foreach($category->subcategories as $subcategory)
                        <li><a href="{{ $subcategory->getUrl() }}">{{ $subcategory->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if ($category->hasProducts())
        <section class="site-block">
            <h3 class="site-block-header">Products</h3>
            <form action="/@@cart" method="post">
                <input type="hidden" name="_method" value="PUT" />
                {{ csrf_field() }}
                <table class="table table-hover table-condensed category-products">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Buy</th>
                    </tr>
                    </thead>

                    <tbody>
                    @each('shop.category-product', $category->products, 'product')
                    </tbody>
                </table>

                <input type="submit" value="Add to Order" />
            </form>
        </section>
    @endif

@endsection
