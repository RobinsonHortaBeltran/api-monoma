<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MyValidateRequest;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
class CandidateController extends Controller
{
    public function index()
    {
        try {
            if (Auth::user()->role == "manager") {
                $key = 'candidates';
                // Verificar si los datos están en caché
                if (Cache::has($key)) {
                    $candidate = Cache::get($key);   
                }else {
                    $candidate = Candidate::with('owner_data')->with('created_by_data')->get();
                    // Guardar los datos en caché durante 60 segundos
                    Cache::put($key, ['candidates' => $candidate], 120);
                }
            } elseif (Auth::user()->role == "agent") {
                $key = 'candidates';
                // Verificar si los datos están en caché
                if (Cache::has($key)) {
                    $candidate = Cache::get($key);
                }else {
                    $candidate = Candidate::with('owner_data')->with('created_by_data')->where('owner', Auth::user()->id)->get();
                    // Guardar los datos en caché durante 60 segundos
                    Cache::put($key, ['candidates' => $candidate], 120);
                }
            }
            if ($candidate) {
                $response = [
                    "meta" => [
                        "success" => true,
                        "errors" => []
                    ],
                    "data" => $candidate
                ];
                $status=201;
               
            } else {
                $response = [
                    "meta" => [
                        "success" => false,
                        "errors" => ["Token expired"]
                    ]
                ];
                $status= 401;
            }
            return response()->json($response, $status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param MyValidateRequest $request
     * @return Renderable
     */

    public function store(Request $request, Candidate $candidate)
    {

        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:255',
            'source' => 'required',
            'owner'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            try {
                switch (Auth::user()->role) {
                    case 'manager':
                        $verify = Candidate::where('name', $request->name)->first();
                        if ($verify) {
                            return "Existe";
                        } else {
                            $candidate->name       = $request->name;
                            $candidate->source     = $request->source;
                            $candidate->owner      = $request->owner;
                            $candidate->created_by = Auth::user()->id;
                            if ($candidate->save()) {
                                $response = [
                                    "meta" => [
                                        "success" => true,
                                        "errors" => []
                                    ],
                                    "data" => $candidate
                                ];
                                return response()->json($response, 201);
                            } else {
                                $response = [
                                    "meta" => [
                                        "success" => false,
                                        "errors" => ["Token expired"]
                                    ]
                                ];
                                return response()->json($response, 401);
                            }
                        }
                        break;
                    case 'agent':
                        return "No puedes crear candidatos";
                        break;

                    default:
                        # code...
                        break;
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function show($id){
        try {
            if (Auth::user()->role=="manager") {
                $candidate = Candidate::where('id', $id)->with('owner_data')->with('created_by_data')->first();
                $response = [
                    "meta" => [
                        "success" => true,
                        "errors" => []
                    ],
                    "data" => $candidate
                ];
               $status= 200;
            }elseif (Auth::user()->role == "agent") {
                 $verify= Candidate::where('id',$id)->with('owner_data')->with('created_by_data')->where('owner', Auth::user()->id)->first();
                  if($verify){
                    $response = [
                        "meta" => [
                            "success" => true,
                            "errors" => []
                        ],
                        "data" => $verify
                    ];

                    $status= 200;
                  }else {
                    $response = [
                        "meta" => [
                            "success" => false,
                            "errors" => ["No lead found"]
                        ]
                    ];
                    $status= 404;
                  }
            }

            return response()->json($response, $status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
