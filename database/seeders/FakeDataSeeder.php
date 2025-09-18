<?php

namespace Database\Seeders;

use App\Helpers\PaymentHelper;
use App\Helpers\TestingAndDev\FakeDataSeederHelper;
use App\Models\Album;
use App\Models\Article;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Chat;
use App\Models\DownloadRecord;
use App\Models\Medium;
use App\Models\Message;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Playlist;
use App\Models\Rate;
use App\Models\Tag;
use App\Models\Track;
use App\Models\User;
use App\Models\View;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeDataSeeder extends Seeder
{
    private int $ratioCount = 1;
//    private int $ratioCount = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Medium::factory(7 * $this->ratioCount)->create();

        User::factory(3 * $this->ratioCount)->create();

        Artist::factory(2 * $this->ratioCount)->create();

        Album::factory(2 * $this->ratioCount)->create();
        Playlist::factory(2 * $this->ratioCount)->create();
        Page::factory(2 * $this->ratioCount)->create();
        Article::factory(3 * $this->ratioCount)->create();
        Track::factory(5 * $this->ratioCount)->create();

        Tag::factory(2 * $this->ratioCount)->create();
        Category::factory(2 * $this->ratioCount)->create();

        $this->makeFakeCatablesOrTagables(10 * $this->ratioCount, Category::class, Article::class);
//        $this->makeFakeCatablesOrTagables(10 * $this->ratioCount, Tag::class, Article::class);

//        Rate::factory(20 * $this->ratioCount)->create();

        Payment::factory(5 * $this->ratioCount)->create();

        View::factory(20 * $this->ratioCount)->create();
        DownloadRecord::factory(10 * $this->ratioCount)->create();

        $this->makeFakeBookmarks(10 * $this->ratioCount);

        Chat::factory(5 * $this->ratioCount)->create();
        Message::factory(50 * $this->ratioCount)->create();
    }

    private function makeFakeBookmarks(int $count){
        for ($i = 0; $i < $count; $i++){
            $type = ['Playlist', 'Album', 'Article', 'Track'][rand(0, 3)];
            $model = "App\\Models\\" . $type;

            $modelId = $model::inRandomOrder()->first()->id;
            $userId = User::inRandomOrder()->first()->id;

            DB::table('bookmarkables')->insert([
                'user_id' => $userId,
                'bookmarkable_type' => $model,
                'bookmarkable_id' => $modelId,
            ]);
        }
    }

    private function makeFakeCatablesOrTagables(int $count, string $tagOrCatNamespace, string $productOrArticleNamespace){
        for ($i = 0; $i < $count; $i++){
            $productOrArticle = $productOrArticleNamespace::inRandomOrder()->first();
            $catOrTag = $tagOrCatNamespace::inRandomOrder()->first();

            if ($tagOrCatNamespace === Category::class){
                DB::table('catables')->insert([
                    'category_id' => $catOrTag->id,
                    'catable_id' => $productOrArticle->id,
                    'catable_type' => $productOrArticleNamespace,
                ]);
            }else{
                DB::table('taggables')->insert([
                    'tag_id' => $catOrTag->id,
                    'taggable_id' => $productOrArticle->id,
                    'taggable_type' => $productOrArticleNamespace,
                ]);
            }

        }

    }

}
