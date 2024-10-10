<?php
namespace App\Listeners;
 
use App\Events\EmployeeExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\general\BackgroundJob;
use App\Models\hrm\Hrm;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Storage;

class EmployeeExportNotification implements ShouldQueue
{
    // use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $backgrond_job;
    public function __construct()
    {
        //
    }
 
    /**
     * Handle the event.
     *
     * @param  \App\Events\EmployeeExport  $event
     * @return void
     */
    public function handle(EmployeeExport $event)
    {
        $background_job = $event->background_job;
        $path = 'files' . DIRECTORY_SEPARATOR . 'company' . DIRECTORY_SEPARATOR;
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= auth()->user()->ins;
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= DIRECTORY_SEPARATOR . date("Y");
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= DIRECTORY_SEPARATOR . date("m");
        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path))){
            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path));
        }
        $path .= DIRECTORY_SEPARATOR;
        $filename = "employees_" . time(). ".csv";
        Storage::disk('public')->put($path.$filename, "");
        
        $conditions = json_decode($background_job->conditions, true);
        $q = Hrm::where('ins', auth()->user()->ins);
        if (isset($conditions['sname'])){
            $q->whereRaw("CONCAT(first_name,' ',last_name) like '%" . $conditions['sname'] . "%'");
        }
        if (isset($conditions['branch'])){
            $branch = $conditions['branch'];
            $q->whereHas('meta', function ($s) use ($branch) {
                return $s->where('branch_id', '=', $branch);
            });
        }
        if (isset($conditions['department'])){
            $department = $conditions['department'];
            $q->whereHas('meta', function ($s) use ($department){
                return $s->where('department_id', '=', $department);
            });
        }
        if (isset($conditions['position'])){
            $position = $conditoins['position'];
            $q->whereHas('meta', function ($s) use($position){
                return $s->where('position_id', '=', $position);
            });

        }
        $q->get(['id','email','picture','first_name','last_name','status','created_at']);
        $count = $q->count();
        $i = 0;
        $filename = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .$path . "employees_" . time(). ".csv";
        $writer = SimpleExcelWriter::create(storage_path($filename));
        foreach ($q->lazy(1000) as $user){
            $writer->addRow($user->toArray());
            if ($i % 1000 === 0) {
                $background_job->percent = $i * 100 / $count;
                $background_job->update(
                [
                    'percent' => $background_job->percent
                ]
                );

                flush(); // Flush the buffer every 1000 rows
            }
            $i++;
        }
        $background_job->percent = 100;
        $background_job->update(
        [
            'percent' => $background_job->percent,
            'path'=>$filename,
        ]
        );
    }
    public function failed(EmployeeExport $event, $exception)
    {
        //
    }
}