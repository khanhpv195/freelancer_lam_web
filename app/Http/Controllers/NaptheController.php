<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Card_card;
use App\Card;
use App\Payment;
use Config;
use Auth;
use Illuminate\Http\UploadedFile;
class NaptheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        
        $user = Auth::user()->id;
        $h = "select *,c.card_name from payments left join cat_cards c On payments.provider = c.card_code where user_id = $user and payments.payment_status =0";
        $hsitory = DB::select($h);
        $CARD_STATUS = Config::get('constants.CARD_STATUS');
        $card = Card_card::all();
        $q = "select * from cat_cards where card_status= 1";
        $result = DB::select($q);
        return view('napthe.index',compact(array('result','hsitory','card')));
    }

    //function nap the tu web
    public function napthecao(Request $request) {

        $IMAGES = Config::get('constants.IS_IMAGE');
        $NOT_IMAGES = Config::get('constants.NOT_IMAGES');
        $CHO_DUYET = Config::get('constants.CHO_DUYET');
        $CHUA_XOA = Config::get('constants.CHUA_XOA');
      

        //get discount
        $q = Card_card::where('card_code',$request->get('card_type'))->get();
        $card_discount = null;
        foreach($q as $value) {
            $card_discount = $value['card_discount'];
        }
        //CHECK NAP BANG THE HAY ANH
        if($request->get('nap_the')  ==  $NOT_IMAGES) {
            $result = Payment::create([
                'phone' => $request->get('user_phone'),
                'card_type_id' => $request->get('card_price'),
                'pin' => $request->get('card_pin'),
                'serial' => $request->get('card_seria'),
                'provider' => $request->get('card_type'),
                'user_id' => $request->get('user_id'),
                'link_id' => null,
                'ip_request' => null,
                'price' => $request->get('card_price'),
                'amount' => 0,
                'rate' => $card_discount,
                'transaction_id' => str_random(10),
                'balance' => 0,
                'requestId' => null,
                'topup_type' => 0,
                'is_image' =>  $NOT_IMAGES,
                'image_url' => null,
                'notes' => null,
                'payment_status' =>  $CHO_DUYET,
                'is_deleted' => $CHUA_XOA,            
           ]);
        } else {
            $file = $request->file('img');
            $messages = [
             'image' => 'Định dạng không cho phép',
             'max' => 'Kích thước file quá lớn',
         ];
             // Điều kiện cho phép upload
             $this->validate($request, [
                 'file' => 'image|max:2028',
             ], $messages);
     
             if ($request->file('img')->isValid()){
                 // Lấy tên file
                 $file_name = $request->file('img')->getClientOriginalName();
                 // Lưu file vào thư mục upload với tên là biến $filename
                 $urlFile = $request->file('img')->move('uploads',$file_name);
             }

            $result = Payment::create([
                'phone' => $request->get('user_phone'),
                'card_type_id' => $request->get('card_price'),
                'pin' => 0,
                'serial' => 0,
                'provider' => $request->get('card_type'),
                'user_id' => $request->get('user_id'),
                'price' => $request->get('card_price'),
                'amount' => 0,
                'rate' => $card_discount,
                'transaction_id' => str_random(10),
                'balance' => 0,
                'topup_type' => 0,
                'is_image' => $IMAGES,
                'image_url' => $urlFile,
                'notes' => null,
                'payment_status' => $CHO_DUYET,
                'is_deleted' => $CHUA_XOA,            
           ]);
        }
      
       if($result) {
        return redirect()->back()->with('message', 'Nạp thẻ thành công, vui lòng chờ hệ thống xác nhận!');
       }
       return false;
    }

    //function hsitory napthe

    public function Historycard()
    {
        $user = Auth::user()->id;
        $q = "select *,c.card_name from payments left join cat_cards c On payments.provider = c.card_code where user_id = $user";
        $hsitory = DB::select($q);
        return view('napthe.index',compact('hsitory'));
    }

    public function HistoryPending()
    {
        $user = Auth::user()->id;

        $hsitory = DB::table('payments')
                ->leftJoin('users', 'payments.user_id', '=', 'users.id')
                ->leftJoin('cat_cards', 'payments.provider', '=', 'cat_cards.card_code')
                ->where('payments.user_id', '=', $user)
                ->orderByRaw('payments.payment_id - payments.created_at ASC')
                ->paginate(10);
        return view('history.napthe',compact('hsitory'));
    }

    public function deleteCard(Request $request)
    {
        $id = $request->get('id');
        $q = "DELETE FROM payments where payment_id = $id";
        $result = DB::select($q);
        return redirect()->back()->with('message', 'Xử lý thành công!');

    }
}
