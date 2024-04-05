<?php

namespace Database\Seeders;

use App\Models\FileTag;
use Illuminate\Database\Seeder;

class FileTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            ['name' => FileTag::emptyRecordName(), 'description' => 'Untagged files. Do not delete this record!'],
            ['name' => 'media', 'description' => 'Media files.'],
            ['name' => 'profile-image', 'description' => 'Profile image.'],
            ['name' => 'attachment', 'description' => 'Attachment.' ],
        ];

        foreach ($tags as $tag) {
            FileTag::create($tag);
        }
    }
}
