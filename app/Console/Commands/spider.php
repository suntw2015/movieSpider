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
        $host = 'https://www.ygdy8.net/html/gndy/dyzz/index.html';
        $rules = [
            'name' => ['a', 'text'],
            'link' => ['a', 'href'],
        ];
        $range = '.co_content8>ul>table';
        $data = QueryList::get($host)->rules($rules)->range($range)->encoding('UTF-8','GB2312')->queryData();
        print_r($data);
    }
}
