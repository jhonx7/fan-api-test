<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 
     *                 example={"email": "user1@test.com", "password": "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                 @OA\Schema(ref="#/components/schemas/Result"),
     *                 @OA\Examples(
     *                      example="result", 
     *                      value={
     *                          "message": "Berhasil Login",
     *                          "data": {
     *                              "user": {
     *                                  "id": 1,
     *                                  "name": "Joe Doe",
     *                                  "npp": 111111,
     *                                  "npp_supervisor": 222222,
     *                                  "email": "test@test.com",
     *                                  "updated_at": "2023-08-01T06:42:15.000000Z",
     *                                  "created_at": "2023-08-01T06:42:15.000000Z",
     *                              },
     *                          "token": "1|KYTyWragQxSyYSx7NOgZ9FsLseJqDc3DMK5w7j1j"
     *                          }
     *                  }, 
     *                      summary="An result object."
     *                 ),
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if ($user !== null) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken($user->id)->plainTextToken;
                    $data = [
                        'user' => $user,
                        'token' => $token,
                    ];
                    return response()->json([
                        "message"   =>"Berhasil Login",
                        "data" => $data
                    ], 200);
                } else {
                    return response()->json([
                        "message"   =>"'Maaf, username atau password yang Anda masukkan salah.'",
                        "data" => []
                    ], 400);
                }
            } else {
                return response()->json([
                    "message"   =>"'Maaf, username atau password yang Anda masukkan salah.'",
                    "data" => []
                ], 400);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message"   =>$th->getMessage(),
                "data" => []
            ], 500);
        }
    }

     /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="npp_supervisor",
     *                     type="number"
     *                 ),
     *                 
     *                 example={"name": "Joe Doe", "email": "test@test.com", "password": "Password", "npp_supervisor": null}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                 @OA\Schema(ref="#/components/schemas/Result"),
     *                 @OA\Examples(
     *                      example="result", 
     *                      value={
     *                          "message": "Berhasil Mendaftar!",
     *                          "data": {
     *                              "user": {
     *                                  "id": 1,
     *                                  "name": "Joe Doe",
     *                                  "npp": 111111,
     *                                  "npp_supervisor": 222222,
     *                                  "email": "test@test.com",
     *                                  "updated_at": "2023-08-01T06:42:15.000000Z",
     *                                  "created_at": "2023-08-01T06:42:15.000000Z",
     *                              },
     *                          "token": "1|KYTyWragQxSyYSx7NOgZ9FsLseJqDc3DMK5w7j1j"
     *                          }
     *                  }, 
     *                      summary="An result object."
     *                 ),
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required'],
            'npp_supervisor' => ['nullable'],
        ]);

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'npp' => rand(100000, 1000000),
                'npp_supervisor' => $request->npp_supervisor ?? null,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken($user->id)->plainTextToken;
            $data = [
                'user' => $user,
                'token' => $token,
            ];
            
            return response()->json([
                "message"   =>"Berhasil Mendaftar!",
                "data" => $data
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message"   =>$th->getMessage(),
                "data" => []
            ], 500);
        }
    }
    
}
