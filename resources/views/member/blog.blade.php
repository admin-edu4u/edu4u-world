@extends('memberLayout')
@section('style')
<style>
  .scrollable-tabs {
        overflow-x: auto;
        white-space: nowrap;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollable-tabs::-webkit-scrollbar {
        display: none;
    }

    @media (max-width: 767px) {
        .carousel-inner {
            height: 520px;
        }

        .blog-carousel-header {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .blog-carousel-sub-header {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
</style>
@endsection
@section('content')
<div class="pt-80 pb-20">
  <div class="blog-header">
    <div class="mw-1200 mx-auto">
      <span class="blog-header-title">Edu4u {{__('lang.blog')}}</span>
    </div>
  </div>
  <div class="mw-1200 mx-auto">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-indicators">
          @foreach(\App\Models\Blog::where('b_status', 1)->where('b_is_star', 1)->orderBy('created_at', 'desc')->get() as $index => $b)
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
          @endforeach
      </div>
      <div class="carousel-inner">
        @foreach(\App\Models\Blog::where('b_status', 1)->where('b_is_star', 1)->orderBy('created_at', 'desc')->get() as $index => $b)
        <div class="carousel-item {{$index == 0 ? 'active' : ''}}">
          <a href="/blog-details/{{ $b->b_url_code }}" class="cursor">
            <div class="col-12 mt-20 row align-items-center">
              <div class="col-12 col-lg-6">
                <img src="{{ asset('images/blogs/' . $b->b_img) }}" class="carousel-blog-img w-100 h-90">
              </div>
              <div class="col-12 col-lg-6">
                <span class="d-block blog-carousel-header">{{$b->b_title}}</span>
                <span class="pt-5 d-block blog-carousel-sub-header pt-3">{!! strip_tags(substr($b->b_content, 0, 100)) . '...' !!}</span>
                <span class="pt-5 d-block blog-carousel-date">{{ \Carbon\Carbon::parse($b->created_at)->format('d M Y') }}</span>
              </div>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>

    <div class="pt-80">
    <div class="scrollable-tabs" style="height:54px;">
        <ul class="nav nav-tabs nav-pills flex-nowrap" role="tablist">
            @foreach(\App\Models\BlogCat::where('bc_status', 1)->get() as $blogcat)
                <li class="nav-item me-3">
                    <a class="nav-link blog-nav-link{{ $loop->first ? ' active' : '' }}" data-bs-toggle="tab" href="#kt_vtab_pane_{{ $loop->iteration }}">{{ $blogcat->bc_name }}</a>
                </li>
            @endforeach
        </ul>
    </div>

        <div class="tab-content" id="myTabContent">
            @foreach(\App\Models\BlogCat::where('bc_status', 1)->get() as $blogcat)
                <div class="tab-pane fade{{ $loop->first ? ' show active' : '' }}" id="kt_vtab_pane_{{ $loop->iteration }}" role="tabpanel">
                    <div class="col-12 row">
                        @foreach(\App\Models\Blog::where('b_cat_id', $blogcat->bc_id)->where('b_status', 1)->orderBy('created_at', 'desc')->get() as $b)
                            <div class="col-12 col-lg-4 mt-15">
                              <a href="/blog-details/{{ $b->b_url_code }}" class="cursor">
                                  <img class="w-100 h-8 br-8" src="{{ asset('images/blogs/' . $b->b_img) }}" style="width: 300px; height: 200px; object-fit: cover;">
                                    <div>
                                        <span class="blog-recommend-title d-block pt-5">{{ $b->b_title }}</span>
                                        <span class="blog-recommend-desc pt-3">{!! strip_tags(substr($b->b_content, 0, 100)) . '...' !!}</span>
                                        <span class="blog-recommend-date d-block pt-3">{{ \Carbon\Carbon::parse($b->created_at)->format('d M Y') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection
