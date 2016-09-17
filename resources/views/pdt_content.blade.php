@extends('master')
@section('title',$product->name)
@section('content')
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <div class="page bk_content">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators" style="width:100%;left:0;margin-left: 0;text-align: right; padding-right: 10%; background: rgba(0,0,0,0.5);bottom: -10px;">
                @foreach($pdt_images as $index => $pdt_image)
                <li data-target="#carousel-example-generic" data-slide-to="{{$index}}" class="{{$index == 0 ? 'active' : ''}}"></li>
                @endforeach
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @foreach($pdt_images as $index => $pdt_image)
                <div class="item {{$index == 0 ? 'active' : ''}}">
                    <img src="{{$pdt_image->image_path}}" alt="..." style="width: 200px;height:200px;">
                </div>
                @endforeach
            </div>
        </div>
        <div class="weui_cells_title">
            <span class="bk_title">{{$product->name}}</span>
            <span class="bk_price" style="float: right;">￥{{$product->price}}</span>
        </div>
        <div class="weui_cells">
            <div class="weui_cell">
                <p class="bk_summary">{{$product->summary}}</p>
            </div>
        </div>
        <div class="weui_cells_title">详细介绍</div>
        <div class="weui_cells">
            <div class="weui_cell">
                <p class="bk_summary">{!!$pdt_content->content!!}</p>
            </div>
        </div>
    </div>
    <div class="bk_fix_bottom">
        <div class="bk_half_area">
            <botton class="weui_btn weui_btn_primary">加入购物车</botton>
        </div>
        <div class="bk_half_area">
            <botton class="weui_btn weui_btn_default">结算(<span id="cart_num" class="m3_price"></span>)</botton>
        </div>
    </div>
@endsection
@section('my-js')
    <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
@endsection