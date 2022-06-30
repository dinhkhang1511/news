<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthConTroller extends Controller
{
    //
    public function checkLogin(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|min:6'
        ],[
            'email.required'   => 'Vui lòng nhập email',
            'email.email'      => 'Vui lòng nhập đúng email',
            'password.unique'  => 'Vui lòng nhập mật khẩu',
            'password.min'     => 'Mật khẩu phải lớn hơn 6 ký tự'
        ]);


        $email = $request->email;
        $password = $request->password;
        if(Auth::attempt(['email' => $email, 'password' => $password]))
        {

        }

    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'confirmPassword'  => 'required|min:6'
        ],[
            'name.required'              => 'Vui lòng nhập tên',
            'email.required'             => 'Vui lòng nhập email',
            'email.unique'               => 'Email đã có người sử dụng',
            'email.email'                => 'Vui lòng nhập đúng email',
            'password.required'          => 'Vui lòng nhập mật khẩu',
            'password.min'               => 'Mật khẩu phải lớn hơn 6 ký tự',
            'confirmPassword.required'  => 'Vui lòng nhập mật khẩu xác nhận'
        ]);


        if($request->password === $request->confirmPassword)
        {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login')->with('success','Đăng ký thành công');
        }
        else{
            return redirect()->back()->with('error', 'Mật khẩu và mật khẩu xác nhận không giống nhau');
        }


    }
}
