<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

use App\Models\SlugExclude;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class WBDatabase
{
    /**
     * Filter all ids
     *
     * @param array $data
     * @param null $column_name
     * @return array
     */
    public static function filterID($data = [], $column_name = null)
    {
        $ids = [];
        foreach ($data as $row) {
            $ids[] = $row->$column_name;
        }

        return $ids;
    }

    /**
     * Custom pagination
     *
     * @param $collections
     * @param int $pagination_num
     * @return LengthAwarePaginator
     */
    public static function paginate($collections, $pagination_num = 0)
    {
        if (!$pagination_num) {
            $pagination_num = config_per_page();
        }

        $total_rows = count($collections->get());
        $current_page = Paginator::resolveCurrentPage();

        $collections->skip(
            $pagination_num * ($current_page - 1)
        )->take($pagination_num);

        return new LengthAwarePaginator($collections->get(), $total_rows, $pagination_num,
            Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }

    /**
     * Exclude slug
     *
     * @return string
     */
    public static function excludeSlug()
    {
        $slugs = SlugExclude::all();
        $excludes = '';

        foreach ($slugs as $row) {
            $excludes .= $row->name . ',';
        }

        $excludes = rtrim($excludes, ',');
        $excludes = ltrim($excludes, ',');

        return $excludes;
    }
}