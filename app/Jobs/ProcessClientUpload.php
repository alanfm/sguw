<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\ClientBond;
use App\Services\ClientService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessClientUpload implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $client_bond_id,
        protected $row,
        protected $client_upload
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = ClientService::parseRow($this->row);

        $bond = ClientBond::find($this->client_bond_id);
        $radcheck = ClientService::createRadcheck($data);
        ClientService::createRadusergroup($data, $bond);

        if (!Client::where('registry', $data['registry'])->count())
            ClientService::addInsertedRecord($this->client_upload->id);
        /**
         * Cadastra o cliente
         */
        Client::firstOrCreate(
            ['cpf' => $data['cpf']],
            [
                'radcheck_id' => $radcheck->id,
                'client_bond_id' => $bond->id,
                ...$data
            ]
        );
    }
}
