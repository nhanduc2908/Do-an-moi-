<?php

namespace App\Exceptions;

use Exception;

class AssessmentLockedException extends Exception
{
    protected $message = 'Đánh giá này đã bị khóa';
    protected $code = 423;

    protected $assessmentId;
    protected $lockedBy;
    protected $lockedAt;
    protected $reason;

    public function __construct($message = null, $assessmentId = null, $lockedBy = null, $reason = null)
    {
        $this->assessmentId = $assessmentId;
        $this->lockedBy = $lockedBy;
        $this->lockedAt = now();
        $this->reason = $reason;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getAssessmentId()
    {
        return $this->assessmentId;
    }

    public function getLockedBy()
    {
        return $this->lockedBy;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'assessment_locked',
            'message' => $this->message,
            'assessment_id' => $this->assessmentId,
            'locked_by' => $this->lockedBy,
            'locked_at' => $this->lockedAt,
            'reason' => $this->reason,
            'code' => 'ASSESSMENT_LOCKED'
        ], $this->code);
    }
}