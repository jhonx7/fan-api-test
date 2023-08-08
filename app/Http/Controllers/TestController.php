<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/pairs",
     *     tags={"Test"},
     *     summary="Count Number Pairs of Array",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="string"
     *                 ),
     *                 example={"data": "[5, 7, 7, 9, 10, 4, 5, 10, 6, 5]"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                 @OA\Schema(ref="#/components/schemas/Result"),
     *         )
     *     )
     * )
     */
    public function pairs(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);
        $array = json_decode($request->data);
        return getNumberPairs($array);
    }
    /**
     * @OA\Post(
     *     path="/api/word",
     *     tags={"Test"},
     *     summary="Count Word of Sentence",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="string"
     *                 ),
     *                 example={"data": "Kemarin Shopia per[gi ke mall."}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                 @OA\Schema(ref="#/components/schemas/Result"),
     *         )
     *     )
     * )
     */
    public function word(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);
        return countWord($request->data);
    }
}
