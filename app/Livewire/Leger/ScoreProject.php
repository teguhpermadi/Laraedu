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
        // $this->score = $data->score;
        switch ($data->score) {
            case '4':
                $this->score = 'BSB';
                break;
            case '3':
                $this->score = 'BSH';
                break;
            case '2':
                $this->score = 'MB';
                $this->color = 'yellow';
                break;
            
            default:
                $this->score = 'BB';
                $this->color = 'orange';
                break;
        }
    }

    public function render()
    {
        return view('livewire.leger.score-project');
    }
}
