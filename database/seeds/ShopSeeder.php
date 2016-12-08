<?php
declare(strict_types = 1);

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use App\UploadedFile;
use Illuminate\Database\Seeder;
use Tests\Unit\Models\Shop\CategoryTest;
use Tests\Unit\Models\Shop\ProductTest;

require_once __DIR__.'/../../tests/Unit/Models/Shop/CategoryTest.php';

class ShopSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->createProductsAndCategories();
        $this->importProductImages();
    }

    private function createProductsAndCategories() {
        // FIXME remote all creators from test
        $root = Category::getRoot();
        $root->name = 'Quati Example Store';
        $root->description = 'This is an example of how a store in Quati platform looks like.';
        $root->save();

        Path::createForComponent($spaceships = CategoryTest::createInRoot([
            'name'        => 'Spaceships',
            'description' => 'We offer a great range of spaceships.<br /><br />
                              Please ensure you have a valid pilot license before purchasing.',
        ]));

        Path::createForComponent($starfighters = CategoryTest::createSubcategory($spaceships, [
            'name'        => 'Starfighters',
            'description' => 'We have a great variety of starfighters,
                              choose the one best adapts to your flying skills.',
        ]), $spaceships->path);
        Path::createForComponent(ProductTest::createInCategory($starfighters, [
            'name'        => 'Cylon Raider',
            'description' => 'The new Raiders are cybernetic in nature: the ship is actually a living creature,
                              with a complex system of organs, veins and biological fluids inside the main body
                              You may need special skills in order to interface with this machine.',
            'price'       => 19999,
        ]), $starfighters->path);
        Path::createForComponent(ProductTest::createInCategory($starfighters, [
            'name'        => 'Viper',
            'description' => 'Viper was introduced into Colonial service shortly before the outbreak of
                              the first Cylon War. However, it was the Mark II Viper series, designed specifically for
                              use with the new Colonial Battlestars, that is best remembered. The Mark II was used
                              during the Cylon War, proving a capable fighting vehicle. It is regarded as one of the
                              reasons the Twelve Colonies did not suffer defeat at the hands of the Cylons. The Mark II
                              remained in service after the end of the war, with William Adama commenting that he last
                              saw one roughly twenty years ago.',
            'price'       => 15000,
        ]), $starfighters->path);

        Path::createForComponent($wings = CategoryTest::createSubcategory($starfighters, [
            'name'        => 'Wing Series',
            'description' => 'Some of the best starfighters commonly names as a letter-wing are found here.',
        ]), $starfighters->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'A-wing',
            'description' => 'A-wings are fast but fragile Rebel Alliance starfighters conceived for reconnaissance 
                              and escort duty. A-wings from Green Squadron participate in the climactic Battle of
                              Endor depicted in Return of the Jedi (1983). At Endor, an A-wing piloted by Arvel Crynyd
                              (Hilton McRae) crashes into the bridge of the Super Star Destroyer Executor, resulting
                              in the Executor crashing out of control into the second Death Star. In addition to McRae,
                              two women recorded A-wing cockpit footage; one of the actors was cut, and the other was
                              dubbed over by a male actor.',
            'price'       => 20000,
        ]), $wings->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'B-wing',
            'description' => 'The B-wing is the largest and most powerful fighter designed by the Rebel Alliance, and
                              it is generally viewed as the successor to the older Y-wing fighter/bomber. B-wings have
                              powerful shields which are considerably stronger than the shields featured on most
                              Imperial or Rebel fighter designs, and they are armed with a greater variety of weapons.
                              B-wings participate at the Battle of Endor in Return of the Jedi and in numerous other
                              engagements throughout the Star Wars Expanded Universe.',
            'price'       => 25000,
        ]), $wings->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'E-wing',
            'description' => 'The E-wing escort starfighter was a single-pilot starfighter developed by FreiTek Inc.
                              It was the first fighter designed entirely under the support of the New Republic.
                              As designed, the E-wing was intended to match, or exceed, the performance of the
                              preceding X-wing series in nearly every respect, and was originally intended to
                              replace the older design in New Republic service. However, the craft suffered from
                              some significant problems when first deployed among front-line squadrons, including
                              malfunction issues with the laser cannons and the new R7 astromech units. As a result,
                              many pilots continued to fly upgraded versions of the venerable X-wing.',
            'price'       => 27000,
        ]), $wings->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'K-wing',
            'description' => 'A heavily armed bomber that could double as an escort or reconnaissance vessel,
                              the Rebellion’s K-wing was frequently flown on strafing runs against planetary targets
                              and slow-moving capital ships. In X-Wing the K-wing’s surprising acceleration, heavy
                              armor plating, and devastating ordnance make it an outstanding ship for hit-and-run
                              operations.',
            'price'       => 21000,
        ]), $wings->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'V-wing',
            'description' => 'The Alpha-3 Nimbus-class V-wing starfighter, often simply known as the V-wing
                              starfighter or Nimbus fighter, was a short-ranged starfighter deployed late in the
                              Clone Wars by the Galactic Republic.',
            'price'       => 22000,
        ]), $wings->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'X-wing',
            'description' => 'The X-wing is a versatile Rebel Alliance starfighter that balances speed with firepower.
                              Armed with four laser cannons and two proton torpedo launchers, the X-wing can take on
                              anything the Empire throws at it.',
            'price'       => 30000,
        ]), $wings->path);
        Path::createForComponent(ProductTest::createInCategory($wings, [
            'name'        => 'Y-wing',
            'description' => 'The Y-wing is a workhorse starfighter has been in use since the Clone Wars. Used for
                              dogfights and for bombing runs against capital ships and ground targets, Y-wings are
                              often overshadowed by newer models such as the X-wing and the A-wing. But the Y-wing\'s
                              historical importance is remarkable, and it has reliably served multiple generations of
                              star pilots.',
            'price'       => 28000,
        ]), $wings->path);

        Path::createForComponent($transportation = CategoryTest::createSubcategory($spaceships, [
            'name'        => 'Transportation',
            'description' => 'Spaceships used for transportation.',
        ]), $spaceships->path);
        Path::createForComponent(ProductTest::createInCategory($transportation, [
            'name'        => 'TARDIS',
            'description' => 'A TARDIS is a product of the advanced technology of the Time Lords, an extraterrestrial
                              civilisation to which the programme\'s central character, the Doctor, belongs. A
                              properly maintained and piloted TARDIS can transport its occupants to any point in
                              time and space. The interior of a TARDIS is much larger than its exterior. It can blend
                              in with its surroundings using the ship\'s "chameleon circuit". TARDISes also possess
                              a degree of sapience and provide their users with additional tools and abilities
                              including a universal translation system based on telepathy.',
            'price'       => 160000,
        ]), $transportation->path);
        Path::createForComponent(ProductTest::createInCategory($transportation, [
            'name'        => 'Millennium Falcon',
            'description' => 'The Millennium Falcon, originally known as YT-1300 492727ZED, was a Corellian YT-1300f
                              light freighter used by the smugglers Han Solo and Chewbacca during the Galactic Civil
                              War. It was previously owned by Lando Calrissian, who lost it to Solo in a game of
                              sabacc.',
            'price'       => 135000,
        ]), $transportation->path);

        Path::createForComponent($tools = CategoryTest::createInRoot(['name' => 'Tools']));
        Path::createForComponent(CategoryTest::createSubcategory($tools, ['name' => 'Manual']), $tools->path);
        Path::createForComponent(CategoryTest::createSubcategory($tools, ['name' => 'Power']), $tools->path);

        Path::createForComponent(CategoryTest::createInRoot(['name' => 'Devices']));
    }

    private function importProductImages() {
        foreach (Product::all() as $product) {
            if ($product->keyword == 'V-wing') {
                continue; // No image for V-wing.
            }

            $file = __DIR__.'/images/products/'.$product->keyword.'.jpg';
            $file = UploadedFile::createFromExternalFile('/images/product/'.$product->keyword.'.jpg', $file);

            $image = new ProductImage();
            $image->product()->associate($product);
            $image->file()->associate($file);
            $image->save();
        }
    }
}
