<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Cache::remember('api_projects', 3600, function () {
            return Project::with([
                'tags' => function ($query) {
                    $query->select(['tags.id', 'name']);
                }
            ])
                ->select(['projects.id', 'projects.name', 'description', 'images', 'created_at'])
                ->get()
                ->map(function ($project) {
                    // Корректно формируем ссылки на изображения
                    $images = is_array($project->images) ? $project->images : (empty($project->images) ? [] : [$project->images]);
                    $project->images = array_map(function ($image) {
                        if (!is_string($image) || empty($image)) return null;
                        if (str_starts_with($image, 'http')) {
                            return $image;
                        }
                        return Storage::disk('projects')->url(ltrim($image, '/'));
                    }, $images);
                    $project->images = array_values(array_filter($project->images));
                    return $project;
                });
        });

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }
}