<?php


namespace App\Http\Controllers\Api\Cataloging;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class AuthorityDataController
 */
final class AuthorityDataController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'res' => [
                'languages' => $this->getLanguages(),
                'subject_terms' => $this->getSubjectTerms(),
                'type' => $this->getTypes(),
                'authors' => $this->getAuthors(),
            ],
        ]);
    }

    private function getLanguages(): array
    {
        return DB::table('language')->select(
            DB::raw("lang_code as language"),
            DB::raw("lang_full_name as full_title"),
            DB::raw("lang_name_" . app()->getLocale() . " as title"),
        )->get()->toArray();
    }

    private function getSubjectTerms(): array
    {
        return DB::table('lib_subjects')->select()->get()->toArray();
    }

    private function getAuthors(): array
    {
        $authors = DB::table("lib_book_authors")->select(
                DB::raw("trim(decode(name,'','', surname ||' ') || decode(name,'','',surname ||' ') || surname) as author")
            )->toSql();
        return DB::table(
            DB::raw("($authors where is_main = 1)")
        )->select()->whereNotNull('author')->get()->toArray();
    }

    private function getTypes(): array
    {
        return DB::table('lib_material_types')->select(
            DB::raw("key as type"),
            DB::raw("title_" . app()->getLocale() . " as title"),
        )->get()->toArray();
    }
}
