<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetches all data from the Post Model (in no particular order)
        // $posts = Post::all();

        // An example (a bad one though) of returning a single Post with index()
        // return Post::where('title','Post Two')->get();

        // Using a plain SQL query using the DB library
        // $posts = DB::select('SELECT * FROM posts');

        // Limits the amount of data Objects returned
        // $posts = Post::orderby('created_at','desc')->take(1)->get();

        // Fetches all data & orders by date created (most current first)
        // $posts = Post::orderby('created_at','desc')->get();

        // Using pagination creates a numbered scroll at the bottom of the page
        // Requires you to place a link in the targeted View: {{$posts->links()}}
        $posts = Post::orderby('created_at','desc')->paginate(10);


        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          'title' => 'required',
          'body' => 'required',
          'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle file upload
        if($request->hasFile('cover_image')) {
          // Get filename with extension
          $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
          // Get just filename
          $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
          //Get just ext
          $extension = $request->file('cover_image')->getClientOriginalExtension();
          // Filename to store
          $fileNameToStore = $filename.'_'.time().'.'.$extension;
          // Upload image
          $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
          $fileNameToStore = 'noimage.jpeg';
        };

        // Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        // Below grabs "id" of current logged in user
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        /*
          The following code redirects after Post creation and returns a message.
          It returns a message using the "/views/inc/messages.blade.php" file,
          defining the "success" variable from that file.
        */
        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $post = Post::find($id);

      // Check for correct user
      if(auth()->user()->id !== $post->user_id) {
        return redirect('/posts')->with('error', 'Unauthorized Page');
      }

      return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'title' => 'required',
          'body' => 'required',
        ]);

        // Update Post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        // Check for correct user
        if(auth()->user()->id !== $post->user_id) {
          return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return redirect('/posts')->with('success', 'Post Removed');
    }
}
