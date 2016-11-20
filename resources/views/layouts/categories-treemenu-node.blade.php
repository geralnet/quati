<?php $subcategories = $category->subcategories()->get(); ?>
<li>{{$category->name}}</li>
@if (count($subcategories) > 0)
    <ul>
        @each('layouts.categories-treemenu-node', $subcategories, 'category')
    </ul>
@endif
