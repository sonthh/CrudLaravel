<?php

namespace App\Http\Controllers;

use App\Constant\SystemConstant;
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
        
        $articles = Article::with('category')->paginate(SystemConstant::PER_PAGE);
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
        $article->category_id = $articleForm['category_id'];
        $article->update();
        $count = Article::with('category')->where('id', '<', $article->id)->count();
        $page = (int) ($count / SystemConstant::PER_PAGE + 1);

        $request->session()->flash('message', 'Editing success!<br />' . $article->name);
        $request->session()->flash('articleId', $article->id);

        return redirect('admin/article/index?page=' . $page);
    }

    //admin/article/delete/{articleId}
    public function delete ($articleId, Request $request) {

        $deleteArticle = Article::query()->where('id', '=', $articleId)->first();
        $deleteArticle->delete();

        $page = $request->query('page');
        //xử lí trả về trang vừa mới xóa, nếu trang vừa mới xóa hết items rồi thì về trang trước đó
        $lastPage = Article::with('category')->paginate(SystemConstant::PER_PAGE)->lastPage();
        if ($page > $lastPage && $page != 1)
            --$page;
        $request->session()->flash('message', 'Deletion success<br />' . $deleteArticle->name);
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

    public function update () {
       Article::query()->update([
           'name' => 'CRUD with Laravel Framework',
           'description' => 'CRUD with Laravel Framework',
           'content' => 'CRUD with Laravel Framework',
           'slug' => 'crud-with-laravel-framework',
       ]);
    }
}
