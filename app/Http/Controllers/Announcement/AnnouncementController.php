<?php

namespace App\Http\Controllers\Announcement;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Announcement::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnnouncementRequest $request)
    {
        $announcement = new Announcement();

        $announcement->title = $request->title;
        $announcement->body = $request->body;
        $announcement->expired_at = $request->expired_at;
        $announcement->save();

        return ['message' => "اطلاعیه با موفقیت ثبت شد", 'result' => true];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcements
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcements
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcements
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return ['message' => "اطلاعیه با موفقیت حذف شد", 'result' => true];
    }
}
