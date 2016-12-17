@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section class="site-block">
        <h1 class="site-block-header">Shopping Cart</h1>
        {{ Form::open(['url'=>'/@cart', 'method'=>'put']) }}
        {{ Form::token() }}

        <table class="table table-hover table-condensed category-products">
            <thead>
            <tr>
                <th>Quantity</th>
                <th>Product</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            </thead>

            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <input name="quantities[{{ $item['product']->id }}]" type="number" step="1"
                               title="{{ $item['product']->name }}" value="{{ $item['quantity'] }}" />
                    </td>
                    <td>{{ $item['product']->name }}</td>
                    <td>{{ sprintf('%0.2f', $item['product']->price) }}</td>
                    <td>{{ sprintf('%0.2f', $item['subtotal']) }}</td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <b>Total: </b><br />
                    {{ sprintf('%0.2f', $totalPrice) }}
                </td>
            </tr>
            </tfoot>
        </table>

        {{ Form::submit('Update Cart') }}
        {{ Form::close() }}
    </section>
@endsection
