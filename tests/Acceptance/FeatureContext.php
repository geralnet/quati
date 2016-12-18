<?php
declare(strict_types = 1);

use App\Models\Shop\Cart;
use App\Models\Shop\Category;
use app\Models\Shop\KeywordGenerator;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use App\User;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\DatabaseTransactions;
use Tests\Unit\Models\Shop\CategoryTest;
use Tests\Unit\Models\Shop\ProductTest;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext {
    use DatabaseTransactions;

    /** @var Category[] */
    private $categories = [];

    /** @var Product[] */
    private $products = [];

    /** @var array */
    private $users = [];

    /**
     * @Given /^I am on "([^"]*)" category page$/
     */
    public function iAmOnCategoryPage($category) {
        $url = $this->categories[$category]->getUrl();
        $this->visit($url);
    }

    /**
     * @Given /^I am on "([^"]*)" product page$/
     */
    public function iAmOnProductPage($product) {
        $this->visit($this->products[$product]->getUrl());
    }

    /**
     * @Given /^I am on the "([^"]*)" page$/
     */
    public function iAmOnThePage($page) {
        switch ($page) {
            case 'sign in':
                $this->visit('/@auth/signin');
                break;
            default:
                throw new PendingException('Invalid page: '.$page);
        }
        $this->assertResponseStatus(200);
    }

    /**
     * @Given /^I am on the shopping cart page$/
     */
    public function iAmOnTheShoppingCartPage() {
        $this->visit('/@cart');
        $this->assertResponseStatus(200);
    }

    /**
     * @Given /^I am signed in as "([^"]*)"$/
     */
    public function iAmSignedInAs($name) {
        $user = $this->users[$name];

        $this->iAmOnThePage('sign in');
        $this->fillField('email', $user['email']);
        $this->fillField('password', $user['password']);
        $this->pressButton('Sign In');
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
        $this->followLinkInCSS($link, '.category-tree');
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
     * @When /^I follow "([^"]*)" in the site header$/
     */
    public function iFollowInTheSiteHeader($link) {
        $this->followLinkInCSS($link, '.site-header');
    }

    /**
     * @Given /^I have "([^"]*)" "([^"]*)" in my shopping cart$/
     */
    public function iHaveInMyShoppingCart($quantity, $product) {
        $product = $this->products[$product];
        $quantity = (int)$quantity;
        Cart::get()->addProduct($product->id, $quantity);
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
     * @Then /^I should see "([^"]*)" in the site header$/
     */
    public function iShouldSeeInTheSiteHeader($text) {
        $this->assertElementContainsText('.site-header', $text);
    }

    /**
     * @Given /^the following users exist:$/
     */
    public function theFollowingUsersExist(TableNode $users) {
        foreach ($users->getIterator() as $user) {
            $this->users[$user['name']] = $user;
            $user['password'] = bcrypt($user['password']);
            User::create($user);
        }
    }

    /**
     * @Given /^there are the following categories:$/
     */
    public function thereAreTheFollowingCategories(TableNode $table) {
        $this->categories[''] = Category::getRoot();
        foreach ($table->getHash() as $row) {
            $name = $row['Category'];
            $parent = $this->categories[$row['Parent']];
            $this->categories[$name] = CategoryTest::createWithPath(['name' => $name], $parent);
        }
    }

    /**
     * @Given /^there are the following products:$/
     */
    public function thereAreTheFollowingProducts(TableNode $table) {
        foreach ($table->getHash() as $row) {
            $name = $row['Product'];
            $category = $this->categories[$row['Category']];
            $this->products[$name] = ProductTest::createWithPath([
                'name'  => $name,
                'price' => $row['Price'],
            ], $category);
        }
    }

    /**
     * @Given /^there is a "([^"]*)" category$/
     */
    public function thereIsACategory($name) {
        $this->categories[$name] = Category::createInRoot(['name' => $name]);
        Path::createForComponent($this->categories[$name]);
    }

    private function followLinkInCSS($link, $css) {
        $link = $this->fixStepArgument($link);
        $found = $this->getSession()->getPage()->find('css', $css)
                      ->findLink($link);

        if (is_null($found)) {
            throw new ElementNotFoundException($this->getSession(), 'link', 'id|title|alt|text', $link);
        }

        $found->click();
    }
}
