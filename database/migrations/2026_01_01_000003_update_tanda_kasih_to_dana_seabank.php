<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('section_konten')) {
            return;
        }

        $row = DB::table('section_konten')->where('section_key', 'section_6_rsvp')->first();
        if (! $row) {
            return;
        }

        $data = json_decode((string) $row->konten_json, true);
        if (! is_array($data)) {
            $data = [];
        }

        unset($data['qris_photo'], $data['qris_name']);

        $data['confirm_box_intro'] = 'Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun apabila memberi adalah ungkapan tanda kasih, Anda dapat mengirimkannya secara cashless melalui DANA atau transfer rekening berikut.';
        $data['ewallet_name'] = 'DANA';
        $data['ewallet_number'] = '08566601948';
        $data['ewallet_holder'] = $data['ewallet_holder'] ?? ($data['bank_holder'] ?? 'Fakhrel & Olivia');
        $data['bank_name'] = 'SeaBank';
        $data['bank_number'] = '901905120917';
        $data['bank_holder'] = $data['bank_holder'] ?? 'Fakhrel & Olivia';

        DB::table('section_konten')
            ->where('section_key', 'section_6_rsvp')
            ->update([
                'konten_json' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Data update migration - no destructive rollback needed.
    }
};
