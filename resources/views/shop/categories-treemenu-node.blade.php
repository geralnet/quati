<?php $subcategories = $category->subcategories(); ?>
<li><a href="{{ $category->getKeywordPath() }}">{{ $category->name }}</a></li>
@if (count($subcategories) > 0)
    <ul>
        @each('shop.categories-treemenu-node', $subcategories, 'category')
    </ul>
@endif
