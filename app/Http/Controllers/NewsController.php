<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use News;

class NewsController extends Controller
{
    public function index(){
        News::getLatestNewsHeadlines();
    }
}
