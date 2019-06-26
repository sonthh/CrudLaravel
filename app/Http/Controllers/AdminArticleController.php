<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Util\SlugUtil;
use DemeterChain\A;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;

class AdminArticleController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //admin/article/index
    public function index () {

        $articles = Article::with('category')->paginate(4);
        return view('admin.article.index', [
            'articles' => $articles
        ]);
    }

    //admin/article/add
    public function add () {
        $categories = Category::all();
        return view('admin.article.add', [
            'categories' => $categories
        ]);
    }
    public function addPost (Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'content' => 'required'
        ]);
        $articleForm = $request->all();
        $article = new Article();
        $article->name = $articleForm['name'];
        $article->category_id = $articleForm['category_id'];
        $article->description = $articleForm['description'];
        $article->content = $articleForm['content'];
        $article->is_active = (int) $articleForm['is_active'];
        $article->slug = SlugUtil::makeSlug($article->name);
        $article->save();
        $request->session()->flash('message', 'Adding success!<br />' . $article->name);
        return redirect('admin/article/index');
    }

    //admin/article/edit/{articleId}
    public function edit ($articleId) {
        $article = Article::with('category')->where('id', '=', $articleId)->first();
        $categories = Category::all();
        $data = [
            'categories' => $categories,
            'article' => $article
        ];
        return view('admin.article.edit', $data);
    }

    //post: admin/article/edit/{articleId}
    public function editPost (Request $request, $articleId) {
        $article = Article::with('category')
                    ->where('id', '=', $articleId)
                    ->first();
        $articleForm = $request->all();
        $article->name = $articleForm['name'];
        $article->description = $articleForm['description'];
        $article->content = $articleForm['content'];
        $article->is_active = (int) $articleForm['is_active'];
        $article->slug = SlugUtil::makeSlug($article->name);
        $article->update();
        $count = Article::with('category')->where('id', '<', $article->id)->count();
        $page = (int) ($count / 4 + 1);
        $request->session()->flash('message', 'Editing success!<br />' . $article->name);
        return redirect('admin/article/index?page=' . $page);
    }

    //admin/article/delete/{articleId}
    public function delete ($articleId, Request $request) {

        $page = $request->query('page');
        Article::query()->where('id', '=', $articleId)->delete();

        $lastPage = Article::with('category')->paginate(4)->lastPage();
        if ($page > $lastPage && $page != 1)
            --$page;
        return redirect('admin/article/index?page=' . $page);
    }

    //admin/article/delete/{articleId}
    public function toggleStatus (Request $request) {
        $articleId = $request->get('articleId');
        $articleStatus = $request->get('articleStatus');
        $article = Article::query()->where('id', '=', $articleId)->first();
        $article->is_active = !$articleStatus;
        $article->update();
        return 'success';
    }
}
