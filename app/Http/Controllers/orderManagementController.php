<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;
use App\Models\User;
use App\Models\Good;
use App\Models\Destination;
use App\Models\ManageOrder;
use Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use League\Csv\Reader;
use App\Mail\SendMailWhenDeliveryCompleted;
use App\Mail\SendMailWhenRequest;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers;
use Illuminate\Support\Facades\Log;

class orderManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Create a new controller instance.
     *
     * @return void
    */

    public $name;
    public $email;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $value = $request->input('value');
        $ordersForEveryUser = $request->input('ordersForEveryUser');
        $isUser = Auth::user()->user_role;

        if($isUser == 1 || $isUser == 2) {
            if($ordersForEveryUser != null) {
                $orders = Order::whereHas('user', function($q) use ($ordersForEveryUser) {
                    $q->where('name', 'like', '%'.$ordersForEveryUser.'%'); 
                })
                ->orWhere('order_name', 'like', '%'.$ordersForEveryUser.'%')
                ->paginate(5);
        
                $userNames = [];
                foreach($orders as $order) {
                    $user = User::find($order->user_id);
                    $userNames[$order->id] = [
                        'name' => $user->name, 
                        'user_id' => $user->id
                    ];   
                }
                return view('orders/viewOders')
                        ->with('orders', $orders)
                        ->with("userNames", $userNames);
            } else {
                $orders = Order::paginate(5);
                $userNames = [];
                foreach($orders as $order) {
                    $user = User::find($order->user_id);
                    $userNames[$order->id] = [
                        'name' => $user->name, 
                        'user_id' => $user->id
                    ];   
                }
                return view('orders/viewOders')->with('orders', $orders)->with("userNames", $userNames);
            }
        }else {
            if($value != null) {
                $orders = Order::where('user_id', Auth::user()->id)
                                    ->where(function($query) use ($value) {
                                        $query->where('order_name', 'like', '%' . $value . '%')
                                            ->orWhere('id', 'like', '%' . $value . '%');
                                    })
                                    ->paginate(5);
                return view('orders/viewUserOrderDetail')->with('datas', $orders);
            } else {
                $orders = Order::where('user_id', Auth::user()->id)->paginate(5);
                return view('orders/viewUserOrderDetail')->with('datas', $orders);
            }
        }
    }

    public function searchResult(Request $request) {
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");
        $startTimeStamp = Carbon::parse($startDate);
        $endTimeStamp = Carbon::parse($endDate);

        $orders = Order::where('user_id', Auth::user()->id)
                                    ->whereDate('created_at', '>=', $startTimeStamp)
                                    ->whereDate('created_at', '<=', $endTimeStamp)
                                    ->paginate(50);
        return view('orders/viewUserOrderDetail')->with('datas', $orders);
    }

    public function store(Request $request)
    {
        try {
            $datas = $request->input('datas');
            $delivery_date = $request->input('delivery_date');
            $destinationIds = $request->input('dest_id');
            $flag = true;

            $user = User::find(Auth::user()->id);

            foreach ($destinationIds as $destinationId) {
                if($user->destinations()->whereHas('user_destinations', function($q) use ($destinationId) {
                    $q->where('destination_id', $destinationId); 
                })->exists()) {
                    continue;
                } else {
                    $flag = false;
                    break;
                }
            }

            if($flag) {
                $newOrder = Order::create([
                    'order_name' => 'AA-3',
                    'user_id' => Auth::user()->id,
                    'status' => '発送前',
                    'delivery_date' => '',
                    'estimate_delivery_date' => $delivery_date,
                ]);
                $newOrder->order_name = 'AAD-' . $newOrder->id;
                $newOrder->save();
                foreach($datas as $data) {
                    for($i = 0; $i < count($destinationIds); $i++) {
                            $manageOrders = ManageOrder::create([
                                'order_id' => $newOrder->id,
                               'good_id' => $data['goodId'],
                               'destination_id' => $data['dest_id'][$i],
                                'quantity' => $data['dest_good_val'][$i],
                            ]);
                    }
                }
                $emailParams = new \stdClass(); 
                $emailParams->usersName = Auth::user()->company_name;
                $emailParams->usersEmail = "info@grandwork.jp";
                $emailParams->subject = $newOrder->order_name;
                $orderDetailLink = "https://inventory-dev.lowcost-print.com/order/" . Auth::user()->id . "/" . $newOrder->id;
                $emailParams->orderDetailLink = $orderDetailLink;
                Mail::to($emailParams->usersEmail)->send(new SendMailWhenRequest($emailParams));
                $emailParams->usersEmail = "s_kawaguchi@shotka.net";
                Mail::to($emailParams->usersEmail)->send(new SendMailWhenRequest($emailParams));
                echo "success";
            } else {
                echo "falid";
            }

        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $datas = $request->input('datas');
            $orderStatus = $request->input('orderStatus');
            $estimate_delivery_date = $request->input('estimate_delivery_date');
            $dest_num = $request->input('dest_num');
            foreach($datas as $data) {
                for($i = 0; $i < $dest_num; $i++) {
                    $manageOrders = ManageOrder::where('order_id', $id)
                                            ->where('good_id', $data['goodId'])
                                            ->where('destination_id', $data['dest_id_m'][$i])
                                            ->first();
                    if($manageOrders) {
                        $manageOrders->quantity = $data['dest_val_m'][$i];
                        $isUpdated = $manageOrders->save();
                    }
                }
            }
            $orders = Order::find($id);
            $user_id = $orders -> user_id;
            $orders->status = $orderStatus;
            $orders->estimate_delivery_date = $estimate_delivery_date;
            $orders->save();
            $orderDetailLink = "https://inventory-dev.lowcost-print.com/order/" . $user_id . "/" . $id;
            if ($orderStatus == "完了") {
                $orders = Order::find($id);
                $orders->delivery_date = Carbon::now()->format('Y-m-d');
                $orders->save();
                foreach($datas as $data) {
                    for($i = 0; $i < $dest_num; $i++) {
                        $good = Good::find($data['goodId']);
                        $good->goodsInventory -= $data['dest_val_m'][$i];
                        $good->save();
                    }
                }
                $user_email = User::find($user_id)->email;
                $emailParams = new \stdClass(); 
                $emailParams->usersName = "Ishidaprint";
                $emailParams->usersEmail = "info@grandwork.jp";
                $emailParams->orderDetailLink = $orderDetailLink;
                $emailParams->subject = $orders->order_name;
                Mail::to($emailParams->usersEmail)->send(new SendMailWhenDeliveryCompleted($emailParams));
                $emailParams->usersName = "Client";
                $emailParams->usersEmail = $user_email;
                Mail::to($emailParams->usersEmail)->send(new SendMailWhenDeliveryCompleted($emailParams));
            }
            echo "success";
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
        // echo $isUpdated;
    }

    public function createNewOrder(Request $request) {
        $goods = User::find(Auth::user()->id)->goods()->get();
        $destinations = User::find(Auth::user()->id)->destinations()->get();
        return view('orders/orderRequest')->with('datas', $goods)->with('destinations', $destinations);
    }

    public function showDetailOrder($user_id, $order_id) {
        $isSame = null;
        $datas = [];
        $tmp = [];
        $data = [];
        $locations = [];
        $destination_ids = [];
        $date = [];
        $count = 0;
        $all_quantity = 0;

        $order_date = Order::find($order_id);
        $created_at = $order_date->created_at->format('Y/m/d');
        $updated_at = $order_date->updated_at->format('Y/m/d');
        if ($order_date->status == "完了") {
            $delivery_date = Carbon::parse($order_date->delivery_date)->format('Y-m-d');
        } else {
            $delivery_date = "";
        }
        $estimate_delivery_date = $order_date->estimate_delivery_date;
        $status = $order_date->status;
        $user_name = User::find($user_id)->name;
        $company_name = User::find($user_id)->company_name;

        $date = [
            $created_at, 
            $updated_at, 
            $delivery_date, 
            $estimate_delivery_date, 
            $status, 
            $user_name,
            $order_id,
            $company_name
        ];
        $destinations = User::find($user_id)->destinations()->get();
        $manage_orders = ManageOrder::where('order_id', $order_id)->get();
        foreach ($manage_orders as $manage_order) {
            $count++;
            $good_id = $manage_order->good_id;
            $good = Good::find($good_id);

            $good_manageId = $good->manageGoodsId;
            $good_title = $good->goodsTitle;
            $good_inventory = $good->goodsInventory;
            $destination = Destination::find($manage_order->destination_id);
            $destination_location = $destination->destinationLocation;
            $quantity = $manage_order->quantity;
            $all_quantity += $quantity;
            $remain_quantity = $good_inventory - $all_quantity;
            array_push($locations, $destination_location);
            array_push($destination_ids, $manage_order->destination_id);
            array_push($tmp, [
                'location'=> $destination_location, 
                'quantity' => $quantity,
                'destination_id' => $manage_order->destination_id,
            ]);
            $data = [
                'order_id' => $order_id,
                'good_id' => $good_id,
                'good_manageId' => $good_manageId,
                'good_title' => $good_title,
                'good_inventory' => $good_inventory,
                'all_quantity'=> $all_quantity, 
                'remain_quantity' => $remain_quantity,
                'destination_location' => $tmp,
            ];
            if($isSame != $good_id) {
                $isSame = $good_id;
            }

            if($count == count($destinations)) {
                array_push($datas, $data);
                $data = [];
                $tmp = [];
                $count = 0;
                $all_quantity = 0;
            }
        }
        $locations = array_unique($locations);
        $destination_ids = array_unique($destination_ids);
        return view('orders/viewDetailOrder')->with('datas', $datas)
                                                ->with('locations', $locations)
                                                ->with('date', $date);
    }

    public function ordersDownload($order_id) {
        $cnt = 0;
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv; charset=Shift_JIS',
            'Content-Disposition' => `attachment; filename=goods_${cnt}.csv`,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];
        //////////////////////////////////////////////////////////////
        
        $isSame = null;
        $datas = [];
        $tmp = [];
        $data = [];
        $locations = [];
        $date = [];
        $count = 0;
        $all_quantity = 0;

        $order_date = Order::find($order_id);
        $created_at = $order_date->created_at->format('Y/m/d');
        $updated_at = $order_date->updated_at->format('Y/m/d');
        $delivery_date = $order_date->delivery_date;
        $estimate_delivery_date = $order_date->estimate_delivery_date;
        $status = $order_date->status;
        $user_id = $order_date->user_id;
        $user_name = User::find($user_id)->name;
        $date = [
            $created_at, 
            $updated_at, 
            $delivery_date, 
            $estimate_delivery_date, 
            $status, 
            $user_name,
            $order_id
        ];
        $destinations = User::find($user_id)->destinations()->get();
        $manage_orders = ManageOrder::where('order_id', $order_id)->get();
        foreach ($manage_orders as $manage_order) {
            $count++;
            $good_id = $manage_order->good_id;
            $good = Good::find($good_id);

            $good_manageId = $good->manageGoodsId;
            $good_title = $good->goodsTitle;
            $good_inventory = $good->goodsInventory;
            $destination = Destination::find($manage_order->destination_id);
            $destination_location = $destination->destinationLocation;
            $quantity = $manage_order->quantity;
            $all_quantity += $quantity;
            $remain_quantity = $good_inventory - $all_quantity;
            array_push($locations, $destination_location);
            array_push($tmp, [
                'location'=> $destination_location, 
                'quantity' => $quantity,
                'destination_id' => $manage_order->destination_id,
            ]);
            $data = [
                'order_id' => $order_id,
                'good_id' => $good_id,
                'good_manageId' => $good_manageId,
                'good_title' => $good_title,
                'good_inventory' => $good_inventory,
                'all_quantity'=> $all_quantity, 
                'remain_quantity' => $remain_quantity,
                'destination_location' => $tmp,
            ];
            if($isSame != $good_id) {
                $isSame = $good_id;
            }
            if($count == count($destinations)) {
                array_push($datas, $data);
                $data = [];
                $tmp = [];
                $count = 0;
                $all_quantity = 0;
            }
        }
        $locations = array_unique($locations);
        //////////////////////////////////////////////////////////////
        $dateDatas1 = [mb_convert_encoding("受注日時","SJIS", "UTF-8"), mb_convert_encoding($date[0],"SJIS", "UTF-8")];
        $dateDatas2 = [mb_convert_encoding("最終更新日時","SJIS", "UTF-8"), mb_convert_encoding($date[1],"SJIS", "UTF-8")];
        $dateDatas3 = [mb_convert_encoding("発送完了日","SJIS", "UTF-8"), mb_convert_encoding($date[2],"SJIS", "UTF-8")];
        $dateDatas4 = [mb_convert_encoding("発送予定日","SJIS", "UTF-8"), mb_convert_encoding($date[3],"SJIS", "UTF-8")];
        $dateDatas5 = [mb_convert_encoding("ステータス","SJIS", "UTF-8"), mb_convert_encoding($date[4],"SJIS", "UTF-8")];
        $FH = fopen('php://output', 'w');
        fputcsv($FH, $dateDatas1);
        fputcsv($FH, $dateDatas2);
        fputcsv($FH, $dateDatas3);
        fputcsv($FH, $dateDatas4);
        fputcsv($FH, $dateDatas5);
        fputcsv($FH, []);
        fputcsv($FH, []);
        fclose($FH);
        $callback = function() use ($datas, $destinations)
        {
            if(Auth::user()->user_role == 3) {
                $title1[0] = mb_convert_encoding("管理ID","SJIS", "UTF-8");
                $title1[1] = mb_convert_encoding("本のタイトル","SJIS", "UTF-8");
                for ($i = 0; $i < count($destinations)/2-1; $i++) {
                    $title1[$i + 2] = "";
                }
                array_push($title1, mb_convert_encoding("配送先","SJIS", "UTF-8"));
                for ($i = 0; $i < count($destinations) - count($destinations)/2-1; $i++) {
                    array_push($title1, "");
                }
                // array_push($title1, mb_convert_encoding("出荷計","SJIS", "UTF-8"));
                array_push($title1, mb_convert_encoding("在庫","SJIS", "UTF-8"));
                $title2[0] = "";
                $title2[1] = "";
                for ($i = 0; $i < count($destinations); $i++) {
                    $title2[$i + 2] = mb_convert_encoding($destinations[$i]->destinationLocation,"SJIS", "UTF-8");
                }
                array_push($title2, "");
                array_push($title2, "");
            } else {
                $title1[0] = mb_convert_encoding("管理ID","SJIS", "UTF-8");
                $title1[1] = mb_convert_encoding("本のタイトル","SJIS", "UTF-8");
                for ($i = 0; $i < count($destinations)/2-1; $i++) {
                    $title1[$i + 2] = "";
                }
                array_push($title1, mb_convert_encoding("配送先","SJIS", "UTF-8"));
                for ($i = 0; $i < count($destinations) - count($destinations)/2-1; $i++) {
                    array_push($title1, "");
                }
                array_push($title1, mb_convert_encoding("出荷計","SJIS", "UTF-8"));
                array_push($title1, mb_convert_encoding("在庫","SJIS", "UTF-8"));
                array_push($title1, mb_convert_encoding("出荷後在庫","SJIS", "UTF-8"));
                $title2[0] = "";
                $title2[1] = "";
                for ($i = 0; $i < count($destinations); $i++) {
                    $title2[$i + 2] = mb_convert_encoding($destinations[$i]->destinationLocation,"SJIS", "UTF-8");
                }
                array_push($title2, "");
                array_push($title2, "");
                // $title2 = ["", "", "QQQ1", "QQQ2", "QQQ3", "QQQ4", "","", ""] ;
            }
            $FH = fopen('php://output', 'w');
            fputcsv($FH, $title1);
            fputcsv($FH, $title2);
            if(Auth::user()->user_role == 3) {
                foreach ($datas as $row) {
                    $tmp = [];
                    $tmp[0] = mb_convert_encoding($row["good_manageId"],"SJIS", "UTF-8");
                    $tmp[1] = mb_convert_encoding($row["good_title"],"SJIS", "UTF-8");
                    for ($i = 0; $i < count($destinations); $i++) {
                        $tmp[$i + 2] = mb_convert_encoding($row["destination_location"][$i]['quantity'],"SJIS", "UTF-8");
                    }
                    // array_push($tmp, mb_convert_encoding($row["all_quantity"],"SJIS", "UTF-8"));
                    array_push($tmp, mb_convert_encoding($row["good_inventory"],"SJIS", "UTF-8"));
                    fputcsv($FH, $tmp);
                }
            } else {
                foreach ($datas as $row) {
                    $tmp = [];
                    $tmp[0] = mb_convert_encoding($row["good_manageId"],"SJIS", "UTF-8");
                    $tmp[1] = mb_convert_encoding($row["good_title"],"SJIS", "UTF-8");
                    for ($i = 0; $i < count($destinations); $i++) {
                        $tmp[$i + 2] = mb_convert_encoding($row["destination_location"][$i]['quantity'],"SJIS", "UTF-8");
                    }
                    array_push($tmp, mb_convert_encoding($row["all_quantity"],"SJIS", "UTF-8"));
                    array_push($tmp, mb_convert_encoding($row["good_inventory"],"SJIS", "UTF-8"));
                    array_push($tmp, mb_convert_encoding($row["remain_quantity"],"SJIS", "UTF-8"));
                    fputcsv($FH, $tmp);
                }
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function ordersRequestDownload() {
        $cnt = 0;
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv; charset=Shift_JIS',
            'Content-Disposition' => `attachment; filename=goods_${cnt}.csv`,
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $goods = User::find(Auth::user()->id)->goods;
        $destinations = User::find(Auth::id())->destinations()->get();
        $dateDatas = [ mb_convert_encoding("発送日","SJIS", "UTF-8"), '2024-02-22'];
        $FH = fopen('php://output', 'w');
        fputcsv($FH, $dateDatas);
        fputcsv($FH, []);
        fclose($FH);
        $callback = function() use ($goods, $destinations)
        {
            $title1[0] = "";
            $title1[1] = "";
            $title2[0] = "";
            $title2[1] = "";
            for ($i = 0; $i < count($destinations)/2-1; $i++) {
                $title1[$i + 2] = "";
            }
            array_push($title1, mb_convert_encoding("配送先ID /ラベル","SJIS", "UTF-8"));
            for ($i = 0; $i < count($destinations) - count($destinations)/2-1; $i++) {
                array_push($title1, "");
            }
            array_push($title1, "");
            array_push($title1, "");

            for ($i = 0; $i < count($destinations); $i++) {
                $title2[$i + 2] = mb_convert_encoding($destinations[$i]->id,"SJIS", "UTF-8");
            }
            array_push($title2, "");
            // array_push($title2, "");

            $title3[0] = mb_convert_encoding("管理ID","SJIS", "UTF-8");
            $title3[1] = mb_convert_encoding("本のタイトル","SJIS", "UTF-8");
            for ($i = 0; $i < count($destinations); $i++) {
                $title3[$i + 2] = mb_convert_encoding($destinations[$i]->destinationLocation,"SJIS", "UTF-8");
            }
            // array_push($title3, mb_convert_encoding("出荷計","SJIS", "UTF-8"));
            array_push($title3, mb_convert_encoding("在庫","SJIS", "UTF-8"));

            $FH = fopen('php://output', 'w');
            fputcsv($FH, $title1);
            fputcsv($FH, $title2);
            fputcsv($FH, $title3);
            foreach ($goods as $row) {
                $tmp = [];
                $tmp[0] = mb_convert_encoding($row["manageGoodsId"],"SJIS", "UTF-8");
                $tmp[1] = mb_convert_encoding($row["goodsTitle"],"SJIS", "UTF-8");
                for ($i = 0; $i < count($destinations); $i++) {
                    $tmp[$i+2] = "";
                }
                // array_push($tmp, "");
                array_push($tmp, mb_convert_encoding($row["goodsInventory"],"SJIS", "UTF-8"));
                fputcsv($FH, $tmp);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function orderRequestUpload(Request $request)
    {
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(4);
        $datas = $csv->getRecords();
        $destinations = User::find(Auth::id())->destinations()->get();

        $keys = [];
        $destinationIds = [];
        $all_quantity = 0;
        $delivery_date = '';
        $flag = true;
        $isOver = false;

        foreach ($datas as $key => $data) {
            if ($key == 0) {
                $delivery_date = $data['本のタイトル'];
            }else if($key == 3) {
                $cnt = -2;
                foreach ($data as $k => $d) {
                    $keys[$cnt] = $k;
                    $destinationIds[$cnt] = $d;
                    $cnt++;
                    if ($cnt >= count($destinations)) break;
                }
            }else if ($key > 3) {
                for ($i = 0; $i < count($destinations); $i++) {
                    $all_quantity += (int)$data[$keys[$i]];
                }
                $goodsInventory = $data['在庫'];
                if((int) $goodsInventory < $all_quantity) {
                    $isOver = true;
                    break;
                }
            }
        }
        $user = User::find(Auth::user()->id);
        array_splice($destinationIds, 0, 2);
        foreach ($destinationIds as $destinationId) {
            if($user->destinations()->whereHas('user_destinations', function($q) use ($destinationId) {
                $q->where('destination_id', $destinationId); 
            })->exists()) {
                continue;
            } else {
                $flag = false;
                break;
            }
        }
        if($flag && !$isOver) {
            try{
                $dateFormat = Carbon::createFromFormat('n/j/Y', $delivery_date)->format('Y-m-d');
                $newOrder = Order::create([
                    'order_name' => 'AA-3',
                    'user_id' => Auth::user()->id,
                    'status' => '発送前',
                    'delivery_date' => '',
                    'estimate_delivery_date' => $dateFormat,
                ]);
                $newOrder->order_name = 'AAD-' . $newOrder->id;
                $newOrder->save();

                $getDataCnt = 0;
                foreach ($datas as $key => $data) {
                    $getDataCnt++;
                    if($getDataCnt > 4) {
                        $manageGoodsId = $data['管理ID'];
                        $goodsTitle = $data['本のタイトル'];
                        $goodsInventory = $data['在庫'];

                        $dQuantities = [];
                        for ($i = 0; $i < count($destinations); $i++) {
                            $dQuantities[$i]= $data[$keys[$i]] ? $data[$keys[$i]] : 0;
                        }
                        $good_id = Good::where('manageGoodsId', $manageGoodsId)->first()->id;
                        for($i = 0; $i < count($destinations); $i++) {
                            $manageOrders = ManageOrder::create([
                                'order_id' => $newOrder->id,
                                'good_id' => $good_id,
                                'destination_id' => $destinationIds[$i],
                                'quantity' => $dQuantities[$i],
                            ]);
                        }
                    }
                }
                $emailParams = new \stdClass(); 
                $emailParams->usersName = Auth::user()->company_name;
                $emailParams->usersEmail = "info@grandwork.jp";
                $emailParams->subject = $newOrder->order_name;
                $orderDetailLink = "https://inventory-dev.lowcost-print.com/orders/" . Auth::user()->id . "/" . $newOrder->id;
                $emailParams->orderDetailLink = $orderDetailLink;
                Mail::to($emailParams->usersEmail)->send(new SendMailWhenRequest($emailParams));
                $emailParams->usersEmail = "s_kawaguchi@shotka.net";
                Mail::to($emailParams->usersEmail)->send(new SendMailWhenRequest($emailParams));
                return "success";
            } catch (\Exception $e) {
                // throw new \Exception($e);
                return $e->getMessage();
            }
            // return redirect()->back()->with('success', 'CSV file uploaded and processed successfully.');
        } else {
            return "falid errorororororor";
        }
    }
}
