@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section class="site-block">
        <h1 class="site-block-header">Payment</h1>
    </section>
@endsection
