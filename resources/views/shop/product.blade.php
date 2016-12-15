@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section class="site-block">
        <h1>{{ $product->name }}</h1>
        <div>
            {{ $product->description }}
            <p>
                <b>Price: </b> <i>$ {{ $product->price }}</i>
            </p>
        </div>
        {{ Form::open(['url'=>'/@cart', 'method'=>'put']) }}
        {{ Form::token() }}
        {{ Form::number('quantities['.$product->id.']', '', ['min'=>0]) }}
        {{ Form::submit('Add to Order') }}
        {{ Form::close() }}
    </section>
@endsection
