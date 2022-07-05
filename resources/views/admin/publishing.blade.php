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
                                <form method="POST" action="{{route('publish')}}">
                                    @csrf
                                    <input type="hidden" value="{{$product->id}}" name="id">
                                    <input type="hidden" value="publish" name="action">
                                    <button type="submit" class="m-2 btn btn-success">Duyệt</button>
                                </form>
                                <form method="POST" action="{{route('publish')}}">
                                    @csrf
                                    <input type="hidden" value="{{$product->id}}" name="id">
                                    <input type="hidden" value="reject" name="action">
                                    <button type="submit" class="m-2 btn btn-danger">Từ chối</button>
                                </form>
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

