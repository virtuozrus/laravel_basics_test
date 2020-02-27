<?php

namespace Tests\Unit;

use App\Department;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function canShowName()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $department = factory(Department::class)->create();

        $this->get(route('departments.index'))
            ->assertSee($department->name);
    }

    /**
     * @test
     */
    public function canSeeUserInCreatingPage()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->get(route('departments.create'))
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    /**
     * @test
     */
    public function canStoreNewDepartment()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('image.jpg');
        $data = [
            'name' => 'Новый отдел',
            'description' => 'Описание нового отдела',
            'logo' => $image,
        ];

        $this->post(route('departments.store'), $data)
            ->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('departments', [
            'id' => 1,
            'name' => $data['name'],
            'description' => $data['description'],
            'logo' => time() . '.jpeg',
        ]);
    }

    /**
     * @test
     */
    public function canStoreNewDepartmentWithUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('image.jpg');
        $data = [
            'name' => 'Новый отдел',
            'description' => 'Описание нового отдела',
            'logo' => $image,
            'users' => [$user->id],
        ];

        $this->post(route('departments.store'), $data)
            ->assertRedirect(route('departments.index'));

        $this
            ->assertDatabaseHas('departments', [
                'id' => 1,
                'name' => $data['name'],
                'description' => $data['description'],
                'logo' => time() . '.jpeg',
            ])
            ->assertDatabaseHas('user_department', [
                'user_id' => 1,
                'department_id' => 1,
            ]);
    }

    /**
     * @test
     */
    public function canFailRequiredFieldWhileStoringNewDepartment()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $data = [
            'name' => 'Новый отдел',
            'description' => 'Описание нового отдела',
        ];

        $this->post(route('departments.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'logo' => 'Поле Логотип обязательно для заполнения.'
            ]);

        $this
            ->assertDatabaseMissing('departments', [
                'id' => 1,
                'name' => $data['name'],
                'description' => $data['description'],
            ])
            ->assertDatabaseMissing('user_department', [
                'user_id' => 1,
                'department_id' => 1,
            ]);
    }

    /**
     * @test
     */
    public function canFailWrongImageWhileStoringNewDepartment()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('image.csv');
        $data = [
            'name' => 'Новый отдел',
            'description' => 'Описание нового отдела',
            'logo' => $image,
        ];

        $this->post(route('departments.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'logo' => 'Поле Логотип должно быть файлом одного из следующих типов: jpg, jpeg, bmp, png, gif.',
            ]);

        $this
            ->assertDatabaseMissing('departments', [
                'id' => 1,
                'name' => $data['name'],
                'description' => $data['description'],
                'logo' => time() . '.jpeg',
            ])
            ->assertDatabaseMissing('user_department', [
                'user_id' => 1,
                'department_id' => 1,
            ]);
    }
}
