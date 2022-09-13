<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NoteController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Note
   */
    public function store(Request $request): Note
    {
        return \App\Models\Note::create([
            'name' => $request->name,
            'description' => $request->description,
            'chat_id' => $request->chat_id,
            'user_id' => $request->user()->id,
        ]);
    }

  /**
   * Display the specified resource.
   *
   * @param Note $note
   * @return NoteResource
   */
    public function show(Note $note): NoteResource
    {
        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $note = \App\Models\Note::find($id);
        $note->update(['name' => $request->name, 'description' => $request->description]);
        // $note->save();
        return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $note = \App\Models\Note::find($id);
        $deleted = $note->delete();
        return response()->json($deleted);
    }
}
