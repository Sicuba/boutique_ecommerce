@extends('layouts.mobile')

@section('title')
	Cart
@endsection

@section('content')

    @if(isset($products))
	@if($products != null)
    <section class="mt-3 ftco-cart">
			{{-- <div class="container">
				<div class="row">
    			<div class="col-md-12 ftco-animate">
    				<div class="cart-list">
	    				<table class="table">
						    <thead class="thead-primary">
						      <tr class="text-center">
						        <th>&nbsp;</th>
						        <th>&nbsp;</th>
						        <th>Product name</th>
						        <th>Price</th>
						        <th>Quantity</th>
						        <th>Total</th>
						      </tr>
						    </thead>
						    <tbody>
							  @foreach($products as $product)
							  <tr class="text-center">
								  <td class="product-remove"><a href="{{ route('product.removeFromCart',['id'=> $product['product_id']]) }}"><span class="ion-ios-close"></span></a></td>

								  <td class="image-prod"><div class="img" style="background-image:url('https://demo.vitrinedigital.eu/{{ $product['image'] }}');"></div></td>

								  <td class="product-name">
									  <h3>{{ $product['name'] }}</h3>
								  </td>

								  <td class="price">${{ $product['final_price'] }}</td>

								  <td class="quantity">
									  <div class="row input-group mb-3 d-flex align-items-center">
                                        <span class="icon-minus px-2 border py-2 mr-2" style="cursor: pointer"></span>
										  <input type="text" name="quantity" class="quantity form-control input-number" disabled value="{{ $product['qty'] }}" min="1" max="100">
                                          <span class="icon-plus px-2 border py-2 ml-2" style="cursor: pointer"></span>
                                        </div>
								  </td>

								  <td class="total">${{ $product['final_price'] }}</td>
							  </tr><!-- END TR-->
							  @endforeach
						    </tbody>
						  </table>
					  </div>
    			</div>
    		</div> --}}
            {{-- Mobile Mode --}}
            <h3 class="text-center" style="font-weight: 500;">Resumo do pedido</h3>
            <div style="width: 100%; height: 100%;">
                @foreach($products as $product)
                <div style="display: flex; max-width: 100%; gap: 15px; justify-content: space-around; align-items: center; border-bottom: 1px solid #808080; padding-bottom: 5px;">
                    <div class="img" style="width: 100px; height: 100px; border-radius: 50%;  background-image:url('https://demo.vitrinedigital.eu/{{ $product['image'] }}');"></div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <p style="width: 120px; word-wrap: break-word">{{ $product['name'] }}</p>
                        <div class="row input-group mb-2 d-flex align-items-center">
                            <span class="icon-minus  border px-1 mr-2" style="cursor: pointer"></span>
                              <input type="text" name="quantity" style="height: 30px!important; width: 60px;" class="quantity form-control input-number" disabled value="{{ $product['qty'] }}" min="1" max="100">
                              <span class="icon-plus px-1 border ml-2" style="cursor: pointer"></span>
                            </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px; align-items: center;">
                        <a href="{{ route('product.removeFromCart',['id'=> $product['product_id']]) }}" ><span style="border: 1px dashed #808080; font-size: 30px; padding: 0 10px; border-radius: 10%; color:#ef4444;" class="ion-ios-close"></span></a>
                        <b>${{ $product['final_price'] }}</b>
                    </div>

                </div>
                @endforeach
            </div>












    		<div class="row justify-content-end">

    			<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
    				<div class="cart-total mb-3">
    					<h3>Cart Totals</h3>
    					<p class="d-flex">
    						<span>Subtotal</span>
    						<span>${{ $totalPrice }}.00</span>
    					</p>
    					<p class="d-flex">
    						<span>Delivery</span>
    						<span>${{ \App\Models\BillingInfo::DELIVERY }}.00</span>
    					</p>
						<p class="d-flex">
							<span>Tax</span>
							<span>${{ \App\Models\BillingInfo::TAX }}.00</span>
						</p>
    					<hr>
    					<p class="d-flex total-price">
    						<span>Total</span>
    						<span>${{ ($totalPrice+\App\Models\BillingInfo::DELIVERY+\App\Models\BillingInfo::TAX) }}</span>
    					</p>
    				</div>
    				<p>
					<form id="logout-form" action="{{ route('createPaymentRequest') }}" method="POST">
						{{ csrf_field() }}
						{{-- <input type="hidden" name="id" value="{{ Auth::user()->getId() }}"> --}}
						<button type="submit" class="btn btn-primary py-3 px-4 mx-3" style="width: 90%; border-radius:0%; color:#fff!important;">Proceed to Checkout</button>
					</form>
					</p>
    			</div>
    		</div>
			</div>
		</section>
	@else
		<div class="hero-wrap hero-bread">
			<div class="container">
				<div class="row no-gutters slider-text align-items-center justify-content-center">
					<div class="col-md-9 ftco-animate text-center">
						<h1 class="mb-0">Your basket is empty!</h1>
						<p class="page-link"><span class="mr-2"><a href="{{ route('shop') }}">Shop here</a></span></p>
					</div>
				</div>
			</div>
		</div>
	@endif
    @else
    <div class="hero-wrap hero-bread">
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-0">Your basket is empty!</h1>
                    <p class="page-link"><span class="mr-2"><a href="{{ route('shop') }}">Shop here</a></span></p>
                </div>
            </div>
        </div>
    </div>
    @endif



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

@endsection

@section('scripts')

  <script>
		$(document).ready(function(){

		var quantitiy=0;
		   $('.quantity-right-plus').click(function(e){

		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());

		        // If is not undefined

		            $('#quantity').val(quantity + 1);


		            // Increment

		    });

		     $('.quantity-left-minus').click(function(e){
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());

		        // If is not undefined

		            // Increment
		            if(quantity>0){
		            $('#quantity').val(quantity - 1);
		            }
		    });

		});
	</script>
@endsection

