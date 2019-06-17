<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;

class spider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:spider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spider start';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $page = 1;

        $rules = [
            'name' => ['a.ulink', 'text'],
            'link' => ['a.ulink', 'href'],
        ];
        $range = '.co_content8>ul td>b';

        $list = [];
        for($i = 1; $i <= $page; $i++) {
            $url = sprintf("https://www.ygdy8.net/html/gndy/dyzz/list_23_%d.html", $i);
            $data = QueryList::get($url)->rules($rules)->range($range)->encoding('UTF-8','GB2312')->queryData();

            foreach ($data as $item) {
                $start = mb_strpos($item['name'], "《");
                $end = mb_strpos($item['name'], "》");
                $item['name'] = mb_substr($item['name'], $start+1, $end-$start-1);
                $list[] = [
                    'name' => $item['name'],
                    'link' => $item['link'],
                ];
            }
        }


        // $list = [
        //     [
        //         'link' => '/html/gndy/dyzz/20190617/58738.html'
        //     ]
        // ];
        $contentRule = [
            'desc' => ['#Zoom td p:first', 'text'],
            'cover' => ['#Zoom td img:first', 'src'],
            'download_url' => ['#Zoom table a', 'href']
        ];
        foreach ($list as $item) {
            $url = 'https://www.dytt8.net' . $item['link'];
            $data = QueryList::get($url)->rules($contentRule)->encoding('UTF-8','GB2312')->queryData();
            if (empty($data)) continue;
            $data = $data[0];
            $tmp = explode("◎", $data['desc']);
            $spaceChar = '　';
            $tmp = array_map(function ($item) use ($spaceChar) {
                return mb_substr($item, mb_strrpos($item, $spaceChar)+1);
                return str_replace(["\t","　"], "", $item);
            }, $tmp);
            $data['translate_name'] = $tmp[1];
            $data['english_name'] = $tmp[2];
            $data['year'] = $tmp[3];
            $data['country'] = $tmp[4];
            $data['type'] = $tmp[5];
            $data['language'] = $tmp[6];
            $data['time_long'] = $tmp[14];
            $data['desc'] = $tmp[19];

            echo sprintf("--------------------------------------------\n");
            echo sprintf("%s\n%s\n%s\n%s\n%s\n%s\n", $item['name'], $item['link'], $data['year'], $data['cover'], $data['time_long'], $data['download_url']);
        }
    }
}
