<?php

namespace Modules\Auth\app\Http\Controllers\AuthUserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\app\Services\AuthUserService\TeacherService;
use Modules\Auth\app\Http\Resources\AuthUserResources\UserResource;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Hiển thị thông tin teacher
     */
    public function show(int $id): JsonResponse
    {
        try {
            $teacher = $this->teacherService->getTeachertById($id);
            
            if (!$teacher) {
                return response()->json([
                    'message' => 'Không tìm thấy teacher'
                ], 404);
            }
            
            return response()->json(new UserResource($teacher));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy thông tin teacher',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
}
