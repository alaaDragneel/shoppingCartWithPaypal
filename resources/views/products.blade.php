@extends('layouts.app')

@section('content')
    <div class="container">
        @if(Session::has('success'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div id="charge-massage" class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @forelse ($products as $product)
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail products">
                        <img src="{{ asset(''. $product->image .'') }}" alt="product Title" class="img-responsive">
                        <div class="caption">
                            <h3>{{ $product->title }}</h3>
                            <p class="desc">{{ $product->desc }}</p>
                            <div class="clearfix">
                                <div class="pull-left price">{{ $product->price }} $</div>
                                <a href="{{ route('add.to.card', ['product_id' => $product->id]) }}" class="btn btn-success pull-right" role="button">Add To Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-danger">No Results</div>
            @endforelse
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-5">
                <div class="pagination">
                    {{ $products->render() }}
                </div>
            </div>
        </div>
    </div>
@endsection
