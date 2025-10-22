<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasAuthenticatedUser;
use Illuminate\Http\Request;
use App\Models\Deal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DealController extends Controller
{
    use HasAuthenticatedUser;
    public function index(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $query = $user->deals()->with('client');

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            return $query->orderByDesc('created_at')->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch deals'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'title' => 'required|string|max:255',
                'status' => 'required|in:new,in_progress,won,lost',
                'amount' => 'nullable|numeric|min:0',
            ]);

            $client = \App\Models\Client::where('id', $validated['client_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $user = $this->getAuthenticatedUser();
            $deal = $user->deals()->create($validated);

            return response()->json($deal->load('client'), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create deal'], 500);
        }
    }

    public function show(Deal $deal)
    {
        try {
            $this->authorize('view', $deal);
            return response()->json($deal->load('client'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch deal'], 500);
        }
    }

    public function update(Request $request, Deal $deal)
    {
        try {
            $this->authorize('update', $deal);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'status' => 'sometimes|in:new,in_progress,won,lost',
                'amount' => 'sometimes|numeric|min:0',
            ]);

            $deal->update($validated);
            return response()->json($deal->load('client'));
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update deal'], 500);
        }
    }

    public function destroy(Deal $deal)
    {
        try {
            $this->authorize('delete', $deal);
            $deal->delete();
            return response()->json(['message' => 'Deal deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete deal'], 500);
        }
    }
}
