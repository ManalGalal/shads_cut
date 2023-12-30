<?php

namespace App\Traits;
use App\Models\WorkDay;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait WorkDayTraits {
    public function validateWorkDays($request) {
        $validated = $request->validated();
        if ($validated["for_branch"]) {
            $validated["branch_id"] = $request->user()->isSuperAdmin() ? $validated["branch_id"] : $request->user()->branch_id;
            $branch_work_days_count = WorkDay::where("branch_id", $validated["branch_id"])
                ->count();
            if ($branch_work_days_count >= 7) {
                throw new BadRequestHttpException(__("errors.branch_has_work_days"));
            }
            return;
        }
        $worker_work_days_count = WorkDay::where("worker_id", $validated["worker_id"])
            ->count();
        if ($worker_work_days_count >= 7) {
            throw new BadRequestHttpException(__("errors.worker_has_work_days"));
        }
    }
}
