<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('locations') || DB::table('locations')->doesntExist()) {
            return;
        }

        DB::transaction(function () {
            $locations = [
                'Lab RPL 1' => ['Lab Komputer 1'],
                'Lab RPL 2' => [],
                'Lab RPL 3' => [],
                'Ruang Kelas Axioo 1' => ['Ruang Kelas Teori 1'],
                'Ruang Kelas Axioo 2' => ['Ruang Kelas Teori 2'],
                'Ruang Kelas Axioo 3' => [],
                'Lab ASM 1' => [],
                'Lab ASM 2' => [],
                'Lab ASM 3' => [],
                'Ruang Kelas ASM Teori 1' => [],
                'Ruang Kelas ASM Teori 2' => [],
                'Toilet Siswa' => ['Toilet Siswa Gedung Utama', 'Toilet Siswa Gedung A'],
                'Toilet Guru' => [],
                'Ruang Guru RPL' => [],
            ];

            foreach ($locations as $name => $legacyNames) {
                $location = DB::table('locations')->where('name', $name)->first();

                foreach ($legacyNames as $legacyName) {
                    $legacyLocations = DB::table('locations')->where('name', $legacyName)->get();

                    foreach ($legacyLocations as $legacyLocation) {
                        if (! $location) {
                            $location = $legacyLocation;
                        } else {
                            DB::table('complaints')
                                ->where('location_id', $legacyLocation->id)
                                ->update(['location_id' => $location->id]);
                            DB::table('locations')->where('id', $legacyLocation->id)->delete();
                        }
                    }
                }

                if (! $location) {
                    $locationId = DB::table('locations')->insertGetId([
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $location = DB::table('locations')->where('id', $locationId)->first();
                }

                DB::table('locations')->where('id', $location->id)->update([
                    'name' => $name,
                    'building' => null,
                    'floor' => null,
                    'description' => null,
                    'updated_at' => now(),
                ]);
            }

            $requiredNames = array_keys($locations);
            $unmappedLocationsHaveComplaints = DB::table('locations as locations')
                ->join('complaints', 'complaints.location_id', '=', 'locations.id')
                ->whereNotIn('locations.name', $requiredNames)
                ->exists();

            if ($unmappedLocationsHaveComplaints) {
                throw new RuntimeException('An existing location with complaints must be mapped before replacing the school room list.');
            }

            DB::table('locations')->whereNotIn('name', $requiredNames)->delete();
        });
    }

    public function down(): void {}
};
