@extends('layouts.mobile')

@section('title')
	Shop
@endsection

@section('content')
    <section>
        <div class="form-group" style="padding: 10px;">
<div style="display: flex; justify-content: space-between;" >
    <a href="{{route('shop')}}" class="btn btn-secondary" style="padding: 10px 40px; border-radius:10%; margin:10px 0px;">All</a>
    <a href="{{ route('cart') }}" class="nav-link cta cta-colored btn" style="padding: 10px 40px; border-radius:10%; margin:10px 0px; background-color: #82ae46; color:#fff!important"><span class="icon-shopping_cart"></span>[{{ Session::has('cart') ? count(Session::get('cart'))  : 0 }}]</a>
</div>


            <select class="form-control" id="categorySelect" onchange="redirectToPage(this)">
                <option value="{{route('shop')}}">Seleciona a categoria</option>
                @foreach ($categories as $category)

                    @if($selectedCategory == -1)
                        <option value="{{ route('category.products', ['id' => $category['id']]) }}">
                            {{ $category['name'] }}
                        </option>
                    @else
                        <option value="{{ route('category.products', ['id' => $category['id']]) }}">
                            {{ $category['name'] }}
                        </option>
                    @endif
                @endforeach
            </select>



          {{--   <select id="pageSelect" onchange="redirectToPage(this)">
                <option value="/shop">All</option>

                @foreach($categories as $category)
							@if($selectedCategory == -1)
                            <a href="{{ route('category.products',['id' => $category['id']]) }}">{{ $category['name'] }}</a>
                            <option value="https://example.com/page1">Page 1</option>
							@else
                            <a href="{{ route('category.products',['id' => $category['id']]) }}">{{ $category['name'] }}</a>
                            <option value="https://example.com/page1">Page 1</option>
							@endif
						@endforeach
            </select> --}}
          </div>

    	<div class="container">
    		<div class="row justify-content-center">
    			{{-- <div class="col-md-10 mb-5 text-center">
    				<ul class="product-category">
						@if($selectedCategory == -1)
							<li><a href="{{ route('shop') }}" class="active">All</a></li>
						@else
							<li><a href="{{ route('shop') }}">All</a></li>
						@endif
						@foreach($categories as $category)
							@if($selectedCategory == -1)
    							<li><a href="{{ route('category.products',['id' => $category['id']]) }}">{{ $category['name'] }}</a></li>
							@else
								<li>
									<a class="{{ $category['id'] == $selectedCategory ? 'active':'' }}" href="{{ route('category.products',['id' => $category['id']]) }}">
										{{ $category['name'] }}
									</a>
								</li>
							@endif
						@endforeach
    				</ul>
    			</div> --}}
    		</div>
    		<div class="row">
				@foreach($products as $product)
    			<div class="col-6 ftco-animate">
    				<div class="product">
    					<a href="{{ route('product',['id'=> $product['name']]) }}" class="img-prod"><img class="img-fluid" src="{{ $product['cover_image_url'] }}" alt="Product Image">
    						@if($product['price'] > 0)
							<span class="status">{{ $product['price'] }}%</span>
							@endif
    						<div class="overlay"></div>
    					</a>
    					<div class="text py-3 pb-4 px-3 text-center">
    						<h3><a href="{{ route('product',['id'=> $product['name']]) }}">{{ $product['name'] }}</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span class="mr-2 price-dc">${{ $product['sale_price'] }}</span><span class="price-sale">${{ $product['final_price'] }}</span></p>
		    					</div>
	    					</div>
	    					<div class="bottom-area d-flex px-3">
	    						<div class="m-auto d-flex">
	    							<a href="{{ route('product.addToCart',['id'=> $product['name']]) }}" class="buy-now d-flex justify-content-center align-items-center mx-1">
	    								<span><i class="ion-ios-cart"></i></span>
	    							</a>
	    							<a href="#" class="heart d-flex justify-content-center align-items-center ">
	    								<span><i class="ion-ios-heart"></i></span>
	    							</a>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
				@endforeach


    		</div>
    		<div style="width: 100%; display: flex; justify-content: center;">



                    <nav>
                        <ul class="pagination">
                            {{-- Link para página anterior --}}
                            @if($pagination['prev_page_url'])
                                <li class="page-item">
                                    <a class="page-link" href="{{ url()->current() }}?page={{ $pagination['current_page'] - 1 }}">Anterior</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                            @endif

                            {{-- Links das páginas --}}
                            @for($i = 1; $i <= $pagination['last_page']; $i++)
                                <li class="page-item {{ $pagination['current_page'] == $i ? 'active' : '' }}">
                                    <a class="page-link" {{-- href="{{ url()->current() }}?page={{ $i }}" --}} href="{{route('shop')}}?page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Link para próxima página --}}
                            @if($pagination['next_page_url'])
                                <li class="page-item">
                                    <a class="page-link" href="{{ url()->current() }}?page={{ $pagination['current_page'] + 1 }}">Próximo</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Próximo</span>
                                </li>
                            @endif
                        </ul>
                    </nav>


			  {{-- {{ $products_paginate->links() }} --}}

        </div>
    	</div>
    </section>




  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

@endsection
@section('scripts')
<script>
      function redirectToPage(selectElement) {
            const selectedUrl = selectElement.value;
            if (selectedUrl) {
                window.location.href = selectedUrl;
            }
        }
</script>
@endsection
