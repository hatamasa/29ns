<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\ApiService;

class InsertShopsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:shops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'call gnavi RestSearchAPI and insert or update shops table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ApiService $apiService)
    {
        parent::__construct();
        $this->ApiService = $apiService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("start InsertShopsCommand");
        $this->info("call gnavi RestSearchAPI");


        DB::beginTransaction();
        try {
            $page = 0;
            do {
                $page++;
                $options = [
                    "hit_per_page" => 100,
                    "offset_page" => 1+100*($page-1),
                ];

                $result = $this->ApiService->callGnaviRestSearchApi($options);
                foreach ($result['rest'] as &$shop) {
                    DB::table("shops")->updateOrInsert(
                        [
                            "shop_cd" => $shop["id"],
                        ],[
                            "shop_cd" => $shop["id"],
                        ]
                    );
                }
                $this->info("page: ".$page."/".ceil($result['total_hit_count']/100));
            } while(ceil($result['total_hit_count']/100) > $page);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info("error!!");
            return;
        }

        $this->info("end InsertShopsCommand");
    }
}
