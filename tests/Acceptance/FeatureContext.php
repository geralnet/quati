<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use app\Models\Shop\KeywordGenerator;
use App\Models\Shop\Product;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\DatabaseTransactions;
use Tests\Unit\Models\Shop\CategoryTest;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext {
    use DatabaseTransactions;

    /** @var Category[] */
    private $categories = [];

    /** @var Product[] */
    private $products = [];

    /**
     * @Given /^I am on "([^"]*)" category page$/
     */
    public function iAmOnCategoryPage($category) {
        if (array_key_exists($category, $this->categories)) {
            $url = $this->categories[$category]->getKeywordPath();
        }
        else {
            $url = '/'.KeywordGenerator::fromName($category);
        }
        $this->visit($url);
    }

    /**
     * @Given /^I am on "([^"]*)" product page$/
     */
    public function iAmOnProductPage($product) {
        $this->visit($this->products[$product]->getKeywordPath());
    }

    /**
     * @Then /^I can see "([^"]*)" in the shopping cart block$/
     */
    public function iCanSeeInTheShoppingCartBlock($text) {
        $this->assertElementContainsText('.shopping-cart', $text);
    }

    /**
     * @When /^I change the quantity of "([^"]*)" to "([^"]*)"$/
     */
    public function iChangeTheQuantityOfTo($product, $quantity) {
        $id = $this->products[$product]->id;
        $this->fillField("quantities[{$id}]", $quantity);
    }

    /**
     * @When /^I follow "([^"]*)" in the category tree$/
     */
    public function iFollowInTheCategoryTree($link) {
        $link = $this->fixStepArgument($link);
        $found = $this->getSession()->getPage()->find('css', '.category-tree')
                      ->findLink($link);

        if (is_null($found)) {
            throw new ElementNotFoundException($this->getSession(), 'link', 'id|title|alt|text', $link);
        }

        $found->click();
    }

    /**
     * @When /^I follow "([^"]*)" in the main view$/
     */
    public function iFollowInTheMainView($link) {
        $link = $this->fixStepArgument($link);
        $found = $this->getSession()->getPage()->find('css', '.site-main')
                      ->findLink($link);

        if (is_null($found)) {
            throw new ElementNotFoundException($this->getSession(), 'link', 'id|title|alt|text', $link);
        }

        $found->click();
    }

    /**
     * @When /^I press the "([^"]*)" block$/
     */
    public function iPressTheBlock(string $block) {
        $class = str_replace(' ', '-', strtolower($block));
        $locator = ".{$class} > a.site-block";
        $found = $this->getSession()->getPage()->find('css', $locator);
        if (is_null($found)) {
            throw new ElementNotFoundException($this->getSession(), 'link', 'css', $locator);
        }
        $found->click();
    }

    /**
     * @Then /^I should be in the "([^"]*)" page$/
     */
    public function iShouldBeInThePage($page) {
        if ($page == 'Shopping Cart') {
            $url = '/@cart';
        }
        else {
            $url = '/@'.str_replace(' ', '-', strtolower($page));
        }
        $this->assertSession()->addressEquals($this->locatePath($url));
        $this->assertSession()->statusCodeEquals(200);
    }

    /**
     * @Then /^I should see "([^"]*)" in the category tree$/
     */
    public function iShouldSeeInTheCategoryTree($text) {
        $this->assertElementContainsText('.category-tree', $text);
    }

    /**
     * @Then /^I should see "([^"]*)" in the main view$/
     */
    public function iShouldSeeInTheMainView($text) {
        $this->assertElementContainsText('.site-main', $text);
    }

    /**
     * @Given /^there are the following categories:$/
     */
    public function thereAreTheFollowingCategories(TableNode $table) {
        $this->categories[''] = Category::getRoot();
        foreach ($table->getHash() as $row) {
            $name = $row['Category'];
            $parent = $this->categories[$row['Parent']];
            $this->categories[$name] = CategoryTest::createSubcategory($parent, ['name' => $name]);
        }
    }

    /**
     * @Given /^there are the following products:$/
     */
    public function thereAreTheFollowingProducts(TableNode $table) {
        foreach ($table->getHash() as $row) {
            $name = $row['Product'];
            $category = $this->categories[$row['Category']];
            $this->products[$name] = Product::createInCategory($category, [
                'name'  => $name,
                'price' => $row['Price'],
            ]);
        }
    }

    /**
     * @Given /^there is a "([^"]*)" category$/
     */
    public function thereIsACategory($name) {
        $this->categories[$name] = Category::createInRoot(['name' => $name]);
    }
}
