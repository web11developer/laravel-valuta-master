<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleCategory;
use App\Http\Requests\Admin\ArticleRequest;
use App\Language;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{

    function checkRight()
    {
        if (Auth::user()->admin != 1) {
            if (Auth::user()->access != 'article') {
                return true;
            }
        }
        return false;
    }

    public function __construct()
    {
        view()->share('type', 'articles');
        $this->middleware('auth', ['except' => ['show', 'index', 'byCategory']]);
    }

    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(5);
//        $articles->setPath('articles/');
        $articles->title = 'Статьи';

        return view('article.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->orWhere('id',$slug)->firstOrFail();

        return view('article.view', compact('article'));
    }

    public function byCategory($articlecategory)
    {
        $articles = Article::where('article_category_id', $articlecategory->id)->orderBy('created_at', 'desc')->paginate(5);
//        $articles->setPath('articles/');
        $articles->title = $articlecategory->title;
        $function_text = function ($content) {
            $string = substr($content, 0, strpos($content, "</p>") + 4);
            return $string;
        };
        return view('article.by_category_view', compact('articles', 'function_text'));
    }

    public function create()
    {
        if ($this->checkRight()) {
            return redirect('home/');
        }

        $languages = Language::pluck('name', 'id')->toArray();
        $articlecategories = ArticleCategory::pluck('title', 'id')->toArray();
        return view('article.create_edit', compact('languages', 'articlecategories'));
    }

    public function store(ArticleRequest $request)
    {
        if ($this->checkRight()) {
            return redirect('home/');
        }
        $article = new Article($request->except('image'));
        $article->user_id = Auth::id();
        $article->save();
        return redirect('article');
    }

    public function edit(Article $article)
    {
        if ($this->checkRight()) {
            return redirect('home/');
        }
        $languages = Language::pluck('name', 'id')->toArray();
        $articlecategories = ArticleCategory::pluck('title', 'id')->toArray();
        return view('article.create_edit', compact('article', 'languages', 'articlecategories'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        if ($this->checkRight()) {
            return redirect('home/');
        }
        $article->user_id = Auth::id();
        $article->update($request->except('image'));
        return redirect('article/' . $article->slug);
    }

    public function delete(Article $article)
    {
        if ($this->checkRight()) {
            return redirect('home/');
        }
        return view('article.delete', compact('article'));
    }

    public function destroy(Article $article)
    {
//        if ($this->checkRight()) {
        if (Auth::user()->admin != 1) {
            return redirect('home/');
        }
        $article->delete();
        return redirect('article');
    }

}
