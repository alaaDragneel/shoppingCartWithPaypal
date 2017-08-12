@extends('layouts.app')

@section('content')
    <div class="container">
        @if(Session::has('success'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                </div>
            </div>
        @endif
        @if(Session::has('fail'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div class="alert alert-danger">
                        {{ Session::get('fail') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <form action="{{ url('/checkOut') }}" method="post">
                {{ csrf_field() }}
                <div class="col-sm-6 col-md-9 col-md-offset-1 col-sm-offset-3">
                    <ul class="list-group">
                        <li class="list-group-item active">Check Out</li>
                        @forelse ($cards as $card)
                            <li class="list-group-item">
                                <div class="col-md-12">
                                    <div class="caption">
                                        <h3>{{ $card->product->title }}</h3>
                                        <p class="desc">{{ $card->product->desc }}</p>
                                        <div class="clearfix">
                                            <div class="pull-left price">{{ $card->product->price }} $</div>

                                            <input class="pull-right price" type="checkbox" name="checkOut[]" value="{{ $card->product->id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="alert alert-danger">No Results</div>
                            </li>
                        @endforelse
                    </ul>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success btn-block" value="check out">
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-5">
                <div class="pagination">
                    {{ $cards->render() }}
                </div>
            </div>
        </div>
    </div>
@endsection
