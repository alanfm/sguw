<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ClientRepository implements ClientRepositoryInterface
{
    public function all(): Collection
    {
        return Client::all();
    }

    public function create(array $data): Model
    {
        return Client::create($data);
    }

    public function update(array $data, Model $client): Model
    {
        $client->update($data);
        return $client;
    }

    public function delete(Model $client): bool
    {
        return $client->delete();
    }

    public function find($id): Model
    {
        return Client::findOrFail($id);
    }

    public function search(string $term = null, int $page): array
    {
        $client = Client::with(['client_bond']);

        if (!is_null($term)) {
            $client->where('name', 'like', '%'.$term.'%')
                ->orWhere('cpf', 'like', '%'.$term.'%')
                ->orWhere('registry', 'like', '%'.$term.'%');
        }

        return [
            'count' => $client->count(),
            'data' => $client->orderBy('name', 'ASC')->paginate(env('APP_PAGINATION'))->appends(['term' => $term]),
            'page' => $page?? 1,
            'termSearch' => $term,
        ];
    }

    public function show(Client $client): Client
    {
        return $client->load(['client_bond', 'radcheck']);
    }
}
