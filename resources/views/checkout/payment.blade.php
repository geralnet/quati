@extends('layouts.master')

@section('sidemenu')
    @include('cart.block')
    @include('shop.categories-treemenu')
@endsection

@section('content')
    <section class="site-block">
        <h1 class="site-block-header">Payment</h1>

        <label>
            <input type="radio" name="payment_type" value="deposit" />
            Bank Deposit or Transfer
        </label><br />

        Bank Bar of Foo account 12345
    </section>
@endsection
