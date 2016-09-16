<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Web\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Home page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHome()
    {
        return theme('page.home');
    }

    /**
     * Show page
     *
     * @param $function
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getShow($function, $slug = null)
    {
        $template = 'page.show';
        $page = null;

        if (!$slug) {
            $page = Page::single($function, 'slug');
        } else {
            $page = Page::single($slug, 'slug');
        }

        if (!$page) {
            abort(404);
        }

        // custom template
        if ($page->template) {
            $template = 'page.templates.' . $page->template;
        }

        $content['title'] = app_title($page->name);
        $content['page'] = $page;

        // SEO
        $content['page_description'] = str_limit(strip_tags($page->description), 155);

        return theme($template, $content);
    }
}
