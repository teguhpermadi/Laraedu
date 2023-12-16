<?php

namespace App\Livewire\Leger;

use App\Models\ProjectStudent;
use Livewire\Component;

class ScoreProject extends Component
{
    public $score, $color;

    public function mount($student_id, $project_target_id)
    {
        $data = ProjectStudent::where('student_id', $student_id)->where('project_target_id', $project_target_id)->first();
        switch ($data) {
            case 'value':
                # code...
                break;
            
            default:
                # code...
                break;
        }
    }

    public function render()
    {
        return view('livewire.leger.score-project');
    }
}
