<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTodoRequest;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('todos.index');
    }

    public function apiIndex(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = $user->todos()->orderBy('due_date', 'asc');

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('category') && $request->input('category') != '') {
            $query->where('category_type', $request->input('category'));
        }

        $todos = $query->paginate(10); // Paginate with 10 items per page
        return response()->json($todos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTodoRequest $request)
    {
        $validatedData = $request->validated();
        $processedCategoryData = $this->processCategoryData($validatedData);
        $validatedData['category_type'] = $processedCategoryData['category_type'];
        $validatedData['extra_data'] = $processedCategoryData['extra_data'];

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->todos()->create($validatedData);

        return redirect()->route('todos.index')->with('success', 'Todo created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        $this->authorize('view', $todo);
        return view('todos.show', compact('todo'));
    }

    /**
     * Display the specified resource as JSON.
     */
    public function apiShow(Todo $todo)
    {
        $this->authorize('view', $todo);
        return response()->json($todo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        $this->authorize('update', $todo);
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTodoRequest $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $validatedData = $request->validated();
        $processedCategoryData = $this->processCategoryData($validatedData);
        $validatedData['category_type'] = $processedCategoryData['category_type'];
        $validatedData['extra_data'] = $processedCategoryData['extra_data'];





        $todo->update($validatedData);
 
        return response()->json(['message' => 'Todo updated successfully!', 'todo' => $todo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);
        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully!']);
    }

    public function getCategories()
    {
        $categories = \App\Categories\TaskCategoryFactory::getAvailableCategories();
        return response()->json($categories);
    }
    /**
     * Extracts extra data based on the category type from validated data.
     *
     * @param array $validatedData
     * @return array
     */
    private function extractExtraData(array $validatedData): array
    {
        $categoryType = $validatedData['category_type'];
        $extraData = [];

        switch ($categoryType) {
            case 'WorkTask':
                $extraData['priority'] = $validatedData['priority'] ?? null;
                break;
            case 'PersonalTask':
                $extraData['mood'] = $validatedData['mood'] ?? null;
                break;
            case 'ShoppingTask':
                $extraData['estimated_cost'] = $validatedData['estimated_cost'] ?? null;
                break;
        }

        return $extraData;
    }
    /**
     * Processes category data from validated input.
     *
     * @param array $validatedData
     * @return array
     * @throws \InvalidArgumentException
     */
    private function processCategoryData(array $validatedData): array
    {
        $categoryType = $validatedData['category_type'];
        $extraData = $this->extractExtraData($validatedData);

        try {
            $category = \App\Categories\TaskCategoryFactory::create($categoryType, $extraData);
            return [
                'category_type' => $category->getType(),
                'extra_data' => $category->getProperties()
            ];
        } catch (\InvalidArgumentException $e) {
            // Re-throw the exception to be caught by the calling method
            throw $e;
        }
    }
}
