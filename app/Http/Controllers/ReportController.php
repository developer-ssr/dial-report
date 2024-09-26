<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $projects = Project::with('videos')->get();
        
        $csvData = [];
        $csvData[] = ['Project Name', 'Status', 'Number of Videos', 'Total Video Size (MB)', 'Project Date Created'];

        foreach ($projects as $project) {
            $videoCount = $project->videos->count();
            $totalSize = $project->videos->sum(function ($video) {
                if (Storage::exists('videos/' . $video->file)) {
                    return Storage::size('videos/' . $video->file) / (1024 * 1024); // Convert bytes to MB
                }
                return 0;
            });

            $csvData[] = [
                $project->name,
                $project->status,
                $videoCount,
                round($totalSize, 2),
                $project->created_at->toDateTimeString(),
            ];
        }

        $csvContent = $this->arrayToCsv($csvData);
        $fileName = 'project_report_' . now()->format('Y-m-d_His') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }

    private function arrayToCsv($data)
    {
        $output = fopen('php://temp', 'r+');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
