<li><a href="{{ $category->getUrl() }}">{{ $category->name }}</a></li>
@if ($category->hasSubcategories())
    <ul>
        @each('shop.categories-treemenu-node', $category->getSubcategories(), 'category')
    </ul>
@endif
