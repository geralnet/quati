<tr>
    <td>
        <img src="{{ $product->getImageURL(1, $product->keyword) }}"
             width="100" height="75" />
    </td>
    <td><a href="{{ $product->getKeywordPath() }}">{{ $product->name }}</a></td>
    <td>$ {{ $product->price }}</td>
    <td>
        <input name="quantities[{{$product->id}}]" type="number" step="1"
               title="{{ $product->name }}" />
    </td>
</tr>
