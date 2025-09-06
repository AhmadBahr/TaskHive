# Task Hive - Kanban Web Application

A production-ready Laravel 11 + PostgreSQL backend for a Kanban task management application.

## Features

- **User Authentication**: Laravel Breeze with Blade templates
- **UUID Support**: All models use UUIDs as primary keys
- **PostgreSQL Database**: Optimized for production use
- **Authorization Policies**: Users can only manage their own boards/tasks
- **RESTful API**: Complete CRUD operations for boards, columns, and tasks
- **Task Management**: Drag & drop support, priority levels, due dates
- **Activity Logging**: Track task movements between columns
- **WIP Limits**: Configurable work-in-progress limits per column

## Database Schema

### Tables

- **users**: User accounts with authentication
- **boards**: Kanban boards (user-owned)
- **columns**: Board columns (Backlog, In Progress, Review, Done)
- **tasks**: Individual tasks with priority, assignee, due date
- **task_activities**: Activity log for task movements

### Key Features

- UUID primary keys for all models
- Foreign key constraints with cascade deletes
- Composite indexes for performance
- Position tracking for drag & drop ordering

## Installation

### Prerequisites

- PHP 8.2+
- Composer
- PostgreSQL 12+
- Node.js & NPM (for frontend assets)

### Setup

1. **Clone and install dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database setup**:
   ```bash
   # Update .env with your PostgreSQL credentials
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=task_hive
   DB_USERNAME=postgres
   DB_PASSWORD=your_password

   # Run migrations and seeders
   php artisan migrate
   php artisan db:seed
   ```

4. **Start the development server**:
   ```bash
   php artisan serve
   ```

## API Endpoints

### Authentication Required

All endpoints require authentication via Laravel Sanctum or session-based auth.

### Boards

- `GET /api/boards` - List user's boards
- `POST /api/boards` - Create new board
- `GET /api/boards/{board}` - Show board details
- `PATCH /api/boards/{board}` - Update board
- `DELETE /api/boards/{board}` - Delete board

### Columns

- `POST /api/boards/{board}/columns` - Create column
- `PATCH /api/boards/{board}/columns/{column}` - Update column
- `DELETE /api/boards/{board}/columns/{column}` - Delete column
- `PATCH /api/boards/{board}/columns/positions` - Update column order

### Tasks

- `POST /api/boards/{board}/tasks` - Create task
- `GET /api/boards/{board}/tasks/{task}` - Show task details
- `PATCH /api/boards/{board}/tasks/{task}` - Update task
- `DELETE /api/boards/{board}/tasks/{task}` - Delete task
- `PATCH /api/boards/{board}/tasks/{task}/move` - Move task between columns
- `PATCH /api/boards/{board}/columns/{column}/tasks/positions` - Update task order

## Models & Relationships

### User
- `hasMany` Boards
- `hasMany` assignedTasks (as assignee)

### Board
- `belongsTo` User
- `hasMany` Columns
- `hasMany` Tasks

### Column
- `belongsTo` Board
- `hasMany` Tasks
- `hasMany` fromActivities
- `hasMany` toActivities

### Task
- `belongsTo` Board
- `belongsTo` Column
- `belongsTo` User (assignee)
- `hasMany` Activities

### TaskActivity
- `belongsTo` Task
- `belongsTo` fromColumn
- `belongsTo` toColumn

## Authorization

All operations are protected by policies:

- **BoardPolicy**: Users can only manage their own boards
- **ColumnPolicy**: Users can only manage columns of their boards
- **TaskPolicy**: Users can only manage tasks of their boards

## Demo Data

The seeder creates:
- Demo user: `demo@taskhive.com`
- Sample board with default columns
- Example tasks in different stages

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Database Reset
```bash
php artisan migrate:fresh --seed
```

## Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure proper database credentials
4. Set up queue workers for background jobs
5. Configure web server (Nginx/Apache)
6. Set up SSL certificates
7. Configure backup strategy

## Security Features

- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection with output escaping
- Authorization policies for data access
- Rate limiting on API endpoints
- Password hashing with bcrypt

## Performance Optimizations

- Database indexes on frequently queried columns
- Eager loading to prevent N+1 queries
- Composite indexes for complex queries
- UUID primary keys for distributed systems
- Efficient foreign key relationships

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
