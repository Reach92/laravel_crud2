<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Student;

use App\Models\Subject;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
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
    public function it_gets_students_list()
    {
        $students = Student::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.students.index'));

        $response->assertOk()->assertSee($students[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_student()
    {
        $data = Student::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.students.store'), $data);

        unset($data['user_id']);

        $this->assertDatabaseHas('students', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_student()
    {
        $student = Student::factory()->create();

        $user = User::factory()->create();
        $subject = Subject::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'major' => $this->faker->text,
            'user_id' => $user->id,
            'subject_id' => $subject->id,
        ];

        $response = $this->putJson(
            route('api.students.update', $student),
            $data
        );

        unset($data['user_id']);

        $data['id'] = $student->id;

        $this->assertDatabaseHas('students', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_student()
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson(route('api.students.destroy', $student));

        $this->assertDeleted($student);

        $response->assertNoContent();
    }
}
