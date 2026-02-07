<?php

namespace Modules\Auth\app\Services\AuthUserService;

use Modules\Auth\app\Repositories\Interfaces\AuthRepositoryInterface;
use Modules\Auth\app\Models\Teacher;
class TeacherService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

   
    
    /**
     * Lấy sinh viên theo ID
     */
    public function getTeacherById(int $id)
    {
        return Cache::remember("teachers:{$id}", 1800, function() use ($id) {
            return Teacher::with(  'lecturer_id','avatar_url',)->find($id);
        });
    }
    
    
   
    
  
}
