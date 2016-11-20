<nav class="site-block sidemenu category-tree">
    <ul>
        @each('layouts.categories-treemenu-node', $root_categories, 'category')
    </ul>
</nav>
