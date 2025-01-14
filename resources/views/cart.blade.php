@extends('layouts.mobile')

@section('title')
	Cart
@endsection

@section('content')

    @if(isset($products))
	@if($products != null)
    <section class="mt-3 ftco-cart">
			<div class="container">
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

								  <td class="image-prod"><div class="img" style="background-image:url('{{ $product['image'] }}');"></div></td>

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
    		</div>
    		<div class="row justify-content-end">
    			<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
    				<div class="cart-total mb-3">
    					<h3>Coupon Code</h3>
    					<p>Enter your coupon code if you have one</p>
  						<form action="#" class="info">
	              <div class="form-group">
	              	<label for="">Coupon code</label>
	                <input type="text" class="form-control text-left px-3" placeholder="">
	              </div>
	            </form>
    				</div>
    				<p><a href="checkout.blade.php" class="btn btn-primary py-3 px-4">Apply Coupon</a></p>
    			</div>
    			<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
    				<div class="cart-total mb-3">
    					<h3>Estimate shipping and tax</h3>
    					<p>Enter your destination to get a shipping estimate</p>
  						<form action="#" class="info">
	              <div class="form-group">
	              	<label for="">Country</label>
	                <input type="text" class="form-control text-left px-3" placeholder="">
	              </div>
	              <div class="form-group">
	              	<label for="country">State/Province</label>
	                <input type="text" class="form-control text-left px-3" placeholder="">
	              </div>
	              <div class="form-group">
	              	<label for="country">Zip/Postal Code</label>
	                <input type="text" class="form-control text-left px-3" placeholder="">
	              </div>
	            </form>
    				</div>
    				<p><a href="checkout.blade.php" class="btn btn-primary py-3 px-4">Estimate</a></p>
    			</div>
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
						<button type="submit" class="btn btn-primary py-3 px-4">Proceed to Checkout</button>
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
