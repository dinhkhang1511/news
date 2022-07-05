<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::orderBy('created_at','desc')->simplePaginate(10);
        return view('admin.news')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all('*');
        return view('admin.new')->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title'     => 'unique:posts,title|required',
            'description'    => 'required',
            'content' => 'required',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ],[
            'title.required'      => 'Vui lòng nhập tên bài viết',
            'title.unique'        => 'Tên bài viết đã tồn tại',
            'description.required'     => 'Vui lòng nhập mô tả bài viết',
            'content.required'  => 'Vui lòng nội dung bài viết',
            'image.*'              => 'Ảnh không hợp lệ',
        ]);

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            try
            {
                $imageName='news_' . time().'.'.$request->image->extension();
                $request->image->move(public_path('/dist/img/post_img'),$imageName);

                Post::create([
                            'title'       =>  $request->title,
                            'description'      =>  $request->description,
                            'content'   =>  $request->content,
                            'category_id' => $request->category_id,
                            'image'      =>  $imageName,
                            'status'     => 'unpublish'

                ]);
                return redirect()->route('post.index')->with('message', 'Thêm thành công');
            }
            catch(Exception $ex)
            {
                Log::info($ex->getMessage());
                throw new $ex;
            }
        }
        return back()->with('message', 'Ảnh không hợp lệ');
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
        $categories = Category::all('*');
        $post = Post::find($id);
        if(!$post)
            return redirect()->route('post.create')->with('error','Bài viết không tồn tại');
        return view('admin.new')->with(['categories' => $categories,
                                        'post'       => $post]);
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
        $request->validate([
            'title'     => 'required|unique:posts,title,' .$id,
            'description'    => 'required',
            'content' => 'required',
            'image'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ],[
            'title.required'      => 'Vui lòng nhập tên bài viết',
            'title.unique'        => 'Tên bài viết đã tồn tại',
            'description.required'     => 'Vui lòng nhập mô tả bài viết',
            'content.required'  => 'Vui lòng nội dung bài viết',
            'image.*'              => 'Ảnh không hợp lệ',
        ]);

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            try
            {
                $imageName='news_' . time().'.'.$request->image->extension();
                $request->image->move(public_path('/dist/img/post_img'),$imageName);

                Post::where('id',$id)->update([
                            'title'       =>  $request->title,
                            'description'      =>  $request->description,
                            'content'   =>  $request->content,
                            'category_id' => $request->category_id,
                            'image'      =>  $imageName
                ]);
                return redirect()->route('post.index')->with('message', 'Sửa thành công');
            }
            catch(Exception $ex)
            {
                Log::info($ex->getMessage());
                throw new $ex;
            }
        }
        else
        {
            try
            {
                Post::where('id',$id)->update([
                            'title'       =>  $request->title,
                            'description'      =>  $request->description,
                            'content'   =>  $request->content,
                            'category_id' => $request->category_id,
                ]);
                return redirect()->route('post.index')->with('message', 'Sửa thành công');
            }
            catch(Exception $ex)
            {
                Log::info($ex->getMessage());
                throw new $ex;
            }
        }
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
        Post::where('id',$id)->delete();
        return redirect()->back()->with('message','Xóa thành công');
    }

    public function publishing(Request $request)
    {
        if($request->action === 'publish')
            $action = 'publish';
        else if ($request->action === 'unpublish')
            $action = 'unpublish';
        else
            return back()->with('warning','Oops, something went wrong');
        if(is_numeric($request->id))
        {
            if(Post::find($request->id))
            {
                Post::where('id', $request->id)
                    ->update([
                    'status' =>  $action]);
            }
            return back()->with('message', 'Sửa thành công');
        }
            return back();
    }
}

