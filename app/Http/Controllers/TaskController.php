<?php

namespace App\Http\Controllers;

//import model task
use App\Models\Task;

//import return type View
use Illuminate\View\View;

//import return type redirectResponse
use Illuminate\Http\RedirectResponse;

//import Http Request
use Illuminate\Http\Request;

//import Facades Storage
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index() : View
    {
        //get all tasks
        $tasks = Task::latest()->paginate(10);

        //render view with tasks
        return view('task.index', compact('tasks'));
    }

    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('task.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('tasks', $image->hashName(), 'public');

        //create task
        Task::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'description'   => $request->description
        ]);

        //redirect to index
        return redirect()->route('tasks.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
    
    /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        //get task by ID
        $task = Task::findOrFail($id);

        //render view with task
        return view('task.show', compact('task'));
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        //get product by ID
        $task = Task::findOrFail($id);

        //render view with product
        return view('task.edit', compact('task'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10'
        ]);

        //get task by ID
        $task = Task::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //hapus gambar lama
            Storage::disk('public')->delete('tasks/'.$task->image);

            //upload gambar baru
            $image = $request->file('image');
            $image->storeAs('tasks', $image->hashName(), 'public');

            //update product with new image
            $task->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'description'   => $request->description
            ]);

        } else {

            //update product without image
            $task->update([
                'title'         => $request->title,
                'description'   => $request->description
            ]);
        }

        //redirect to index
        return redirect()->route('tasks.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    
    /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        //get task by ID
        $task = Task::findOrFail($id);

        //delete image
        Storage::disk('public')->delete('tasks/'.$task->image);

        //delete task
        $task->delete();

        //redirect to index
        return redirect()->route('tasks.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
