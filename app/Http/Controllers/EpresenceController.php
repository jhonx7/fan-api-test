<?php

namespace App\Http\Controllers;

use App\Models\Epresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EpresenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/presences",
     *     tags={"Presences"},
     *     summary="Show User Presences By Month",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="month",
     *          description="Bulan",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          @OA\Examples(example="Agustus 2023", value="08", summary="Month"),
     *     ),
     *     @OA\Parameter(
     *          name="year",
     *          description="Tahun",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          @OA\Examples(example="Agustus 2023", value="2023", summary="Year"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent())
     *     )
     * )
     */
    public function index(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        try {
            $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);

            $tanggal_awal = mktime(0, 0, 0, (int)$month, '1', (int)$year);
            $tanggal_akhir = mktime(0, 0, 0, (int)$month, (int)$jumlah_hari, (int)$year);

            $data = array();
            for ($i = $tanggal_awal; $i <= $tanggal_akhir; $i += 60 * 60 * 24) {
                $clockin = Epresence::where('user_id', auth()->id())->where('type', 'IN')->whereDate('waktu', date('Y-m-d', $i))->first();
                $clockout = Epresence::where('user_id', auth()->id())->where('type', 'OUT')->whereDate('waktu', date('Y-m-d', $i))->first();

                if ($clockin or $clockout) {
                    array_push($data, [
                        'id_user' => auth()->id(),
                        'nama_user' => auth()->user()->name,
                        'tanggal' => date('Y-m-d', $i),
                        'waktu_masuk' => $clockin ? date('H:i:s', strtotime($clockin->waktu)) : null,
                        'waktu_pulang' => $clockout ? date('H:i:s', strtotime($clockout->waktu)) : null,
                        'status_masuk' => $clockin ? ($clockin->is_approve ? "APPROVE" : "REJECT") : "REJECT",
                        'status_pulang' => $clockout ? ($clockout->is_approve ? "APPROVE" : "REJECT") : "REJECT",
                    ]);
                }
            }

            return response()->json([
                "message"   => "Berhasil Mendapatkan Data",
                "data" => $data
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                "message"   => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/presences",
     *     tags={"Presences"},
     *     summary="User Presence",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="waktu",
     *                     type="string"
     *                 ),
     *                 example={"type": "IN", "waktu": "2023-08-08 08:00:00"}
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
     *                          "message": "Berhasil Absen",
     *                          "data": {},
     *                  }, 
     *                      summary="An result object."
     *                 ),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required'],
            'waktu' => ['required'],
        ]);
        try {

            $clockin = Epresence::where('user_id', auth()->id())->where('type', 'IN')->whereDate('waktu', date('Y-m-d', strtotime($request->waktu)))->first();
            if ($request->type == 'IN') {
                if ($clockin) {
                    return response()->json([
                        "message"   => "Anda sudah Clock In",
                        "data" => []
                    ], 400);
                }
                Epresence::create([
                    'user_id' => auth()->id(),
                    'type'     => $request->type,
                    'waktu'     => $request->waktu,
                ]);
            } else {
                if (!$clockin) {
                    return response()->json([
                        "message"   => "Anda belum Clock In",
                        "data" => []
                    ], 400);
                }
                $clockout = Epresence::where('user_id', auth()->id())->where('type', 'OUT')->whereDate('waktu', date('Y-m-d', strtotime($request->waktu)))->first();
                if ($clockout) {
                    return response()->json([
                        "message"   => "Anda sudah Clock Clockout",
                        "data" => []
                    ], 400);
                }
                Epresence::create([
                    'user_id' => auth()->id(),
                    'type'     => $request->type,
                    'waktu'     => $request->waktu,
                ]);
            }
            return response()->json([
                "message"   => "Berhasil Absen",
                "data" => []
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message"   => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

    /**
     * @OA\PATCH(
     *     path="/api/presences",
     *     tags={"Presences"},
     *     summary="User Presence Approval by Supervisor",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="epresence_id",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="approve",
     *                     type="boolean"
     *                 ),
     *                 example={"epresence_id": 1, "approve": true}
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
     *                          "message": "Berhasil mengubah data",
     *                          "data": {},
     *                  }, 
     *                      summary="An result object."
     *                 ),
     *         )
     *     )
     * )
     */
    public function approval(Request $request)
    {
        $validated = $request->validate([
            'epresence_id' => ['required'],
            'approve' => ['required'],
        ]);

        try {
            $epresence = Epresence::find($request->epresence_id);
            if (!$epresence) {
                return response()->json([
                    "message"   => "Data tidak ditemukan",
                    "data" => []
                ], 404);
            }
            if ($epresence->user->npp_supervisor != auth()->user()->npp) {
                return response()->json([
                    "message"   => "Anda tidak memiliki izin untuk mengubah data ini",
                    "data" => []
                ], 401);
            }

            $epresence->update([
                'is_approve'=> $request->approve,
            ]);
            return response()->json([
                "message"   => "Berhasil mengubah data",
                "data" => []
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "message"   => $th->getMessage(),
                "data" => []
            ], 500);
        }
    }

}
