    @extends('frontend.frontend_dashboard')
@section('main')


   

<!-- ================================
       START CONTACT AREA
================================= -->
<section class="cart-area section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="card card-item">
                    <div class="card-body">
                        <h3 class="pb-3 card-title fs-22">Billing Details</h3>
                        <div class="divider"><span></span></div>
<form method="post" class="row" action="{{ route('payment') }}" enctype="multipart/form-data">
    @csrf
    <div class="input-box col-lg-6">
        <label class="label-text"> Name</label>
        <div class="form-group">
            <input class="form-control form--control" type="text" name="name" value="{{ Auth::user()->name }}">
            <span class="la la-user input-icon"></span>
        </div>
    </div><!-- end input-box -->
    <div class="input-box col-lg-6">
        <label class="label-text">Email</label>
        <div class="form-group">
            <input class="form-control form--control" type="email" name="email" value="{{ Auth::user()->email }}">
            <span class="la la-user input-icon"></span>
        </div>
    </div><!-- end input-box -->
    <div class="input-box col-lg-12">
        <label class="label-text">Address</label>
        <div class="form-group">
            <input class="form-control form--control" type="text" name="address" value="{{ Auth::user()->address }}">
            <span class="la la-envelope input-icon"></span>
        </div>
    </div><!-- end input-box -->
    <div class="input-box col-lg-12">
        <label class="label-text">Phone Number</label>
        <div class="form-group">
            <input id="phone" class="form-control form--control" type="tel" name="phone" value="{{ Auth::user()->phone }}">
        </div>
    </div><!-- end input-box -->




                    </div><!-- end card-body -->
                </div><!-- end card -->
<div class="card card-item">
    <div class="card-body">
        <h3 class="pb-3 card-title fs-22">Select Payment Method</h3>
        <div class="divider"><span></span></div>
        <div class="payment-option-wrap">
            <div class="payment-tab is-active">
                <div class="payment-tab-toggle">
                    <input checked="" id="bankTransfer" name="cash_delivery" type="radio" value="handcash">
                    <label for="bankTransfer">Direct Payment</label>
                </div>

                <div class="payment-tab-toggle">
                    <input checked="" id="bankTransfer" name="cash_delivery" type="radio" value="stripe">
                    <label for="bankTransfer">Stripe Payment</label>
                </div>

            </div><!-- end payment-tab -->




        </div>
    </div><!-- end card-body -->
</div><!-- end card -->
            </div><!-- end col-lg-7 -->
            <div class="col-lg-5">
                <div class="card card-item">
                    <div class="card-body">
                        <h3 class="pb-3 card-title fs-22">Order Details</h3>
                        <div class="divider"><span></span></div>
                        <div class="order-details-lists">

                             @foreach ($carts as $item)
                              <input type="hidden" name="slug[]" value="{{ $item->options->slug }}">
      <input type="hidden" name="property_id[]" value="{{ $item->id }}">
      <input type="hidden" name="property_name[]" value="{{ $item->name }}">
      <input type="hidden" name="price[]" value="{{ $item->price }}">
      <input type="hidden" name="agent_id[]" value="{{ $item->options->agent_id }}">
    <div class="pb-3 mb-3 media media-card border-bottom border-bottom-gray">
        <a href="{{ url('property/details/'.$item->id.'/'.$item->options->slug) }}" class="media-img">
            <img src="{{ asset($item->options->image) }}" alt="Cart image" style=" width:100px; height:100px;">
        </a>
        <div class="media-body">
            <h5 class="pb-2 fs-15"><a href="{{ url('property/details/'.$item->id.'/'.$item->options->slug) }}">{{ $item->name }} </a></h5>
            <p class="text-black font-weight-semi-bold lh-18">${{ $item->price }}  </p>
        </div>
    </div><!-- end media -->
    @endforeach


                        </div><!-- end order-details-lists -->
                        <a href="{{ route('index') }}" class="btn-text"><i class="mr-1 la la-edit"></i>Edit</a>
                    </div><!-- end card-body -->
                </div><!-- end card -->
                <div class="card card-item">
                    <div class="card-body">
                        <h3 class="pb-3 card-title fs-22">Order Summary</h3>
                        <div class="divider"><span></span></div>
                         <ul class="generic-list-item generic-list-item-flash fs-15">

            <li class="d-flex align-items-center justify-content-between font-weight-bold">
                <span class="text-black">Total:</span>
                <span>${{ $cartTotal }}</span>
            </li>
        </ul>
                        <div class="pt-3 btn-box border-top border-top-gray">
                    <button type="submit" class="btn btn-primary w-100">Proceed <i class="ml-1 la la-arrow-right icon"></i></button>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col-lg-5 -->
        </div><!-- end row -->
    </div><!-- end container -->
</section>
<!-- ================================
       END CONTACT AREA
================================= -->







</form>





@endsection
