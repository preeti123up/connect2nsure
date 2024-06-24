<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Leave;
use App\Models\User;

class LeaveReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leave;
    public $reportingManager;

    /**
     * Create a new event instance.
     *
     * @param User $reportingManager
     * @param Leave $leave
     * @return void
     */
    public function __construct(User $reportingManager, Leave $leave)
    {
        $this->leave = $leave;
        $this->reportingManager = $reportingManager;
    }
}
