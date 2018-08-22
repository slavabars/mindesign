@extends('tpl.tpl')

@section('content')
<div class="container" style="padding: 20px 0px;">
   {!! Form::open(['url'=>secure_url('/search'),'method'=>'post']) !!}
   <div class="row">
       <div class="col-md-10">
           {!! Form::text('key',null,['class'=>'form-control', 'required']) !!}
       </div>
       <div class="col-md-2"><button class="btn btn-default">Искать</button></div>
    </div>
    {!! Form::close() !!}
</div>

<div class="container">
<div class="row">
    <div class="col-md-3">
        <ul>
            @foreach($categories as $category)
                <li><a href="/category/{{$category->alias}}">{{$category->title}}</a></li>
                @if(\App\Categories::whereParent($category->id)->count()>0)
                    <ul>
                    @foreach(\App\Categories::whereParent($category->id)->get() as $item)
                            <li><a href="/category/{{$item->alias}}">{{$item->title}}</a></li>
                    @endforeach
                    </ul>
                @endif
            @endforeach
        </ul>
    </div>
    <div class="col-md-9">
        @foreach($products as $product)
            <div class="row">
                <div class="col-md-2">
                    <img src="{{$product->image}}" alt="{{$product->title}}" width="100%">
                </div>
                <div class="col-md-10">
                    <b>title:</b> {{$product->title}}<br>
                    <b>description:</b> {!! nl2br($product->description) !!}<br>
                    <b>url:</b> <a href="{{$product->url}}"></a>{{$product->url}}<br>
                    <b>price:</b> {{$product->price}}<br><br>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
@stop