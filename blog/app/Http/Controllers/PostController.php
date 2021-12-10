<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Category;
//use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index()
    {
        //using pageination
        return view('posts.index', [
            'posts' => Post::latest()->filter(
                request( ['search','category','author'] )
                )->paginate(5)->withQueryString()
                //simplePaginate(), alternative paginate()
                //withQueryString() keeping the category query string in the url from the previous page 
            
            ]);
        // return view('posts.index', [
        //     'posts' => Post::latest()->filter(request( ['search','category','author'] ))->get(), //filter() defined in Post model
            
        //     ]);
    }

    public function show(Post $post)
    {
        return view('posts.show',[
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    public function create()
    {
        //admin access only, without using middleware in routes 
        if(auth()->user()?->username !== 'nasif_ahmed'){
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('posts.create');
    }
}

