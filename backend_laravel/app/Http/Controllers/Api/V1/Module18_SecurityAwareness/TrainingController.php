<?php

namespace App\Http\Controllers\Api\V1\Module18_SecurityAwareness;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\TrainingService;

class TrainingController extends Controller
{
    protected $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    /**
     * Danh sách courses
     */
    public function courses(Request $request)
    {
        $courses = $this->trainingService->getCourses([
            'category' => $request->category,
            'level' => $request->level,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Course detail
     */
    public function courseDetail($id)
    {
        $course = $this->trainingService->getCourseDetail($id);

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    /**
     * Enroll in course
     */
    public function enroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment = $this->trainingService->enroll(
            $request->course_id,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $enrollment,
            'message' => 'Đăng ký khóa học thành công'
        ]);
    }

    /**
     * Track progress
     */
    public function progress(Request $request)
    {
        $progress = $this->trainingService->getProgress(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $progress
        ]);
    }

    /**
     * Update lesson progress
     */
    public function updateProgress(Request $request, $enrollmentId)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|string',
            'completed' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $progress = $this->trainingService->updateLessonProgress(
            $enrollmentId,
            $request->lesson_id,
            $request->completed
        );

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Cập nhật tiến độ thành công'
        ]);
    }

    /**
     * Complete course
     */
    public function completeCourse($enrollmentId)
    {
        $result = $this->trainingService->completeCourse($enrollmentId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Hoàn thành khóa học! Cấp chứng chỉ thành công' : 'Không thể hoàn thành khóa học'
        ]);
    }

    /**
     * Get certificate
     */
    public function getCertificate($enrollmentId)
    {
        $certificate = $this->trainingService->generateCertificate($enrollmentId);

        return response()->json([
            'success' => true,
            'data' => $certificate
        ]);
    }

    /**
     * Create course (admin)
     */
    public function createCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max=200',
            'description' => 'required|string',
            'category' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|integer|min=1',
            'lessons' => 'required|array',
            'lessons.*.title' => 'required|string',
            'lessons.*.content' => 'required|string',
            'lessons.*.duration_minutes' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = $this->trainingService->createCourse($request->all());

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Tạo khóa học thành công'
        ]);
    }

    /**
     * Assign training to user
     */
    public function assignTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|string',
            'user_id' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment = $this->trainingService->assignTraining($request->all());

        return response()->json([
            'success' => true,
            'data' => $assignment,
            'message' => 'Gán khóa học thành công'
        ]);
    }

    /**
     * Training compliance report
     */
    public function complianceReport(Request $request)
    {
        $report = $this->trainingService->getComplianceReport();

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}