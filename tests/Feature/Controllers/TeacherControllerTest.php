<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Teacher;

use App\Models\Subject;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_teachers()
    {
        $teachers = Teacher::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('teachers.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.teachers.index')
            ->assertViewHas('teachers');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_teacher()
    {
        $response = $this->get(route('teachers.create'));

        $response->assertOk()->assertViewIs('app.teachers.create');
    }

    /**
     * @test
     */
    public function it_stores_the_teacher()
    {
        $data = Teacher::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('teachers.store'), $data);

        $this->assertDatabaseHas('teachers', $data);

        $teacher = Teacher::latest('id')->first();

        $response->assertRedirect(route('teachers.edit', $teacher));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_teacher()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->get(route('teachers.show', $teacher));

        $response
            ->assertOk()
            ->assertViewIs('app.teachers.show')
            ->assertViewHas('teacher');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_teacher()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->get(route('teachers.edit', $teacher));

        $response
            ->assertOk()
            ->assertViewIs('app.teachers.edit')
            ->assertViewHas('teacher');
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

        $response = $this->put(route('teachers.update', $teacher), $data);

        $data['id'] = $teacher->id;

        $this->assertDatabaseHas('teachers', $data);

        $response->assertRedirect(route('teachers.edit', $teacher));
    }

    /**
     * @test
     */
    public function it_deletes_the_teacher()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->delete(route('teachers.destroy', $teacher));

        $response->assertRedirect(route('teachers.index'));

        $this->assertDeleted($teacher);
    }
}
