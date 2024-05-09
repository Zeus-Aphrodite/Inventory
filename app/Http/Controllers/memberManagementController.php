<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Destinationpagenumber;
use Auth;

class memberManagementController extends Controller
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

    public function index(Request $request)
    {
        $item = $request->input('value');
        if($item) {
            $members = User::where(function ($query) use ($item) {
                                $query->where('name', 'like', '%' . $item . '%')
                                    ->orWhere('email', 'like', '%' . $item . '%')
                                    ->orWhere('company_name', 'like', '%' . $item . '%');
                            })
                            ->paginate(5);
            return view('members/viewAllmembers')->with("members", $members);
        }else {
            $members = User::paginate(5);
            return view('members/viewAllmembers')->with("members", $members);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_name = $request->input('company_name');
        $name = $request->input('name');
        $email = $request->input('email');
        $furigana_name = $request->input('furigana_name');
        $password = $request->input('password');
        $phone_number = $request->input('phone_number');
        $post_code_prefix = $request->input('post_code_prefix');
        $post_code_suffix = $request->input('post_code_suffix');
        $location = $request->input('location');
        $street_adress = $request->input('street_adress');
        $building_name = $request->input('building_name');
        $selecetPermission = $request->input('selecetPermission');

        $post_code = $post_code_prefix. "-". $post_code_suffix;
        
        $formData = array(
            'company_name' => $company_name,
            'name' => $name,
            'email' => $email,
            'furigana_name' => $furigana_name,
            'password' => $password,
            'phone_number' => $phone_number,
            'post_code' => $post_code,
            'location' => $location,
            'street_adress' => $street_adress,
            'building_name' => $building_name,
            'user_role' => $selecetPermission,
        );
        try {
            $user = User::create($formData);
            $user_id = $user->id;
            $num = array(
                'user_id'=> $user_id,
                'rowNumber'=> 10 
            );
            Destinationpagenumber::create($num);
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company_name = $request->input('company_name');
        $name = $request->input('name');
        $email = $request->input('email');
        $furigana_name = $request->input('furigana_name');
        $password = $request->input('password');
        $phone_number = $request->input('phone_number');
        $post_code_prefix = $request->input('post_code_prefix');
        $post_code_suffix = $request->input('post_code_suffix');
        $location = $request->input('location');
        $street_adress = $request->input('street_adress');
        $building_name = $request->input('building_name');
        $selecetPermission = $request->input('selecetPermission');
        
        $formData = array(
            'company_name' => $company_name,
            'name' => $name,
            'email' => $email,
            'furigana_name' => $furigana_name,
            'password' => $password,
            'phone_number' => $phone_number,
            'post_code' => $post_code_prefix . "-" . $post_code_suffix,
            'location' => $location,
            'street_adress' => $street_adress,
            'building_name' => $building_name,
            'user_role' => $selecetPermission,
        );
        try {
            $user = User::find($id);
            $user->company_name = $formData['company_name'];
            $user->name = $formData['name'];
            $user->email = $formData['email'];
            $user->furigana_name = $formData['furigana_name'];
            if ($password) $user->password = $formData['password'];
            $user->phone_number = $formData['phone_number'];
            $user->post_code = $formData['post_code'];
            $user->location = $formData['location'];
            $user->street_adress = $formData['street_adress'];
            $user->building_name = $formData['building_name'];
            if ($selecetPermission) $user->user_role = $formData['user_role'];
            $user->save();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error']);
        }
    }

    protected function messages() 
    {
        return [
            'company_name.required' => '法人名の入力は必須です。',
            'name.required' => '担当者氏名の入力は必須項目です。',
            'furigana_name.required' => '担当者のふりがなの入力は必要です。',
            'phone_number.required' => '電話番号の入力は必須です。',
            'phone_number.min' => '電話番号を正確に入力してください。',
            'phone_number.max' => '電話番号を正確に入力してください。',
            'post_code_subfix.required' => '郵便番号のエントリは必須です。',
            'post_code_subfix.max' => '郵便番号を正確に入力してください。',
            'location.required' => '住所項目フィールドは必須です。',
            'street_adress.required' => '番地項目フィールドは必須です。',
            'email.required' => '電子メールフィールドは必須です。',
            'email.email' => '有効な電子メール アドレスを入力してください。',
            'email.unique' => '電子メール アドレスはすでに使用されています。',
            'password.required' => 'パスワードフィールドは必須です。',
            'password.min' => 'パスワードは少なくとも 8 文字である必要があります。',
            
        ];
    }

    public function validation(Request $request) {
        $allParameters = $request->all();
        return Validator::make($allParameters, [
            'name' => ['required','string', 'max:255'],
            'company_name' => ['required','string', 'max:255'],
            'furigana_name' => ['required','string', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['required', 'string', 'max:11', 'min:10'],
            'post_code_prefix' => ['required', 'string', 'max:3'],
            'post_code_suffix' => ['required', 'string', 'max:4'],
            'location' => ['required','string', 'max:255'],
            'street_adress' => ['required','string', 'max:255'],
            'building_name' => ['nullable', 'string', 'max:255'],
        ], $this->messages());
    }

    public function editMemberInfor(Request $request) {
        return view('members.newMember');
    }
}
