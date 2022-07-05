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
        for($page = 1; $page <= 5; $page++)
        {
            $url = 'https://vnexpress.net/giai-tri-p'.$page;
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler->filter('.list-news-subfolder div.thumb-art a')->each(
                function (Crawler $node,$i=0) {
                    $dt = Carbon::now();
                    $client = new Client();
                    try{
                        $crawler = $client->request('GET', $node->attr('href'));

                    $post = new Post();
                    $post->title        = $crawler->filter('h1.title-detail')->text();
                    $post->category_id  = Category::where('name','giải trí')->first()->id;
                    if(!$post->category_id)
                        dd("không tồn tại category");
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
                }catch(Exception $ex){return;}
                }
            );
        }
        dump('Done!!!');
    }

    public function crawlCategories()
    {
        $url = 'https://vnexpress.net';
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $list = $crawler->filter('nav li')->each(
            function (Crawler $node) {

                if($this->filterCategories(['home','newlest','podcast','tamsu'],$node->attr('class')))
                {
                    $name_category = $node->filter('a')->attr('title');
                    if($name_category===null)
                        return;
                    Category::updateOrCreate([
                        'name' => $name_category
                    ]);
                }
            }
        );
        // return redirect()->
    }


}