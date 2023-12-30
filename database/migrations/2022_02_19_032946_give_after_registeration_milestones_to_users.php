<?php

use App\Models\User;
use App\Traits\MilestoneTraits;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GiveAfterRegisterationMilestonesToUsers extends Migration {
    use MilestoneTraits;
    public function up() {
        $users = User::select(["id"])
            ->whereRaw("NOT EXISTS (select id from milestones where user_id = users.id  and milestones.reason_en = 'After registration')")
            ->get();
        foreach ($users as $user) {
            $this->pointsAfterRegisteration($user->id);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        // no reverse migration
    }
}
