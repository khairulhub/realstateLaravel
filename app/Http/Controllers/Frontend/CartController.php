<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\Propertitype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Image;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
 use Carbon\Carbon;
 use App\Models\PackagePlan;
 use Barryvdh\DomPDF\Facade\Pdf;
 use App\Models\PropertyMessage;
use App\Models\State;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Order;

use Illuminate\Support\Facades\Mail;
use App\Mail\Orderconfirm;



class CartController extends Controller
{
    public function AddToCart(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        // Check if the property is already in the cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });
        // If the property is already in the cart, update the quantity

       if($cartItem->isNotEmpty()) {
            return response()->json([
                'error'=> 'Property already in cart'
            ]);
        }
        if($property->lowest_price == null){
            Cart::add([
                'id' => $id,
                'name' => $request->property_name, 
                'qty' => 1, 
                'price' => $property->max_price,
                'weight' => 1,
                'options' => [
                    'image' => $property->property_thambnail,
                    'slug' => $request->property_slug,
                    'agent_id' => $property->agent_id,
                    ]
                ]);
        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->property_name, 
                'qty' => 1, 
                'price' => $property->lowest_price,
                'weight' => 1,
                'options' => [
                    'image' => $property->property_thambnail,
                    'slug' => $request->property_slug,
                    'agent_id' => $property->agent_id,
                    ]
                ]);
        }
        return response()->json(['success' => 'Property added to cart successfully']);
    }



    public function CartData(){

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method 

public function AddMiniCart(){

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method 

public function RemoveMiniCart($rowId){

        Cart::remove($rowId);
        return response()->json(['success' => 'Property Remove From Cart']);

    }// End Method 

public function MyCart(){

        return view('frontend.mycart.view_mycart');

    } // End Method 

public function GetCartProperty(){

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method 



 public function CartRemove($rowId){

        Cart::remove($rowId);
        return response()->json(['success' => 'Property Remove From Cart']);

    }// End Method 

public function CheckoutCreate(){

        if (Auth::check()) {
            
            if (Cart::total() > 0) {
                $carts = Cart::content();
                $cartTotal = Cart::total();
                $cartQty = Cart::count();

                return view('frontend.checkout.checkout_view',compact('carts','cartTotal','cartQty'));
            } else{

                $notification = array(
                    'message' => 'Add At list One property',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification); 

            }

        }else{

            $notification = array(
                'message' => 'You Need to Login First',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification); 

        }

    }// End Method 

public function Payment(Request $request){

       
        $total_amount = round(Cart::total());
        // Cerate a new Payment Record 

        $data = new Payment();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->cash_delivery = $request->cash_delivery;
        $data->total_amount = $total_amount;
        $data->payment_type = 'Direct Payment';
        
        $data->invoice_no = 'EOS' . mt_rand(10000000, 99999999);
        $data->order_date = Carbon::now()->format('d F Y');
        $data->order_month = Carbon::now()->format('F');
        $data->order_year = Carbon::now()->format('Y');
        $data->status = 'pending';
        $data->created_at = Carbon::now(); 
        $data->save();


            foreach ($request->property_name as $key => $property_name) {
        
            $existingOrder = Order::where('user_id',Auth::user()->id)->where('property_id',$request->property_id[$key])->first();

            if ($existingOrder) {

                $notification = array(
                    'message' => 'You Have already enrolled in this property',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification); 
            } // end if 

            $order = new Order();
            $order->payment_id = $data->id;
            $order->user_id = Auth::user()->id;
            $order->property_id = $request->property_id[$key];
            $order->agent_id = $request->agent_id[$key];
            $order->property_name = $property_name;
            $order->price = $request->price[$key];
            $order->save();

           } // end foreach 

           $request->session()->forget('cart');
           $paymentId = $data->id;

           $sendmail = Payment::find($paymentId);
           $data = [
                'invoice_no' => $sendmail->invoice_no,
                'amount' => $total_amount,
                'name' => $sendmail->name,
                'email' => $sendmail->email,
           ];

            Mail::to($request->email)->send(new Orderconfirm($data));

            if ($request->cash_delivery == 'stripe') {
               echo "stripe";
            }else{

                $notification = array(
                    'message' => 'Cash Payment Submit Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('index')->with($notification); 

            }  
       

    }// End Method 


}
