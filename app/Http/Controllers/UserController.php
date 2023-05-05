<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;

class UserController
{
    use ApiResponse;

    /**
     * Display the authenticated user's data.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();

        return $this->successResponse(new UserResource($user));
    }

    /**
     * Update the authenticated user's data in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userId = auth()->id();
        $user=User::find($userId);

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8',
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(), 422);       
        }

        $user->name = $input['name'];
        $user->email = $input['email'];
        if (isset($input['password'])) {
            $user->password = Hash::make($input['password']);
        }
        $user->save();

        return $this->customResponse(new UserResource($user), 'User updated successfully.', 200);
    }
}