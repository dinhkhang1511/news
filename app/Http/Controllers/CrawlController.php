<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlController extends Controller
{
    //
    public function filterCategories($filters,$name)
    {
        foreach($filters as $item)
        {
            if($name === $item)
                return false;
        }
        return true;
    }

    public function crawl()
    {
        ini_set('max_execution_time', '0');
        $categories = Category::all('*');
        if(!$categories)
            dd('Vui lòng cào thể loại trước.');
        foreach($categories as $category)
        {
            dump('Bắt đầu cào thể loại '.$category->name.'...');
            for($page = 1; $page <= 5; $page++)
            {
                $countDuplicate = 0;
                $url = "https://vnexpress.net$category->slug-p".$page;
                $client = new Client();
                $crawler = $client->request('GET', $url);
                $crawler->filter('.list-news-subfolder div.thumb-art a')->each(
                    function (Crawler $node) use($category,&$countDuplicate) {
                        if($countDuplicate >= 10) // Check cào trùng bài viết 10 lần thì thoát khỏi hàm each()
                        {
                            return;
                        }
                        $dt = Carbon::now();
                        $client = new Client();
                        try{
                            $crawler = $client->request('GET', $node->attr('href'));
                            $post = new Post();
                            $post->title        = $crawler->filter('h1.title-detail')->text();
                            $post->category_id  = $category->id;
                            $post->status       = 'unpublish';
                            $post->description  = $crawler->filter('p.description')->text();
                            $post->link         = $node->attr('href');
                            $post->content      = $crawler->filter('article.fck_detail')->html();
                            $post->image        = $crawler->filter('article.fck_detail img')->attr('data-src');
                            if(!$post->image)
                            {
                                $post->image        = $crawler->filter('article.fck_detail img')->attr('src');
                            }
                            $date = $crawler->filter('span.date')->text();
                            $date = trim(explode(',',$date)[1]);
                            $humanDate = explode('/',$date); // DD/MM/YYYY
                            $dt->day = $humanDate[0];
                            $dt->month = $humanDate[1];
                            $dt->year = $humanDate[2];
                            $post->created_at = $dt->toDateString();
                            $post->updated_at = $dt->toDateString();
                            $post->save();
                            dump($post->title);
                            Log::info($post->title);
                            sleep(3);
                        }catch(Exception $ex){
                            $countDuplicate++;
                            dump('Bài viết đã tồn tại hoặc sai định dạng - ' . $countDuplicate);
                            // sleep(1); // Có thể để sleep cho an toàn tránh bị chặn rq
                        }

                    }
                );
                if($countDuplicate >=10 || $page === 5 )
                {
                    dump('Chuyển qua thể loại tiếp theo');
                    break;
                }
            }
        }
        ini_set('max_execution_time', '120');
        dump('Done!!!');
    }

    public function crawlCategories()
    {
        $url = 'https://vnexpress.net';
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $crawler->filter('nav li')->each(
            function (Crawler $node) {

                if($this->filterCategories(['home','newlest','video','podcasts','tamsu'],$node->attr('class')))
                {
                    $name_category = $node->filter('a')->attr('title');
                    $slug          = $node->filter('a')->attr('href');
                    if($name_category===null)
                        return;
                    Category::updateOrCreate([
                        'name' => $name_category,
                        'slug' => $slug
                    ]);
                    dump($name_category);
                }
            }
        );
        dump('Done!!!');
    }


}
