<?php

use App\Models\Product\Category;
use App\Models\Product\Product;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\DatabaseTransactions;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext {
    use DatabaseTransactions;

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
        $categoryA->save();

        $categoryAA = new Category(['name' => 'Category AA']);
        $categoryAA->parent()->associate($categoryA);
        $categoryAA->save();

        $categoryB = new Category(['name' => 'Category B']);
        $categoryB->save();

        $productA1 = new Product(['name' => 'Product A 1']);
        $productA1->category()->associate($categoryA);
        $productA1->save();

        $productA2 = new Product(['name' => 'Product A 2']);
        $productA2->category()->associate($categoryA);
        $productA2->save();

        $productB1 = new Product(['name' => 'Product B 1']);
        $productB1->category()->associate($categoryB);
        $productB1->save();
    }

    /**
     * @Then /^I should see the main categories and its products$/
     */
    public function iShouldSeeTheMainCategoriesAndItsProducts() {
        $this->assertPageContainsText('Category A');
        $this->assertPageContainsText('Category B');
        $this->assertPageContainsText('Product A 1');
        $this->assertPageContainsText('Product A 2');
        $this->assertPageContainsText('Product B 1');
    }
}
