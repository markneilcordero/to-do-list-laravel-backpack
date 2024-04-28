<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TodoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TodoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TodoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Todo::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/todo');
        CRUD::setEntityNameStrings('todo', 'todos');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TodoRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        CRUD::field([
          'name' => 'title',
          'label' => 'Title',
          'type' => 'text',
        ]);

        CRUD::field([
          'name' => 'description',
          'label' => 'Description',
          'type' => 'textarea',
        ]);

        CRUD::field([
          'name' => 'due_date',
          'label' => 'Due Date',
          'type' => 'date',
        ]);

        CRUD::field([
          'name' => 'status',
          'label' => 'Status',
          'type' => 'enum',
          'options' => [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
          ]
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store(TodoRequest $request)
    {
      $todo = new \App\Models\Todo();
      $todo->title = $request->input('title');
      $todo->description = $request->input('description');
      $todo->due_date = $request->input('due_date');
      $todo->status = $request->input('status');
      $todo->save();
      \Alert::add('success', 'Todo created successfully!')->flash();
      return redirect()->back();
    }
}
