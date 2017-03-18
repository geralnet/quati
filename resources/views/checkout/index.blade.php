@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section class="site-block">
        <h1 class="site-block-header">Products</h1>
        @include('cart.summary', ['static' => true])
    </section>
    <section class="site-block">
        <h1 class="site-block-header">Delivery Address</h1>
    </section>
@endsection
