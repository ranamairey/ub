<?php

namespace App\Providers;

use App\Repositories\AccessRepository;
use App\Repositories\AdviceRepository;
use App\Repositories\AgencyRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ActivityRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\DoctorVisitRepository;
use App\Interfaces\AccessRepositoryInterface;
use App\Interfaces\AdviceRepositoryInterface;
use App\Interfaces\AgencyRepositoryInterface;
use App\Interfaces\AccountRepositoryInterface;
use App\Interfaces\ActivityRepositoryInterface;
use App\Interfaces\AppointmentRepositoryInterface;
use App\Interfaces\DoctorVisitRepositoryInterface;
use App\Repositories\ChildTreatmentProgramRepository;
use App\Repositories\HealthEducationLectureRepository;
use App\Repositories\MalnutritionChildVisitRepository;
use App\Interfaces\ChildTreatmentProgramRepositoryInterface;
use App\Interfaces\HealthEducationLectureRepositoryInterface;
use App\Interfaces\MalnutritionChildVisitRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccessRepositoryInterface::class, AccessRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(AdviceRepositoryInterface::class,AdviceRepository::class);
        $this->app->bind(AgencyRepositoryInterface::class,AgencyRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class,AppointmentRepository::class);
        $this->app->bind(ChildTreatmentProgramRepositoryInterface::class,ChildTreatmentProgramRepository::class);
        $this->app->bind(DoctorVisitRepositoryInterface::class,DoctorVisitRepository::class);
        $this->app->bind(HealthEducationLectureRepositoryInterface::class,HealthEducationLectureRepository::class);
        $this->app->bind(MalnutritionChildVisitRepositoryInterface::class,MalnutritionChildVisitRepository::class);





    
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
