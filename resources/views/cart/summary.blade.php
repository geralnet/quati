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
    @forelse ($items as $item)
        <tr>
            <td>
                @if ($static)
                    {{ $item['quantity'] }}
                @else
                    <input name="quantities[{{ $item['product']->id }}]" type="number" step="1"
                           title="{{ $item['product']->name }}" value="{{ $item['quantity'] }}" />
                @endif
            </td>
            <td>{{ $item['product']->name }}</td>
            <td>{{ sprintf('%0.2f', $item['product']->price) }}</td>
            <td>{{ sprintf('%0.2f', $item['subtotal']) }}</td>
        </tr>
    @empty
        <tr>
            <th colspan="4">
                Your shopping cart is empty.
            </th>
        </tr>
    @endforelse
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
