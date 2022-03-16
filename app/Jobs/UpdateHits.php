<?php

namespace App\Jobs;

use App\Models\MedicalTest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class UpdateHits implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public MedicalTest $medicalTest;
    private string $theSecondArgument;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MedicalTest $medicalTest, string $theSecondArgument)
    {
        $this->medicalTest = $medicalTest;
        $this->theSecondArgument = $theSecondArgument;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->medicalTest->hits += 1;

        $this->medicalTest->save();
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->medicalTest->id))->expireAfter(30)];
    }
}
