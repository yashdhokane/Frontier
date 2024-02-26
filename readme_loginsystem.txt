1) composer create-project laravel/laravel login_system_1
============================================

2) cd login_system_1
===========================================

3) code .
===============================================

4) php artisan serve
====================================================

5) composer require laravel/breeze (vscode)
==============================================

6) php artisan breeze:install

Would you like dark mode support? (yes/no) [no]
❯ n


Which testing framework do you prefer? [PHPUnit] PHPUnit ........................................................................................................................................ 0  Pest ........................................................................................................................................... 1
❯ 1
========================================================================
7) npm run build
====================================================================

8) set env file
====================================================================

9) set migration file (user)

   public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('usertype')->default('user');
            $table->rememberToken();
            $table->timestamps();
        });
    }
============================================================

10) php artisan migrate 
===========================================================

11) changes in registercontroller
 public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'min:10', 'max:12'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
===============================================================

12) changes in view auth register.blade.php

<div>
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="lastname" :value="__('Last Name')" />
            <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="number" name="phone" :value="old('phone')" required autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
=================================================================

13) changes in model user.php

* @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
    ];

==================================================================

14) go to app/provider/routeserviceprovider.php

change 
   public const HOME = '/Dashboard';
 to 
   public const HOME = '/home';

====================================================================

15) got to web.php & add 
	route::get('/home', [HomeController::class, 'index']);
=====================================================================

16) php artisan make:controller HomeController
====================================================================

17) then go to home controller 

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
   public function index(){

    if(Auth::id()){
        $usertype=Auth()->use()->usertype;

        if($usertype=='user'){
            return view('dashboard');
        }else if($usertype=='admin'){
            return view('admiin.adminhome');
        }else{
            return redirect()->back();
        }
    }
    
   }
}

add this 
================================================================

18) then check login for user and admin
================================================================

19) now got resoursce app layout -> nevigation.blade.php

change navigation to home and home again and change in welcome.blade.php home also
================================================================

20) then change the route by adding midleware

	route::get('/home', [HomeController::class, 'index'])->	middleware('auth')->name('home');

===================================================================
21) now create 

22) php artisan make:middleware Admin

    public function handle(Request $request, Closure $next): Response
    {
        

        if(Auth()->user()->usertype=='admin'){

            return $next($request);

        }
        abort(401);
    }

=====================================================================

23) also go to kernal.php

and add 

'admin' => \App\Http\Middleware\Admin::class,


=====================================================================
24) 
then made changes in profile controller and profile blade 

first update profile details and password
 in view/layouts/profile/partials/update-profile-information-form.blade.php

<div>
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" :value="old('firstname', $user->firstname)" required autofocus autocomplete="firstname" />
            <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
        </div>
        <div>
            <x-input-label for="lastname" :value="__('Last Name')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $user->lastname)" required autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>
=======================================================================

25) went to  controller/request/Auth/profileupdaterequest.php and made chages for request validation

<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'phone' => ['string', 'max:10'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}

========================================================================







