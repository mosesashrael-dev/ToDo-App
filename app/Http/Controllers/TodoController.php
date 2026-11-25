<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = Todo::query();

        if ($filter === 'completed') {
            $query->where('is_completed', true);
        } elseif ($filter === 'pending') {
            $query->where('is_completed', false);
        }

        $todos = $query->orderBy('is_completed', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->get();

        $totalTodos = Todo::count();
        $completedTodos = Todo::where('is_completed', true)->count();

        return view('todos.index', compact('todos', 'filter', 'totalTodos', 'completedTodos'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $isCompleted = $request->has('is_completed');

        Todo::create([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        return redirect()->route('todos.index')->with('success', 'ToDo baru berhasil ditambahkan!');
    }

    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $isCompleted = $request->has('is_completed');

        $completedAt = $todo->completed_at;
        if ($isCompleted && !$todo->is_completed) {
            $completedAt = now();
        } elseif (!$isCompleted) {
            $completedAt = null;
        }

        $todo->update([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'is_completed' => $isCompleted,
            'completed_at' => $completedAt,
        ]);

        return redirect()->route('todos.index')->with('success', 'ToDo berhasil diperbarui!');
    }

    public function toggleComplete(Todo $todo)
    {
        $newStatus = !$todo->is_completed;

        $todo->update([
            'is_completed' => $newStatus,
            'completed_at' => $newStatus ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status ToDo berhasil diubah!');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', 'ToDo berhasil dihapus!');
    }
}