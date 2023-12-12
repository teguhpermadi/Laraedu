<?php

namespace App\Observers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Userable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TeacherObserver
{
    /**
     * Handle the Teacher "created" event.
     */
    public function created(Teacher $teacher): void
    {
        // if($teacher->password)
        // {
        //     $pass = Hash::make($teacher->password);
        // } else {
        //     $pass = Hash::make('password');
        // }

        // $namaLengkap = explode(' ', $teacher->name);
        
        // $user = User::create([
        //     'name' => $teacher->name,
        //     'username' => $namaLengkap[0],
        //     'email' => Str::slug($teacher->name).'@teacher.com',
        //     'password' => $pass,
        // ]);
        
        // Userable::create([
        //     'user_id' => $user->id,
        //     'userable_id' => $teacher->id,
        //     'userable_type' => Teacher::class,
        // ]);

        // // dd($teacher->userable);
        // $user->assignRole('teacher');
    }

    /**
     * Handle the Teacher "updated" event.
     */
    public function updated(Teacher $teacher): void
    {
        // Log::info('Teacher updated: ' . $teacher->name);
        $userable = Userable::where('userable_id', $teacher->id)->first();
        $userable->user->update([
            'name' => $teacher->name,
        ]);
    }

    /**
     * Handle the Teacher "deleted" event.
     */
    public function deleted(Teacher $teacher): void
    {
        // Log::info('Teacher deleted: ' . $teacher->name);
        Userable::where('userable_id', $teacher->id)->delete();
    }

    /**
     * Handle the Teacher "restored" event.
     */
    public function restored(Teacher $teacher): void
    {
        // Log::info('Teacher restored: ' . $teacher->name);
    }

    /**
     * Handle the Teacher "force deleted" event.
     */
    public function forceDeleted(Teacher $teacher): void
    {
        // Log::info('Teacher force deleted: ' . $teacher->name);
    }
}
