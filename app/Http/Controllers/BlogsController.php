<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;

class BlogsController extends Controller
{

    public $user_id = '';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user_id =  auth()->user() ?  auth()->user()->id : null;
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $blogs = Blogs::all();


        $blogs = $blogs->map(function ($blog, $key) {
            $blog->tags = json_decode($blog->tags);
            $blog->description = $string = (strlen($blog->description) > 100) ? substr($blog->description, 0, 100) . '...' : $blog->description;;
            return $blog;
        });

        // dd($blogs);
        return view('blogs')->with('blogs', $blogs);
    }
    public function showHome()
    {
        $blogs = Blogs::all();


        $blogs = $blogs->map(function ($blog, $key) {
            $blog->tags = json_decode($blog->tags);
            $blog->description = $string = (strlen($blog->description) > 100) ? substr($blog->description, 0, 100) . '...' : $blog->description;;
            return $blog;
        });

        return view('welcome')->with('blogs', $blogs);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return $request->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|max:225',
            'description' => 'required|max:65535',
            'tags' => 'required'
        ]);

        $blog = ($request->id) ? Blogs::find($request->id) : new Blogs();

        if (!empty($request->image)) {
            $filenameWithExt = $request->image->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();

            $fileNameToStore =  time() . '_' . $filename . '.' . $extension;
            $request->image->move('public/blogs', $fileNameToStore);
            $blog->image_path = 'public/blogs/' . $fileNameToStore;
        }

        $blog->user_id = $this->user_id;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->tags = is_array($request->tags) ? json_encode($request->tags) : json_encode([]);
        $blog->save();

        return redirect('all-blogs');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $blog = Blogs::findOrfail($id);
        $blog->tags = json_decode($blog->tags);
        // dd($blog);
        return view('dashboard')->with('blog', $blog);
        // dd($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $blog = Blogs::findOrfail($id);
        $blog->delete();
        // dd($request->all());
        return redirect('all-blogs');
    }
}
