<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportProblemRequest;
use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create(ReportProblemRequest $request){
       $report= Report::create([
           'user_id'=>jwtUser()->id,
           'text'=>$request->text,
        ]);

        if (isset($request['images'])) {
        foreach ($request->images as $imagefile) {
            $relativePath =StaticController::saveImage($imagefile, 'images/reports/');
            $imagefile = $relativePath;
        };

        ReportImage::create([
                'report_id'=>$report->id,
                'thumbnail'=>$imagefile,
             ]);
          }
        
        
    return response()->json('Problem reported successfully!');
    }
}
