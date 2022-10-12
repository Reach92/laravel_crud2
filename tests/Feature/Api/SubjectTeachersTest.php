<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Subject;
use App\Models\Teacher;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectTeachersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_subject_teachers()
    {
        $subject = Subject::factory()->create();
        $teachers = Teacher::factory()
            ->count(2)
            ->create([
                'subject_id' => $subject->id,
            ]);

        $response = $this->getJson(
            route('api.subjects.teachers.index', $subject)
        );

        $response->assertOk()->assertSee($teachers[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_subject_teachers()
    {
        $subject = Subject::factory()->create();
        $data = Teacher::factory()
            ->make([
                'subject_id' => $subject->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.subjects.teachers.store', $subject),
            $data
        );

        $this->assertDatabaseHas('teachers', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $teacher = Teacher::latest('id')->first();

        $this->assertEquals($subject->id, $teacher->subject_id);
    }
}
