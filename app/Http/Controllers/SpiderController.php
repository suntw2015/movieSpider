<?php

namespace App\Http\Controllers;

use QL\QueryList;

class SpiderController extends Controller
{
    public function start()
    {
        $host = 'https://www.dytt8.net';
        $data = QueryList::get($host)->find("a");
        print_r($data->all());
    }
}