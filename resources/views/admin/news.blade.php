@include('admin.partials.header')

  <div class="row">
    <div class="col-12">
      <!-- Recent Order Table -->
      <div class="card card-table-border-none" id="recent-orders">
        <div class="card-header justify-content-between">
          <h2>Sản phẩm</h2>
        </div>
        <div class="card-body pt-0 pb-5">
            @if(Session::has('message'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('message')}}
                </div>
            @endif
            @if(Session::has('warning'))
                <div class="alert alert-warning" role="alert">
                    {{Session::get('warning')}}
                </div>
            @endif
            <table class="table card-table table-responsive table-responsive-large" style="width:100%">
                <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên bài viết</th>
                    <th class="d-none d-md-table-cell">Mô tả</th>
                    <th class="d-none d-md-table-cell">Ngày</th>
                    <th class="d-none d-md-table-cell">Tình trạng</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                     @foreach($posts as $post)
                    <tr>
                        <td class="ml-5 d-none d-md-table-cell">
                            <img style="height:100px; width:100px;" src="{{!str_starts_with($post->image,'news') ? $post->image : asset('dist/img/post_img'). "/" .$post->image}}"/>
                        </td>
                        <td >
                        <a class="text-dark" href="{{$post->link}}"> {{ $post->title }}</a>
                        </td>
                        <td class="d-none d-md-table-cell">{{$post->description}}</td>
                        <td class="d-none d-md-table-cell">{{$post->created_at }} </td>

                        @if($post->status === 'publish')
                            <td>
                                <span class="badge badge-success">{{$post->status}}</span>
                            </td>
                        @endif
                        @if($post->status === 'unpublish')
                            <td>
                                <span class="badge badge-warning">{{$post->status}}</span>
                            </td>
                        @endif
                        @if(Auth::check())
                            <td class="text-right">
                                <div class="dropdown show d-inline-block widget-dropdown">
                                    <a class="dropdown-toggle icon-burger-mini" href="#" role="button" id="dropdown-recent-order2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-recent-order2">
                                        <li class="dropdown-item">
                                            <a href="{{route('post.edit',['post' => $post->id])}}" class="text-dark">Sửa</a>
                                        </li>
                                        <li class="dropdown-item">
                                            <form method="POST" action="{{route('post.destroy',['post' => $post->id])}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Bạn có chắc bạn muốn xóa sản phẩm này?');" class="text-dark">Xóa</button>
                                            </form>
                                        </li>
                                        <li class="dropdown-item">
                                            <form method="POST" action="{{route('publishing')}}">
                                                @csrf
                                                <input type="hidden" value="{{$post->id}}" name="id">
                                                <input type="hidden" value="{{$post->status === 'publish' ? 'unpublish' : 'publish'}}" name="action">
                                                <button type="submit" class="text-dark">{{$post->status === 'publish' ? 'Unpublish' : 'Publish'}}</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        @endif
                    </tr>
                     @endforeach
            </tbody>
          </table>
          {{!empty($posts->links()) ? $posts->links() : ''}}
        </div>
      </div>
</div>
  </div>

  @include('admin.partials.footer')

