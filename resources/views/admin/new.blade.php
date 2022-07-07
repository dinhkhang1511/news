@include('admin.partials.header')
  <div class="row">
      <div class="col-lg-8">
          <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>{{isset($product) ? 'Sửa bài viết' : 'Thêm bài viết'}}</h2>
                </div>

                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{Session::get('success')}}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{Session::get('error')}}
                        </div>
                    @endif
                    <form method="POST" enctype="multipart/form-data" action="{{ isset($post) ? route('post.update',['post' => $post->id]) : route('post.store') }}" >
                        @csrf
                        @if(isset($post))
                            @method('PUT')
                        @endif
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <p class="alert alert-danger">{{$error}}</p>
                            @endforeach
                        @endif
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Tiêu đề bài viết</label>
                            <input type="text" class="form-control" id="title" placeholder="Tiêu đề" name='title' required
                            value="{{ isset($post->title) ? $post->title : old('title') }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Mô tả</label>
                            <textarea type="text" class="form-control" id="description" placeholder="Mô tả" name="description" required>
                                {{isset($post->description) ? $post->description : old('description') }}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect12">Thể loại</label>
                            <select class="form-control" id="category_id" name='category_id'>>
                                @if(isset($post->category_id))
                                    {{--check có đang sửa post hay ko nếu có thì select category của post đang sửa  --}}
                                    @foreach($categories as $category)
                                        <option value = "{{$category->id}}" {{$post->category_id === $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                    @endforeach
                                @else
                                    @foreach($categories as $category)
                                        <option value = "{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlPassword">Nội dung </label>
                            <textarea id="editor" name="content">
                                @if(isset($post->content))
                                    {!! $post->content !!}
                                @else
                                {{old('content')}}
                                <p>This is the editor content.</p>
                                @endif
                            </textarea>
                        <div class="form-group">
                            <label for="exampleFormControlPassword">Ảnh </label>
                            <input type="file" class="form-control" id="image" onchange="readURL(this)" name="image" >
                            @if(!isset($post->image))
                                <img id="image-preview" style="width: auto; height:100px;" src=""/>
                            @else
                                <img id="image-preview" style="width: auto; height:100px;" src="{{!str_starts_with($post->image,'news') ? $post->image : asset('dist/img/post_img'). "/" .$post->image}}"/>
                            @endif
                        </div>

                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-primary btn-default">Lưu</button>
                            <a id="cancel" type="button" href="#" onclick="window.history.back()" class="btn btn-secondary btn-default">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ">

        </div>
    </div>
    @include('admin.partials.ckeditor')
    @include('admin.partials.footer')

