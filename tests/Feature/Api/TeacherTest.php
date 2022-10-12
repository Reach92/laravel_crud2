<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Teacher;

use App\Models\Subject;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherTest extends TestCase
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
    public function it_gets_teachers_list()
    {
        $teachers = Teacher::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.teachers.index'));

        $response->assertOk()->assertSee($teachers[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_teacher()
    {
        $data = Teacher::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.teachers.store'), $data);

        $this->assertDatabaseHas('teachers', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_teacher()
    {
        $teacher = Teacher::factory()->create();

        $subject = Subject::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'job' => $this->faker->text,
            'subject_id' => $subject->id,
        ];

        $response = $this->putJson(
            route('api.teachers.update', $teacher),
            $data
        );

        $data['id'] = $teacher->id;

        $this->assertDatabaseHas('teachers', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_teacher()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->deleteJson(route('api.teachers.destroy', $teacher));

        $this->assertDeleted($teacher);

        $response->assertNoContent();
    }
}
