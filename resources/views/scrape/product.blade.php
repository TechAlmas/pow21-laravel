@extends("crudbooster::admin_template")

@section('content')
      <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title">{{ __('Products') }}</h4>
                        </div>
                        <div class="col-6 text-left">
                        <form class="form-inline" method="GET">
                            <div class="form-group">
                                <input type="seach" placeholder="Search" class="form-control" name="s" value="{{ app('request')->input('s') }}" autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-succes">
                                <span class="fas fa-search"></span>
                            </button>
                        </form> 
                        </div>
                        <div class="col-6 text-right">
                        @if(isset($category->name))
                            <a href="{{route('scrape.bcc.scrapeCategoryDetails',[$category->website_slug,$category->id])}}" class="btn btn-sm btn-primary">{{ __('Scrape '.$category->name) }}</a>
                        @endif
                        </div>
                </div>
                <div class="card-body">
                    
                    <div class="">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <th scope="col">{{ __('ID') }}</th>
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col">{{ __('Product Title') }}</th>
                                <th scope="col">{{ __('Url') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            {{$product->id}}
                                        </td>
                                        <td>
                                            @if($product->image)
                                                @php
                                                $parsed = parse_url($product->image);
                                                if(empty($parsed['scheme'])){
                                                    $urlStr = asset('storage/product_images/'.$product->image);
                                                }else{
                                                     $urlStr = $product->image;
                                                }
                                                @endphp
                                       
                                                <img src="{{$urlStr}}" width="100px">
                                            @endif
                                        </td>
                                        <td>{{ $product->product_title }}</td>
                                        <td>
                                            @php
                                            $parsed = parse_url($product->url);
                                            @endphp
                                            @if(empty($parsed['scheme']))
                                                <a href="https://www.cat.com{{ $product->url }}" target="_blank">Target URL</a>
                                            @else
                                                <a href="{{ $product->url }}" target="_blank">
                                                    Target URL
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('scrape.bcc.product_view',$product->id) }}">View</a> | <a href="{{ route('scrape.bcc.product_edit',$product->id) }}">Edit</a> | 
                                            <a href="{{ route('scrape.bcc.scrapeProduct',$product->id) }}">Scrape</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $products->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
