<nav class="site-block sidemenu category-tree">
    <ul>
        @each('shop.categories-treemenu-node', \App\Models\Shop\Category::getRoot()->getSubcategories(), 'category')
    </ul>
</nav>
