<?php

namespace App\Http\Controllers;
use App\Mail\RegisterMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register', 'verification']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(User::where("email", $request->get("email"))->where("verification", 1)->first()) {
            $credentials = $request->only('email', 'password');

            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }

        return response()->json([
            'status' => 'warning',
            'message' => 'Kayıt işlemi yapılırken tarafınıza gönderilen eposta ile üyeliğinizi onaylamanız gerekmektedir.',
        ]);

    }

    public function register(Request $request){
        $this->validator()->validate();

        $user = User::create([
            'name' => $request->get("name"),
            'email' => $request->get("email"),
            'verification' => 0,
            'password' => Hash::make($request->get("password")),
        ]);

//        $token = Auth::login($user);

        $data["name"] = $request->get("name");
        $data["email"] = $request->get("email");
        Mail::to($request->get("email"))->send(new RegisterMail($data));


        return response()->json([
            'status' => 'success',
            'message' => 'Üyeliğiniz başarılı bir şekilde yapıldı. Tarafınıza gönderilen epostayı onayladıktan sonra giriş yapabilirsiniz.',
//            'user' => $user,
//            'authorisation' => [
//                'token' => $token,
//                'type' => 'bearer',
//            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Oturum başarıyla kapatıldı',
        ]);
    }

    public function show()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user){
            if($user->email != request()->get('email')) {
                if (User::where("email", request()->get('email'))->first()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Girdiğiniz e-posta başka biri tarafından kullanılıyor.',
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kullanıcı bulunamadı.',
            ]);
        }

        $this->validator()->validate();
        $fields = array();
        $fields['name'] = request()->get('name');

        if(request()->get('email'))
            $fields['email'] = request()->get('email');

        if(request()->get('password'))
            $fields['password'] = Hash::make(request()->get('password'));

        try {
            $this->model = User::find($id);
            foreach ($fields as $key => $field)
                $this->model->$key = $field;

            $this->model->save();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        } finally {
            return response()->json([
                'status' => 'success',
                'message' => 'Kayıt başarıyla güncellendi!',
            ]);
        }
    }

    public function verification(Request $request, $email){
        $user = User::where("email", $email)->first();
        if($user) {
            if($user->verification == 1)
                return 'Üyeliğiniz aktif durumdadır.';

            $user->verification = 1;
            $user->save();

            if($user)
                return 'Üyeliğiniz onaylanmıştır.';
        }
        return 'Kullanıcı bilgisi bulunamadı';
    }

    protected function validator()
    {
        if(request()->getMethod() == 'PUT'){
            $rules = array(
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            );
        } else {
            $rules = array(
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            );
        }
        $attributes = array(
            'name' => 'Kullanıcı Adı',
            'email' => 'Email',
            'password' => 'Şifre',
        );
        return Validator::make(\request()->all(), $rules,array(),$attributes);
    }

}
