<?php

namespace App\Livewire\Leger;

use App\Models\ProjectNote as ModelsProjectNote;
use Livewire\Component;

class ProjectNote extends Component
{
    public $note = '';
    
    public function mount($project_id, $student_id)
    {
        $data = ModelsProjectNote::where('project_id', $project_id)->where('student_id', $student_id)->first();
        $this->note = ($data) ? $data->note : '-';
    }

    public function render()
    {
        return view('livewire.leger.project-note');
    }
}
