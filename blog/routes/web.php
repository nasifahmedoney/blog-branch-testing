<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentsController;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#Route::get('/', function () {
#    return view('welcome');
#});


//$post = Post::all();
//
//ddd(Post::all());
//using $with property in Post model, n+1 problem, Post-> category,author

//restful actions
//index, show, create, store, edit, update, destroy
Route::post('newsletter', function(){
    request()->validate([
        'email' => 'required|email'
    ]);//required, of type 'email'
    $mailchimp = new \MailchimpMarketing\ApiClient();

    $mailchimp->setConfig([
        'apiKey' => config('services.mailchimp.key'),
        'server' => 'us20'
    ]);
    try{
        //$response = $mailchimp->ping->get();
        $response = $mailchimp->lists->addListMember('a074cf2add',[
            'email_address' => request('email'),
            'status' => 'subscribed'
        ]);
    }
    catch(\Exception $e){
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => 'Cant add to email list'
        ]);
    }
    
    //ddd($response);
    return redirect('/')->with('success','Subscribed!');
}); 
Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('post/{post:slug}', [PostController::class, 'show'] );
Route::post('post/{post:slug}/comments', [PostCommentsController::class, 'store'] );


Route::get('register', [RegisterController::class, 'create'] )->middleware('guest');
Route::post('register', [RegisterController::class, 'store'] )->middleware('guest');
//middleware->app/Http/kernel.php

Route::get('login',[SessionsController::class, 'create'])->middleware('guest');
Route::post('login',[SessionsController::class, 'store'])->middleware('guest');

Route::post('logout',[SessionsController::class, 'destroy'])->middleware('auth');


// Route::get('categories/{category:slug}',function(Category $category)
// {
//     return view('posts',[
//         //using $with property in Post model
//         'posts' => $category->posts,
//         'currentCategory' =>$category,
//         'categories' => Category::all()
//         //'posts' => $category->posts->load('category','author')
//     ]);
// })->name('category');

// Route::get('authors/{authors:username}',function(User $authors)
// {
//     return view('posts.index',[
//         //using $with property in Post model
//         'posts' => $authors->posts,
//         'categories' => Category::all()
//     ]);
// });


    /*
    $path = __DIR__."/../resources/posts/{$slug}.html"; #path to the file
    
    if(!file_exists($path)) #redirect or abort if file not found
    {
        return redirect('/');
        //abort(404);
    }

    $post = cache()->remember("posts.{$slug}", 5, function() use($path) # save in cache for 5 secs 
    {
        //var_dump('file_get_contents');
        return file_get_contents($path);
    });

    return view('post', [
       'post' => $post
    ]);
    */


/*
Route::get('posts/{post}', function ($slug) {
    if (! file_exists($path = __DIR__ . "/../resources/posts/{$slug}.html")) {
        return redirect('/');
    }

    $post = cache()->remember("posts.{$slug}", 1200, fn() => file_get_contents($path));

    return view('post', ['post' => $post]);
})->where('post', '[A-z_\-]+');

*/
#post=cache()->remember("anyname.{$any_thing}", now()->addSecond(60),function() use ($path)
//addSeconds,mins,hours and other options
