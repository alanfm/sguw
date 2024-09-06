<?php

namespace App\Services;

use App\Jobs\ProcessClientUpload;
use App\Models\Client;
use App\Models\ClientBond;
use App\Models\ClientUpload;
use App\Models\Radius\Radcheck;
use App\Models\Radius\Radusergroup;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Aspera\Spreadsheet\XLSX\Reader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClientService
{
    public function __construct(
        protected ClientRepository $clientRepository
    )
    {
        //
    }

    public function create(array $data)
    {
        return $this->clientRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->clientRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->clientRepository->delete($id);
    }

    public function all()
    {
        return $this->clientRepository->all();
    }

    public function find($id)
    {
        return $this->clientRepository->find($id);
    }

    public function search(string $term = null, int $page): array
    {
        return $this->clientRepository->search($term, $page);
    }

    public function show(Client $client): Client
    {
        return $this->clientRepository->show($client);
    }

    public function createUpload(Request $request, $fileName)
    {
        $reader = new Reader();
        $reader->open(base_path() .'/storage/app/uploads/'. $fileName);

        $client_bond_id = $request->client_bond_id;
        $total_records = 0;
        $inserted_records = 0;
        $client_upload = ClientUpload::create([
            'file' => $fileName,
            'keep_clients' => $request->keep_clients,
            'observations' => $request->observations,
            'client_bond_id' => $request->client_bond_id,
            'total_records' => $total_records,
            'inserted_records' => $inserted_records,
            'user_id' => $request->user()->id
        ]);

        foreach($reader as $row) {
            ++$total_records;

            if (Client::where('registry', '=',  str_replace(['.', '-'], '', $row[1]))->count())
                continue;

            ProcessClientUpload::dispatch($client_bond_id, $row, $client_upload)->onQueue('default')->afterResponse();
        }

        $client_upload->total_records = $total_records;
        $client_upload->save();

        $reader->close();
    }

    public static function addInsertedRecord($id)
    {
        $upload = ClientUpload::find($id);

        $upload->inserted_records = $upload->inserted_records + 1;

        $upload->save();
    }

    public static function parseRow($data): array
    {
        return [
            'name' => $data[0],
            'registry' => str_replace(['.', '-'], '', $data[1]),
            'cpf' => str_replace(['.', '-'], '', $data[2]),
            'birth' => self::parseDate($data[3])->format('Y-m-d'),
            'observations' => $data[4]?? null,
            'email' => $data[5]?? null,
        ];
    }

    public static function parseDate($date): Carbon
    {
        $date = array_map('intval', explode('/', $date));
        // Log::info($date);
        // $date[2] += $date[2] >= 0 && $date[2] <= date("y") ? 2000: 1900;
        return Carbon::createSafe($date[2], $date[1], $date[0]);
    }

    public static function createRadcheck($data)
    {
        return Radcheck::firstOrCreate(
            ['username' => $data['registry']],
            [
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => substr($data['cpf'], 0, 5),
            ]
        );
    }

    public static function createRadusergroup($data, $bond)
    {
        return Radusergroup::updateOrCreate(
            ['username' => $data['registry']],
            [
                'groupname' => $bond->radgroupcheck->groupname,
                'priority' => $bond->priority
            ]
        );
    }
}
