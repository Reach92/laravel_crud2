<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Subject;
use App\Models\Student;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectStudentsTest extends TestCase
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
    public function it_gets_subject_students()
    {
        $subject = Subject::factory()->create();
        $students = Student::factory()
            ->count(2)
            ->create([
                'subject_id' => $subject->id,
            ]);

        $response = $this->getJson(
            route('api.subjects.students.index', $subject)
        );

        $response->assertOk()->assertSee($students[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_subject_students()
    {
        $subject = Subject::factory()->create();
        $data = Student::factory()
            ->make([
                'subject_id' => $subject->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.subjects.students.store', $subject),
            $data
        );

        unset($data['user_id']);

        $this->assertDatabaseHas('students', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $student = Student::latest('id')->first();

        $this->assertEquals($subject->id, $student->subject_id);
    }
}
