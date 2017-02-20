<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin', auth()->user());
    	$limit = request()->limit;
        return User::paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin', auth()->user());
        $validator = $this->validator($request->only(['name', 'username', 'password', 'role']));

        if($validator->fails())
        {
            return response()->json([
                'created' => false,
                'errors' => $validator->errors(),
                'user' => $request->all()
            ])->setStatusCode(422);
        }

        $user = User::create([
        	'name' => $request->name,
        	'username' => $request->username,
        	'password' => bcrypt($request->password),
        	'role' => $request->role,
        ]);
        return [
            'created' => true,
            'user' => $user
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin', auth()->user());
        $user = User::findOrFail($id);
        $validator = $this->validator($request->only(['id', 'name', 'username', 'password', 'role']), true);

        if($validator->fails())
        {
            return response()->json([
                'updated' => false,
                'errors' => $validator->errors(),
                'user' => $user
            ])->setStatusCode(422);
        }

        $user->update($request->intersect(['name', 'username', 'role']));
        if(trim($request->password) !== '')
        	$user->update(['password' => bcrypt($request->password)]);
        return [
            'updated' => true,
            'user' => $user
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('admin', auth()->user());
        $user = User::findOrFail($id);
        $user->delete();
        return ['deleted' => true];
    }

    public function validator($data, $update = false)
    {
    	if(!$update)
        	$validator = Validator::make($data, [
	            'name' => ['required', 'min:3', 'max:200'],             
	            'username' => ['required', 'min:3', 'max:20', 'unique:users,username'],
	            'password' => ['required'],
	            'role' => ['required', Rule::in(['admin', 'user'])]
	        ]);
        else 
        	$validator = Validator::make($data, [
	            'name' => ['required', 'min:3', 'max:200'],             
	            'username' => ['required', 'min:3', 'max:20', Rule::unique('users')->ignore($data['id'])],
	            'password' => ['present'],
	            'role' => ['required', Rule::in(['admin', 'user'])]
	        ]);
        return $validator;
    }
}
