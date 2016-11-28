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
                    @foreach($category->subcategories() as $subcategory)
                        <li><a href="{{ $subcategory->getKeywordPath() }}">{{ $subcategory->name }}</a></li>
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
                <table class="category-products">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Buy</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($category->products as $product)
                        <tr>
                            <td><a href="{{ $product->getKeywordPath() }}">{{ $product->name }}</a></td>
                            <td>$ {{ $product->price }}</td>
                            <td>
                                <input name="quantities[{{$product->id}}]" type="number" step="1"
                                       title="{{ $product->name }}" />
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <input type="submit" value="Add to Order" />
            </form>
        </section>
    @endif

@endsection
