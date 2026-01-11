<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function adminDashboard()
    {
        // Render the main admin dashboard page
        return view('admin.index');
    }

    /**
     * Log out the current admin user and redirect to login page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminLogout(Request $request): RedirectResponse
    {
        // Log out the user from the 'web' guard
        Auth::guard('web')->logout();

        // Invalidate the current session to prevent reuse
        $request->session()->invalidate();

        // Regenerate CSRF token for security
        $request->session()->regenerateToken();

        // Redirect to the custom admin login route
        return redirect()->route('admin.login');
    }

    /**
     * Show the admin login form or handle login POST.
     */
    public function adminLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->only('login', 'password');
            // Accept login by email, name, or phone
            $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($credentials['login']) ? 'phone' : 'name');
            $loginField = [$loginType => $credentials['login'], 'password' => $credentials['password']];
            if (Auth::attempt($loginField, $request->has('remember'))) {
                $user = Auth::user();
                if ($user->roles === 'admin') {
                    $request->session()->regenerate();
                    return redirect()->intended(route('admin.dashboard'));
                }

                // If not an admin, log them out immediately
                Auth::guard('web')->logout();
                return back()->withErrors(['login' => 'Unauthorized access. Only admins can login here.'])->withInput();
            }
            return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
        }
        // Render the login page
        return view('admin.admin_login');
    }
    public function adminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function adminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username; // assuming username column exists
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data->photo = $filename;
        }

        $data->save();

        // Basic redirect back with success message (requires toastr or similar on frontend to see, or session flash)
        return redirect()->back()->with('message', 'Admin Profile Updated Successfully');
    }

}
