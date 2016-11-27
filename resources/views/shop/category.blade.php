@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section class="site-block">
        <h1 class="site-block-header">{{ $category->name }}</h1>
        <div>{!! $category->description  !!}</div>
    </section>
    <section class="site-block">
        <h3 class="site-block-header">Subcategories</h3>
        <div>
            <ul class="subcategories">
                @foreach($category->subcategories() as $subcategory)
                    <li><a href="{{ $subcategory->getKeywordPath() }}">{{ $subcategory->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </section>
    <section class="site-block">
        <h3 class="site-block-header">Products</h3>
        <div>
            <ul>
                @foreach($category->products as $product)
                    <li><a href="{{ $product->getKeywordPath() }}">{{ $product->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
