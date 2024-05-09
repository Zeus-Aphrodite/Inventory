<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Destination;
use App\Models\UserDestination;
use App\Models\Destinationpagenumber;
use Auth;

class destinationManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $rowNumber = Destinationpagenumber::where('user_id', auth()->user()->id)->first();
        $users = User::where('user_role', 3)->get();
        if (Auth::user()->user_role == 3) {
            $user = User::find(Auth::user()->id);
        } else {
            $user = User::where('user_role', 3)->first();
        }
        $datas = $user->destinations()->paginate($rowNumber->rowNumber);
        return view('destinations.shippingDestinationsManagement')
                        ->with("datas", $datas)->with("users", $users)
                        ->with("selectedUser", $user)
                        ->with("rowNumber", $rowNumber->rowNumber);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->input('clientId');
        $destinationName = $request->input('destinationName');
        $locationName = $request->input('locationName');
        $streetAddressName = $request->input('streetAddressName');
        $destinationBuildingName = $request->input('destinationBuildingName');

        $postCodeSuffix = $request->input('post_code_suffix');
        $postCodePrefix = $request->input('post_code_prefix');
        $destinationPostCode = $postCodePrefix . '-' . $postCodeSuffix;

        $destination = Destination::create([
            "destinationName" => $destinationName,
            "destinationPostCode" => $destinationPostCode,
            "destinationLocation" => $locationName,
            "destinationStreetAdress" => $streetAddressName,
            "destinationBuildingName" => $destinationBuildingName,
        ]);

        $destination_id = $destination->id;

        $res = UserDestination::create([
            "user_id" => $id,
            "destination_id" => $destination_id,
        ]);
        return 'success';
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $rowNumber = Destinationpagenumber::where('user_id', auth()->user()->id)->first();
        $value = $request->input('value');
        $users = User::where('user_role', 3)->get();
        $user = User::find($id);
        // $datas = User::find($id)->destinations()->paginate(5);
        if (Auth::user()->user_role == 3) {
            $query = User::find(Auth::user()->id)->destinations();
        } else {
            $query = User::find($id)->destinations();
        }
        if ($value && $value != "selectedOption") {
            $query = $query ->where('destinationName', 'like', '%' . $value . '%')
                            ->where('user_id', $id);
        }
        $datas = $query->paginate($rowNumber->rowNumber);
        return view('destinations.shippingDestinationsManagement')
                        ->with("datas", $datas)
                        ->with("users", $users)->with("selectedUser", $user)
                        ->with("rowNumber", $rowNumber->rowNumber);;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, )
    {
        $formData = $request->all();
        $uid = $formData["clientId"];
        $destination_id = $id;
        $postCode = $formData['post_code_prefix']. '-'. $formData['post_code_suffix'];
        try {
            $destination = Destination::find($id);
            $destination->destinationName = $formData['destinationName'];
            $destination->destinationPostCode = $postCode;
            $destination->destinationLocation = $formData['locationName'];
            $destination->destinationStreetAdress = $formData['streetAddressName'];
            $destination->destinationBuildingName = $formData['destinationBuildingName'];
            $destination->save();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $destination = Destination::find($id);
            $destination->delete();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error']);
        }
    }

    public function changeRowNumber(Request $request, $id) {
        try {
            $currentUserRowNumber = Destinationpagenumber::where('user_id', auth()->user()->id)->first();
            $currentUserRowNumber->rowNumber = (int) $id;
            $currentUserRowNumber->save();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error']);
        }
    }
}
