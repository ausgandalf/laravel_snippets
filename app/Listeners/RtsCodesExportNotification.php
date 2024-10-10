<?php
namespace App\Listeners;
 
use Config;
use App\Events\RtsCodesExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\general\BackgroundJob;
use App\Models\workshop\RtsCode;
use App\Models\workshop\LaborRate;
use App\Models\workshop\ServiceCode;
use App\Models\workshop\Skill;
use App\Models\workshop\RtsCodeSkill;
use App\Models\vehicle\Make;
use App\Models\vehicle\Vmodel;
use App\Models\additional\Additional;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RtsCodesExportNotification implements ShouldQueue
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
     * @param  \App\Events\RtsCodesExport  $event
     * @return void
     */
    public function handle(RtsCodesExport $event)
    {
        // Log::info("called");
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
        
        
        $conditions = json_decode($background_job->conditions, true);
        $q = RtsCode::where('ins', auth()->user()->ins);
        if (isset($conditions['make_id'])){
            $q->where('v_make_id', $conditions['make_id']);
        }
        if (isset($conditions['rts_code'])){
            $q->where('rts_code', 'like', '%'. $conditions['rts_code'] . '%');
        }
        if (isset($conditions['description'])){
            $q->where('description', 'like', '%'. $conditions['description'] . '%');
        }
        $q->get(['id','rts_code','v_make_id','allowed_time_unit','labor_rate_id','vat_rate_id','description']);
        $count = $q->count();
        $i = 0;
        if ($conditions['model'] == 1){
            $filename = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .$path . "rts_codes_model_specific_" . date("Y_m_d_his"). ".csv";
        }else{
            $filename = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR .$path . "rts_codes_" . date("Y_m_d_his"). ".csv";
        }
        $writer = SimpleExcelWriter::create(storage_path($filename));
        if ($conditions['model'] == 1){
            $writer->addHeader(
                [
                    trans('workshops.rts_codes.rts_code'), 
                    trans('workshops.rts_codes.make'),
                    trans('workshops.rts_codes.model'),
                    trans('workshops.rts_codes.allowed_time_unit')
                ]
            );
        }else{
            $writer->addHeader(
                [
                    trans('workshops.rts_codes.rts_code'), 
                    trans('workshops.rts_codes.make'),
                    trans('workshops.rts_codes.allowed_time_unit'),
                    trans('workshops.rts_codes.labor_rate'),
                    trans('workshops.rts_codes.skills'),
                    trans('workshops.rts_codes.vat_rate'),
                    trans('workshops.rts_codes.description')
                ]
            );
        }
        $step = 1000;
        foreach ($q->lazy($step) as $rts_code){
            
            if ($conditions['model'] == 1){
                if (is_numeric($rts_code->v_make_id )){
                    $make = Make::find($rts_code->v_make_id);
                    foreach ($rts_code->model_allowed_times as $mat){
                        $model = Vmodel::find($mat->model_id);
                        if ($model){
                            $rts_code_arr = [];
                            $rts_code_arr['rts_code'] = $rts_code->rts_code;
                            $rts_code_arr['make'] = $make->name;
                            $rts_code_arr['model'] = $model->name;
                            $rts_code_arr['allowed_time_unit'] = number_format($mat->allowed_time, 2, '.', '');
                            $writer->addRow($rts_code_arr);
                            if ($i % $step === 0) {
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
                    }
                }
            }else{
                $rts_code_arr = [];
                $rts_code_arr['rts_code'] = $rts_code->rts_code;
                if (is_numeric($rts_code->v_make_id )){
                    $make = Make::find($rts_code->v_make_id);
                    $rts_code_arr['make'] = $make->name;
                }else{
                    $rts_code_arr['make'] = "";
                }
                
                $rts_code_arr['allowed_time_unit'] = number_format($rts_code->allowed_time_unit, 2, '.', '');
                $labor_rate = LaborRate::find($rts_code->labor_rate_id);
                $rts_code_arr['labor_rate'] = $labor_rate->rate_code;
                $skills = RtsCodeSkill::where("rts_code_id", $rts_code->id)->get();
                $rts_skills = [];
                foreach ($skills as $skill){
                    $s = Skill::find($skill->skill_id);
                    array_push($rts_skills, $s->name);
                }
                $rts_code_arr['skills'] = implode(",", $rts_skills);
                $vat_rate = Additional::find($rts_code->vat_rate_id);
                $rts_code_arr['vat_rate'] = $vat_rate->name;
                $writer->addRow($rts_code_arr);
                if ($i % $step === 0) {
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
        }
        $background_job->percent = 100;
        $background_job->update(
        [
            'percent' => $background_job->percent,
            'path'=>$filename,
        ]
        );
    }
    public function failed(RtsCodesExport $event, $exception)
    {
        //
        $background_job = $event->background_job;
        $background_jobs = Config::get('constants.general.background_jobs');
        $background_job->update(
            [
                'status'=>$background_jobs['STATUS']['FAILED']
            ]
        );
    }
}