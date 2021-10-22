<?php

namespace App\Http\Controllers\News;

use App\Enumerations\DocumentTypeEnums;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return News::join('enumerations', 'news.category', '=', 'enumerations.id')
            ->select('news.*', 'enumerations.title as category_title')
            ->addSelect(DB::raw('public.g2j(news.created_at::timestamp) as "created_time" '))
            ->where('created_by', Auth::id())
            ->paginate(10);

        //return News::where('created_by', Auth::id())->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|min:10',
            'description' => 'required|min:11',
            'category' => 'required',
            'address' =>'required'
        ]);

        $news = new News();

        $news->title = $request->title;
        $news->content = $request->description;
        $news->category = $request->category;
        $news->created_by = Auth::id();
        $news->img = $request->address;

        $news->save();

        return ['message' => 'اخبار باموفقیت ثبت شد.', 'result'=> true];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return News::join('enumerations', 'news.category', '=', 'enumerations.id')
            ->select('news.*', 'enumerations.title as category_title')
            ->addSelect(DB::raw('public.g2j(news.created_at::timestamp) as "created_time" '))
            ->where('news.id', $news->id)
            ->where('news.created_by', Auth::id())
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $flight = News::find($news->id);

        $flight->delete();

        return ["message" => "عملیات حذف با موفقیت انجام شد", 'result' => true];
    }

    public function uploadFileNews(Request $request)
    {
        $fileUploaded = uploadFile($request->file,'public/news', Auth::id());
        $fileUploaded['address'] = str_replace('public', 'storage', $fileUploaded['address']);

        return $fileUploaded;

      //  return ["message" => "فایل باموفقیت بارگزاری شد.", 'result' => true];

    }
}
