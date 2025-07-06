# VRG Projekt - Todo Application

This project is a Todo application built with Laravel (PHP) for the backend and Vue.js for the frontend. It demonstrates the use of several design patterns to ensure a clean, maintainable, and scalable codebase.

## Setup Instructions

To get this project up and running on your local machine, follow these steps:

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js & npm (or Yarn)
-   PostgreSQL (recommended) or SQLite (for mocking)

### 1. Clone the Repository

```bash
git clone [repository_url]
cd vrg
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file to configure your database connection.

**For PostgreSQL:**

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**For Mocking (SQLite):**

If you prefer to use SQLite for development/testing without a full database setup, you can configure `.env` as follows:

```
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/your/database.sqlite # e.g., database/database.sqlite
```

Then, create an empty `database.sqlite` file in your `database` directory:

```bash
touch database/database.sqlite
```

### 4. Run Database Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create the necessary tables and populate them with some initial data.

### 5. Install JavaScript Dependencies

```bash
npm install
# OR
yarn install
```

### 6. Compile Frontend Assets

```bash
npm run dev
# OR
yarn dev
```

For production, use `npm run build` or `yarn build`.

### 7. Start the Development Server

```bash
php artisan serve
```

The application will typically be available at `http://127.0.0.1:8000`.

## Project Structure

The project follows a standard Laravel directory structure, with a focus on clear separation of concerns:

-   `app/`: Contains the core logic of your application.
    -   `app/Categories/`: Houses the implementation of the Abstract Factory pattern for task categories.
    -   `app/Http/Controllers/`: Contains the application's controllers, handling incoming requests and returning responses.
    -   `app/Http/Requests/`: Contains Form Request classes for validating incoming request data (acting as DTOs).
    -   `app/Models/`: Contains Eloquent models, representing database tables and acting as Data Access Objects (DAOs).
-   `database/`: Database migrations, seeders, and factories.
-   `resources/js/components/`: Vue.js components for the frontend, including login, registration, and todo management.
-   `resources/views/`: Blade templates for the application's frontend views.
-   `routes/`: Web and API routes for the application.
-   `public/`: Publicly accessible assets.
-   `storage/`: Application-generated files, caches, and logs.

## Used Design Patterns

This project leverages several common design patterns to improve code organization, maintainability, and scalability:

### 1. Abstract Factory Pattern

**Purpose**: Provides an interface for creating families of related or dependent objects without specifying their concrete classes.

**Example in Project**:
The `App\Categories\TaskCategoryFactory` class is an implementation of the Abstract Factory pattern. It provides a `create` method that returns different types of `TaskCategory` objects (e.g., `WorkTaskCategory`, `PersonalTaskCategory`, `ShoppingTaskCategory`) based on the input type, without the client code needing to know the specific class names.

```php
// vrg/app/Categories/TaskCategoryFactory.php
class TaskCategoryFactory
{
    public static function create(string $type, array $properties = []): TaskCategory
    {
        switch ($type) {
            case 'WorkTask':
                return new WorkTaskCategory($properties['priority']);
            // ... other cases
        }
    }
}

// Usage in a controller (e.g., TodoController)
$category = \App\Categories\TaskCategoryFactory::create($validatedData['category_type'], $extraData);
```

### 2. DAO (Data Access Object) Pattern

**Purpose**: Abstracts and encapsulates all access to the data source. The DAO manages the connection with the data source to obtain and store data.

**Example in Project**:
Laravel's Eloquent ORM models, such as `App\Models\Todo` and `App\Models\User`, serve as DAOs. They provide a clean, object-oriented interface to interact with the database tables.

```php
// vrg/app/Models/Todo.php
class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'description', 'due_date', 'status', 'category_type', 'extra_data'];
    // ... relationships and other methods
}

// Usage in a controller (e.g., TodoController)
$user = Auth::user();
$user->todos()->create($validatedData); // Using the Todo model as a DAO
```

### 3. DTO (Data Transfer Object) Pattern

**Purpose**: An object that carries data between processes. It's primarily used to pass data with multiple attributes in one shot from one layer of an application to another to reduce the number of calls.

**Example in Project**:
Laravel's Form Request classes, like `App\Http\Requests\StoreTodoRequest`, act as DTOs. They encapsulate the validated input data from HTTP requests, making it available to the controller in a structured and type-safe manner.

```php
// vrg/app/Http/Requests/StoreTodoRequest.php
class StoreTodoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // ... other validation rules
        ];
    }
}

// Usage in a controller (e.g., TodoController)
public function store(StoreTodoRequest $request)
{
    $validatedData = $request->validated(); // $validatedData acts as a DTO
    // ... use $validatedData
}
```

