<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Model\Post;
use App\User;
use App\Country;
use App\Photo;
use App\Address;
use App\Role;
use App\Staff;
use App\Tag;
use App\Video;
use Carbon\Carbon;
//disqus
Route::get('showcomment', function () {
return view('commentofdisqus');
});
//WYSIWYG
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {//allow all web
  //'middleware' => ['web', 'auth']], function () { all web but need to login as multi-users
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::get('editor', function () {
return view('showeditor');
});
//LaravelCollective
Route::get('LaravelCollective', function () {
return view('testColective');
});
//more model manipulation
//scope
Route::get('scopeController', 'PostController@index');
//Mutator
Route::get('setname', function () {
    $user =User::find(1);
    $user->name='Mr.leang';
    $user->save();
});
//Accessor
Route::get('getnameandemail', function () {
    $user = User::find(1);
    echo $user->name .'<br>'. $user->email;
});
//Carbon
Route::get('dates', function () {
    $date = new DateTime('now');//+1 week
    echo $date->format('d-m-y');
    echo '<br>';
    echo Carbon::now()->format('d-m-y');
    echo '<br>';
    echo Carbon::now()->subMonth()->diffForHumans();
    echo '<br>';
    echo Carbon::now()->addMonth()->diffForHumans();
    echo '<br>';
    echo Carbon::now()->nextWeekday()->diffForHumans();
});
//polymorphic Many to Many relationship CRUD
Route::get('deletepmm', function () {
    $post =Post::find(8);
    foreach($post->tags as $tag){
     $tag->whereId(1)->delete();
    }
});
Route::get('updatepmm', function () {
    $post =Post::findOrFail(1);
    foreach($post->tags as $tag){
        $tag->whereId(2)->update(['name'=>'update name in tags table with ID 2']);
       $tag->whereName('php')->update(['name'=>'update name']);
    }
    //$ta =Tag::find(2);
    //$post->tags()->save($ta); //insert into taggable table
    //$post->tags()->attach($ta);//insert into taggable table
    //$post->tags()->sync(['5']);
});

Route::get('readpmm', function () {
    $post =Post::findOrFail(1);
    foreach($post->tags as $tag){
        echo $tag->name;
    }
});


Route::get('createpmm', function () {
$post =Post::create(['user_id'=>2, 'title'=>'Create Poly N-N', 'content'=>'This is Poly NN content']);
$tagP=Tag::find(1);
$post->tags()->save($tagP); echo 'P and T is done';

$video =Video::create(['name'=>'VideoPolyNN.mov']);
$tagV = Tag::find(2);
$video->tags()->save($tagV); echo 'V and T is done';
});
//polymorphic relationship CRUD
Route::get('unassignpolymtm', function () {
    $staff = Staff::findOrFail(1);
    $staff->photos()->whereId(9)->update(['imageable_id'=>'0','imageable_type'=>'']);
    echo 'blank the imageable_id and imageable_type in photos table';
});
Route::get('assignpolymtm', function () {
    $staff =Staff::findOrFail(2);
    $photo =Photo::findOrFail(10);
    $staff->photos()->save($photo);
    echo 'assignpolymtm is done';
});
Route::get('deletepolymtm', function () {
    $staff = Staff::findOrFail(1);
    //$staff->photos()->delete();
    //$staff->photos()->whereId(4)->delete();
    //$staff->photos()->wherePath('example.jpg')->delete();
    //$staff->photos()->where('Path','example.jpg')->delete();
    $staff->photos()->where('Path','=','example.jpg')->delete();
    echo 'delete is done';
});
Route::get('updatepolymtm', function () {
    $staff = Staff::findOrFail(1);
    $photo=$staff->photos()->whereId(4)->first();
    $photo->path='update example.jpg';
    $photo->save(); //or $photo->update();
    echo 'update is done';
});
Route::get('readpolymtm', function () {
    $staff = Staff::findOrFail(1);
    foreach($staff->photos as $photo){
        echo $photo->path;
    }
});
Route::get('createpolymtm', function () {
    $staff =Staff::find(1);
    $staff->photos()->create(['path'=>'example.jpg']);
});
//Many to Many CRUD
Route::get('sync', function () {//attach role to use
    $user = User::findOrFail(1);
    $user->roles()->sync([1,2,3]);
});
Route::get('detach', function () { //delete all attach
     $user = User::findOrFail(2);
     //$user->roles()->detach(3);
     $user->roles()->detach(); // unattach all
});
Route::get('attach', function () {//attach roles to create role_user table
    $user = User::findOrFail(2);
    $user->roles()->attach(2);
});
Route::get('deletemtm', function () {
    $user =User::findOrFail(1);
    foreach($user->roles as $role){
        $role->whereId(4)->delete(); //softdelete if using
        //$role->whereId(4)->forceDelete();//drop record if using
        //$role->delete(); delete all records if using
        echo 'delete is done';
    }
});
Route::get('updatemtm', function () {
$user =User::findOrFail(1);
if($user->has('roles')){
    foreach($user->roles as $role){
        if($role->name =='suppervisor'){
            $role->name='manager';
            $role->save();
            echo 'update is done';
        }
    }
}
});
Route::get('readmtm', function () {
    $user = User::findOrFail(1);
    foreach($user->roles as $role){
        echo $user->name .$role->name;
    //dd($role); dd($user->roles);
}
});
Route::get('createmtm', function () {
    $user = User::find(2);
    $role = new Role(['name'=>'Analysis']);
    $user->roles()->save($role);
    echo 'data is inserted';
});
//One to Many CRUD
Route::get('deleteonetomany', function () {
    $user = User::find(1);
    //$user->posts()->whereId(7)->delete(); //softdelete
    $user->posts()->whereId(7)->forceDelete();//drop record
    //$user->posts()->delete(); delete all records
    echo 'delete is done with forceDelete()';
});
Route::get('updateonetomany', function () {
    $user = User::find(1);
    $user->posts()->whereId(7)->update(['title'=>'update one to many with ID 7',
                            'content'=>'content one to many update with ID 7']);
    echo 'Update is done';
});
Route::get('readonetomany', function () {
    $user = User::findOrFail(1);
    foreach($user->posts as $post){
        echo $post->title ."<br>";
    }
    //echo $user->posts;//will returns all object as array
    // echo $user->post; //will returns one row object as array
});
Route::get('createonetomany', function () {
    $user = User::findOrFail(1);
    $post =new Post(['title'=>'One to Many', 'content'=>'Insert to One to Many']);
    $user->posts()->save($post);
    echo 'one to many is inserted the data';
});
//One to One CRUD
Route::get('deleteonetoone', function () {
    //$user = User::findOrFail(1);
    //$user->address()->delete();
    $user = Address::findOrFail(3);
    $user->delete();
    echo 'delete is done with user id';
});
Route::get('readonetoone', function(){
    $user = User::findOrFail(1);
    echo $user->address->name;
});
Route::get('updateonetoone', function () {
    //$address = Address::whereUserId(1)->first();//return first match object of UserId
    //$address = Address::where('id', 2)->first();//use address id
    $address = Address::where('id','=', 2)->first();//use address id
    $address->name='Address 2 is updated now';
    $address->update();//$address->save();
    echo "updated done";
});
Route::get('insertonetoone', function(){
    $user= User::findOrFail(1);
    //$address = new Address(['name'=>'one to one CRUD insert data']);
    $address = new Address(['name'=>'one to one second time']);
    $user->address()->save($address);
    echo "data is inserted";
});
//Polymorpic Many to Many
Route::get('tag/post', function () {
    $tag=Tag::find(2);
    foreach($tag->posts as $post){
        echo $post->title;
    }
});
Route::get('post/tag', function () {
    $post = Post::find(1);
    foreach($post->tags as $tag){
        echo $tag->name;
    }
});
//Polymorphic Relation, the reverse
Route::get('photo/{id}/post', function ($id) {
$photo = Photo::findOrFail($id);
return $photo->imageable;
});
//Polymorphic Relation
Route::get('user/photos', function(){
    $user =User::find(1);
    foreach($user->photos as $photo){
        echo $photo->path;//return $photo;
    }
});
Route::get('post/photos', function(){
    $post =Post::find(1);
    foreach($post->photos as $photo){
        echo $photo->path ."<br>";//return $photo;
    }
});
//Has Many Through Relationship
Route::get('user/country', function(){
    $country = Country::find(2);
    foreach($country->posts as $post){
        echo $post->title;
    }
});
//Accessing intermediate table/ pivot
Route::get('user/pivot', function(){
    $user =User::find(1);
    foreach($user->roles as $role){
        echo $role->pivot->created_at;
        //echo $role->pivot;
    }
});
//Eloquent Relationships
Route::get('user/{id}/role', function($id){
    $user = User::find($id);
    foreach($user->roles as $role){
        echo "User Name ".$user->name ." is ".$role->name;
        //echo $role->name;
    }
});
Route::get('posts', function(){//one to many relationship
    $user= User::find(1);
    foreach($user->posts as $post){
        echo $post->title  . $post->content ."<br>";
    }
});
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

