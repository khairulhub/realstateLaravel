@extends('frontend.frontend_dashboard')
@section('main')



<!-- ================================
       START CONTACT AREA
================================= -->
<section class="cart-area section-padding">
    <div class="container">
        <div class="table-responsive">
            <table class="table generic-table">
                <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Property Details</th>
                    <th scope="col">Price</th>
                    
                    <th scope="col">Action</th>
                </tr>
                </thead>
               <tbody id="cartPage">
               
                
                </tbody>
            </table>
            
        </div>
        <div class="col-lg-4 ml-auto mb-3">
            <div class="bg-light p-4 rounded-rounded mt-40px">
                <h3 class="fs-18 font-weight-bold pb-3">Cart Totals</h3>
                <div class="divider"><span></span></div>
                <ul class="generic-list-item pb-4">
                    <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                        <span class="text-black">Subtotal:</span>
                        <span id="cartSubTotal"> </span>
                    </li>
                    <li class="d-flex align-items-center justify-content-between font-weight-semi-bold">
                        <span class="text-black">Total:</span>
                         <span id="cartSubTotal"> </span>
                    </li>
                </ul>
                <a href="{{ route('checkout') }}" class="btn btn-primary w-100">Checkout </a>
            </div>
        </div>
    </div><!-- end container -->
</section>
<!-- ================================
       END CONTACT AREA
================================= -->







@endsection