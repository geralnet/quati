<?php

use App\Models\Product\Category;
use App\Models\Product\Product;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\DatabaseTransactions;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext {
    use DatabaseTransactions;

    /**
     * @Given /^there are the following categories and products:$/
     */
    public function thereAreTheFollowingCategoriesAndProducts(TableNode $table) {
        $categories = [];
        foreach ($table->getHash() as $row) {
            $category = new Category();
            $category->name = $row['Category'];
            if ($row['Parent']) {
                $category->parent()->associate($categories[$row['Parent']]);
            }
            $category->save();
            $categories[$row['Category']] = $category;

            foreach (explode(',', $row['Products']) as $product) {
                $product = new Product(['name' => $product]);
                $product->category()->associate($category);
                $product->save();
            }
        }
    }

    /**
     * @Then /^I should see "([^"]*)" in the main view$/
     */
    public function iShouldSeeInTheMainView($text) {
        $this->assertElementContainsText('*[role="main"]', $text);
    }
}
