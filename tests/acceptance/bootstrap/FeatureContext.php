<?php
use App\Models\Product\Product;
use App\Models\Product\Category;
use Behat\MinkExtension\Context\MinkContext;

if (false) {
    echo "a";
}
/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext {
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct() {
    }

    /**
     * @Given /^there are categories and products$/
     */
    public function thereAreCategoriesAndProducts() {
        $categoryA = new Category(['name' => 'Category A']);
        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent = $categoryA;
        $categoryB = new Category(['name' => 'Category B']);

        $productA1 = new Product(['name' => 'Product A 1']);
        $productA1->category = $categoryA;
        $productA2 = new Product(['name' => 'Product A 2']);
        $productA2->category = $categoryA;
        $productB1 = new Product(['name' => 'Product B 1']);
        $productB1->category = $categoryB;
    }
}
