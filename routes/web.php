<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Model\Post;
use App\User;
//Eloquent Relationships
Route::get('post/{id}/user', function($id){//reverse one to one
    //return Post::find($id)->user->name;
    $u=Post::find($id)->user;
   return "name ".$u->name ." email ".$u->email ." date time ".$u->created_at;
});
Route::get('user/{id}/post', function($id){//one to one
    return User::find($id)->post->title;
    //return User::find($id)->post;
    //return User::find($id)->post->content;
});

//Eloquent Database ORM
Route::get('basicinsert', function(){
    $post =new Post;
    $post->title = 'New Eloquent title inserts here';
    $post->content = 'Eloquent is not difficult';
    $post->save();
});
Route::get('basicinsertupdate', function(){
    $post = Post::find(1);
    $post->title = 'New Eloquent title inserts 2';
    $post->content = 'Eloquent is easy';
    $post->save();
});
Route::get('createmass', function(){
    Post::create(['title'=>'the create mass assignment',
                    'content'=>'Here is the mass assignment creation']);
});
Route::get('/readall', function(){
    $posts=Post::all();
    foreach($posts as $post){
        echo "Read title " . $post->time ." content " .$post->content ."<br>";
    }
    });
Route::get('/find', function(){
 $post=Post::find(2);
 return "This is title: ". $post->title;
});

Route::get('/findwhere', function(){
    $posts = Post::where('id', 1)->orderBy('id', 'desc')->take(1)->get();
    foreach($posts as $post){
    echo $post->title . $post->content . $post->id ;
    }
    //return $posts;
});
Route::get('findmore', function(){
    //$posts=Post::where('id', 1)->firstOrFail();    //$posts= Post::findOrFail(1);
    //$posts=Post::where('id', 2)->firstOrFail(); $posts= Post::findOrFail(2);
    //$posts=Post::where('id', 3)->firstOrFail(); $posts= Post::findOrFail(3);
    $posts=Post::where('title', 'New Eloquent title inserts 2')->firstOrFail();
    //$posts= Post::findOrFail(3);
    return $posts;
});
Route::get('updateeloquent', function(){
    Post::where('id', 3)->where('title', 'the create mass assignment')
    ->update(['content'=>'This is updated from Eloquent1',
    'title'=> 'the create mass assignment Eloquent1'])  ;
});
Route::get('deleteeloquent', function(){
    $post=Post::find(4);
    $post->delete();
});
Route::get('destroyeloquent', function(){
    //Post::destroy(3);
    Post::destroy([1,2,3]);
    //Post::where('id','3')->delete();
});
Route::get('softdelete', function(){
   Post::find(6)->delete();
});
Route::get('readsoftdelete', function(){
    $posts= Post::onlyTrashed()->get(); // $posts= Post::withTrashed()->where('id', 5)->get();
    foreach($posts as $post){ //return $post;
        echo "Read title: " . $post->time ." content: ".$post->content . " deleted at: " .$post->deleted_at."<br>";
    }
});
Route::get('restore', function(){
    Post::withTrashed()->where('id', 6)->restore();
    //Post::withTrashed()->restore();
});
Route::get('forcedelete', function(){
    Post::withTrashed()->where('id', 5)->forceDelete();
    //Post::onlyTrashed()->forceDelete();
});
Route::get('/', function () {
    return view('welcome');
});
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
//Database Raw SQL Queries
Route::get('/read', function(){
    $results = DB::select('select * from users');
    //$results = DB::select('select * from users where id = :id', ['id' => 9]);
    //$results = DB::select('select * from users where id= ?',[2]);
    /**  * return view('user.index', ['users' => $users]);  *compact($users); ['users' => $users]
       * }*/
      foreach ($results as $user) {
        echo "The name is ".$user->name . " and email is " .$user->email . "<br>";
      }
      //return var_dump($results); //$results is the class, so that object like $results->name, $results->email ...
});
Route::get('rawdb', function(){
    $posts = DB::table('posts')->whereDate('created_at', '2020-5-18')->get();
    //return $posts;
    foreach($posts as $post){
        echo "Created At 2020-05-18 is " .$post->title ."<br>";
    }
});
Route::get('/insert', function(){
DB::insert('insert into users (name, email,  password) values (?,?,?)', ['visoth', 'visoth@gmail.com', '12345']);
});
Route::get('/update', function(){
    $affected = DB::update('update users set name = "Leang" where id = ?', ['1']);
    return "There is ". $affected . " updated."; //$affected is the updated number
});
Route::get('/delete', function(){
    $deleted = DB::delete('delete from users where id =?', [1]);
    return "There is ". $deleted ." deleted.";//$deleted is the deleted number
});
Route::get('/drop', function(){
    DB::statement('drop table users');
});

