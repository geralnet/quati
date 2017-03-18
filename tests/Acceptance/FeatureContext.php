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
     * @Given /^I am not signed in$/
     */
    public function iAmNotSignedIn() {
        $this->visit('/');
        $this->assertPageContainsText('You are not signed in.');
    }

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
            case 'sign up':
                $this->visit('/@auth/signup');
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
     * @Given /^I am signed in as a user$/
     */
    public function iAmSignedInAsAUser() {
        User::create([
            'name'     => 'Test User',
            'email'    => 'user@quati.test',
            'password' => bcrypt('password'),
        ]);
        $this->iSignInAsTheSameUserAgain();
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
     * @Given /^I go to the shopping cart page$/
     */
    public function iGoToTheShoppingCartPage() {
        $this->iAmOnTheShoppingCartPage();
    }

    /**
     * @Given /^I have "([^"]*)" "([^"]*)" in my shopping cart$/
     */
    public function iHaveInMyShoppingCart($quantity, $product) {
        if (!array_key_exists($product, $this->products)) {
            $this->createProduct($product, 100, 'Product Category');
        }
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
     * @Then /^I should be on the sign in page$/
     */
    public function iShouldBeOnTheSignInPage() {
        $this->assertPageAddress('/@auth/signin');
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
     * @When /^I sign in as a user$/
     */
    public function iSignInAsAUser() {
        $this->iAmSignedInAsAUser();
    }

    /**
     * @When /^I sign in as the same user again$/
     */
    public function iSignInAsTheSameUserAgain() {
        $this->iAmOnThePage('sign in');
        $this->fillField('email', 'user@quati.test');
        $this->fillField('password', 'password');
        $this->pressButton('Sign In');
    }

    /**
     * @When /^I sign out$/
     */
    public function iSignOut() {
        $this->pressButton('sign out');
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
        foreach ($table->getHash() as $row) {
            $this->createCategory($row['Category'], $row['Parent']);
        }
    }

    /**
     * @Given /^there are the following products:$/
     */
    public function thereAreTheFollowingProducts(TableNode $table) {
        foreach ($table->getHash() as $row) {
            $this->createProduct(
                $row['Product'],
                $row['Price'],
                $row['Category']
            );
        }
    }

    /**
     * @Given /^there is a "([^"]*)" category$/
     */
    public function thereIsACategory($name) {
        $this->categories[$name] = Category::createInRoot(['name' => $name]);
        Path::createForComponent($this->categories[$name]);
    }

    private function createCategory($name, $parent = '') {
        if (($name == '') && ($parent == '')) {
            $this->categories[''] = Category::getRoot();
            return;
        }

        if (!array_key_exists($parent, $this->categories)) {
            $this->createCategory($parent);
        }
        $parent = $this->categories[$parent];
        $this->categories[$name] = CategoryTest::createWithPath(
            ['name' => $name],
            $parent
        );
    }

    private function createProduct($name, $price, $category) {
        if (!array_key_exists($category, $this->categories)) {
            $this->createCategory($category);
        }
        $category = $this->categories[$category];
        $this->products[$name] = ProductTest::createWithPath([
            'name'  => $name,
            'price' => $price,
        ], $category);
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
