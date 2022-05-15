@extends("crudbooster::admin_template")

@section('content')
  <div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-12">
                        <h4 class="card-title">{{ __('Categories') }}</h4>
                    </div>
                   
                     <div class="col-12">
                     <form method="post" action="{{ route('scrape.bcc.update') }}" autocomplete="off">
                
                    @csrf
                    
                    <input type="hidden" name="website_id" value="{{ $website->id }}">
                    <div class="row">
                    <div class="col-sm-10 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                        <label for="product_category">{{ _('Product Category') }}</label>
                        <input type="text" name="metas[category_url]" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ _('Product Category') }}" value="{{ $meta_values['category_url'] }}" id="product_category" readonly>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ route('scrape.bcc.scrapeCategory',[$website->id,'category_url']) }}" style="margin-top: 30px;display: inherit;">Start Scrape</a>
                    </div>
                    </div>
               
                  <!--   <button type="submit" class="btn btn-fill btn-primary">{{ _('Update') }}</button> -->
               
            </form>
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <table class="table tablesorter " id="">
                        <thead class=" text-primary">
                            <th scope="col">{{ __('ID') }}</th>
                            <th scope="col">{{ __('Image') }}</th>
                            <th scope="col">{{ __('Category') }}</th>
                            <th scope="col">{{ __('Url') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        {{$category->id}}
                                    </td>
                                    <td>
                                        @if($category->image)
                                            @php
                                            $parsed = parse_url($category->image);
                                            if(empty($parsed['scheme'])){
                                                $urlStr = asset('public/storage/category_images/'.$category->image);
                                            }else{
                                                 $urlStr = $category->image;
                                            }
                                            @endphp
                                   
                                            <img src="{{$urlStr}}" width="100px">
                                        @endif
                                    </td>
                                    <td>{!! $category->name !!}</td>
                                    <td><a href="https://www.bccannabisstores.com{{ $category->url }}" target="_blank">Target URL</a></td>
                                    <td>
                                        <a href="{{route('scrape.bcc.product',[$category->id])}}">Products</a> | <a href="{{route('scrape.bcc.category.edit',[$category->id])}}">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                <nav class="d-flex justify-content-end" aria-label="...">
                    {{ $categories->links() }}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
