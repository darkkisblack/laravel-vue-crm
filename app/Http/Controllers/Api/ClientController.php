<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasAuthenticatedUser;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    use HasAuthenticatedUser;
    public function index(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $query = $user->clients();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            return $query->orderByDesc('created_at')->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch clients'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string',
            ]);

            $user = $this->getAuthenticatedUser();
            $client = $user->clients()->create($validated);

            return response()->json($client, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create client'], 500);
        }
    }

    public function show(Client $client)
    {
        try {
            $this->authorize('view', $client);
            return response()->json($client->load('deals'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch client'], 500);
        }
    }

    public function update(Request $request, Client $client)
    {
        try {
            $this->authorize('update', $client);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string',
            ]);

            $client->update($validated);
            return response()->json($client);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update client'], 500);
        }
    }

    public function destroy(Client $client)
    {
        try {
            $this->authorize('delete', $client);
            $client->delete();
            return response()->json(['message' => 'Client deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete client'], 500);
        }
    }
}
