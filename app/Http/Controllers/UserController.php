<?php

namespace App\Http\Controllers;

use App\Filter\UserFilter;
use App\Jobs\CreateUser;
use App\Models\Continent;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only('index', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $continents = Continent::all()->unique('continent');
        $countries = Country::has('users')->orderBy('country')->get();
        $users = User::with('country');
        $emails = $users->pluck('email');
        $users = (new UserFilter($users, $request))->apply()->get();

        return response()->view('users',
            [
                'users' => $users,
                'emails' => $emails,
                'countries' => $countries,
                'continents' => $continents,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        CreateUser::dispatch($request);
        return response()->redirectTo(route('users.index'))
            ->with('success', "New users successfully added.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->view('profile', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $countries = Country::all();
        return response()->view('forms.edit', ['user' => $user, 'countries' => $countries]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required|min:5|max:255',
                'email' => 'required|email|max:255',
            ]
        );

        if($validator->fails()){
            return back()
                ->withErrors($validator->errors())
                ->withInput(request()->all());
        } else {
            $user = User::find($id);
            $user->name = $request->name;
            $user->country_id = $request->country;

            if($request->email !== $user->email) {
                $validator = Validator::make(request()->all(), ['email' => 'unique:users',]);
                if ($validator->fails()) {
                    return back()->withErrors($validator->errors());
                } else {
                    $user->email = $request->email;
                }
            }

            if($request->old_password !== null){
                if(Hash::check($request->old_password, $user->password)){
                    $validator = Validator::make(request()->all(), ['new_password' => 'required|min:8|max:255',]);
                    if ($validator->fails()) {
                        return back()->withErrors($validator->errors());
                    } else {
                        $user->password = Hash::make($request->new_password);
                    }
                } else{
                    return back()->with('error', 'Your old password is incorrect.');
                }
            }

            $user->save();

            return response()->redirectTo(route('users.show', ['user' => Auth::user()->id]))
                ->with('success', "Profile was successfully updated.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $project = User::findOrFail($id);
        $project->delete();
        return response()->redirectTo(route('home'));
    }
}
