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
        $host = 'https://www.ygdy8.net/html/gndy/dyzz/list_23_1.html';

        $page = 10;

        $rules = [
            'name' => ['a.ulink', 'text'],
            'link' => ['a.ulink', 'href'],
        ];
        $range = '.co_content8>ul td>b';

        for($i = 1; $i <= $page; $i++) {
            $url = sprintf("https://www.ygdy8.net/html/gndy/dyzz/list_23_%d.html", $i);
            $data = QueryList::get($host)->rules($rules)->range($range)->encoding('UTF-8','GB2312')->queryData();
            foreach ($data as $item) {
                printf("%30s %s", $item['name'], $item['link']);
            }
        }
    }
}
