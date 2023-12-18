<?php

namespace App\Providers;

use App\Events\CalculateReport;
use App\Events\PrintReport;
use App\Listeners\CalculateScoreListener;
use App\Listeners\PrintReportListener;
use App\Models\Competency;
use App\Models\Project;
use App\Models\ProjectTarget;
use App\Models\StudentCompetency;
use App\Models\StudentGrade;
use App\Models\Teacher;
use App\Models\TeacherExtracurricular;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use App\Observers\CompetencyObserver;
use App\Observers\ProjectObserver;
use App\Observers\ProjectTargetObserver;
use App\Observers\StudentGradeObserver;
use App\Observers\TeacherExtracurricularObserver;
use App\Observers\TeacherGradeObserver;
use App\Observers\TeacherObserver;
use App\Observers\TeacherSubjectObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Teacher::observe(TeacherObserver::class);
        Competency::observe(CompetencyObserver::class);
        StudentGrade::observe(StudentGradeObserver::class);
        TeacherGrade::observe(TeacherGradeObserver::class);
        TeacherSubject::observe(TeacherSubjectObserver::class);
        TeacherExtracurricular::observe(TeacherExtracurricularObserver::class);
        ProjectTarget::observe(ProjectTargetObserver::class);
        Project::observe(ProjectObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
