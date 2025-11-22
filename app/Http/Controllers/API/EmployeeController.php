<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Employees",
 *     description="API for managing employees"
 * )
 */

class EmployeeController extends Controller
{
    /**
     * Get paginated list of employees
     *
     * @OA\Get(
     *     path="/api/employee/all",
     *     summary="Get employee list",
     *     description="Retrieve paginated list of employees ordered by latest",
     *     tags={"Employees"},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="response_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="employees",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/EmployeeResource")
     *                 ),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=48)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="response_code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve employee list")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $employees = EmployeeModel::with([
            'getJabatan.getDepartemen'
        ])
        ->whereIn('id_status_karyawan', [1, 2, 3, 7])
        ->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'response_code' => 200,
            'data' => [
                'employees' => EmployeeResource::collection($employees),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    /**
     * Get single employee by ID
     *
     * @OA\Get(
     *     path="/api/employee/{id}",
     *     summary="Get employee detail",
     *     description="Retrieve detailed information of a specific employee by ID",
     *     tags={"Employees"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Employee ID",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="response_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/EmployeeResource"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="response_code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Employee not found")
     *         )
     *     )
     * )
     */

    public function findOne($id)
    {
        $employee = EmployeeModel::with([
            'getJabatan.getDepartemen'
        ]
        )->find($id);
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'response_code' => 404,
                'message' => 'Employee not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'response_code' => 200,
            'data' => new EmployeeResource($employee),
        ]);
    }

    /**
     * Get employees by department
     *
     * @OA\Get(
     *     path="/api/employee/department/{id}",
     *     summary="Get employee list by department",
     *     description="Retrieve all employees that belong to a specific department ID",
     *     tags={"Employees"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Department ID",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="response_code", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EmployeeResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No employees found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="response_code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Employee not found")
     *         )
     *     )
     * )
     */
    public function byDepartemen($id)
    {
        $employee = EmployeeModel::with([
            'getJabatan.getDepartemen'
        ]
        )
        ->whereIn('id_status_karyawan', [1, 2, 3, 7])
        ->where('id_departemen', $id)->get();

        if ($employee->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'response_code' => 404,
                'message' => 'Employee not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'response_code' => 200,
            'data' => EmployeeResource::collection($employee),
        ]);
    }
}
