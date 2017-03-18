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

        {{ Form::open(['url'=>'/@checkout/address', 'method'=>'post']) }}
        {{ Form::token() }}

        <textarea name="address" style="width: 100%;" rows="5"></textarea><br />

        {{ Form::submit('Continue') }}
        {{ Form::close() }}
    </section>
@endsection
