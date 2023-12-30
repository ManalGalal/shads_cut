<?php

use App\Models\Milestone;
use App\Models\User;
use App\Traits\MilestoneTraits;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GiveRegistrationPointsToUsers extends Migration {
    use MilestoneTraits;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $users = User::all();
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
        Milestone::whereRaw("1=1")
            ->delete();
    }
}
